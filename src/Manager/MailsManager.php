<?php

namespace Manager;

use Exception;

require_once ROOT . '/config/config.php';

class MailsManager
{
    public function __construct()
    {

    }

    public function sendMailToCreator($post): array
    {
        $result['isSend'] = false;
        $result['message'] = '';

        try {
            if ($post != null) {
                $to = TO;
                $subject = $post['subject'];
                $message = $post['message'];
                $headers = "From: (Email) " . $post['email'];

                if (mail($to, $subject, $message, $headers)) {
                    $result['isSend'] = true;
                    $result['message'] = 'Votre mail à bien été envoyé';
                    return $result;
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