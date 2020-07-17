<?php

namespace App\Controller\Admin;

use App\Entity\Commentaire;
use App\Form\ConfirmDeletionFormType;
use App\Repository\CommentaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * Autoriser l'accès qu'aux administrateurs et moderateurs sur toutes les routes de ce controlleur
 * @IsGranted("ROLE_MODERATEUR")
 * @Route("/admin/commentaire", name="admin_commentaire_")
 */
class CommentaireController extends AbstractController
{
    /**
     * @Route("s", name="list")
     * @param CommentaireRepository $commentaireRepository
     * @return Response
     */
    public function index(CommentaireRepository $commentaireRepository)
    {
        $list_commentaires = $commentaireRepository->findAll();
        return $this->render('admin_commentaire/index.html.twig', [
            'list_commentaires' => $list_commentaires,
            'comm'
        ]);
    }

    /**
     * @Route("/{id}/delete", name="delete")
     * @param Commentaire $commentaire
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function delete(Commentaire $commentaire, Request $request, EntityManagerInterface $entityManager )
    {
        $form = $this->createForm(ConfirmDeletionFormType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $entityManager->remove($commentaire);
            $entityManager->flush();
            $this->addFlash('success', 'Commentaire supprimé');
            return $this->redirectToRoute('admin_commentaire_list');
        }
        return $this->render('admin_commentaire/delete.html.twig', [
            'deletionForm' => $form->createView(),
            'commentaire' => $commentaire
        ]);
    }
}
