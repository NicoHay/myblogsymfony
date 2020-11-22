<?php

namespace App\Form;

use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class PostUpdateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class , [
//                "attr" => [
//                    "class" => 'form-control'
//                ]
            ])
            ->add('content', TextareaType::class , [
                "constraints" => [new Length(['min'=> 2])],
//                "attr" => [
//                    "class" => 'form-control'
//                ]
            ])
            ->add('author', TextType::class , [
//                "attr" => [
//                    "class" => 'form-control'
//                ]
            ])
            ->add("submit", SubmitType::class, [
//                "attr" => [
//                    "class" => 'btn btn-primary'
//                ]
            ] );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
