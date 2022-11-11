<?php

namespace App\DataFixtures;

use App\Entity\Souvenir;
use App\Repository\SouvenirRepository;
use App\Entity\Album;
use App\Repository\AlbumRepository;
use App\Entity\Member;
use App\Repository\MemberRepository;
use App\Entity\Context;
use App\Repository\ContextRepository;
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
     * Generates initialization data for contexts : [label,description]
     * @return \\Generator
     */
    private static function contextDataGenerator()
    {
        yield ["Vie étudiante","Souvenirs qu'on se fait dans le contexte du campus"];
        yield ["Voyages","Souvenirs qu'on se fait en vacances/voyages"];
        yield ["Perso","Souvenirs qu'on se fait avec soi-même au quotidien"];
        yield ["En famille","Souvenirs qu'on se fait au quotidien en famille"];

    }

    /**
     * Generates initialization data for souvenirs : [album, title, date, contexts]
     * @return \\Generator
     */
    private static function souvenirsDataGenerator()
    {
        yield ["Souvenirs de Arthur","Je me mets au vélo !", date_create("2017-04-21"),["Perso"]];
        yield ["Souvenirs de Louise","Je me mets à la peinture !", date_create("2016-09-30"),["Perso"]];
        yield ["Souvenirs de Morgane", "Je me mets au surf au Brésil !", date_create("2015-09-29"),["Voyages"]];
        yield ["Souvenirs de Morgane","Un tour à la crêperie en famille", date_create("2021-12-26"),["En famille"]];
        yield ["Souvenirs de Morgane","Pancakes et film dans mon canapé", date_create("2018-06-04"),["Perso"]];
        yield ["Souvenirs de Morgane","Résultats du bac !!!", date_create("2018-07-10"),["Vie étudiante"]];
    }


    public function load(ObjectManager $manager)
    {
        $albumRepo = $manager->getRepository(Album::class);
        $memberRepo = $manager->getRepository(Member::class);
        $contextRepo = $manager->getRepository(Context::class);


        
        foreach (self::contextDataGenerator() as [$label, $description] ) {

            $context = new Context();
            $context->setLabel($label);
            $context->setDescription($description);
            $manager->persist($context);         
        }
        $manager->flush();



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


        foreach (self::souvenirsDataGenerator() as [$album, $title, $date, $contexts])
        {
            $alb = $albumRepo->findOneBy(['name' => $album]);
            $contexts_list = $contextRepo->findOneBy(['label' => $contexts]);
            $souv = new Souvenir();
            $souv->setAlbum($alb);
            $souv->addContext($contexts_list);
            $souv->setTitle($title);
            $souv->setDate($date);
            $alb->addSouvenir($souv);
            // there's a cascade persist on album-souvenirs which avoids persisting down the relation
            $manager->persist($alb);
        }
        $manager->flush();
    }
}