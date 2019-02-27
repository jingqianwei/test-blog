<?php
/**
 * Created by PhpStorm.
 * User: chinwe.jing
 * Date: 2019/2/27
 * Time: 15:36
 */

namespace App\Curl;

use GuzzleHttp\Client;

abstract class AbstractHttpCurlDriver implements CurlInterfaceDriver
{
    protected $client;
    protected $response;

    public function __construct(Client $client)
    {
        if (is_null($client)) {
            $client = new Client();
        }

        $this->setClient($client);
    }

    /**
     * 设置client
     * @param Client $client
     */
    private function setClient(Client $client)
    {
        $this->client = $client;
    }

    /**
     * 获取client
     * @return mixed
     */
    private function getClient()
    {
        return $this->client;
    }

    /**
     * get请求
     * @param $url
     * @param array $options
     * @return mixed
     */
    public function get($url, array $options = [])
    {
        $response = $this->getClient()->request('GET', $url, $options)->getBody()->getContents();

        $this->setResponse($response);

        return $response;
    }

    /**
     * post请求
     * @param $url
     * @param array $options
     * @return mixed
     */
    public function post($url, array $options = [])
    {
        $response = $this->getClient()->request('POST', $url, $options)->getBody()->getContents();

        $this->setResponse($response);

        return $response;
    }

    /**
     * request请求
     * @param $method
     * @param $url
     * @param array $options
     * @return mixed
     */
    public function request($method, $url, array $options = [])
    {
        $response = $this->getClient()->request($method, $url, $options)->getBody()->getContents();

        $this->setResponse($response);

        return $response;
    }

    /**
     * 设置response
     * @param $response
     */
    private function setResponse($response)
    {
        $this->response = $response;
    }

    /**
     * 获取response
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }
}
