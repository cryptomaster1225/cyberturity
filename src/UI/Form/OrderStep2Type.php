<?php

declare(strict_types=1);

namespace UI\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class OrderStep2Type
 * @package UI\Form
 */
class OrderStep2Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('lastName', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Email(['mode' => 'strict']),
                ],
            ])
            ->add('companyName', TextType::class, [
                'label'    => 'Company name (optional)',
                'required' => false,
            ])
            ->add('addressLine1', TextType::class, [
                'label'       => 'Address',
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('addressLine2', TextType::class, [
                'label'    => 'Address line 2',
                'required' => false,
            ])
            ->add('city', TextType::class, [
                'label'       => 'City',
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('postalCode', TextType::class, [
                'label'       => 'Postal code',
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('country', CountryType::class, [
                'label'             => 'Country',
                'preferred_choices' => [
                    'US',
                ],
                'constraints'       => [
                    new NotBlank(),
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Continue to summary',
            ]);
    }

    public function getBlockPrefix(): string
    {
        return 'order2';
    }
}
