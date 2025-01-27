<?php

declare(strict_types=1);

namespace App\Controller\admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdminCategoriesController extends AbstractController
{
    /**
     *
     * @const PAGE_ADMIN_CATEGORIES
     */
    private const PAGE_ADMIN_CATEGORIES = 'pages/admin/admin.categories.html.twig';

    #[Route('/admin/categories', name: 'admin.categories')]
    public function index(): Response
    {
        return $this->render(self::PAGE_ADMIN_CATEGORIES);
    }
}
