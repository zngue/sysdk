<?php
namespace ShangYou\Helpers;

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\ValidationData;
use ShangYou\Exceptions\NoConfigurationException;
use ShangYou\Exceptions\ValidationException;

class JwtToken
{
    /**
     * 创建JWT Token
     *
     * @param array $payloads
     * @param int   $expire 有效时间,默认为2小时,单位s
     *
     * @return \Lcobucci\JWT\Token
     * @throws NoConfigurationException
     */
    public static function createToken(array $payloads, $expire = 3600 * 2)
    {
        $builder = new Builder();

        foreach ($payloads as $key => $payload) {
            $builder->set($key, $payload);
        }

        return $builder->setIssuedAt(time())
                       ->setExpiration(time() + $expire)
                       ->sign(new Sha256(), self::getSecretKey())
                       ->getToken();
    }

    /**
     * 解析Token
     *
     * @param string $token
     *
     * @return \Lcobucci\JWT\Token
     * @throws ValidationException 如果token过期,则抛出该异常
     */
    public static function parseToken($token)
    {
        $token = (new Parser())->parse((string) $token);

        if (!$token->verify(new Sha256(), self::getSecretKey())) {
            throw new ValidationException('Token无效');
        }

        $validation = new ValidationData();
        if (!$token->validate($validation)) {
            throw new ValidationException('Token已过期');
        }

        return $token;
    }

    private static function getSecretKey()
    {
        $secret = env('JWT_SECRET');
        if (empty($secret)) {
            throw new NoConfigurationException('配置项 JWT_SECRET 缺失');
        }

        return $secret;
    }
}