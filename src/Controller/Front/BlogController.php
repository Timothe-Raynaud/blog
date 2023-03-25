<?php

namespace Controller\Front;

use Exception;
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
        $loader = new Twig\Loader\FilesystemLoader(ROOT .'/templates');
        $this->twig = new Twig\Environment($loader);
        $this->postsRepository = new PostsRepository();
        $this->postsManager = new PostsManager();
        $this->session = $_SESSION;
    }


    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    public function blog(?string $typeOfMessage = null): void
    {
        $message = null;
        if (!empty($typeOfMessage)){
            $message = $this->postsManager->getMessage($typeOfMessage);
        }
        $posts = $this->postsRepository->getValidatedPosts();
        echo $this->twig->render('front/pages/blog/blog.html.twig', [
            'posts' => $posts,
            'session' => $this->session,
            'message' => $message,
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

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     * @throws Exception
     */
    public function addPost(array $post): void
    {
        $result = $this->postsManager->addPost($post);

        if ($result['isAdd']){
            header("Location: blog?postIsCreate");
        } else {
            $result['type'] = 'danger';
            echo $this->twig->render('front/pages/blog/create_post.html.twig', [
                'session' => $this->session,
                'message' => $result,
            ]);
        }
    }
}
