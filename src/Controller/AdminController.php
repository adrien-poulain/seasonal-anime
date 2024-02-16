<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Entity\Animes;
use App\Entity\Seasons;
use App\Entity\User;

class AdminController extends AbstractController
{
    public function __construct(EntityManagerInterface $em, RequestStack $rs)
    {
        $this->bdd = $em;
        $this->request = $rs->getCurrentRequest();
        $this->session = $rs->getSession();
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
        if($this->request->request->all()) {
            $user = new User();
            $user->setUsername($this->request->get('username'));
            $user->setPassword($this->request->get('password'));
            $this->bdd->getRepository(User::class)->save($user);
        }
        $variables = [
            "users" => $this->bdd->getRepository(User::class)->findAll()
        ];
        return $this->render('admin/users/index.html.twig', $variables);
    }

    #[Route('/dashboard/seasons', name: 'admin_seasons')]
    public function admin_seasons(): Response
    {
        $variables = [
            'seasons' => $this->bdd->getRepository(Seasons::class)->findAll(),
        ];
        return $this->render('admin/seasons/index.html.twig', $variables);
    }

    #[Route('/dashboard/seasons/add', name: 'admin_seasons_add')]
    public function admin_seasons_add(): Response
    {
        if($this->request->request->all()) {
            $season = new Seasons();
            $season->setLibelle($this->request->get('libelle'));
            $season->setStart(new \DateTime($this->request->get('start')));
            $season->setUpdatedAt(new \DateTime('now'));
            $this->bdd->getRepository(Seasons::class)->save($season);
            return $this->redirectToRoute('admin_seasons');
        }
        return $this->render('admin/seasons/add.html.twig');
    }

    #[Route('/dashboard/seasons/{seasonId}/edit', name: 'admin_seasons_edit')]
    public function admin_seasons_edit(int $seasonId): Response
    {
        $season = $this->bdd->getRepository(Seasons::class)->find($seasonId);
        if($this->request->request->all()) {
            $season->setLibelle($this->request->get('libelle'));
            $season->setStart(new \DateTime($this->request->get('start')));
            $season->setUpdatedAt(new \DateTime('now'));
            $this->bdd->getRepository(Seasons::class)->save($season);
        }
        $variables = [
            "season" => $season,
        ];
        return $this->render('admin/seasons/edit.html.twig', $variables);
    }
    #[Route('/dashboard/seasons/{seasonId}/add-anime', name: 'admin_seasons_add_anime')]
    public function admin_seasons_add_anime(int $seasonId): Response
    {
        $season = $this->bdd->getRepository(Seasons::class)->find($seasonId);
        
        $variables = [
            "season" => $season,
        ];
        return $this->render('admin/seasons/addAnime.html.twig', $variables);
    }

    #[Route('/dashboard/seasons/{seasonId}/delete', name: 'admin_seasons_delete')]
    public function admin_seasons_delete(int $seasonId): Response
    {
        $season = $this->bdd->getRepository(Seasons::class)->find($seasonId);
        $this->bdd->getRepository(Seasons::class)->remove($season);
        return $this->redirectToRoute('admin_seasons');
    }

    #[Route('/dashboard/animes', name: 'admin_animes')]
    public function admin_animes(): Response
    {
        return $this->render('admin/animes/index.html.twig');
    }
}
