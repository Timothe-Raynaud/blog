<?php

namespace Manager;

use Exception;
use PHPMailer\PHPMailer\PHPMailer;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
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


    /**
     * @throws \PHPMailer\PHPMailer\Exception
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function sendMailToCreator(array $post): array
    {
        $result['isSend'] = false;
        $result['message'] = '';

        if (!empty($post)) {
            $template = $this->twig->load('email/contact.html.twig');
            $body = $template->render([
                'message' => $post['message']
            ]);
            $this->phpMailer->setFrom($post['email']);
            $this->phpMailer->addAddress(MAIL_TO);
            $this->phpMailer->Subject = $post['subject'];
            $this->phpMailer->Body = $body;

            if ($this->phpMailer->send()) {
                $result['isSend'] = true;
                $result['message'] = 'Votre mail à bien été envoyé';
                return $result;
            }


            $result['message'] = 'Une erreur est survenue lors de l\'envoi du mail';
            return $result;
        }


        return $result;
    }


    /**
     * @throws \PHPMailer\PHPMailer\Exception
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
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

        if ($this->phpMailer->send()){
            $this->phpMailer->send();
            return true;
        }

        return false;
    }
}