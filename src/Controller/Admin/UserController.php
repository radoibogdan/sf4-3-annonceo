<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\ConfirmDeletionFormType;
use App\Form\RegistrationFormType;
use App\Form\UserProfileFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * Autoriser l'accès qu'aux administrateurs et moderateurs sur toutes les routes de ce controlleur
 * @IsGranted("ROLE_ADMIN")
 * @Route("/admin/user", name="admin_user_")
 */
class UserController extends AbstractController
{
    /**
     * @Route("s", name="list")
     * @param UserRepository $userRepository
     * @return Response
     */
    public function index(UserRepository $userRepository)
    {
        $user_list = $userRepository->findAll();

        return $this->render('admin_user/index.html.twig', [
            'user_list' => $user_list
        ]);
    }

    /**
     * @Route("/{id}/edit/", name="edit")
     * @param User $user
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function edit(User $user, Request $request, EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(UserProfileFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $entityManager->flush();
            $this->addFlash('success','Profil utilisateur modifié');
        }
        return $this->render('admin_user/edit.html.twig',[
            'userForm' => $form->createView(),
            'user'=> $user
        ]);
    }

    /**
     * @Route("/{id}/delete", name="delete")
     * @param User $user
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function delete(User $user,Request $request, EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(ConfirmDeletionFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $entityManager->remove($user);
            $entityManager->flush();
            $this->addFlash('success','L\'utilisateur a été supprimé');
            return $this->render('admin_user/index.html.twig');
        }
        return $this->render('admin_user/delete.html.twig',[
            'deletionForm'=> $form->createView(),
            'user' => $user
        ]);
    }

}
