# core
provide cloud operation interface for Tencent Cloud

# Usage

## ssl manager
```php
require './vendor/autoload.php';
use EasyCloudRequest\Tencent\Gateway;

$request = new RequestBag(
    'GET',
    'https://ssl.tencentcloudapi.com/',
    [
        "Action" => 'DescribeCertificates',
        "Version" => '2019-12-05',
        'Region' => '',
    ],
);

$cloud = new SimpleCloud([
    'default' => Gateway::class,
    'gateway' => [
        'volc' => [
            'ak' => 'your ak',
            'sk' => 'your sk',
        ]
    ],
    'http_options' => [
        "http_errors" => false,
        "proxy" => [],
        "verify" => false,
        "timeout" => 120,
        "connect_timeout" => 60,
    ]
]);
$result = $cloud->requests($request);
var_dump($result);
```