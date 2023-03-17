<?php

declare(strict_types=1);

namespace Webgriffe\SyliusItalianInvoiceableOrderPlugin\Form\Extension;

use Sylius\Bundle\AddressingBundle\Form\Type\AddressType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Webgriffe\SyliusItalianInvoiceableOrderPlugin\Model\ItalianInvoiceableAddressInterface;

final class ItalianInvoiceableAddressTypeExtension extends AbstractTypeExtension
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'billingRecipientType',
                ChoiceType::class,
                [
                    'label' => 'webgriffe_sylius_italian_invoiceable_order.form.address.billing_recipient_type.label',
                    'choices' => self::getAvailableAddressTypes(),
                    'expanded' => true,
                    'multiple' => false,
                    'required' => true,
                    'placeholder' => false,
                ],
            )
            ->add(
                'taxCode',
                TextType::class,
                [
                    'label' => 'webgriffe_sylius_italian_invoiceable_order.form.address.tax_code.label',
                    'required' => false,
                ],
            )
            ->add(
                'vatNumber',
                TextType::class,
                [
                    'label' => 'webgriffe_sylius_italian_invoiceable_order.form.address.vat_number.label',
                    'required' => false,
                ],
            )
            ->add(
                'sdiCode',
                TextType::class,
                [
                    'label' => 'webgriffe_sylius_italian_invoiceable_order.form.address.sdi_code.label',
                    'required' => false,
                ],
            )
            ->add(
                'pecAddress',
                TextType::class,
                [
                    'label' => 'webgriffe_sylius_italian_invoiceable_order.form.address.pec_address.label',
                    'required' => false,
                ],
            )
        ;
    }

    public static function getAvailableAddressTypes(): array
    {
        return [
            'webgriffe_sylius_italian_invoiceable_order.form.address.billing_recipient_type.company' => ItalianInvoiceableAddressInterface::BILLING_RECIPIENT_TYPE_COMPANY,
            'webgriffe_sylius_italian_invoiceable_order.form.address.billing_recipient_type.individual' => ItalianInvoiceableAddressInterface::BILLING_RECIPIENT_TYPE_INDIVIDUAL,
        ];
    }

    public static function getExtendedTypes(): array
    {
        return [AddressType::class];
    }
}
