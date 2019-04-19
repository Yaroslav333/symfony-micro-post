<?php

namespace App\Controller;


use App\Service\Greeting;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class BlogController
 * @package App\Controller
 * @Route("/blog")
 */
class BlogController extends AbstractController
{
    /**
     * @var Greeting
     */
    private $greeting;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(
        Greeting $greeting,
        SessionInterface $session,
        RouterInterface $router
    )
    {
        $this->greeting = $greeting;
        $this->session = $session;
        $this->router = $router;
    }

    /**
     * @Route("/", name="blog_index")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
        return $this->render('blog/index.html.twig',
            [
                'posts' => $this->session->get('posts')
            ]
        );
    }

    /**
     * @Route("/add", name="blog_add")
     */
    public function add()
    {
        $posts = $this->session->get('posts');
        $posts[uniqid('', true)] = [
            'title' => 'My super title' . rand(1, 10),
            'text'  => 'Some Text' . rand(1, 10),
            'date'  => new \DateTime()
        ];

        $this->session->set('posts', $posts);

        return new RedirectResponse($this->router->generate('blog_index'));
    }

    /**
     * @Route("/show/{id}", name="blog_show")
     */
    public function show($id)
    {
        $posts = $this->session->get('posts');

        if (!$posts || !isset($posts[$id])) {
             throw new NotFoundHttpException('Post not found');
        }

        return $this->render('blog/post.html.twig',
            [
                'id' => $id,
                'post' => $posts[$id]
            ]
        );
    }
}