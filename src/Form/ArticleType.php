<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use App\Entity\Rubrique;
use App\Form\ImageType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\Service\Countries;

// use Symfony\Component\Intl\Intl;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // $countries = Intl::getRegionBundle()->getCountryNames();
        $countries = Countries::$countries;

        $builder
            ->add('titre')
            ->add('sousTitre')
            ->add('contenu')
            ->add('legend')
            ->add('image', ImageType::class, array(
                // 'required' => false
            ))
            ->add('rubrique', EntityType::class, array(
                'class' => Rubrique::class,
                'choice_label' => 'titre'
            ))
            ->add('pays',ChoiceType::class, [
                'choices'  => $countries,
                'choice_label' => function($choice, $key, $value) {
                    return $value;
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
