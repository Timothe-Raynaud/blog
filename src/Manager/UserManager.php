<?php

namespace Manager;

use Exception;
use Entity\User;
use Repository\UserRepository;
use Repository\ResetPasswordRepository;

class UserManager
{
    private UserRepository $userRepository;
    private MailsManager $mailsManager;
    private ResetPasswordRepository $resetPasswordRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
        $this->mailsManager = new MailsManager();
        $this->resetPasswordRepository = new ResetPasswordRepository();
    }

    /**
     * @throws Exception
     */
    public function addNewUser(array $post): array
    {
        $result['isAdd'] = false;
        $result['message'] = '';

        try {
            if (!empty($post)) {
                $email = $post['email'];
                $username = $post['username'];
                $login = $post['login'];
                $password = $post['password'];

                // Test input
                if ($this->userRepository->getUserByUsername($username)) {
                    $result['message'] = 'Le nom d\'utilisateur est déja pris.';
                    return $result;
                }
                if ($this->userRepository->getUserByLogin($login)) {
                    $result['message'] = 'Ce login est déja pris';
                    return $result;
                }
                if ($this->userRepository->getUserByEmail($email)) {
                    $result['message'] = 'Cet adresse email est déjà utilisé';
                    return $result;
                }

                // Ok - Create User
                $password = password_hash($password, PASSWORD_DEFAULT);

                if ($this->userRepository->setUser($login, $password, $username, $email)) {
                    $result['isAdd'] = true;
                    $result['message'] = 'Le compte à bien été créer';
                    return $result;
                }
            }
        } catch (Exception $exception) {
            throw new Exception($exception);
        }
        $result['message'] = 'Une erreur est survenue lors de l\'inscription';
        return $result;
    }

    /**
     * @throws Exception
     */
    public function connecting(array $post): array
    {
        $result['isConnecting'] = false;
        $result['message'] = '';

        try {
            if (!empty($post)) {
                $login = $post['login'];
                $password = $post['password'];
                $user = $this->userRepository->getUserByLogin($login);

                // Test input
                if (!$user) {
                    $result['message'] = 'Login incorect';
                    return $result;
                }
                if (!password_verify($password, $user->getPassword())) {
                    $result['message'] = 'Mot de passe incorect';
                    return $result;
                }
                if(!$user->getIsAvailable()){
                    $result['message'] = 'Ce compte n\'est pas encore activé';
                    return $result;
                }

                // Ok - Create session
                $this->createSession($user);
                $result['isConnecting'] = true;
                $result['message'] = 'Bienvenu ' . $user->getUsername() . ' !';

                return $result;
            }
        } catch (Exception $exception) {
            throw new Exception($exception);
        }
        $result['message'] = 'Une erreur est survenu lors de la connection';
        return $result;
    }

    /**
     * @throws Exception
     */
    public function sendMailResetPassword(array $post): array
    {
        $result['isSend'] = false;
        $result['message'] = '';

        try {
            if (!empty($post)) {
                $email = $post['email'];
                $user = $this->userRepository->getUserByEmail($email);

                // Test input
                if (!$user) {
                    $result['message'] = 'Email incorect';
                    return $result;
                }
                if (!$user->getIsAvailable()) {
                    $result['message'] = 'Votre compte n\'est pas activé';
                }

                // Ok - Send reset mail password
                $token = bin2hex(random_bytes(32));
                $link = $_SERVER['HTTP_HOST'] . '/reset?' . $token;

                if ($this->mailsManager->sendResetMail($email, $link, $user->getUsername())
                    && $this->resetPasswordRepository->setResetPassword($token, $user->getId())) {

                    $result['isSend'] = true;
                    $result['message'] = 'Un mail de reinitialisation vous à été envoyé';
                    return $result;
                }

            }
        } catch (Exception $exception) {
            throw new Exception($exception);
        }
        $result['message'] = 'Une erreur est survenue';
        return $result;
    }

    /**
     * @throws Exception
     */
    public function resetPassword(array $post): array
    {
        $result['isReset'] = false;
        $result['message'] = 'Une erreur est survenue';

        try {
            if (!empty($post)) {
                $firstPassword = $post['firstPassword'];
                $secondPassword = $post['secondPassword'];
                $token = $post['token'];
                $userId = $post['userId'];
                $user = $this->userRepository->getUserById($userId);

                // Test input
                if (!$user) {
                    $result['message'] = 'Un problème est survenue, veuillez recommencer l\'opération depuis le debut.';
                    return $result;
                }
                if($firstPassword != $secondPassword){
                    $result['message'] = 'Les mots de passe doivent être identiques';
                    return $result;
                }

                // Ok - Update password
                $password = password_hash($firstPassword, PASSWORD_DEFAULT);
                if ($this->userRepository->updatePassword($userId, $password)
                   &&$this->resetPasswordRepository->setIsUsed($token)){

                    $result['isReset'] = true;
                    $result['message'] = 'Le mot de passe a bien été modifié <br> Redirection à la page de connexion';
                    return $result;
                }

            }
        } catch (Exception $exception) {
            throw new Exception($exception);
        }

        return $result;
    }

    /**
     * @throws Exception
     */
    public function updateAccount(array $post): array
    {
        $result['isUpdate'] = false;
        $result['message'] = 'Une erreur est survenue';

        try {
            if (!empty($post)) {
                $username = $post['username'];
                $email = $post['email'];
                $login = $post['login'];
                $userId = $post['userId'];
                $user = $this->userRepository->getUserById($userId);

                // Test input
                if (!$user) {
                    return $result;
                }
                if($username === $user->getUsername() && $login === $user->getLogin() && $email === $user->getEmail()){
                    $result['message'] = 'Votre profil est déjà à jour';
                    return $result;
                }
                if ($username != $user->getUsername()){
                    if ($this->userRepository->getUserByUsername($username)) {
                        $result['message'] = 'Le nom d\'utilisateur est déja pris.';
                        return $result;
                    }
                }
                if ($login != $user->getLogin()){
                    if ($this->userRepository->getUserByLogin($login)) {
                        $result['message'] = 'Ce login est déja pris';
                        return $result;
                    }
                }
                if ($email != $user->getEmail()){
                    if ($this->userRepository->getUserByEmail($email)) {
                        $result['message'] = 'Cet adresse email est déjà utilisé';
                        return $result;
                    }
                }

                // Ok - Update account
                if ($this->userRepository->updateAccount($userId, $login, $username, $email)){
                    $result['message'] = 'Votre compte à bien été mis à jour.';
                    $result['isUpdate'] = true;

                    //Update session
                    $user = $this->userRepository->getUserById($userId);
                    $this->createSession($user);

                    return $result;
                }

            }
        } catch (Exception $exception) {
            throw new Exception($exception);
        }

        return $result;
    }

    /**
     * @throws Exception
     */
    public function updatePassword(array $post): array
    {
        $result['isUpdate'] = false;
        $result['message'] = 'Une erreur est survenue';

        try {
            if (!empty($post)) {;
                $password = $post['password'];
                $firstNewPassword = $post['firstNewPassword'];
                $secondNewPassword = $post['secondNewPassword'];
                $userId = $post['userId'];
                $user = $this->userRepository->getUserById($userId);

                // Test input
                if (!$user) {
                    return $result;
                }
                if($firstNewPassword != $secondNewPassword){
                    $result['message'] = 'Les mots de passe doivent être identiques';
                    return $result;
                }
                if (!password_verify($password, $user->getPassword())) {
                    $result['message'] = 'Mot de passe incorect';
                    return $result;
                }

                // Ok - Update password
                $password = password_hash($firstNewPassword, PASSWORD_DEFAULT);
                if ($this->userRepository->updatePassword($userId, $password)){
                    $result['isUpdate'] = true;
                    $result['message'] = 'Le mot de passe a bien été modifié';

                    return $result;
                }

            }
        } catch (Exception $exception) {
            throw new Exception($exception);
        }

        return $result;
    }

    /**
     * @throws Exception
     */
    public function updateRole(array $post): array
    {
        $result['isUpdate'] = false;
        $result['message'] = 'Une erreur est survenue';

        try {
            if (!empty($post)) {;
                $role = $post['role'];
                $login = $post['login'];
                $user = $this->userRepository->getUserByLogin($login);

                // Test input
                if (!$user) {
                    return $result;
                }
                if ($user->getRole()->getId() === (int)$role){
                    $result['message'] = 'Le role est déja définis sur ' . $user->getRole()->getRole() . ' .';
                    return $result;
                }

                // Ok - Update password
                if ($this->userRepository->updateRole($user->getId(), $role)){
                    $result['isUpdate'] = true;
                    $result['message'] = 'Le role de l\'utilisateur à bien été modifié.';

                    return $result;
                }

            }
        } catch (Exception $exception) {
            throw new Exception($exception);
        }

        return $result;
    }

    public function getUserByResetToken(string $token) : ?array
    {
        $now = date('Y-m-d h:i:s');
        $resetPassword = $this->resetPasswordRepository->getResetPasswordByToken($token);
        if (empty($resetPassword)){
            return null;
        }
        if ($resetPassword['is_used']){
            return null;
        }
        if ($resetPassword['expiration_date'] < $now){
            return null;
        }

        return $resetPassword;
    }

    public function isLoginExist(string $login): bool
    {
        if ($this->userRepository->getUserByLogin($login)){
            return true;
        }
        return false;
    }

    public function isUsernameExist(string $username): bool
    {
        if ($this->userRepository->getUserByUsername($username)){
            return true;
        }
        return false;
    }

    public function createSession(User $user): void
    {
        $_SESSION['username'] = $user->getUsername();
        $_SESSION['login'] = $user->getLogin();
        $_SESSION['userId'] = $user->getId();
        $_SESSION['email'] = $user->getEmail();
        $_SESSION['role'] = $user->getRole()->getRole();
    }

    public function removeSession(): void
    {
        session_destroy();
    }

    public function askIfErrorAdmin(?int $isError = null): ?String
    {
        $errorMessage = null;
        if ($isError === 1){
            $errorMessage = 'L\'acces à l\'admin est réserver aux administrateur.';
        }
        if ($isError === 2){
            $errorMessage = '
            L\'acces à la creation de post est réserver à certains utilisateur. 
            Veuillez contacter l\'admin pour en faire la demande ou vous connecter avec un compte valide.
            ';
        }

        return $errorMessage;
    }
}
