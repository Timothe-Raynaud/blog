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
        if($this->userRepository->getUserByLogin($login) != null){
            echo json_encode( array('exist' => 'true'));
        } else{
            echo json_encode( array('exist' => 'false'));
        }
    }

    public function isUsernameExist($username) : void
    {
        if($this->contactRepository->getContactsByUsername($username) != null){
            echo json_encode( array('exist' => 'true'));
        } else{
            echo json_encode( array('exist' => 'false'));
        }
    }

    public function  addNewUser($login, $password, $email, $firstname, $lastname) : String
    {
        return '';
    }
}