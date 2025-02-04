<?php
namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controleur des formations
 *
 * @author emds
 */
class FormationsController extends AbstractController {

    /**
     * Le chemin constant de la template Twig à afficher
     * @const PAGE_FORMATIONS
     */
    private const PAGE_FORMATIONS = 'pages/formations.html.twig';

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
     * @param FormationRepository $formationRepository Injecté par Symfony
     * @param CategorieRepository $categorieRepository Injecté par Symfony
     */
    public function __construct(FormationRepository $formationRepository, CategorieRepository $categorieRepository) {
        $this->formationRepository = $formationRepository;
        $this->categorieRepository= $categorieRepository;
    }

    /**
     * Route d'index pour les formations
     * @return Response
     */
    #[Route('/formations', name: 'formations')]
    public function index(): Response{
        $formations = $this->formationRepository->findAll();
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::PAGE_FORMATIONS, [
            'formations' => $formations,
            'categories' => $categories
        ]);
    }

    /**
     * Route de tri pour les formations
     * @param string $champ Sur quel champ doit-on trier les enregistrements
     * @param string $ordre Dans quel ordre doit-on trier les enregistrements
     * @param string $table Si $champ dans une autre table
     * @return Response
     */
    #[Route('/formations/tri/{champ}/{ordre}/{table}', name: 'formations.sort')]
    public function sort($champ, $ordre, $table=""): Response{
        $formations = $this->formationRepository->findAllOrderBy($champ, $ordre, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::PAGE_FORMATIONS, [
            'formations' => $formations,
            'categories' => $categories
        ]);
    }

    /**
     * Route de filtre pour les formations
     * @param string $champ Sur quel champ doit-on filtrer les enregistrements
     * @param Request $request La requête actuelle (injecté par Symfony)
     * @param string $table Si $champ dans une autre table
     * @return Response
     */
    #[Route('/formations/recherche/{champ}/{table}', name: 'formations.findallcontain')]
    public function findAllContain($champ, Request $request, $table=""): Response{
        $valeur = $request->get("recherche");
        $formations = $this->formationRepository->findByContainValue($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::PAGE_FORMATIONS, [
            'formations' => $formations,
            'categories' => $categories,
            'valeur' => $valeur,
            'table' => $table
        ]);
    }

    /**
     * Route d'index pour l'affichage d'une formation
     * @param $id L'identifiant de la formation à afficher
     * @return Response
     */
    #[Route('/formations/formation/{id}', name: 'formations.showone')]
    public function showOne($id): Response{
        $formation = $this->formationRepository->find($id);
        return $this->render('pages/formation.html.twig', [
            'formation' => $formation
        ]);
    }
    
}
