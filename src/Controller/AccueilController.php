<?php
namespace App\Controller;

use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of AccueilController
 *
 * @author emds
 */
class AccueilController extends AbstractController{
    
    /**
     * L'objet faisant l'interface entre les données des formations et le contrôleur
     * @var FormationRepository
     */
    private $repository;
    
    /**
     * Constructeur du contrôleur
     * @param FormationRepository $repository Injecté par Symfony
     */
    public function __construct(FormationRepository $repository) {
        $this->repository = $repository;
    }

    /**
     * Route affichant la page d'accueil
     * @return Response
     */
    #[Route('/', name: 'accueil')]
    public function index(): Response{
        $formations = $this->repository->findAllLasted(2);
        return $this->render("pages/accueil.html.twig", [
            'formations' => $formations
        ]);
    }

    /**
     * Route affichant les conditions générales d'utilisation
     * @return Response
     */
    #[Route('/cgu', name: 'cgu')]
    public function cgu(): Response{
        return $this->render("pages/cgu.html.twig");
    }
}
