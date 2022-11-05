<?php
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Album;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Controleur Album
 * @Route("/album")
 */
class AlbumController extends AbstractController
{
    /**
     * @Route("/", name="album", methods="GET")
     */
    public function indexAction()
    {
        return $this->render('index.html.twig',
            [ 'welcome' => "Bienvenue dans les albums" ]
        );
    }
    /**
 * Lists all album entities.
 *
 * @Route("/list", name = "albums_list", methods="GET")
 */
public function listAction(ManagerRegistry $doctrine): Response
{
    $entityManager= $doctrine->getManager();
    $albums = $entityManager->getRepository(Album::class)->findAll();
    dump($albums);
    return $this->render('album/index.html.twig',
        [ 'albums' => $albums ]
        );
}
    
/**
 * Finds and displays a album entity.
 *
 * @Route("/{id}", name="album_show", requirements={ "id": "\d+"}, methods="GET")
 */
public function showAction(Album $album): Response
{
    return $this->render('album/show.html.twig',
    [ 'album' => $album ]
    );
}
}
