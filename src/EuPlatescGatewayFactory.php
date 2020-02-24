<?php

/**
 * This file was created by the developers from Infifni.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://infifnisoftware.ro and write us
 * an email on contact@infifnisoftware.ro.
 */

declare(strict_types=1);

namespace Infifni\SyliusEuPlatescPlugin;

use Infifni\SyliusEuPlatescPlugin\Payum\SyliusApi;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\GatewayFactory;

final class EuPlatescGatewayFactory extends GatewayFactory
{
    protected function populateConfig(ArrayObject $config): void
    {
        $config->defaults(
            [
                'euplatesc.factory_name' => 'euplatesc',
                'euplatesc.factory_title' => 'euplatesc',
            ]
        );

        if (false === (bool) $config['payum.api']) {
            $config['payum.default_options'] = [
                'environment' => SyliusApi::SANDBOX_ENVIRONMENT,
                'merchant_id' => SyliusApi::SANDBOX_MERCHANT_ID,
                'key' => SyliusApi::SANDBOX_KEY,
            ];
            $config->defaults($config['payum.default_options']);

            $config['payum.required_options'] = ['environment', 'merchant_id', 'key'];

            $config['payum.api'] = static function (ArrayObject $config): SyliusApi {
                $config->validateNotEmpty($config['payum.required_options']);

                return new SyliusApi(
                    $config['environment'],
                    $config['merchant_id'],
                    $config['key']
                );
            };
        }
    }
}
