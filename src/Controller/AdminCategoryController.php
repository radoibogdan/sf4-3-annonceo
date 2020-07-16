<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Autoriser l'accès qu'aux administrateurs sur toutes les routes de ce controlleur
 * @IsGranted("ROLE_ADMIN")
 * @Route("/admin/category", name="admin_category_")
 */
class AdminCategoryController extends AbstractController
{
    /**
     * @Route("/list", name="list")
     */
    public function index()
    {
        return $this->render('admin_category/profile.html.twig', [
            'controller_name' => 'AdminCategoryController',
        ]);
    }
}
