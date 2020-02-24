<?php

declare(strict_types=1);

/**
 * This file was created by the developers from Infifni.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://infifnisoftware.ro and write us
 * an email on contact@infifnisoftware.ro.
 */

namespace Infifni\SyliusEuPlatescPlugin\Form\Type;

use Infifni\SyliusEuPlatescPlugin\Payum\SyliusApi;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\EqualTo;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotEqualTo;

final class EuPlatescGatewayConfigurationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'environment',
                ChoiceType::class,
                [
                    'choices' => [
                        'infifni.euplatesc_plugin.sandbox' => SyliusApi::SANDBOX_ENVIRONMENT,
                        'infifni.euplatesc_plugin.secure' => SyliusApi::SECURE_ENVIRONMENT,
                    ],
                    'label' => 'infifni.euplatesc_plugin.environment',
                ]
            )
            ->add(
                'merchantId',
                TextType::class,
                [
                    'label' => 'infifni.euplatesc_plugin.merchant_id',
                    'constraints' => [
                        new NotBlank(
                            [
                                'message' => 'infifni.euplatesc_plugin.gateway_configuration.merchant_id.not_blank',
                                'groups' => ['sylius'],
                            ]
                        )
                    ],
                    'data' => SyliusApi::SANDBOX_MERCHANT_ID,
                ]
            )
            ->add(
                'key',
                TextType::class,
                [
                    'label' => 'infifni.euplatesc_plugin.key',
                    'constraints' => [
                        new NotBlank(
                            [
                                'message' => 'infifni.euplatesc_plugin.gateway_configuration.key.not_blank',
                                'groups' => ['sylius'],
                            ]
                        ),
                    ],
                    'data' => SyliusApi::SANDBOX_KEY,
                ]
            );

        $builder->addEventListener(FormEvents::PRE_SUBMIT, [$this, 'preSubmitFormEventAction']);
    }

    /**
     * @param FormEvent $event
     */
    public function preSubmitFormEventAction(FormEvent $event): void
    {
        $formData = $event->getData();
        $form = $event->getForm();
        if (SyliusApi::SANDBOX_ENVIRONMENT === $formData['environment']) {
            $form
                ->add(
                    'merchantId',
                    TextType::class,
                    [
                        'label' => 'infifni.euplatesc_plugin.merchant_id',
                        'constraints' => [
                            new EqualTo([
                                'value' => SyliusApi::SANDBOX_MERCHANT_ID,
                                'groups' => ['sylius'],
                            ])
                        ]
                    ]
                )
                ->add(
                    'key',
                    TextType::class,
                    [
                        'label' => 'infifni.euplatesc_plugin.key',
                        'constraints' => [
                            new NotBlank(
                                [
                                    'message' => 'infifni.euplatesc_plugin.gateway_configuration.key.not_blank',
                                    'groups' => ['sylius'],
                                ]
                            ),
                            new EqualTo([
                                'value' => SyliusApi::SANDBOX_KEY,
                                'groups' => ['sylius'],
                            ])
                        ]
                    ]
                );
        }
        if (SyliusApi::SECURE_ENVIRONMENT === $formData['environment']) {
            $form
                ->add(
                    'merchantId',
                    TextType::class,
                    [
                        'label' => 'infifni.euplatesc_plugin.merchant_id',
                        'constraints' => [
                            new NotBlank(
                                [
                                    'message' => 'infifni.euplatesc_plugin.gateway_configuration.merchant_id.not_blank',
                                    'groups' => ['sylius'],
                                ]
                            ),
                            new NotEqualTo([
                                'value' => SyliusApi::SANDBOX_MERCHANT_ID,
                                'groups' => ['sylius'],
                            ])
                        ]
                    ]
                )
                ->add(
                    'key',
                    TextType::class,
                    [
                        'label' => 'infifni.euplatesc_plugin.key',
                        'constraints' => [
                            new NotBlank(
                                [
                                    'message' => 'infifni.euplatesc_plugin.gateway_configuration.key.not_blank',
                                    'groups' => ['sylius'],
                                ]
                            ),
                            new NotEqualTo([
                                'value' => SyliusApi::SANDBOX_KEY,
                                'groups' => ['sylius'],
                            ])
                        ]
                    ]
                );
        }
    }
}
