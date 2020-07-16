<?php

namespace App\Form;

use App\Entity\Blog;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use App\Entity\CategoryBlog;
use App\Form\ImageType;


class BlogType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre')
            ->add('contenu')
            ->add('visible')
            ->add('categories', EntityType::class, array(
                'class' => CategoryBlog::class,
                'choice_label' => 'titre',
                'multiple' => true
            ))
            //->add('createdAt')
            //->add('updatedAt')
            // ->add('imageName')
            // ->add('imageSize')
            //->add('createdBy')
            // ->add('imageFile', FileType::class, array(
            //     'required' => false
            // ))
            ->add('image', ImageType::class, array(
                // 'required' => false
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Blog::class,
        ]);
    }
}
