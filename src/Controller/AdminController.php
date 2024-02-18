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
use Imagine\Image\Box;
use Imagine\Gd\Imagine;

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
            'currentSeason' => $this->bdd->getRepository(Seasons::class)->getCurrentSeason(),
            'nbPassedSeasons' => $this->bdd->getRepository(Seasons::class)->getNumberOfPastSeasons(),
            'nbUpcommingSeasons' => $this->bdd->getRepository(Seasons::class)->getNumberOfUpcomingSeasons(),
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
            "animes" => $this->bdd->getRepository(Animes::class)->findBy(['season' => $seasonId]),
        ];
        return $this->render('admin/seasons/edit.html.twig', $variables);
    }

    #[Route('/dashboard/seasons/{seasonId}/add-anime', name: 'admin_seasons_add_anime')]
    public function admin_seasons_add_anime(int $seasonId): Response
    {
        $season = $this->bdd->getRepository(Seasons::class)->find($seasonId);
        if($this->request->isMethod('POST') && count($this->request->get('animes')) > 0) {
            foreach ($this->request->get('animes') as $requestAnimeId) {
                $anime = $this->bdd->getRepository(Animes::class)->find($requestAnimeId);
                $anime->setSeasonId($season);
                $this->bdd->getRepository(Animes::class)->save($anime);
            }
            return $this->redirectToRoute('admin_seasons_edit', ['seasonId' => $seasonId]);
        }
        
        $variables = [
            "season" => $season,
            "availableAnime" => $this->bdd->getRepository(Animes::class)->findBy(['season' => null])
        ];
        return $this->render('admin/seasons/addAnime.html.twig', $variables);
    }

    #[Route('/dashboard/seasons/{seasonId}/delete-anime/{animeId}', name: 'admin_seasons_delete_anime')]
    public function admin_seasons_delete_anime(int $seasonId, int $animeId): Response
    {
        $anime = $this->bdd->getRepository(Animes::class)->find($animeId);
        $anime->setSeasonId(null);
        $this->bdd->getRepository(Animes::class)->save($anime);
        return $this->redirectToRoute('admin_seasons_edit', ['seasonId' => $seasonId]);
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
        $variables = [
            "animes" => $this->bdd->getRepository(Animes::class)->findAll(),
        ];
        return $this->render('admin/animes/index.html.twig', $variables);
    }

    #[Route('/dashboard/animes/add', name: 'admin_add_animes')]
    public function admin_add_animes(): Response
    {
        if ($this->request->isMethod('POST')) {
            $linkImg = $this->compressImage($this->request->files->get('thumbnail'),$this->cleanString($this->request->get('title')));
            $anime = new Animes();
            if($this->request->get('season')) {
                $anime->setSeasonId($this->bdd->getRepository(Seasons::class)->find($this->request->get('season')));
            }
            $anime->setThumbnailLink($linkImg);
            $anime->setTitle($this->request->get('title'));
            $anime->setSummary($this->request->get('summary'));
            $anime->setPublishDay($this->request->get('publishDay'));
            $anime->setPublishTime(new \DateTime($this->request->get('publishTime')));
            $anime->setPublishStart(new \DateTime($this->request->get('publishStart')));
            $anime->setNbEpisodes($this->request->get('nbEpisodes'));
            $this->bdd->getRepository(Animes::class)->save($anime);
            return $this->redirectToRoute('admin_animes');
        }

        $variables = [
            'seasons' => $this->bdd->getRepository(Seasons::class)->findAll(),
        ];
        return $this->render('admin/animes/addAnime.html.twig', $variables);
    }

    #[Route('/dashboard/animes/{animeId}/edit', name: 'admin_animes_edit')]
    public function admin_animes_edit(int $animeId): Response
    {
        $anime = $this->bdd->getRepository(Animes::class)->find($animeId);
        if ($this->request->isMethod('POST')) {
            if($this->request->files->get('thumbnail')) {
                $linkImg = $this->compressImage($this->request->files->get('thumbnail'),$this->cleanString($this->request->get('title')));
                $anime->setThumbnailLink($linkImg);
            }
            $anime->setSeasonId($this->bdd->getRepository(Seasons::class)->find($this->request->get('season')));
            if($this->request->get('season') == '') {
                $anime->setSeasonId(null);
            }
            $anime->setTitle($this->request->get('title'));
            $anime->setSummary($this->request->get('summary'));
            $anime->setPublishDay($this->request->get('publishDay'));
            $anime->setPublishTime(new \DateTime($this->request->get('publishTime')));
            $anime->setPublishStart(new \DateTime($this->request->get('publishStart')));
            $anime->setNbEpisodes($this->request->get('nbEpisodes'));
            $this->bdd->getRepository(Animes::class)->save($anime);
        }
        $variables = [
            "anime" => $anime,
            'seasons' => $this->bdd->getRepository(Seasons::class)->findAll(),
        ];
        return $this->render('admin/animes/edit.html.twig', $variables);
    }

    public function compressImage($file, $name)
    {
        $imagine = new Imagine();
        $image = $imagine->open($file->getPathname());

        // Redimensionnez l'image à 720p
        $size = $image->getSize();
        $ratio = $size->getWidth() / $size->getHeight();
        $newHeight = 720;
        $newWidth = $newHeight * $ratio;

        $image->resize(new Box($newWidth, $newHeight));

        // Définir le chemin de sauvegarde
        $uploadsDir = $this->getParameter('uploads_directory'); // Assurez-vous que ce paramètre est défini dans services.yaml
        $newFilename = $name . '.' . $file->guessExtension();

        // Sauvegarder l'image
        $image->save($uploadsDir.'/'.$newFilename);

        return $newFilename;
    }

    public function cleanString($string) {
        // Retirez les caractères spéciaux
        $string = preg_replace('/[^A-Za-z0-9\- ]/', '', $string);
    
        // Convertissez en minuscules
        $string = strtolower($string);
    
        // Remplacez les espaces par des tirets
        $string = str_replace(' ', '-', $string);
    
        return $string;
    }
}
