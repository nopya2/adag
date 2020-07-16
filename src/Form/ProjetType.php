<?php

namespace App\Form;

use App\Entity\Projet;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Entity\Service;
use App\Entity\Partenaire;

class ProjetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre')
            ->add('description')
            ->add('visible')
            // ->add('createdAt')
            // ->add('updatedAt')
            // ->add('imageName')
            // ->add('imageSize')
            // ->add('createdBy')
            ->add('realizedAt', TextType::class)
            ->add('service', EntityType::class, array(
                'class' => Service::class,
                'choice_label' => 'titre'
            ))
            ->add('imageFile', FileType::class, array(
                'required' => false
            ))
            ->add('destinataires', EntityType::class, array(
                'class' => Partenaire::class,
                'choice_label' => 'nom',
                'multiple' => true,
                'required' => false
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Projet::class,
        ]);
    }
}
