<?php

namespace App\Controller\Admin;

use App\Entity\Tableau;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;

use Doctrine\ORM\QueryBuilder;


class TableauCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Tableau::class;
    }



    public function configureFields(string $pageName): iterable
    {

    return [
        IdField::new('id')->hideOnForm(),
        AssociationField::new('createur'),
        BooleanField::new('publie')
        ->onlyOnForms()
        ->hideWhenCreating(),
        TextField::new('description'),

        AssociationField::new('souvenir')
        ->onlyOnForms()
        // on ne souhaite pas gérer l'association entre les souvenirs et le tableau dès la création du tableau
        ->hideWhenCreating()
        ->setTemplatePath('admin/fields/album_souvenirs.html.twig')
        // Ajout possible seulement pour des souvenirs qui appartiennent au même propriétaire de l'album que le créateur du tableau
        ->setQueryBuilder(
            function (QueryBuilder $queryBuilder) {
            // récupération de l'instance courante de tableau
            $currentTableau = $this->getContext()->getEntity()->getInstance();
            $createur = $currentTableau->getcreateur();
            $memberId = $createur->getId();
            // charge les seuls souvenirs dont le 'member' de l'album est le créateur de la galerie
            $queryBuilder->leftJoin('entity.album', 'i')
                ->leftJoin('i.member', 'm')
                ->andWhere('m.id = :member_id')
                ->setParameter('member_id', $memberId);    
            return $queryBuilder;
            }
           ),
    ];
    }

    public function configureActions(Actions $actions): Actions
    {

    return $actions
        ->add(Crud::PAGE_INDEX, Action::DETAIL)
    ;
    }
}
