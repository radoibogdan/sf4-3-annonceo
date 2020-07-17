<?php

namespace App\Controller\Admin;

use App\Entity\Annonce;
use App\Form\AnnonceFormType;
use App\Form\ConfirmDeletionFormType;
use App\Repository\AnnonceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * Autoriser l'accès qu'aux administrateurs et moderateurs sur toutes les routes de ce controlleur
 * @IsGranted("ROLE_MODERATEUR")
 * @Route("/admin/annonce", name="admin_annonce_")
 */
class AnnonceController extends AbstractController
{
    /**
     * @Route("s", name="list")
     * @param AnnonceRepository $annonceRepository
     * @return Response
     */
    public function index(AnnonceRepository $annonceRepository)
    {
        $annonces = $annonceRepository->findAll();
        return $this->render('admin_annonce/index.html.twig', [
            'annonces_list' => $annonces
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit")
     * @param Annonce $annonce
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function edit(Annonce $annonce, Request $request, EntityManagerInterface $entityManager)
    {
        // Le fait de mettre Annonce comme argument va récupérer la bonne Annonce de la base
        // Pas besoin de récupérer l'id dans la fonction et de le passer à la méthode find()
        $form = $this->createForm(AnnonceFormType::class, $annonce);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            // pas besoin de getData. Les modifications sont faites automatiquement
            $entityManager->flush();
            $this->addFlash('success','Les modifications apportées à l\'annonce ont été enregistrées!');
        }
        return $this->render('admin_annonce/edit.html.twig', [
            'annonce' => $annonce, // pour rajouter des informations en plus du formulaire
            'annonceForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/delete", name="delete")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param Annonce $annonce
     * @return Response
     */
    public function delete(Request $request, EntityManagerInterface $entityManager, Annonce $annonce)
    {
        $form = $this->createForm(ConfirmDeletionFormType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $entityManager->remove($annonce);
            $entityManager->flush();
            $this->addFlash('success','L\'annonce a été supprimée');
            return $this->redirectToRoute('admin_annonce_list');
        }
        return $this->render('admin_annonce/delete.html.twig', [
            'annonce' => $annonce,
            'deletionForm' => $form->createView()
        ]);
    }
}
