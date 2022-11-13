<?php

namespace App\Controller\Admin;

use App\Entity\Souvenir;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;


class SouvenirCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Souvenir::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            // Id shouldn't be modified
            IdField::new('id')->hideOnForm(),
            TextField::new('title'),
                //->setTemplatePath('admin/fields/souvenir_index_title.html.twig'), souvenir
            DateField::new('Date'),
            TextField::new('album'),
            AssociationField::new('contexts') // remplacer par le nom de l'attribut spécifique, par exemple 'context'
        ->onlyOnDetail()
        ->formatValue(function ($value, $entity) {
            return implode(', ', $entity->getContexts()->toArray()); // ici Contexts()
        })
        ];
    }

    public function configureActions(Actions $actions): Actions
    {

    return $actions
        ->add(Crud::PAGE_INDEX, Action::DETAIL)
    ;
    }
}
