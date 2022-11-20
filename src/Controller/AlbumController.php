<?php

namespace App\Controller;

use App\Entity\Album;
use App\Form\AlbumType;
use App\Entity\Member;
use App\Repository\AlbumRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/album")
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 */
class AlbumController extends AbstractController
{
    /**
     * @Route("/", name="app_album_index", methods={"GET"})
     */
    public function index(AlbumRepository $albumRepository): Response
    {
        return $this->render('album/index.html.twig', [
            'albums' => $albumRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new/{id}", name="app_album_new", methods={"GET", "POST"})
     */
    public function new(Request $request, AlbumRepository $albumRepository, Member $member): Response
    {
        $album = new Album();
        $album->setMember($member);
        $form = $this->createForm(AlbumType::class, $album);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $albumRepository->add($album, true);

            // Make sure message will be displayed after redirect
            $this->addFlash('Album', 'Album bien ajouté !');
            // $this->addFlash() is equivalent to $request->getSession()->getFlashBag()->add()
            // or to $this->get('session')->getFlashBag()->add();

            return $this->redirectToRoute('app_album_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('album/new.html.twig', [
            'album' => $album,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_album_show", methods={"GET"})
     */
    public function show(Album $album): Response
    {
        $hasAccess = $this->isGranted('ROLE_ADMIN') || ($this->getUser()->getMember() == $album->getMember());
        if(! $hasAccess) {
            throw $this->createAccessDeniedException("Vous ne pouvez pas consulter l'album d'un autre membre !");
}
        return $this->render('album/show.html.twig', [
            'album' => $album,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_album_edit", methods={"GET", "POST"})
     * @IsGranted("ROLE_USER")
     */
    public function edit(Request $request, Album $album, AlbumRepository $albumRepository): Response
    {

        $hasAccess = $this->isGranted('ROLE_ADMIN') || ($this->getUser()->getMember() == $album->getMember());
        if(! $hasAccess) {
            throw $this->createAccessDeniedException("Vous ne pouvez pas éditer un album qui n'est pas à vous !");
}
        $form = $this->createForm(AlbumType::class, $album);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $albumRepository->add($album, true);

            return $this->redirectToRoute('app_album_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('album/edit.html.twig', [
            'album' => $album,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_album_delete", methods={"POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Album $album, AlbumRepository $albumRepository): Response
    {
        $hasAccess = $this->isGranted('ROLE_ADMIN') || ($this->getUser()->getMember() == $album->getMember());
        if(! $hasAccess) {
            throw $this->createAccessDeniedException("Vous ne pouvez pas supprimer l'album d'un autre membre !");}

        if ($this->isCsrfTokenValid('delete'.$album->getId(), $request->request->get('_token'))) {
            $albumRepository->remove($album, true);
        }

        return $this->redirectToRoute('app_album_index', [], Response::HTTP_SEE_OTHER);
    }
}
