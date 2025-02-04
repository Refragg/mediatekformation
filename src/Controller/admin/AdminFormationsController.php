<?php

declare(strict_types=1);

namespace App\Controller\admin;

use App\Entity\Formation;
use App\Form\FormationsType;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Contrôleur gérant les pages d'administration des formations
 */
class AdminFormationsController extends AbstractController
{
    /**
     * Le chemin constant de la template Twig à afficher
     * @const PAGE_ADMIN_FORMATIONS
     */
    private const PAGE_ADMIN_FORMATIONS = 'pages/admin/admin.formations.html.twig';

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
     * Route d'index pour l'administration des formations
     * @return Response
     */
    #[Route('/admin/formations', name: 'admin.formations')]
    public function index(): Response
    {
        $formations = $this->formationRepository->findAll();
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::PAGE_ADMIN_FORMATIONS, [
            'formations' => $formations,
            'categories' => $categories
        ]);
    }

    /**
     * Route de tri pour l'administration des formations
     * @param string $champ Sur quel champ doit-on trier les enregistrements
     * @param string $ordre Dans quel ordre doit-on trier les enregistrements
     * @param string $table Si $champ dans une autre table
     * @return Response
     */
    #[Route('/admin/formations/tri/{champ}/{ordre}/{table}', name: 'admin.formations.sort')]
    public function sort($champ, $ordre, $table=""): Response{
        $formations = $this->formationRepository->findAllOrderBy($champ, $ordre, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::PAGE_ADMIN_FORMATIONS, [
            'formations' => $formations,
            'categories' => $categories
        ]);
    }

    /**
     * Route de filtre pour l'administration des formations
     * @param string $champ Sur quel champ doit-on filtrer les enregistrements
     * @param Request $request La requête actuelle (injecté par Symfony)
     * @param string $table Si $champ dans une autre table
     * @return Response
     */
    #[Route('/admin/formations/recherche/{champ}/{table}', name: 'admin.formations.findallcontain')]
    public function findAllContain($champ, Request $request, $table=""): Response{
        $valeur = $request->get("recherche");
        $formations = $this->formationRepository->findByContainValue($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::PAGE_ADMIN_FORMATIONS, [
            'formations' => $formations,
            'categories' => $categories,
            'valeur' => $valeur,
            'table' => $table
        ]);
    }

    /**
     * Route de suppression de formation pour l'administration des formations
     * @param int $id L'identifiant de la formation à supprimer
     * @return Response
     */
    #[Route('/admin/formations/delete?{id}', name: 'admin.formations.delete')]
    public function delete(int $id): Response {
        $visite = $this->formationRepository->find($id);
        $this->formationRepository->remove($visite);
        return $this->redirectToRoute('admin.formations');
    }

    /**
     * Route de modification d'une formation pour l'administration des formations.
     *
     * Selon la requête cette route modifie la formation demandée ou bien, elle
     * affiche le formulaire de modification d'une formation
     * @param int $id L'identifiant de la formation à modifier
     * @param Request $request La requête actuelle (injecté par Symfony)
     * @return Response
     */
    #[Route('/admin/formations/edit?{id}', name: 'admin.formations.edit')]
    public function edit(int $id, Request $request): Response {
        $formation = $this->formationRepository->find($id);
        $formFormations = $this->createForm(FormationsType::class, $formation);

        $formFormations->handleRequest($request);
        if ($formFormations->isSubmitted() && $formFormations->isValid()) {
            $this->formationRepository->addOrEdit($formation);
            return $this->redirectToRoute('admin.formations');
        }

        return $this->render('pages/admin/admin.formations.edit.html.twig', [
            'formation' => $formation,
            'formformations' => $formFormations->createView()
        ]);
    }

    /**
     * Route d'addition d'une formation pour l'administration des formations.
     *
     * Selon la requête cette route ajoute la formation demandée ou bien, elle
     * affiche le formulaire d'addition d'une formation
     * @param Request $request La requête actuelle (injecté par Symfony)
     * @return Response
     */
    #[Route('/admin/formations/add', name: 'admin.formations.add')]
    public function addFormation(Request $request): Response{
        $formation = new Formation();
        $formFormation = $this->createForm(FormationsType::class, $formation);

        $formFormation->handleRequest($request);
        if ($formFormation->isSubmitted() && $formFormation->isValid()) {
            $this->formationRepository->addOrEdit($formation);
            return $this->redirectToRoute('admin.formations');
        }

        return $this->render('pages/admin/admin.formations.add.html.twig', [
            'formformations' => $formFormation->createView(),
        ]);
    }
}
