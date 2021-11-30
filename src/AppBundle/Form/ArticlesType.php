<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
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
                'attr' => ['class' => 'form-control mb-2']
            ])
            ->add('quantity', IntegerType::class, [
                'label' => 'Quantité:',
                'attr' => ['class' => 'form-control mb-2']
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description:',
                'attr' => ['class' => 'form-control mb-2',
                            'style' => 'height: 180px']
            ])
            ->add('submit', SubmitType::class, [
                'attr' => ['class' => 'btn btn-info']
            ]);
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
