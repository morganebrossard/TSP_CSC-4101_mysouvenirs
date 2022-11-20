<?php

namespace App\Form;

use App\Entity\Tableau;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Repository\SouvenirRepository;

class TableauType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $tableau = $options['data'] ?? null;
        $member = $tableau->getCreateur();
        $builder
            ->add('description')
            ->add('publie')
            ->add('createur', null, [
                'disabled'   => true,
            ])
            ->add('souvenir', null, [
                'query_builder' => function (SouvenirRepository $er) use ($member) {
                        return $er->createQueryBuilder('s')
                        ->leftJoin('s.album', 'a')
                        ->andWhere('a.member = :member')
                        ->setParameter('member', $member)
                        ;
                    }
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tableau::class,
        ]);
    }
}
