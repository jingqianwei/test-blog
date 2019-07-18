<?php
/**
 * Created by PhpStorm.
 * User: chinwe.jing
 * Date: 2019/7/18
 * Time: 16:57
 */

namespace App\Utils;

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\ValidationData;

class JwtAuth
{
    private static $instance;

    // 加密后的token
    private $token;
    // 解析JWT得到的token
    private $decodeToken;
    // 用户ID
    private $uid;
    // jwt密钥
    private $secrect = 'cSWI7BXwInlDsvdSxSQjAXcE32STE6kD';

    // jwt参数
    private $iss = 'http://example.com';//该JWT的签发者
    private $aud = 'http://example.org';//配置听众
    private $id = '4f1g23a12aa';//配置ID（JTI声明）

    /**
     * 获取token
     * @return string
     */
    public function getToken()
    {
        return (string)$this->token;
    }

    /**
     * 设置类内部 $token的值
     * @param $token
     * @return $this
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    /**
     * 设置uid
     * @param $uid
     * @return $this
     */
    public function setUid($uid)
    {
        $this->uid = $uid;
        return $this;
    }

    /**
     * 得到 解密过后的 uid
     * @return mixed
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * 加密jwt
     * @return $this
     */
    public function encode()
    {
        $time = time();
        $this->token = (string)(new Builder())
            ->setIssuer($this->iss) //发布者
            ->setAudience($this->aud)//接收者
            ->setId($this->id, true)//对当前token设置的标识
            ->setIssuedAt($time)//token创建时间
            ->setNotBefore($time + 60)//当前时间在这个时间前，token不能使用
            ->setExpiration($time + 3600)//过期时间
            ->set('uid', $this->uid)//自定义数据
            ->sign(new Sha256(), $this->secrect) //设置签名
            ->getToken(); //获取加密后的token，转为字符串

        return $this;
    }

    /**
     * 解密token
     * @return \Lcobucci\JWT\Token
     */
    public function decode()
    {
        if (!$this->decodeToken) {
            $this->decodeToken = (new Parser())->parse((string)$this->token);
            $this->uid = $this->decodeToken->getClaim('uid');
        }

        return $this->decodeToken;
    }

    /**
     * 验证令牌是否有效
     * @return bool
     */
    public function validate()
    {
        $data = new ValidationData();
        $data->setAudience($this->aud);
        $data->setIssuer($this->iss);
        $data->setId($this->id);
        return $this->decode()->validate($data);
    }

    /**
     * 验证令牌在生成后是否被修改
     * @return bool
     */
    public function verify()
    {
        $res = $this->decode()->verify(new Sha256(), $this->secrect);
        return $res;
    }


    /**
     * 该类的实例
     * @return JwtAuth
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * 单例模式 禁止该类在外部被new
     * JwtAuth constructor.
     */
    private function __construct()
    {

    }

    /**
     * 单例模式 禁止外部克隆
     */
    private function __clone()
    {
        // TODO: Implement __clone() method.
    }
}
