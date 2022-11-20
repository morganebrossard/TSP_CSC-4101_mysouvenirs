<?php

namespace App\Controller;

use App\Entity\Tableau;
use App\Entity\Souvenir;
use App\Entity\Member;
use App\Form\TableauType;
use App\Repository\TableauRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/tableau")
 */
class TableauController extends AbstractController
{

        /**
     * @Route("/home", name="home", methods="GET")
     */
    public function homeAction()
    {
        return $this->render('home.html.twig');
    }

            /**
     * @Route("/apropos", name="apropos", methods="GET")
     */
    public function aproposAction()
    {
        return $this->render('apropos.html.twig');
    }

    
    /**
     * @Route("/", name="app_tableau_index", methods={"GET"})
     */
    public function index(TableauRepository $tableauRepository): Response
    {

        if($this->isGranted('ROLE_ADMIN')) {
             return $this->render('tableau/index.html.twig', [
                'tableaus' => $tableauRepository->findAll(),
            ]); }

        else { return $this->render('tableau/index.html.twig', [
                   'tableaus' => $tableauRepository->findBy(['publie' => true])]); }

    }


    /**
     * @Route("/new/{id}", name="app_tableau_new", methods={"GET", "POST"})
     */
    public function new(Request $request, TableauRepository $tableauRepository, Member $createur): Response
    {
        $tableau = new Tableau();
        $tableau->setCreateur($createur);
        $form = $this->createForm(TableauType::class, $tableau);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tableauRepository->add($tableau, true);
            
            $this->addFlash('Tableau', 'Tableau bien ajouté !');

            return $this->redirectToRoute('app_tableau_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tableau/new.html.twig', [
            'tableau' => $tableau,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_tableau_show", methods={"GET"})
     */
    public function show(Tableau $tableau): Response
    {
        return $this->render('tableau/show.html.twig', [
            'tableau' => $tableau, 
        ]);
    }


    /**
     * @Route("/{tableau_id}/souvenir/{souv_id}", name="app_tableau_souvenir_show", methods={"GET"})
      * @ParamConverter("tableau", options={"id" = "tableau_id"})
      * @ParamConverter("souv", options={"id" = "souv_id"})
     */
    public function souvenir_show(Tableau $tableau, Souvenir $souv): Response
    {
        if(! $tableau->getSouvenir()->contains($souv)) {
            throw $this->createNotFoundException("Nous n'avons pas trouvé un tel souvenir dans ce tableau !");
        }
    
        $hasAccess = false;
        if($this->isGranted('ROLE_ADMIN') || $tableau->isPublie()) {
            $hasAccess = true;
        }
        else {
            $user = $this->getMember();
              if( $user ) {
                  $member = $user->getMember();
                  if ( $member &&  ($member == $tableau->getCreateur()) ) {
                      $hasAccess = true;
                  }
              }
        }

        if(! $hasAccess) {
            throw $this->createAccessDeniedException("Vous ne pouvez pas accéder à la ressource !");
        }

        return $this->render('tableau/souvenir_show.html.twig', [
            'tableau' => $tableau, 'souv' => $souv
        ]);
    }


    /**
     * @Route("/{id}/edit", name="app_tableau_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Tableau $tableau, TableauRepository $tableauRepository): Response
    {

        $hasAccess = $this->isGranted('ROLE_ADMIN') || ($this->getUser()->getMember() == $tableau->getCreateur());
        if(! $hasAccess) {
            throw $this->createAccessDeniedException("Vous ne pouvez pas éditer le tableau d'un autre membre !");
}

        $form = $this->createForm(TableauType::class, $tableau);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tableauRepository->add($tableau, true);

            return $this->redirectToRoute('app_tableau_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tableau/edit.html.twig', [
            'tableau' => $tableau,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_tableau_delete", methods={"POST"})
     */
    public function delete(Request $request, Tableau $tableau, TableauRepository $tableauRepository): Response
    {
        $hasAccess = $this->isGranted('ROLE_ADMIN') || ($this->getUser()->getMember() == $member->getName());
        if(! $hasAccess) {
            throw $this->createAccessDeniedException("Vous ne pouvez pas supprimer le tableau d'un autre membre !");
}

        if ($this->isCsrfTokenValid('delete'.$tableau->getId(), $request->request->get('_token'))) {
            $tableauRepository->remove($tableau, true);
        }

        return $this->redirectToRoute('app_tableau_index', [], Response::HTTP_SEE_OTHER);
    }
}
