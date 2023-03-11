<?php

namespace Manager;

use Exception;
use Repository\UserRepository;
use Repository\ContactRepository;
use Repository\ResetPasswordRepository;

class UserManager
{
    private UserRepository $userRepository;
    private ContactRepository $contactRepository;
    private MailsManager $mailsManager;
    private ResetPasswordRepository $resetPasswordRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
        $this->contactRepository = new ContactRepository();
        $this->mailsManager = new MailsManager();
        $this->resetPasswordRepository = new ResetPasswordRepository();
    }

    public function addNewUser($post): array
    {
        $result['isAdd'] = false;
        $result['message'] = '';

        try {
            if ($post != null) {
                $email = $post['email'];
                $username = $post['username'];
                $login = $post['login'];
                $password = $post['password'];

                // Test input
                if ($this->contactRepository->getContactsByUsername($username)) {
                    $result['message'] = 'Le nom d\'utilisateur est déja pris.';
                    return $result;
                }
                if ($this->userRepository->getUserByLogin($login)) {
                    $result['message'] = 'Ce login est déja pris';
                    return $result;
                }
                if ($this->contactRepository->getContactsByEmail($email)) {
                    $result['message'] = 'Cet adresse email est déjà utilisé';
                    return $result;
                }

                // Ok - Create User
                if ($this->contactRepository->setContact($username, $email)) {
                    $contact = $this->contactRepository->getContactsByUsername($username);
                    $password = password_hash($password, PASSWORD_DEFAULT);

                    if ($this->userRepository->setUser($login, $password, 'DEFAULT', $contact['contact_id'])) {
                        $result['isAdd'] = true;
                        $result['message'] = 'Le compte à bien été créer';
                        return $result;
                    }
                }
            }
        } catch (Exception $exception) {
            if (DEV_ENVIRONMENT) {
                var_dump($exception);
            }
        }
        $result['message'] = 'Une erreur est survenue lors de l\'inscription';
        return $result;
    }

    public function connecting($post): array
    {
        $result['isConnecting'] = false;
        $result['message'] = '';

        try {
            if ($post != null) {
                $login = $post['login'];
                $password = $post['password'];
                $user = $this->userRepository->getUserByLogin($login);

                // Test input
                if (!$user) {
                    $result['message'] = 'Login incorect';
                    return $result;
                }
                if (!password_verify($password, $user['password'])) {
                    $result['message'] = 'Mot de passe incorect';
                    return $result;
                }
                if($user['is_available'] === 0){
                    $result['message'] = 'Ce compte n\'est pas encore activé';
                    return $result;
                }

                // Ok - Create session
                $this->createSession($user);
                $result['isConnecting'] = true;
                $result['message'] = 'Bienvenu ' . $user['username'] . ' !';

                return $result;
            }
        } catch (Exception $exception) {
            var_dump($exception);
        }
        $result['message'] = 'Une erreur est survenu lors de la connection';
        return $result;
    }

    public function sendMailResetPassword($post): array
    {
        $result['isSend'] = false;
        $result['message'] = '';

        try {
            if ($post != null) {
                $email = $post['email'];
                $user = $this->userRepository->getUserByEmail($email);

                // Test input
                if (!$user) {
                    $result['message'] = 'Email incorect';
                    return $result;
                }
                if ($user['is_available'] === 0) {
                    $result['message'] = 'Votre compte n\'est pas activé';
                }

                // Ok - Send reset mail password
                $token = bin2hex(random_bytes(32));
                $link = $_SERVER['HTTP_HOST'] . '/reset?' . $token;

                if ($this->mailsManager->sendResetMail($email, $link, $user['username'])
                    && $this->resetPasswordRepository->setResetPassword($token, $user['user_id'])) {

                    $result['isSend'] = true;
                    $result['message'] = 'Un mail de reinitialisation vous à été envoyé';
                    return $result;
                }

            }
        } catch (Exception $exception) {
            var_dump($exception);
        }
        $result['message'] = 'Une erreur est survenue';
        return $result;
    }

    public function resetPassword($post): array
    {
        $result['isReset'] = false;
        $result['message'] = 'Une erreur est survenue';

        try {
            if ($post != null) {
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
                    and $this->resetPasswordRepository->setIsUsed($token)){

                    $result['isReset'] = true;
                    $result['message'] = 'Le mot de passe a bien été modifié <br> Redirection à la page de connexion';
                    return $result;
                }

            }
        } catch (Exception $exception) {
            var_dump($exception);
        }

        return $result;
    }

    public function updateAccount($post): array
    {
        $result['isUpdate'] = false;
        $result['message'] = 'Une erreur est survenue';

        try {
            if ($post != null) {
                $username = $post['username'];
                $email = $post['email'];
                $login = $post['login'];
                $userId = $post['userId'];
                $user = $this->userRepository->getUserById($userId);

                // Test input
                if (!$user) {
                    return $result;
                }
                if($username === $user['username'] and $login === $user['login'] and $email === $user['email']){
                    $result['message'] = 'Votre profil est déjà à jour';
                    return $result;
                }
                if ($username != $user['username']){
                    if ($this->contactRepository->getContactsByUsername($username)) {
                        $result['message'] = 'Le nom d\'utilisateur est déja pris.';
                        return $result;
                    }
                }
                if ($login != $user['login']){
                    if ($this->userRepository->getUserByLogin($login)) {
                        $result['message'] = 'Ce login est déja pris';
                        return $result;
                    }
                }
                if ($email != $user['email']){
                    if ($this->contactRepository->getContactsByEmail($email)) {
                        $result['message'] = 'Cet adresse email est déjà utilisé';
                        return $result;
                    }
                }

                // Ok - Update account
                $contactId = $user['contact_id'];
                if ($this->userRepository->updateAccount($userId, $login) and $this->contactRepository->updateContact($contactId, $username, $email)){
                    $result['message'] = 'Votre compte à bien été mis à jour.';
                    $result['isUpdate'] = true;

                    //Update session
                    $user = $this->userRepository->getUserById($userId);
                    $this->createSession($user);

                    return $result;
                }

            }
        } catch (Exception $exception) {
            var_dump($exception);
        }

        return $result;
    }

    public function updatePassword($post): array
    {
        $result['isUpdate'] = false;
        $result['message'] = 'Une erreur est survenue';

        try {
            if ($post != null) {;
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
                if (!password_verify($password, $user['password'])) {
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
            var_dump($exception);
        }

        return $result;
    }

    public function getUserByResetToken($token) : ?array
    {
        $now = date('Y-m-d h:i:s');
        $user = $this->resetPasswordRepository->getResetUserByToken($token);
        if ($user == null){
            return null;
        }
        if ($user['is_used']){
            return null;
        }
        if ($user['expiration_date'] < $now){
            return null;
        }

        return $user;
    }

    public function isLoginExist($login): array
    {
        $result = $this->userRepository->getUserByLogin($login);

        return ['exist' => $result];
    }

    public function isUsernameExist($username): array
    {
        $result = $this->contactRepository->getContactsByUsername($username);
        
        return ['exist' => $result];
    }

    public function createSession($user): void
    {
        $_SESSION['username'] = $user['username'];
        $_SESSION['login'] = $user['login'];
        $_SESSION['userId'] = $user['user_id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];
    }

    public function removeSession(): void
    {
        session_destroy();
    }

    public function askIfErrorAdmin($isError): ?String
    {
        $errorMessage = null;
        if ($isError === '1'){
            $errorMessage = 'L\'acces à l\'admin est réserver aux administrateur.';
        }

        return $errorMessage;
    }
}