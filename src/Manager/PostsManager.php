<?php

namespace Manager;

use Exception;
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

    /**
     * @throws Exception
     */
    public function addPost(array $post): array
    {
        $result['isAdd'] = false;

        if (empty($this->session['userId'])){
            $result['message'] = 'Vous devez être connecté';
            return $result;
        }

        if (empty($post)) {
            $result['message'] = 'Une erreur est survenue.';
            return $result;
        }

        $title = $post['title'];
        $chapo = $post['chapo'];
        $content = nl2br($post['content']);

        if ($this->postsRepository->addPost($title, $chapo, $content, $this->session['userId'])){
            $result['isAdd'] = true;

            return $result;
        }

        $result['message'] = 'Une erreur est survenue.';

        return $result;
    }

    /**
     * @throws Exception
     */
    public function updatedPost(int $postId, array $post): array
    {
        $result['isUpdated'] = false;
        $result['message'] = 'Une erreur est survenue.';

        if (empty($this->session['userId'])){
            $result['message'] = 'Vous devez être connecté';
            return $result;
        }

        if (empty($post)) {
            return $result;
        }

        $title = $post['title'];
        $chapo = $post['chapo'];
        $content = nl2br($post['content']);

        if ($this->postsRepository->updatePost($postId, $title, $chapo, $content, $this->session['userId'])){
            $result['isUpdated'] = true;

            return $result;
        }

        return $result;
    }

    public function getMessage(string $type) : ?array
    {
        return match ($type){
            'postIsCreate' => ['type' => 'success', 'message' => 'Votre post à bien été fait.'],
            'postIsUpdated' => ['type' => 'success', 'message' => 'Votre post à été mis à jour'],
            default => null,
        };
    }
}
