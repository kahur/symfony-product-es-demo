<?php

namespace KH\Api\Validators;

use ELM\Entity\Group;
use ELM\Entity\User;
use KH\Entity\Product;
use KH\Entity\ProductDetail;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;

/**
 * @package ${NAMESPACE}
 * @author Kamil Hurajt <hurajtk@gmail.com>
 */
class ProductValidator extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'constraints' => [
                    new Length([
                        'min' => 2,
                        'max' => 255,
                        'minMessage' => 'Title has to contain least 2 characters',
                        'maxMessage' => 'You have reached maximum character limit of 255'
                    ])
                ]
            ])
            ->add('shortDesc', TextType::class, [
                'constraints' => [
                    new Length([
                        'min' => '2',
                        'max' => '255',
                        'minMessage' => 'Short title must contain at least 2 characters',
                        'maxMessage' => 'You have reached maximum character limit of 255'
                    ])
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
            'allow_extra_fields' => true
        ]);
    }
}