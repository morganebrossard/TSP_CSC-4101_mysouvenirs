<?php

namespace App\DataFixtures;

use App\Entity\Souvenir;
use App\Repository\SouvenirRepository;
use App\Entity\Album;
use App\Repository\AlbumRepository;
use App\Entity\Member;
use App\Repository\MemberRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;


class AppFixtures extends Fixture
{
    /**
     * Generates initialization data for members : [name]
     * @return \\Generator
     */
    private static function membersDataGenerator()
    {
        yield ["Morgane"];
        yield ["Louise"];
        yield ["Arthur"];
    }

    /**
     * Generates initialization data for albums : [name,member]
     * @return \\Generator
     */
    private static function albumsDataGenerator()
    {
        yield ["Souvenirs de Morgane","Morgane"];
        yield ["Souvenirs de Louise","Louise"];
        yield ["Souvenirs de Arthur","Arthur"];
    }


    /**
     * Generates initialization data for contexts : [label,description,subcontexts,parent]
     * @return \\Generator
     */
    private static function contextDataGenerator()
    {
        yield ["Evènements",["Vie étudiante","Voyages"],""];
        yield ["Vie quotidienne",""];
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
        $memberRepo = $manager->getRepository(Member::class);


        foreach (self::membersDataGenerator() as [$name] ) {

            $member = new Member();
            $member->setName($name);
            $manager->persist($member);          
        }
        $manager->flush();


        foreach (self::albumsDataGenerator() as [$name,$member] ) {

            $membre = $memberRepo->findOneBy(['name' => $member]);
            $album = new Album();
            $album->setName($name);
            $album->setMember($membre);
            $manager->persist($album);          
        }
        $manager->flush();

        foreach (self::souvenirsDataGenerator() as [$album, $title, $date])
        {
            $alb = $albumRepo->findOneBy(['name' => $album]);
            $souv = new Souvenir();
            $souv->setAlbum($alb);
            $souv->setTitle($title);
            $souv->setDate($date);
            $alb->addSouvenir($souv);
            // there's a cascade persist on album-souvenirs which avoids persisting down the relation
            $manager->persist($alb);
        }
        $manager->flush();
    }
}