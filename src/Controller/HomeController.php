<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Entity\Seasons;
use Doctrine\Common\Collections\ArrayCollection;

class HomeController extends AbstractController
{
    public function __construct(EntityManagerInterface $em, RequestStack $rs)
    {
        $this->bdd = $em;
        $this->request = $rs->getCurrentRequest();
        $this->session = $rs->getSession();
    }

    #[Route('/', name: 'home')]
    public function index(): Response
    {
        $variables = [
            'sortedAnimes' => $this->bdd->getRepository(Seasons::class)->getAnimesByDayForCurrentSeason(),
        ];
        return $this->render('home/index.html.twig', $variables);
    }
}
