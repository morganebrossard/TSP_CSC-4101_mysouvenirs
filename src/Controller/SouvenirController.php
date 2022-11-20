<?php

namespace App\Controller;

use App\Entity\Souvenir;
use App\Form\SouvenirType;
use App\Entity\Album;
use App\Repository\SouvenirRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/souvenir")
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 */
class SouvenirController extends AbstractController
{
    
    /**
     * @Route("/", name="app_souvenir_index", methods={"GET"})
     */
    public function index(SouvenirRepository $souvenirRepository): Response
    {
        return $this->render('souvenir/index.html.twig', [
            'souvenirs' => $souvenirRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new/{id}", name="app_souvenir_new", methods={"GET", "POST"})
     */
    public function new(Request $request, SouvenirRepository $souvenirRepository, Album $album, EntityManagerInterface $entityManager): Response
    {
        $souvenir = new Souvenir();
        $souvenir->setAlbum($album);
        $form = $this->createForm(SouvenirType::class, $souvenir);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $souvenirRepository->add($souvenir, true);
            $entityManager->persist($souvenir);
            $entityManager->flush();
   
            $this->addFlash('Souvenir', 'Souvenir bien ajouté !');

            return $this->redirectToRoute('app_souvenir_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('souvenir/new.html.twig', [
            'souvenir' => $souvenir,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_souvenir_show", methods={"GET"})
     */
    public function show(Souvenir $souvenir): Response
    {

        $album = $souvenir->getAlbum();
        $bool = $this->getUser()->getMember() == $album->getMember();
        $hasAccess = $this->isGranted('ROLE_ADMIN') || ($bool);

        if(! $hasAccess) {
            throw $this->createAccessDeniedException("Vous ne pouvez pas consulter le souvenir d'un autre membre !");
        }


        return $this->render('souvenir/show.html.twig', [
            'souvenir' => $souvenir,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_souvenir_edit", methods={"GET", "POST"})
     * @IsGranted("ROLE_USER")
     */
    public function edit(Request $request, Souvenir $souvenir, SouvenirRepository $souvenirRepository): Response
    {
        $form = $this->createForm(SouvenirType::class, $souvenir);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $souvenirRepository->add($souvenir, true);

            return $this->redirectToRoute('app_souvenir_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('souvenir/edit.html.twig', [
            'souvenir' => $souvenir,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_souvenir_delete", methods={"POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Souvenir $souvenir, SouvenirRepository $souvenirRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$souvenir->getId(), $request->request->get('_token'))) {
            $souvenirRepository->remove($souvenir, true);
        }

        return $this->redirectToRoute('app_souvenir_index', [], Response::HTTP_SEE_OTHER);
    }
}
