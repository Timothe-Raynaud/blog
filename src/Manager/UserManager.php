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
                    $password = password_hash($password, PASSWORD_DEFAULT);
                    $contact = $this->contactRepository->getContactsByUsername($username);

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

    public function resetPassword($post): array
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

                $token = $_SERVER['HTTP_HOST'] . '/reset?' . bin2hex(random_bytes(32));
                if ($this->mailsManager->sendResetMail($email, $token) && $this->resetPasswordRepository->setResetPassword($token, $user['user_id'])) {
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

    public function createSession($user): void
    {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
    }

    public function removeSession(): void
    {
        session_destroy();
    }

}