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
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;


class AppFixtures extends Fixture implements DependentFixtureInterface
{

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }

    /**
     * Generates initialization data for members : [name,user]
     * @return \\Generator
     */
    private static function membersDataGenerator()
    {
        yield ["Morgane","morgane@localhost"];
        yield ["Louise","louise@localhost"];
        yield ["Arthur","arthur@localhost"];
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
     * Generates initialization data for contexts : [label,description, parent]
     * @return \\Generator
     */
    private static function contextDataGenerator()
    {
        yield ["Occasion", "A quelle occasion a-t-on vécu ce souvenir", null];
        yield ["Entourage", "Avec qui s'est-on-fait ce souvenir", null];
        yield ["Vie étudiante","Souvenir créé dans la vie étudiante", "Occasion"];
        yield ["Voyage","Souvenir créé lors d'un voyage", "Occasion"];
        yield ["Perso","Souvenir créé seul", "Entourage"];
        yield ["En famille","Souvenir créé en famille", "Entourage"];

    }

    /**
     * Generates initialization data for souvenirs : [album, title, date, contexts,imageName]
     * @return \\Generator
     */
    private static function souvenirsDataGenerator()
    {
        yield ["Souvenirs de Arthur","Je me mets au vélo !", date_create("2017-04-21"),["Perso"],null];
        yield ["Souvenirs de Louise","Je me mets à la peinture !", date_create("2016-09-30"),["Perso"],null];
        yield ["Souvenirs de Morgane", "Je me mets au surf au Brésil !", date_create("2015-09-29"),["Voyage","Perso"],null];
        yield ["Souvenirs de Morgane","Un tour à la crêperie en famille", date_create("2021-12-26"),["En famille"],null];
        yield ["Souvenirs de Morgane","Pancakes et film dans mon canapé", date_create("2018-06-04"),["Perso"],null];
        yield ["Souvenirs de Morgane","Résultats du bac !!!", date_create("2018-07-10"),["Vie étudiante","Perso"],null];
    }


    public function load(ObjectManager $manager)
    {
        $albumRepo = $manager->getRepository(Album::class);
        $memberRepo = $manager->getRepository(Member::class);
        $parentRepo = $manager->getRepository(Context::class);

        
        foreach (self::contextDataGenerator() as [$label, $description, $parent] ) {

            $context = new Context();
            $context->setLabel($label);
            $context->setDescription($description);
            $par = $parentRepo->FindOneBy(["label"=>$parent]);
            $context->setParent($par);
            $this->AddReference($label, $context);
            $manager->persist($context);  
            $manager->flush(); 
            dump($context);   
        }

        dump("Entités Contextes");


        foreach (self::membersDataGenerator() as [$name,$user] ) {

            $member = new Member();
            if ($user) {
                $username = $manager->getRepository(User::class)->findOneByEmail($user);
                $member->setUser($username);
            }
            $member->setName($name);
            $manager->persist($member);          
        }
        $manager->flush();

        dump("Entités Membres");


        foreach (self::albumsDataGenerator() as [$name,$member] ) {

            $membre = $memberRepo->findOneBy(['name' => $member]);
            $album = new Album();
            $album->setName($name);
            $album->setMember($membre);
            $manager->persist($album);          
        }
        $manager->flush();

        dump("Entités Albums");


        foreach (self::souvenirsDataGenerator() as [$album, $title, $date, $contexts, $imageName])
        {

            $alb = $albumRepo->findOneBy(['name' => $album]);
            $souv = new Souvenir();
            $souv->setAlbum($alb);

            foreach ($contexts as $cont) {
               $souv->addContext($this->getReference($cont)); }

            $souv->setTitle($title);
            $souv->setDate($date);
            $souv->setImageName($imageName);

            $alb->addSouvenir($souv);
            // there's a cascade persist on album-souvenirs which avoids persisting down the relation
            $manager->persist($alb);
        }
        $manager->flush();

        dump("Entités Souvenirs");
    }

}