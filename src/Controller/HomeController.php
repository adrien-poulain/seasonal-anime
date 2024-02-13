<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Animes;

class HomeController extends AbstractController
{
    public function __construct(EntityManagerInterface $em)
    {
        $this->bdd = $em;
    }

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        // dump($this->bdd->getRepository(Animes::class)->findAll());
        return $this->render('home/index.html.twig');
    }
}
