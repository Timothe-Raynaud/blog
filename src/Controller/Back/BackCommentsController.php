<?php

namespace Controller\Back;

use Config\Config;
use Exception;
use Repository\CommentsRepository;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

class BackCommentsController
{
    private Environment $twig;
    private CommentsRepository $commentsRepository;

    private array $session;

    public function __construct()
    {
        $loader = new FilesystemLoader(Config::$ROOT  . '/templates');
        $this->twig = new Environment($loader);
        $this->commentsRepository = new CommentsRepository();
        $this->session = $_SESSION;
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function comments(): void
    {
        if (!isset($this->session['role'])) {
            header("Location: login?1");
        } else if ($this->session['role'] != 'ADMIN') {
            header("Location: my-account?1");
        } else {
            $comments = $this->commentsRepository->getAllComments();
            $numberOfValidatedComments = $this->commentsRepository->countValidatedCommentByPostId()['validatedPost'] ?? 0;

            echo $this->twig->render('back/pages/comments.html.twig', [
                'session' => $this->session,
                'comments' => $comments,
                'numberOfValidatedComments' => $numberOfValidatedComments,
            ]);
        }
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function commentValidation(int $comment_id): void
    {
        if (!isset($this->session['role'])) {
            header("Location: login?1");
        } else if ($this->session['role'] != 'ADMIN') {
            header("Location: my-account?1");
        } else {
            $this->commentsRepository->setIsAvailable($comment_id);

            $this->comments();
        }
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function commentBlockation(int $comment_id): void
    {
        if (!isset($this->session['role'])) {
            header("Location: login?1");
        } else if ($this->session['role'] != 'ADMIN') {
            header("Location: my-account?1");
        } else {
            $this->commentsRepository->setIsNotAvailable($comment_id);

            $this->comments();
        }
    }

}
