<?php

namespace Manager;

require_once ROOT . '/config/config.php';

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

                // Test validity
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

                $token = bin2hex(random_bytes(32));
                $link = $_SERVER['HTTP_HOST'] . '/reset?' . $token;
                if ($this->mailsManager->sendResetMail($email, $link, $user['username']) && $this->resetPasswordRepository->setResetPassword($token, $user['user_id'])) {
                    $result['isSend'] = true;
                    $result['message'] = 'Un mail de resiliation vous à été envoyé';
                    return $result;
                }

            }
        } catch (Exception $exception) {
            var_dump($exception);
        }
        $result['message'] = 'Une erreur est survenu';
        return $result;
    }

    public function resetPassword($post): array
    {
        $result['isReset'] = false;

        try {
            if ($post != null) {
                $result['message'] = 'Une erreur est survenu';
                $firstPassword = $post['firstPassword'];
                $secondPassword = $post['secondPassword'];
                $userId = $post['userId'];
                $user = $this->userRepository->getUserById($userId);

                if (!$user) {
                    $result['message'] = 'Un problème est survenue, veuillez recommencer l\'opération depuis le debut.';
                    return $result;
                }
                if($firstPassword != $secondPassword){
                    $result['message'] = 'Les mot de passe doivent être identique';
                    return $result;
                }

                $password = password_hash($firstPassword, PASSWORD_DEFAULT);
                if ($this->userRepository->setPassword($userId, $password)){
                    $result['isReset'] = true;
                    $result['message'] = 'Le mot de passe a bien été modifié<br> Redirection à la page de conenction';
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
        if ($this->userRepository->getUserByLogin($login)) {
            return ['exist' => 'true'];
        }
        return ['exist' => 'false'];
    }

    public function isUsernameExist($username): array
    {
        if ($this->contactRepository->getContactsByUsername($username)) {
            return ['exist' => 'true'];
        }
        return ['exist' => 'false'];
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

}