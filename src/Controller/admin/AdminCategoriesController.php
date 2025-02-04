<?php

declare(strict_types=1);

namespace App\Controller\admin;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Contrôleur gérant les pages d'administration des catégories
 */
class AdminCategoriesController extends AbstractController
{
    /**
     * Le chemin constant de la template Twig à afficher
     * @const PAGE_ADMIN_CATEGORIES
     */
    private const PAGE_ADMIN_CATEGORIES = 'pages/admin/admin.categories.html.twig';

    /**
     * L'objet faisant l'interface entre les données des catégories et le contrôleur
     * @var CategorieRepository
     */
    private $categorieRepository;

    /**
     * Constructeur du contrôleur
     * @param CategorieRepository $categorieRepository Injecté par Symfony
     */
    public function __construct(CategorieRepository $categorieRepository) {
        $this->categorieRepository = $categorieRepository;
    }

    /**
     * Route d'index pour l'administration des catégories
     * @return Response
     */
    #[Route('/admin/categories', name: 'admin.categories')]
    public function index(): Response
    {
        $categories = $this->categorieRepository->findAll();

        return $this->render(self::PAGE_ADMIN_CATEGORIES, [
            'categories' => $categories
        ]);
    }

    /**
     * Route de tri pour l'administration des catégories
     * @param string $ordre ASC pour un tri ascendant ou DESC pour un tri descendant
     * @return Response
     */
    #[Route('/admin/categories/tri/{ordre}', name: 'admin.categories.sort')]
    public function sort($ordre): Response{
        $categories = $this->categorieRepository->findAllOrderBy($ordre);
        return $this->render(self::PAGE_ADMIN_CATEGORIES, [
            'categories' => $categories
        ]);
    }

    /**
     * Route de filtre pour l'administration des catégories
     * @param Request $request La requête actuelle (injecté par Symfony)
     * @return Response
     */
    #[Route('/admin/categories/recherche', name: 'admin.categories.findbyname')]
    public function findbyname(Request $request): Response{
        $valeur = $request->get("recherche");
        $categories = $this->categorieRepository->findByName($valeur);
        return $this->render(self::PAGE_ADMIN_CATEGORIES, [
            'categories' => $categories,
            'valeur' => $valeur,
        ]);
    }

    /**
     * Route de suppression de catégorie pour l'administration des catégories
     * @param int $id L'identifiant de la catégorie à supprimer
     * @return Response
     */
    #[Route('/admin/categories/delete?{id}', name: 'admin.categories.delete')]
    public function delete(int $id): Response {
        $visite = $this->categorieRepository->find($id);
        $this->categorieRepository->remove($visite);
        return $this->redirectToRoute('admin.categories');
    }

    /**
     * Route d'addition d'une catégorie pour l'administration des catégories
     * @param Request $request La requête actuelle (injecté par Symfony)
     * @return Response
     */
    #[Route('/admin/categories/add', name: 'admin.categories.add')]
    public function addCategorie(Request $request): Response{
        $name = $request->get("name");
        $categorie = new Categorie();
        $categorie->setName($name);

        if (!$this->categorieRepository->existsByName($name)) {
            $this->categorieRepository->add($categorie);
        }

        return $this->redirectToRoute('admin.categories');
    }
}
