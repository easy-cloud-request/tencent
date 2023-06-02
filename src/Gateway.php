<?php

namespace EasyCloudRequest\Tencent;

use EasyCloudRequest\Core\Gateways\BaseGateway;
use EasyCloudRequest\Core\Support\RequestBag;
use EasyCloudRequest\Core\Support\Response;

class Gateway extends BaseGateway
{
    /**
     * tencent sender
     *
     * @param RequestBag $requestBag
     * @return Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \RuntimeException
     */
    public function requests(RequestBag $requestBag): Response
    {
        try {
            $client = new Client($this->config, $requestBag);
            $response = $client->request();
            $result = $this->unwrapResponse($response);

            if (empty($result['Response']['Error'])) {
                return new Response(200, $result);
            } else {
                return new Response(500, $result);
            }
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            if ($e->hasResponse()) {
                $result = $this->unwrapResponse($e->getResponse());
                return new Response($e->getCode(), $result);
            }
            return new Response($e->getCode(), $e->getMessage());
        } catch (\Exception $e) {
            return new Response($e->getCode(), $e->getMessage());
        } catch (\Throwable $e) {
            return new Response($e->getCode(), $e->getMessage());
        }
    }
}
