<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Entity\Animes;

class HomeController extends AbstractController
{
    public function __construct(EntityManagerInterface $em, RequestStack $rs)
    {
        $this->bdd = $em;
        $this->request = $rs;
    }

    #[Route('/', name: 'home')]
    public function index(): Response
    {
        // dump($this->bdd->getRepository(Animes::class)->findAll());
        return $this->render('home/index.html.twig');
    }
}
