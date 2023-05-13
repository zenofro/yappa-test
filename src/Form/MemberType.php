<?php

namespace App\Form;

use App\Entity\Member;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class MemberType extends AbstractType
{
    public function __construct(
        public UrlGeneratorInterface $router
    ){}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('day', NumberType::class, ['mapped' => false])
            ->add('month', NumberType::class, ['mapped' => false])
            ->add('year', NumberType::class, ['mapped' => false])
            ->add('membershipNumber', TextType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Member::class,
        ]);
    }
}
