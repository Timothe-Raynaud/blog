<?php

namespace Controller\Front;

use Config\Config;
use Exception;
use Manager\PostsManager;
use Manager\CommentsManager;
use Repository\PostsRepository;
use Repository\CommentsRepository;
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
    private CommentsManager $commentsManager;
    private CommentsRepository $commentsRepository;

    public function __construct()
    {
        $loader = new Twig\Loader\FilesystemLoader(Config::$ROOT .'/templates');
        $this->twig = new Twig\Environment($loader);
        $this->postsRepository = new PostsRepository();
        $this->postsManager = new PostsManager();
        $this->commentsRepository = new CommentsRepository();
        $this->commentsManager = new CommentsManager();
        $this->session = $_SESSION;
    }


    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    public function blog(?string $infos = null): void
    {

        $message = null;
        $currentPage = 1;

        if (!empty($infos)){

            parse_str($infos, $infosArray);

            if (isset($infosArray['message'])){
                $message = $this->postsManager->getMessage($infosArray['message']);
            }
            if ($infosArray['page']){
                $currentPage = $infosArray['page'];
            }

        }

        $posts = $this->postsRepository->getValidatedPosts();
        echo $this->twig->render('front/pages/blog/blog.html.twig', [
            'posts' => $posts,
            'session' => $this->session,
            'message' => $message,
            'currentPage' => $currentPage,
        ]);
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function post(int $postId): void
    {
        $post = $this->postsRepository->getPostById($postId);
        $comments = $this->commentsRepository->getValidatedCommentByPostId($postId);

        echo $this->twig->render('front/pages/blog/post.html.twig', [
            'post' => $post,
            'comments' => $comments,
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
    public function addPost(array $formPost): void
    {
        $result = $this->postsManager->addPost($formPost);

        if ($result['isAdd']){
            header("Location: blog?page=1&message=postIsCreate");
        } else {
            $result['type'] = 'danger';
            echo $this->twig->render('front/pages/blog/create_post.html.twig', [
                'session' => $this->session,
                'message' => $result,
            ]);
        }
    }

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     * @throws Exception
     */
    public function addComment(int $postId, array $formPost): void
    {
        $result = $this->commentsManager->addComment($postId, $formPost);

        $post = $this->postsRepository->getPostById($postId);
        $comments = $this->commentsRepository->getValidatedCommentByPostId($postId);

        echo $this->twig->render('front/pages/blog/post.html.twig', [
            'session' => $this->session,
            'message' => $result,
            'post' => $post,
            'comments' => $comments,
        ]);
    }

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     * @throws Exception
     */
    public function modifyPost(int $postId): void
    {
        if (!isset($this->session['role'])) {
            header("Location: login?2");
        } else if ($this->session['role'] === 'ADMIN' || $this->session['role'] === 'CREATOR' ) {
            $post = $this->postsRepository->getPostById($postId);

            echo $this->twig->render('front/pages/blog/modify_post.html.twig', [
                'session' => $this->session,
                'post' => $post,
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
    public function updatedPost(int $postId,array $formPost): void
    {
        $result = $this->postsManager->updatedPost($postId, $formPost);

        if ($result['isUpdated']){
            header("Location: blog?page=1&message=postIsUpdated");
        } else {
            $result['type'] = 'danger';
            echo $this->twig->render('front/pages/blog/create_post.html.twig', [
                'session' => $this->session,
                'message' => $result,
            ]);
        }
    }

}
