<?php

namespace Controller\Front;

use Manager\PostsManager;
use Repository\PostsRepository;
use Twig;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class BlogController
{
    private Twig\Environment $twig;

    private array $session;
    private PostsManager $postsManager;
    private PostsRepository $postsRepository;

    public function __construct()
    {
        $this->postsRepository = new PostsRepository();
        $this->postsManager = new PostsManager();
        $loader = new Twig\Loader\FilesystemLoader(ROOT.'/templates');
        $this->twig = new Twig\Environment($loader);
        $this->session = $_SESSION;
    }


    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    public function blog(?string $message = null): void
    {
        $posts = $this->postsRepository->getAllPosts();
        echo $this->twig->render('front/pages/blog/blog.html.twig', [
            'posts' => $posts,
            'session' => $this->session,
        ]);
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function post(int $post_id): void
    {
        $post = $this->postsRepository->getPostById($post_id);

        echo $this->twig->render('front/pages/blog/post.html.twig', [
            'post' => $post,
            'session' => $this->session,
        ]);
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function createPost(): void
    {
        if (!isset($this->session['role'])) {
            header("Location: login?2");
        } else if ($this->session['role'] === 'ADMIN' || $this->session['role'] === 'CREATOR' ) {
            echo $this->twig->render('front/pages/blog/create_post.html.twig', [
                'session' => $this->session,
            ]);
        } else {
            header("Location: my-account?2");
        }
    }

    public function addPost(array $post): void
    {
        $result = $this->postsManager->addPost($post);
        if ($result['isAdd']){

        } else {
            header("Location: blog");
        }
    }

}
