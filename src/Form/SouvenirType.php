<?php

namespace App\Form;

use App\Entity\Souvenir;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class SouvenirType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('date')
            ->add('place')
            ->add('imageName', TextType::class,  ['disabled' => true])
            #Possibilité d'ajouter une image lors de la création/la modification d'un souvenir, sur le site
            ->add('imageFile', VichImageType::class, ['required' => false]) 
            ->add('album', null, [
                'disabled'   => true,
            ])
            ->add('tableaux')
            ->add('contexts')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Souvenir::class,
        ]);
    }
}
