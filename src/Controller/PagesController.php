<?php

namespace App\Controller;

use App\Repository\BlogPostRepository;
use App\Repository\CommentRepository;
use Core\Security;
use Nette\Mail\Message;
use Core\Attribute\Route;
use Core\Response\Response;
use Nette\Mail\SendmailMailer;
use Core\Response\RedirectResponse;

class PagesController {

    private readonly Security $security;
    private readonly BlogPostRepository $blogPostRepository;
    private readonly CommentRepository $commentRepository;

    public function __construct() {
        $this->security = new Security();
        $this->blogPostRepository = new BlogPostRepository();
        $this->commentRepository = new CommentRepository();
    }

    #[Route(path: '/', name: 'index', methods: ['GET'])]
    public function index(): RedirectResponse {
        return new RedirectResponse('home');
    }

    #[Route(path: '/home', name: 'home', methods: ['GET'])]
    public function home(): Response {
        $articles = $this->blogPostRepository->findAll();

        $comments = [];
        foreach ($articles as $key => $value) {
            $comments[$key] = $this->commentRepository->countBy(['validated' => true, 'blog_post_id' => $value->getId()]);
        }

        return new Response('home.html.twig', [
            'blogPosts' => $articles,
            'comments' => $comments
        ]);
    }

    #[Route(path: '/contact', name: 'contact', methods: ['GET', 'POST'])]
    public function contact(): Response {
        $message = null;
        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!$this->security->isCsrfTokenValid('contact', $_POST['_csrf_token'])) {
                $message = "Une erreur est survenue, veuillez réessayer.";
            } else {
                $username = filter_var(htmlspecialchars(strip_tags(trim(addslashes($_POST['username'])))), FILTER_SANITIZE_EMAIL);
                if (!$username || !$this->checkEmail($username)) {
                    $error = 2;
                    $message = "L'adresse email n'est pas valide.";
                } else {
                    $message = htmlspecialchars(strip_tags(trim(addslashes($_POST['message']))));
                    $nomprenom = htmlspecialchars(strip_tags(trim(addslashes($_POST['nomprenom']))));
                    $mail = new Message;
                    $mail->setFrom("$nomprenom <$username>")
                        ->addTo('thomas@tydoo.fr')
                        ->setSubject('Mesage du blog')
                        ->setBody($message);

                    $mailer = new SendmailMailer;
                    $mailer->send($mail);
                    return new RedirectResponse('contact_done');
                }
            }
        }

        return new Response(
            'contact.html.twig',
            [
                'message' => $message,
                'error' => $error,
                '_csrf_token' => $this->security->generateToken('contact')
            ]
        );
    }

    #[Route('/contact/done', name: 'contact_done', methods: ['GET'])]
    public function contact_done(): Response {
        return new Response('contact_done.html.twig');
    }

    private function checkEmail(string $email): bool {
        $pattern = '/^\S+@\S+\.\S+$/';

        if (preg_match($pattern, $email)) {
            return true;
        } else {
            return false;
        }
    }
}
