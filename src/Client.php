<?php

namespace EasyCloudRequest\Tencent;

use EasyCloudRequest\Core\Support\Config;
use EasyCloudRequest\Core\Support\RequestBag;
use TencentCloud\Common\AbstractClient;
use TencentCloud\Common\Credential;
use TencentCloud\Common\Profile\ClientProfile;
use TencentCloud\Common\Profile\HttpProfile;

class Client extends AbstractClient
{
    protected $requestBag;

    public function __construct(Config $config, RequestBag $requestBag)
    {
        $this->requestBag = $requestBag;

        $region = $this->requestBag->queryParams['Region'];
        unset($this->requestBag->queryParams['Region']);

        $version = $requestBag->queryParams['Version'];
        unset($requestBag->queryParams['Version']);
        
        parent::__construct(
            '',
            $version,
            new Credential($config->get('ak'), $config->get('sk')),
            $region,
            new ClientProfile(null, new HttpProfile(
                "{$requestBag->scheme}://",
                $requestBag->host,
                $requestBag->method,
                $config->get('http_config.timeout')
            ))
        );
    }

    /**
     * invoke and get response
     *
     * @return \GuzzleHttp\Psr7\Response
     * @throws \ReflectionException
     */
    public function request()
    {
        $r = new \ReflectionMethod(parent::class, 'doRequestWithTC3');
        $r->setAccessible(true);
        // $action, $request, $options, $headers, $payload
        $action = $this->requestBag->queryParams['Action'];
        unset($this->requestBag->queryParams['Action']);

        $request = new RequestModel($this->requestBag->queryParams);

        return $r->invokeArgs($this, [
            $action,
            $request,
            [],
            $this->requestBag->headerParams,
            $this->requestBag->body
        ]);
    }
}
