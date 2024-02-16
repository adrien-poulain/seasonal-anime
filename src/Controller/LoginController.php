<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Entity\User;

class LoginController extends AbstractController
{
    public function __construct(EntityManagerInterface $em, RequestStack $rs)
    {
        $this->bdd = $em;
        $this->request = $rs->getCurrentRequest();
        $this->session = $rs->getSession();
    }

    #[Route('/login', name: 'login')]
    public function index(): Response
    {
        $error = "";
        if($this->request->request->all()) {
            $user = $this->bdd->getRepository(User::class)->findOneBy(['username' => $this->request->get('username')]);
            if ($user && $user->getPassword() && password_verify($this->request->get('password'), $user->getPassword())) {
                $this->session->set('user', $user);
                return $this->redirectToRoute('home');
            } else {
                $error = "Identifiants incorrect !";
            }

        }
        $variables = [
            'error' => $error,
        ];
        return $this->render('login/index.html.twig', $variables);
    }

    #[Route('/logout', name: 'logout')]
    public function logout(): Response
    {
        $this->session->set('user', null);
        return $this->redirectToRoute('home');
    }
}
