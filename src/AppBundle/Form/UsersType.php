<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UsersType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastName', TextType::class, [
                'label' => 'Nom:',
                'attr' => ['class' => 'form-control my-2 me-2',
                    'style' => 'width: 200px']
            ])
            ->add('firstName', TextType::class, [
                'label' => 'Prénom:',
                'attr' => ['class' => 'form-control my-2',
                    'style' => 'width: 200px']
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email:',
                'attr' => ['class' => 'form-control my-2 me-2',
                    'style' => 'width: 200px']
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe:',
                'attr' => ['class' => 'form-control my-2 me-2',
                    'style' => 'width: 410px']
            ])
            ->add('role', ChoiceType::class, [
                'label' => 'Rôle:',
                'choices' => [
                    'Admin' => 1,
                    'Client' => 2,
                    'RH' => 3
                ],
                'attr' => ['class' => 'form-control my-2',
                    'style' => 'width: 200px']
            ])
            ->add('photo', FileType::class, [
                'label' => 'Photo ( Pas obligatoire ):',
                'required' => false,
                'attr' => ['class' => 'form-control mb-2',
                    'style' => 'width: 410px']
            ])
            ->add('submit', SubmitType::class, [
                'attr' => ['class' => 'btn btn-info']
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Users'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_users';
    }


}
