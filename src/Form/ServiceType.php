<?php

namespace App\Form;

use App\Entity\Service;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use App\Entity\User;

class ServiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('description', TextareaType::class, array(
                'attr' => array(
                    'class' => 'ckeditor'
                )
            ))
            // ->add('createdAt')
            // ->add('updatedAt')
            ->add('icone')
            ->add('titre')
            ->add('visible')
            // ->add('imageName')
            // ->add('imageSize')
            // ->add('user', EntityType::class, array(
            //     'class' => User::class,
            //     'choice_label' => 'username'
            // ))
            ->add('imageFile', FileType::class, array(
                'required' => false
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Service::class,
        ]);
    }
}
