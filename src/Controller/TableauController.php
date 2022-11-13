<?php

namespace App\Controller;

use App\Entity\Tableau;
use App\Entity\Souvenir;
use App\Form\TableauType;
use App\Repository\TableauRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("/tableau")
 */
class TableauController extends AbstractController
{
    /**
     * @Route("/", name="app_tableau_index", methods={"GET"})
     */
    public function index(TableauRepository $tableauRepository): Response
    {
        return $this->render('tableau/index.html.twig', [
            'tableaus' => $tableauRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_tableau_new", methods={"GET", "POST"})
     */
    public function new(Request $request, TableauRepository $tableauRepository): Response
    {
        $tableau = new Tableau();
        $form = $this->createForm(TableauType::class, $tableau);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tableauRepository->add($tableau, true);

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
        return $this->render('tableau/souvenir_show.html.twig', [
            'tableau' => $tableau, 'souv' => $souv
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_tableau_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Tableau $tableau, TableauRepository $tableauRepository): Response
    {
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
        if ($this->isCsrfTokenValid('delete'.$tableau->getId(), $request->request->get('_token'))) {
            $tableauRepository->remove($tableau, true);
        }

        return $this->redirectToRoute('app_tableau_index', [], Response::HTTP_SEE_OTHER);
    }
}
