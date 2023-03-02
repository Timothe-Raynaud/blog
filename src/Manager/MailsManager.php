<?php

namespace Manager;

use Exception;
use PHPMailer\PHPMailer\PHPMailer;

require_once ROOT . '/config/config.php';

class MailsManager
{
    private PHPMailer $phpMailer;

    public function __construct()
    {
        $this->phpMailer = new PHPMailer();
        $this->phpMailer->isSMTP();
        $this->phpMailer->Host = MAILHOST;
        $this->phpMailer->Port = MAILPORT;
        $this->phpMailer->SMTPAuth = MAILSMTPAUTH;
        $this->phpMailer->SMTPSecure = MAILSMTSECURE;
    }

    public function sendMailToCreator($post): array
    {
        $result['isSend'] = false;
        $result['message'] = '';

        try {
            if ($post != null) {
                $this->phpMailer->setFrom($post['email']);
                $this->phpMailer->addAddress(TO);
                $this->phpMailer->Subject = $post['subject'];
                $this->phpMailer->Body = $post['message'];

                try{
                    $this->phpMailer->Debugoutput = function($str, $level) { echo "$level: $str"; };
                    $this->phpMailer->send();
                    $result['isSend'] = true;
                    $result['message'] = 'Votre mail à bien été envoyé';
                    return $result;
                } catch (Exception $exception){
                    var_dump($exception);
                }

                $result['message'] = 'Une erreur est survenue lors de l\'envoi du mail';
                return $result;
            }
        } catch (Exception $exception) {
            var_dump($exception);
        }
        return $result;
    }

    public function sendResetMail($to, $token): bool
    {
        $subject = 'Reinitialisation d\'un mot de passe';
        $message = "Pour réinitialisé le mot de passe aller sur : <br>{$token}";

        if (mail($to, $subject, $message)) {
            return true;
        }

        return false;
    }
}