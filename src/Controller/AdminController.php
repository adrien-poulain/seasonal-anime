<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Entity\Animes;
use App\Entity\Seasons;

class AdminController extends AbstractController
{
    public function __construct(EntityManagerInterface $em, RequestStack $rs)
    {
        $this->bdd = $em;
        $this->request = $rs;
    }

    #[Route('/dashboard', name: 'admin_panel')]
    public function index(): Response
    {
        $variables = [
            'nbAnimesTotal' => $this->bdd->getRepository(Animes::class)->countAll(),
        ];
        return $this->render('admin/index.html.twig', $variables);
    }

    #[Route('/dashboard/users', name: 'admin_users')]
    public function admin_users(): Response
    {
        return $this->render('admin/users/index.html.twig');
    }

    #[Route('/dashboard/seasons', name: 'admin_seasons')]
    public function admin_seasons(): Response
    {
        return $this->render('admin/seasons/index.html.twig');
    }

    #[Route('/dashboard/animes', name: 'admin_animes')]
    public function admin_animes(): Response
    {
        return $this->render('admin/animes/index.html.twig');
    }
}
