<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticlesType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Article:',
                'attr' => ['class' => 'form-control my-2 me-1',
                            'style' => 'width: 250px']
            ])
            ->add('price', TextType::class, [
                'label' => 'Prix:',
                'attr' => ['class' => 'form-control my-2 me-1',
                    'style' => 'width: 70px']
            ])
            ->add('quantity', IntegerType::class, [
                'label' => 'Quantité:',
                'attr' => ['class' => 'form-control my-2',
                            'style' => 'width: 70px']
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description:',
                'attr' => ['class' => 'form-control my-2',
                            'style' => 'height: 8rem; width: 400px']
            ])
            ->add('photo', FileType::class, [
                'attr' => ['class' => 'form-control',
                            'style' => 'width: 400px']
            ])
            ->add('submit', SubmitType::class, [
                'attr' => ['class' => 'btn btn-info my-2']
            ])
        ;
    }
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Articles'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_articles';
    }


}
