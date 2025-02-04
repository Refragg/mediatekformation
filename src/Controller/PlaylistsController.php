<?php
namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of PlaylistsController
 *
 * @author emds
 */
class PlaylistsController extends AbstractController {

    /**
     * Le chemin constant de la template Twig à afficher
     * @const PAGE_PLAYLISTS
     */
    private const PAGE_PLAYLISTS = 'pages/playlists.html.twig';

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
     * @param CategorieRepository $categorieRepository Injecté par Symfony
     * @param FormationRepository $formationRespository Injecté par Symfony
     */
    public function __construct(PlaylistRepository $playlistRepository,
            CategorieRepository $categorieRepository,
            FormationRepository $formationRespository) {
        $this->playlistRepository = $playlistRepository;
        $this->categorieRepository = $categorieRepository;
        $this->formationRepository = $formationRespository;
    }
    
    /**
     * Route d'index pour les playlists
     * @Route("/playlists", name="playlists")
     * @return Response
     */
    #[Route('/playlists', name: 'playlists')]
    public function index(): Response{
        $playlists = $this->playlistRepository->findAllOrderByName('ASC');
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::PAGE_PLAYLISTS, [
            'playlists' => $playlists,
            'categories' => $categories
        ]);
    }

    /**
     * Route de tri pour les playlists
     * @param string $champ Sur quel champ doit-on trier les enregistrements
     * @param string $ordre Dans quel ordre doit-on trier les enregistrements
     * @return Response
     */
    #[Route('/playlists/tri/{champ}/{ordre}', name: 'playlists.sort')]
    public function sort($champ, $ordre): Response{
        if ($champ === 'name') {
            $playlists = $this->playlistRepository->findAllOrderByName($ordre);
        } elseif ($champ === 'count') {
            $playlists = $this->playlistRepository->findAllOrderByCount($ordre);
        } else {
            $playlists = $this->playlistRepository->findAll();
        }

        $categories = $this->categorieRepository->findAll();
        return $this->render(self::PAGE_PLAYLISTS, [
            'playlists' => $playlists,
            'categories' => $categories
        ]);
    }

    /**
     * Route de filtre pour les playlists
     * @param string $champ Sur quel champ doit-on filtrer les enregistrements
     * @param Request $request La requête actuelle (injecté par Symfony)
     * @param string $table Si $champ dans une autre table
     * @return Response
     */
    #[Route('/playlists/recherche/{champ}/{table}', name: 'playlists.findallcontain')]
    public function findAllContain($champ, Request $request, $table=""): Response{
        $valeur = $request->get("recherche");
        $playlists = $this->playlistRepository->findByContainValue($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::PAGE_PLAYLISTS, [
            'playlists' => $playlists,
            'categories' => $categories,
            'valeur' => $valeur,
            'table' => $table
        ]);
    }

    /**
     * Route d'index pour l'affichage d'une playlist
     * @param $id L'identifiant de la playlist à afficher
     * @return Response
     */
    #[Route('/playlists/playlist/{id}', name: 'playlists.showone')]
    public function showOne($id): Response{
        $playlist = $this->playlistRepository->find($id);
        $playlistCategories = $this->categorieRepository->findAllForOnePlaylist($id);
        $playlistFormations = $this->formationRepository->findAllForOnePlaylist($id);
        return $this->render('pages/playlist.html.twig', [
            'playlist' => $playlist,
            'playlistcategories' => $playlistCategories,
            'playlistformations' => $playlistFormations
        ]);
    }
    
}
