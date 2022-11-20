<?php

namespace App\Controller;

use App\Entity\Member;
use App\Form\MemberType;
use App\Repository\MemberRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @Route("/member")
 */
class MemberController extends AbstractController
{
    /**
     * @Route("/", name="app_member_index", methods={"GET"})
     */
    public function index(MemberRepository $memberRepository): Response
    {
        return $this->render('member/index.html.twig', [
            'members' => $memberRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_member_new", methods={"GET", "POST"})
     */
    public function new(Request $request, MemberRepository $memberRepository): Response
    {
        $hasAccess = $this->isGranted('ROLE_ADMIN');
        if(! $hasAccess) {
            throw $this->createAccessDeniedException("Vous ne pouvez pas créer de membre, vous n'êtes pas admin !");
}
        $member = new Member();
        $form = $this->createForm(MemberType::class, $member);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $memberRepository->add($member, true);

            $this->addFlash('Member', 'Membrebien ajouté !');

            return $this->redirectToRoute('app_member_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('member/new.html.twig', [
            'member' => $member,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_member_show", methods={"GET"})
     */
    public function show(Member $member): Response
    {
        $hasAccess = $this->isGranted('ROLE_ADMIN') || ($this->getUser()->getMember() == $member->getName());
        if(! $hasAccess) {
            throw $this->createAccessDeniedException("Vous ne pouvez pas consulter le profil d'un autre membre !");
}
        return $this->render('member/show.html.twig', [
            'member' => $member,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_member_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Member $member, MemberRepository $memberRepository): Response
    {
        $hasAccess = $this->isGranted('ROLE_ADMIN') || ($this->getUser()->getMember() == $member->getName());
        if(! $hasAccess) {
            throw $this->createAccessDeniedException("Vous ne pouvez pas éditer le profil d'un autre membre !");}


        $form = $this->createForm(MemberType::class, $member);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $memberRepository->add($member, true);

            return $this->redirectToRoute('app_member_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('member/edit.html.twig', [
            'member' => $member,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_member_delete", methods={"POST"})
     */
    public function delete(Request $request, Member $member, MemberRepository $memberRepository): Response
    {
        $hasAccess = $this->isGranted('ROLE_ADMIN');
        if(! $hasAccess) {
            throw $this->createAccessDeniedException("Vous ne pouvez pas supprimer un autre membre !");
}

        $hasAccess = $this->isGranted('ROLE_ADMIN');
        if(! $hasAccess) {
            throw $this->createAccessDeniedException("Vous ne pouvez pas supprimer membre !");}

        if ($this->isCsrfTokenValid('delete'.$member->getId(), $request->request->get('_token'))) {
            $memberRepository->remove($member, true);
        }

        return $this->redirectToRoute('app_member_index', [], Response::HTTP_SEE_OTHER);
    }
}
