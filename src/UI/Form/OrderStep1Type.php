<?php

declare(strict_types=1);

namespace UI\Form;

use Domain\Model\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;

/**
 * Class OrderStep1Type
 * @package UI\Form
 */
class OrderStep1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('product', EntityType::class, [
                'class'        => Product::class,
                'choice_label' => static function (Product $product) {
                    return sprintf(
                        '%s',
                        $product->name()
                    );
                },
                'constraints'  => [
                    new NotBlank(),
                ],
            ])
            ->add('quantity', IntegerType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Range(['min' => 1]),
                ],
                'attr'        => [
                    'min' => 1,
                ],
            ])
            ->add('discountCode', TextType::class, [
                'required' => false,
            ])
            ->add('apply', ButtonType::class)
            ->add('submit', SubmitType::class, [
                'label' => 'Continue to billing',
            ]);
    }

    public function getBlockPrefix(): string
    {
        return 'order1';
    }
}
