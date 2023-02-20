<?php

namespace Manager;

use Exception;

require_once ROOT.'/config/config.php';

class MailsManager
{
    public function __construct()
    {

    }

    public function sendMailToCreator($post): string
    {
        try{
            if ($post != null) {
                $to = TO;
                $subject = $post['subject'];
                $message = $post['message'];
                $headers = "From: (Email) " . $post['email'] . " (Firstname) " . $post['firstname'] . " (Lastname) " . $post['lastname'];

                if (mail($to, $subject, $message, $headers)) {
                    return 'sendMail';
                }
                return 'errorMail';
            }
        } catch (Exception $exception){
            if(DEV_ENVIRONMENT){
                var_dump($e);
            }
            return false;
        }
        return false;
    }
}