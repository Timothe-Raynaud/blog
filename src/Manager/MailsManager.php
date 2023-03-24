<?php

namespace Manager;

use Exception;
use PHPMailer\PHPMailer\PHPMailer;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class MailsManager
{
    private PHPMailer $phpMailer;
    private Environment $twig;

    public function __construct()
    {
        $this->phpMailer = new PHPMailer();
        $this->phpMailer->isSMTP();
        $this->phpMailer->Host = MAIL_HOST;
        $this->phpMailer->Port = MAIL_PORT;
        $this->phpMailer->SMTPAuth = MAIL_SMTP_AUTH;
        $this->phpMailer->SMTPSecure = MAIL_SMTP_SECURE;
        $loader = new FilesystemLoader(ROOT . '/templates');
        $this->twig = new Environment($loader);
    }

    public function sendMailToCreator(array $post): array
    {
        $result['isSend'] = false;
        $result['message'] = '';

        if ($post != null) {
            $template = $this->twig->load('email/contact.html.twig');
            $body = $template->render([
                'message' => $post['message']
            ]);
            $this->phpMailer->setFrom($post['email']);
            $this->phpMailer->addAddress(MAIL_TO);
            $this->phpMailer->Subject = $post['subject'];
            $this->phpMailer->Body = $body;

            try{
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

        return $result;
    }

    public function sendResetMail(string $to, string $token, string $username): bool
    {
        $template = $this->twig->load('email/reset_password.html.twig');
        $body = $template->render([
            'token' => $token,
            'username' => $username,
        ]);
        $this->phpMailer->setFrom(MAIL_FROM);
        $this->phpMailer->addAddress($to);
        $this->phpMailer->Subject = 'Reinitialisation du mot de passe';
        $this->phpMailer->Body = $body;
        try{
            $this->phpMailer->send();
            return true;
        } catch (Exception $exception){
            var_dump($exception);
        }

        return false;
    }
}