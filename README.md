<h1 style="text-align: center">
    <a href="https://infifnisoftware.ro" target="_blank">
        <img src="https://infifnisoftware.ro/themes/custom/infifni/logo.svg" alt="infifni logo" height="300" />
    </a>
    <br />
    Sylius EuPlătesc PLUGIN
</h1>

<p>
This plugin works with EuPlătesc version 3, the HTTP POST variant where you make a POST request
to EuPlătesc transaction processor and EuPlătesc does a POST redirect back to an url that you specify.
</p>

## Installation

1. Run `composer require infifni/euplatesc-plugin`.

2. Add plugin dependencies to your `config/bundles.php` file:
```php
// config/bundles.php
return [
    // other lines
    new Infifni\SyliusEuPlatescPlugin\InfifniSyliusEuPlatescPlugin(),
];
```

3. Import routes in `app/config/routing.yml`:

```yaml

# app/config/routing.yml
# other lines
infifni_sylius_euplatesc_plugin:
    resource: "@InfifniSyliusEuPlatescPlugin/Resources/config/routing.yml"
```

## Settings

After receiving access to an EuPlătesc account you will need to set the return url, which is
the url where EuPlătesc does a POST request with details after payment.

Go to https://manager.euplatesc.ro/v3/index.php and fill the Success URL and Fail URL
with https://yourdomain.com/payment/euplatesc/notify