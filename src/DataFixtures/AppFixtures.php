<?php

namespace App\DataFixtures;

use App\Entity\Souvenir;
use App\Repository\SouvenirRepository;
use App\Entity\Album;
use App\Repository\AlbumRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;


class AppFixtures extends Fixture
{

    /**
     * Generates initialization data for albums : [name]
     * @return \\Generator
     */
    private static function albumsDataGenerator()
    {
        yield ["Souvenirs de Morgane"];
        yield ["Souvenirs de Louise"];
        yield ["Souvenirs de Arthur"];
    }

    /**
     * Generates initialization data for souvenirs : [album, title, date]
     * @return \\Generator
     */
    private static function souvenirsDataGenerator()
    {
        yield ["Souvenirs de Arthur","Je me mets au vélo !", date_create("2017-04-21")];
        yield ["Souvenirs de Louise","Je me mets à la peinture !", date_create("2016-09-30")];
        yield ["Souvenirs de Morgane", "Je me mets au surf !", date_create("2015-09-29")];
        yield ["Souvenirs de Morgane","Un tour à la crêperie en famille", date_create("2021-12-26")];
        yield ["Souvenirs de Morgane","Pancakes et film dans mon canapé", date_create("2018-06-04")];
        yield ["Souvenirs de Morgane","Résultats du bac !!!", date_create("2018-07-10")];
    }


    public function load(ObjectManager $manager)
    {
        $albumRepo = $manager->getRepository(Album::class);

        foreach (self::albumsDataGenerator() as [$name] ) {
            $album = new Album();
            $album->setName($name);
            $manager->persist($album);          
        }
        $manager->flush();

        foreach (self::souvenirsDataGenerator() as [$album, $title, $date])
        {
            $album = $albumRepo->findOneBy(['name' => $name]);
            $souv = new Souvenir();
            $souv->setAlbum($album);
            $souv->setTitle($title);
            $souv->setDate($date);
            $album->addSouvenir($souv);
            // there's a cascade persist on album-souvenirs which avoids persisting down the relation
            $manager->persist($album);
        }
        $manager->flush();
    }
}