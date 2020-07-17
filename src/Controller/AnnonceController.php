<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\Product;
use App\Form\AnnonceFormType;
use App\Form\CategoryFormType;
use App\Repository\AnnonceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnnonceController extends AbstractController
{
    /**
     * @Route("/annonces", name="annonces")
     * @param AnnonceRepository $annonceRepository
     * @return Response
     */
    public function index(AnnonceRepository $annonceRepository)
    {
        $list_annonce = $annonceRepository->findAll();

        return $this->render('annonce/index.html.twig', [
            'list_annonce' => $list_annonce
        ]);
    }

    /**
     * Grace au ParamConverter (installé par frameworkExtraBundle / Annotation)
     * Symfony va récupérer l'entité Product qui correspond à l'identifiant dans l'URI
     *
     * @Route("/annonce/{id}", name="annonce_show")
     * @param Annonce $annonce
     * @return Response
     */
    public function show(Annonce $annonce): Response
    {
        return $this->render('annonce/show.html.twig',[
            'annonce' => $annonce
        ]);
    }
    /**
     * @Route("/new", name="annonce_add")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function add(Request $request, EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(AnnonceFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // récupérer les informations du formulaire
            // parce que le formulaire est lié à l'entité Annonce, le getData retourne une Annonce
            $annonce = $form->getData();
            $entityManager->persist($annonce);
            $entityManager->flush();
            $this->addFlash('success','L\'annonce vient d\'être créé.');
            return $this->redirectToRoute('annonces');
        }
        return $this->render('/annonce/add.html.twig', [
            'annonceForm' => $form->createView()
        ]);
    }
}
