<?php

namespace Manager;

use Repository\PostsRepository;

class PostsManager
{
    private array $session;
    private PostsRepository $postsRepository;

    public function __construct()
    {
        $this->session = $_SESSION;
        $this->postsRepository = new PostsRepository();
    }

    public function addPost(array $post): array
    {
        $result['isAdd'] = false;

        if (empty($this->session['user_id'])){
            $result['message'] = 'Vous devez être connecté';
            return $result;
        }

        if (empty($post)) {
            $result['message'] = 'Une erreur est survenue.';
            return $result;
        }

        $title = $post['title'];
        $content = $post['content'];
        $now = new \DateTime();

        if ($this->postsRepository-->addPost($title, $content, $this->session['user_id'], $now)){
            $result['isAdd'] = true;
            $result['message'] = 'Le post a été créer';

            return $result;
        }

        $result['message'] = 'Une erreur est survenue.';

        return $result;
    }
}
