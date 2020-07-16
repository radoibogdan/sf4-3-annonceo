<?php

namespace App\Controller\Admin;

use App\Entity\Categorie;
use App\Form\CategoryFormType;
use App\Form\ConfirmDeletionFormType;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Autoriser l'accès qu'aux administrateurs sur toutes les routes de ce controlleur
 * @IsGranted("ROLE_ADMIN")
 * @Route("/admin/category", name="admin_category_")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/list", name="list")
     * @param CategorieRepository $categorieRepository
     * @return Response
     */
    public function index(CategorieRepository $categorieRepository)
    {
        $categories = $categorieRepository->findAll();
        return $this->render('/admin_category/index.html.twig', [
            'categorie_list' => $categories
        ]);
    }

    /**
     * @Route("/new", name="add")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function add(Request $request, EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(CategoryFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // récupérer les informations du formulaire
            // parce que le formulaire est lié à l'entité category, le getData retourne une Category
            $categorie = $form->getData();
            $entityManager->persist($categorie);
            $entityManager->flush();
            $this->addFlash('success','La catégorie vient d\'être rajoutée.');
            return $this->redirectToRoute('admin_category_list');
        }
        return $this->render('/admin_category/add.html.twig', [
            'categoryForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit")
     * @param Categorie $categorie
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function edit(Categorie $categorie, Request $request, EntityManagerInterface $entityManager)
    {
        // Le fait de mettre Category comme argument va récupérer la bonne catégorie de la base
        // Pas besoin de récupérer l'id dans la fonction et de le passer à la méthode find()
        $form = $this->createForm(CategoryFormType::class, $categorie);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            // pas besoin de getData. Les modifications sont faites automatiquement
            $entityManager->flush();
            $this->addFlash('success','Les modifications apportées à la catégorie ont été enregistrées!');
        }
        return $this->render('/admin_category/edit.html.twig', [
            'category' => $categorie, // pour rajouter des informations en plus du formulaire
            'categoryForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/delete", name="delete")
     * @param Categorie $categorie
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function delete(Categorie $categorie, Request $request, EntityManagerInterface $entityManager)
    {
        // ConfirmDeletionFormType n'est pas lié à une entité
        $form = $this->createForm(ConfirmDeletionFormType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            //A l'inverse de persist(), remove() prépare à la suppression d'une entité
            $entityManager->remove($categorie);
            $entityManager->flush();

            $this->addFlash('success', 'Le produit a été supprimé');
            return $this->redirectToRoute('admin_category_list');
        }
        return $this->render('/admin_category/delete.html.twig', [
            'category' => $categorie,
            'deletionForm' => $form->createView()
        ]);
    }
}
