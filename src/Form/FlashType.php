<?php

namespace App\Form;

use App\Entity\Flash;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Rubrique;
use App\Entity\Article;

class FlashType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('contenu')
            // ->add('createdAt')
            // ->add('auteur')
            ->add('article', EntityType::class, array(
                'required' => true,
                'empty_data' => 'DECIMA',
                'class' => Article::class,
                'choice_label' => 'titre'
            ))
            // ->add('rubrique', EntityType::class, array(
            //     'class' => Rubrique::class,
            //     'choice_label' => 'titre'
            // ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Flash::class,
        ]);
    }
}
