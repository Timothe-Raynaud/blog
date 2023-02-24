<?php

Namespace Manager;

require_once ROOT.'/config/config.php';

use Repository\UserRepository;
use Repository\ContactRepository;

class UserManager
{
    private UserRepository $userRepository;
    private ContactRepository $contactRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
        $this->contactRepository = new ContactRepository();
    }

    public function isLoginExist($login) : void
    {
        if($this->userRepository->getUserByLogin($login)){
            echo json_encode( array('exist' => 'true'));
        } else{
            echo json_encode( array('exist' => 'false'));
        }
    }

    public function isUsernameExist($username) : void
    {
        if($this->contactRepository->getContactsByUsername($username)){
            echo json_encode( array('exist' => 'true'));
        } else{
            echo json_encode( array('exist' => 'false'));
        }
    }

    public function  addNewUser($post) : array
    {
        $result['isAdd'] = false;
        $result['message'] = '';

        try{
            if ($post != null) {
                $email = $post['email'];
                $username = $post['username'];
                $login = $post['login'];
                $password = $post['password'];
                if ($this->contactRepository->getContactsByUsername($username)){
                    $result['message'] = 'Le nom d\'utilisateur est déja pris.';
                    return $result;
                }else if ($this->userRepository->getUserByLogin($login)) {
                    $result['message'] = 'Ce login est déja pris';
                    return $result;
                } else if ($this->contactRepository->getContactsByEmail($email)){
                    $result['message'] = 'Cet adresse email est déjà utilisé';
                    return $result;
                } else{
                    if ($this->contactRepository->setContact($username, $email)){
                        $password = password_hash($password, PASSWORD_DEFAULT);
                        $contact = $this->contactRepository->getContactsByUsername($username);
                        if($this->userRepository->setUser($login, $password, 'DEFAULT', $contact['contact_id'])){
                            $result['isAdd'] = true;
                            $result['message'] = 'Le compte à bien été créer';
                            return $result;
                        }
                    }
                }
            }
        } catch (Exception $exception){
            if(DEV_ENVIRONMENT){
                var_dump($exception);
            }
        }
        $result['message'] = 'Une erreur est survenue lors de l\'inscription';
        return $result;
    }

    public function  connecting($post) : array
    {
        $result['isAdd'] = false;
        $result['message'] = '';

        try{
            if ($post != null) {
                $login = $post['login'];
                $password = $post['password'];

                $user = $this->userRepository->getUserByLogin($login);
                if ($user){
                    if (password_verify($password, $user['password'])){
                        $this->createSession($user);
                        $result['isAdd'] = true;
                        $result['message'] = 'Bienvenu '. $user['username'] . ' !';
                        return $result;
                    } else {
                        $result['message'] = 'Mot de passe incorect';
                        return $result;
                    }
                } else{
                    $result['message'] = 'Login incorect';
                    return $result;
                }
            }
        } catch (Exception $exception){
            if(DEV_ENVIRONMENT){
                var_dump($exception);
            }
        }
        $result['message'] = 'Une erreur est survenu lors de la connection';
        return $result;
    }

    public function createSession($user) : void
    {
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
    }

    public function removeSession() : void
    {
        session_destroy();
    }

}