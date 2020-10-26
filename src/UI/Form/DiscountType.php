<?php

declare(strict_types=1);

namespace UI\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Class DiscountType
 * @package UI\Form
 */
class DiscountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('code', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('amount', NumberType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Range(['min' => 0]),
                    new Callback(static function ($value, ExecutionContextInterface $context) {
                        if ($value && $value > 100 && $context->getRoot()->get('kind') && $context->getRoot()->get('kind')->getData() === '%') {
                            $context
                                ->buildViolation('The maximum amount of discount is 100%')
                                ->addViolation();
                        }
                    }),
                ],
            ])
            ->add('kind', ChoiceType::class, [
                'choices' => [
                    'AUD'     => 'AUD',
                    'Percent' => '%',
                ],
            ])
            ->add('submit', SubmitType::class);
    }
}
