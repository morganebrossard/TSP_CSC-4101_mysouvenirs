<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Souvenir;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Controleur Souvenir
 * @Route("/souvenir")
 */

class SouvenirController extends AbstractController
{

    /**
     * @Route("/home", name="home", methods="GET")
     */
    public function homeAction()
    {
        return $this->render('home.html.twig');
    }


    /**
     * @Route("/", name="souvenir", methods="GET")
     */
    public function indexAction()
    {
        return $this->render('index.html.twig',
            [ 'welcome' => "Bienvenue dans les souvenirs" ]
        );
    }



    /**
 * Lists all souvenir entities.
 *
 * @Route("/list", name = "souvenirs_list", methods="GET")
 */
public function listAction(ManagerRegistry $doctrine): Response
{
    $entityManager= $doctrine->getManager();
    $souvenirs = $entityManager->getRepository(Souvenir::class)->findAll();

    dump($souvenirs);

    return $this->render('souvenir/index.html.twig',
        [ 'souvenirs' => $souvenirs ]
        );
}



/**
 * Finds and displays a souvenir entity.
 *
 * @Route("/{id}", name="souvenir_show", requirements={ "id": "\d+"}, methods="GET")
 */
public function showAction(Souvenir $souvenir): Response
{
    return $this->render('souvenir/show.html.twig',
    [ 'souvenir' => $souvenir ]
    );
}
}