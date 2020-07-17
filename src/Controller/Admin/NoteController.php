<?php

namespace App\Controller\Admin;

use App\Entity\Note;
use App\Form\ConfirmDeletionFormType;
use App\Repository\NoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * Autoriser l'accès qu'aux administrateurs et moderateurs sur toutes les routes de ce controlleur
 * @IsGranted("ROLE_MODERATEUR")
 * @Route("/admin/note", name="admin_note_")
 */
class NoteController extends AbstractController
{
    /**
     * @Route("s", name="list")
     * @param NoteRepository $noteRepository
     * @return Response
     */
    public function index(NoteRepository $noteRepository)
    {
        $list_notes = $noteRepository->findAll();
        return $this->render('/admin_note/index.html.twig', [
            'list_notes' => $list_notes
        ]);
    }

    /**
     * @Route("/{id}/delete", name="delete")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param Note $note
     * @return Response
     */
    public function delete(Request $request, EntityManagerInterface $entityManager, Note $note)
    {
        $form = $this->createForm(ConfirmDeletionFormType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $entityManager->remove($note);
            $entityManager->flush();
            $this->addFlash('success', 'Note supprimés');
            return $this->redirectToRoute('admin_note_list');
        }
        return $this->render('admin_note/delete.html.twig', [
            'deletionForm' => $form->createView(),
            'note' => $note
        ]);
    }
}
