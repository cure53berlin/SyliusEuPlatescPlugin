<?php

/**
 * This file was created by the developers from Infifni.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://infifnisoftware.ro and write us
 * an email on contact@infifnisoftware.ro.
 */

declare(strict_types=1);

namespace Infifni\SyliusEuPlatescPlugin\Payum\Action;

use Infifni\SyliusEuPlatescPlugin\Payum\SyliusApi;
use Infifni\SyliusEuPlatescPlugin\Utils\EuPlatescHelper;
use Payum\Core\Action\ActionInterface;
use Payum\Core\ApiAwareInterface;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Exception\UnsupportedApiException;
use Payum\Core\Reply\HttpPostRedirect;
use Sylius\Component\Core\Model\AddressInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use Payum\Core\Request\Capture;
use Webmozart\Assert\Assert;

final class CaptureAction implements ActionInterface, ApiAwareInterface
{
    /** @var SyliusApi */
    private $api;

    public function execute($request): void
    {
        RequestNotSupportedException::assertSupports($this, $request);
        /** @var PaymentInterface $payment */
        $payment = $request->getModel();
        /** @var OrderInterface $order */
        $order = $request->getFirstModel()->getOrder();

        throw new HttpPostRedirect(
            SyliusApi::PAYMENT_URL,
            $this->prepareOrder($order, $payment)
        );
    }

    public function supports($request): bool
    {
        return
            $request instanceof Capture &&
            $request->getModel() instanceof PaymentInterface;
    }

    private function prepareOrder(OrderInterface $order, PaymentInterface $payment)
    {
        /** @var CustomerInterface $customer */
        $customer = $order->getCustomer();
        Assert::isInstanceOf(
            $customer,
            CustomerInterface::class,
            sprintf(
                'Make sure the first model is the %s instance.',
                CustomerInterface::class
            )
        );

        /** @var AddressInterface $billingAddress */
        $billingAddress = $order->getBillingAddress();
        Assert::isInstanceOf(
            $billingAddress,
            AddressInterface::class,
            sprintf(
                'Make sure the first model is the %s instance.',
                AddressInterface::class
            )
        );

        $paymentData = [
            'fname' => (string) $billingAddress->getFirstName(),
            'lname' => (string) $billingAddress->getLastName(),
            'country' => (string) $billingAddress->getCountryCode(),
            'company' => (string) $billingAddress->getCompany(),
            'city' => (string) $billingAddress->getCity(),
            'state' => (string) $billingAddress->getProvinceName(),
            'zip_code' => (string) $billingAddress->getPostcode(),
            'add' => (string) $billingAddress->getStreet(),
            'email' => (string) $customer->getEmail(),
            'phone' => (string) $billingAddress->getPhoneNumber(),
            'fax' => '',
        ];

        $hashData = [
            'amount' => $payment->getAmount(),
            'curr' => $payment->getCurrencyCode(),
            'invoice_id' => $payment->getId(),
            'order_desc' => 'Order ' . $order->getNumber() . ' with ' . $order->getItems()->count() . ' items.',
            'merch_id' => $this->api->getMerchantId(),
            'timestamp' => gmdate('YmdHis'),
            'nonce' => md5(microtime() . mt_rand()),
        ];
        $hashData['fp_hash'] = strtoupper(EuPlatescHelper::macFormat($hashData, $this->api->getKey()));

        $paymentData = array_merge($paymentData, $hashData);

        /** @var AddressInterface $shippingAddress */
        $shippingAddress = $order->getShippingAddress();
        Assert::isInstanceOf(
            $shippingAddress,
            AddressInterface::class,
            sprintf(
                'Make sure the first model is the %s instance.',
                AddressInterface::class
            )
        );
        $paymentData = array_merge($paymentData, [
            'sfname' => (string) $shippingAddress->getFirstName(),
            'slname' => (string) $shippingAddress->getLastName(),
            'scountry' => (string) $shippingAddress->getCountryCode(),
            'scompany' => (string) $shippingAddress->getCompany(),
            'scity' => (string) $shippingAddress->getCity(),
            'sstate' => (string) $shippingAddress->getProvinceName(),
            'szip_code' => (string) $shippingAddress->getPostcode(),
            'sadd' => (string) $shippingAddress->getStreet(),
            'semail' => (string) $customer->getEmail(),
            'sphone' => (string) $shippingAddress->getPhoneNumber(),
            'sfax' => '',
        ]);

        return $paymentData;
    }

    /**
     * {@inheritdoc}
     */
    public function setApi($api): void
    {
        if (!$api instanceof SyliusApi) {
            throw new UnsupportedApiException('Not supported. Expected an instance of ' . SyliusApi::class);
        }

        $this->api = $api;
    }
}