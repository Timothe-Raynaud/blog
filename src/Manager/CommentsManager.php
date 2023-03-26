<?php

namespace Manager;

use Exception;
use Repository\CommentsRepository;

class CommentsManager
{
    private array $session;
    private CommentsRepository $commentsRepository;

    public function __construct()
    {
        $this->session = $_SESSION;
        $this->commentsRepository = new CommentsRepository();
    }

    /**
     * @throws Exception
     */
    public function addComment(int $postId, array $formPost): array
    {
        $result['isAdd'] = false;

        if (empty($this->session['userId'])){
            $result['message'] = 'Vous devez être connecté';
            return $result;
        }

        if (empty($formPost)) {
            $result['message'] = 'Une erreur est survenue.';
            return $result;
        }

        $content = $formPost['content'];

        if ($this->commentsRepository->setComment($postId, $content, $this->session['userId'])){
            $result['isAdd'] = true;

            return $result;
        }

        $result['message'] = 'Une erreur est survenue.';

        return $result;
    }

}
