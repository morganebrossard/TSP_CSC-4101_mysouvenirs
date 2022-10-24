<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Member;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Controleur Member
 * @Route("/member")
 */

class MemberController extends AbstractController
{
    /**
     * @Route("/", name="member", methods="GET")
     */
    public function indexAction()
    {
        return $this->render('index.html.twig',
            [ 'welcome' => "Bienvenue dans la liste des membres" ]
        );
    }


    /**
 * Lists all member entities.
 *
 * @Route("/list", name = "members_list", methods="GET")
 */
public function listAction(ManagerRegistry $doctrine): Response
{
    $entityManager= $doctrine->getManager();
    $members = $entityManager->getRepository(Member::class)->findAll();

    dump($members);

    return $this->render('member/index.html.twig',
        [ 'members' => $members ]
        );
}
    


/**
 * Finds and displays a member entity.
 *
 * @Route("/{id}", name="member_show", requirements={ "id": "\d+"}, methods="GET")
 */
public function showAction(Member $member): Response
{
    return $this->render('member/show.html.twig',
    [ 'member' => $member ]
    );
}
}
