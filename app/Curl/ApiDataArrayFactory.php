<?php
/**
 * Created by PhpStorm.
 * User: chinwe.jing
 * Date: 2019/2/27
 * Time: 16:06
 */

namespace App\Curl;


use App\Exceptions\ResponseNotJsonException;
use App\Exceptions\ResponseNotXMLException;
use SimpleXMLElement;

/**
 * 一个类只做一件事
 * Class ApiDataArrayFactory
 * @link 参考网址：https://learnku.com/articles/22936
 * @package App\Curl
 */
class ApiDataArrayFactory
{
    /**
     * @param CurlInterfaceDriver $curl
     * @return array|mixed
     * @throws ResponseNotJsonException|ResponseNotXMLException
     */
    public static function make(CurlInterfaceDriver $curl)
    {
        if ($curl instanceof JsonHttpCurlDriver) {
            return static::json2Array($curl->getResponse());
        }

        if ($curl instanceof XMLHttpCurlDriver) {
            return static::xml2Array($curl->getResponse());
        }
    }

    /**
     * json转数组
     * @param $json
     * @return mixed
     * @throws ResponseNotJsonException
     */
    private static function json2Array($json)
    {
        try {
            $data = \GuzzleHttp\json_decode($json, true);
        } catch (\InvalidArgumentException $e) {
            throw new ResponseNotJsonException($e->getMessage(), $e->getCode());
        }

        return $data;
    }

    /**
     * xml转数组
     * @param $xml
     * @return array
     * @throws ResponseNotXMLException
     */
    private static function xml2Array($xml)
    {
        if (!static::isXml($xml)) {
            throw new ResponseNotXMLException('传的值类型不是xml', 201);
        }

        $xml = new SimpleXMLElement($xml);

        return self::_XML2Array($xml);
    }

    /**
     * xml数据处理
     * @param SimpleXMLElement $parent
     * @return array
     */
    private static function _XML2Array(SimpleXMLElement $parent)
    {
        $array = array();

        foreach ($parent as $name => $element) {
            ($node = &$array[$name])
            && (1 === count($node) ? $node = array($node) : 1)
            && $node = &$node[];

            $node = $element->count() ? self::_XML2Array($element) : trim($element);
        }

        return $array;
    }

    /**
     * 判断是否为xml
     * @param $xml
     * @return int
     */
    private static function isXml($xml)
    {
        return xml_parse(xml_parser_create(), $xml, true);
    }
}
