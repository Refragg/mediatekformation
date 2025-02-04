<?php

declare(strict_types=1);

namespace App\Controller\admin;

use App\Entity\Formation;
use App\Entity\Playlist;
use App\Form\PlaylistsType;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Contrôleur gérant les pages d'administration des formations
 */
class AdminPlaylistsController extends AbstractController
{
    /**
     * Le chemin constant de la template Twig à afficher
     * @const PAGE_ADMIN_PLAYLISTS
     */
    private const PAGE_ADMIN_PLAYLISTS = 'pages/admin/admin.playlists.html.twig';

    /**
     * L'objet faisant l'interface entre les données des playlists et le contrôleur
     * @var PlaylistRepository
     */
    private $playlistRepository;

    /**
     * L'objet faisant l'interface entre les données des formations et le contrôleur
     * @var FormationRepository
     */
    private $formationRepository;

    /**
     * L'objet faisant l'interface entre les données des catégories et le contrôleur
     * @var CategorieRepository
     */
    private $categorieRepository;

    /**
     * Constructeur du contrôleur
     * @param PlaylistRepository $playlistRepository Injecté par Symfony
     * @param FormationRepository $formationRepository Injecté par Symfony
     * @param CategorieRepository $categorieRepository Injecté par Symfony
     */
    public function __construct(PlaylistRepository $playlistRepository, FormationRepository $formationRepository, CategorieRepository $categorieRepository) {
        $this->playlistRepository = $playlistRepository;
        $this->formationRepository = $formationRepository;
        $this->categorieRepository= $categorieRepository;
    }

    /**
     * Route d'index pour l'administration des playlists
     * @return Response
     */
    #[Route('/admin/playlists', name: 'admin.playlists')]
    public function index(): Response
    {
        $playlists = $this->playlistRepository->findAllOrderByName('ASC');
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::PAGE_ADMIN_PLAYLISTS, [
            'playlists' => $playlists,
            'categories' => $categories
        ]);
    }

    /**
     * Route de tri pour l'administration des playlists
     * @param string $champ Sur quel champ doit-on trier les enregistrements
     * @param string $ordre Dans quel ordre doit-on trier les enregistrements
     * @return Response
     */
    #[Route('/admin/playlists/tri/{champ}/{ordre}', name: 'admin.playlists.sort')]
    public function sort($champ, $ordre): Response{
        if ($champ === 'name') {
            $playlists = $this->playlistRepository->findAllOrderByName($ordre);
        } elseif ($champ === 'count') {
            $playlists = $this->playlistRepository->findAllOrderByCount($ordre);
        } else {
            $playlists = $this->playlistRepository->findAll();
        }

        $categories = $this->categorieRepository->findAll();
        return $this->render(self::PAGE_ADMIN_PLAYLISTS, [
            'playlists' => $playlists,
            'categories' => $categories
        ]);
    }

    /**
     * Route de filtre pour l'administration des playlists
     * @param string $champ Sur quel champ doit-on filtrer les enregistrements
     * @param Request $request La requête actuelle (injecté par Symfony)
     * @param string $table Si $champ dans une autre table
     * @return Response
     */
    #[Route('/admin/playlists/recherche/{champ}/{table}', name: 'admin.playlists.findallcontain')]
    public function findAllContain($champ, Request $request, $table=""): Response{
        $valeur = $request->get("recherche");
        $playlists = $this->playlistRepository->findByContainValue($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::PAGE_ADMIN_PLAYLISTS, [
            'playlists' => $playlists,
            'categories' => $categories,
            'valeur' => $valeur,
            'table' => $table
        ]);
    }

    /**
     * Route de suppression de playlist pour l'administration des playlists
     * @param int $id L'identifiant de la playlist à supprimer
     * @return Response
     */
    #[Route('/admin/playlists/delete?{id}', name: 'admin.playlists.delete')]
    public function delete(int $id): Response {
        $playlist = $this->playlistRepository->find($id);
        if ($playlist->getFormationsCount() > 0)
            return new Response("Cette playlist ne peut être supprimée car elle n'est pas vide.", Response::HTTP_FORBIDDEN);

        $this->playlistRepository->remove($playlist);
        return $this->redirectToRoute('admin.playlists');
    }

    /**
     * Route de modification d'une playlist pour l'administration des playlists.
     *
     * Selon la requête cette route modifie la playlist demandée ou bien, elle
     * affiche le formulaire de modification d'une playlist
     * @param int $id L'identifiant de la playlist à modifier
     * @param Request $request La requête actuelle (injecté par Symfony)
     * @return Response
     */
    #[Route('/admin/playlists/edit?{id}', name: 'admin.playlists.edit')]
    public function edit(int $id, Request $request): Response {
        $playlist = $this->playlistRepository->find($id);
        $formations = $this->formationRepository->findAllForOnePlaylist($id);
        $categories = $this->categorieRepository->findAll();

        $formPlaylists = $this->createForm(PlaylistsType::class, $playlist);

        $formPlaylists->handleRequest($request);
        if ($formPlaylists->isSubmitted() && $formPlaylists->isValid()) {
            $this->playlistRepository->addOrEdit($playlist);
            return $this->redirectToRoute('admin.playlists');
        }

        return $this->render('pages/admin/admin.playlists.edit.html.twig', [
            'playlist' => $playlist,
            'formplaylists' => $formPlaylists->createView(),
            'formations' => $formations,
            'categories' => $categories
        ]);
    }

    /**
     * Route d'addition d'une playlist pour l'administration des playlists.
     *
     * Selon la requête cette route ajoute la playlist demandée ou bien, elle
     * affiche le formulaire d'addition d'une playlist
     * @param Request $request La requête actuelle (injecté par Symfony)
     * @return Response
     */
    #[Route('/admin/playlists/add', name: 'admin.playlists.add')]
    public function addFormation(Request $request): Response{
        $playlist = new Playlist();
        $formPlaylists = $this->createForm(PlaylistsType::class, $playlist);

        $formPlaylists->handleRequest($request);
        if ($formPlaylists->isSubmitted() && $formPlaylists->isValid()) {
            $this->playlistRepository->addOrEdit($playlist);
            return $this->redirectToRoute('admin.playlists');
        }

        return $this->render('pages/admin/admin.playlists.add.html.twig', [
            'formplaylists' => $formPlaylists->createView(),
        ]);
    }
}
