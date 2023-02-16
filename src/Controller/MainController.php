<?php

namespace Controller;

require_once ROOT.'config/config.php';

use Repository;

class MainController
{
    private \Twig\Environment $twig;

    public function __construct()
    {
        $loader = new \Twig\Loader\FilesystemLoader(ROOT.'templates');
        $twig = new \Twig\Environment($loader);
        $this->twig = $twig;
    }

    public function index($post)
    {
        $data = '';
        if (isset($post['submit'])) {
            $to = "raynaud.timothe@gmail.com";
            $subject = $post['subject'];
            $message = $post['message'];
            $headers = "From: (Email) " . $post['email'] . " (Firstname) " . $post['firstname'] . " (Lastname) " . $post['lastname'];

            if (mail($to, $subject, $message, $headers)) {
                $data = 'sendMail';
            } else {
                $data = 'errorMail';
            }
        }
        echo $this->twig->render('front/pages/home.html.twig', [
            'data' => $data,
        ]);
    }

    public function blog()
    {
        $postsRepository = new Repository\PostsRepository();
        $posts = $postsRepository->getAllPosts();
        echo $this->twig->render('front/pages/blog.html.twig', [
            'posts' => $posts,
        ]);
    }

    public function post($id)
    {
        $postsRepository = new Repository\PostsRepository();
        $contactsRepository = new Repository\ContactRepository();
        $post = $postsRepository->getPostById($id);
        $createdBy = $contactsRepository->getContactsById($post['created_by']);

        echo $this->twig->render('front/pages/post.html.twig', [
            'post' => $post,
            'createdBy' => $createdBy,
        ]);
    }

    public function login()
    {
        echo $this->twig->render('front/pages/login.html.twig', [
        ]);
    }
}
