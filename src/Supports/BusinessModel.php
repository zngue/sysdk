<?php

namespace ShangYou\Supports;


use Illuminate\Support\Facades\Cache;
use ShangYou\Exceptions\WrapedValidationException;

abstract class BusinessModel
{

    /**
     * @var static
     */
    protected static $instances = [];

    /**
     * 当前模型单例
     *
     * @return static
     */
    public static function instance()
    {
        if (!isset(static::$instances[static::class])) {
            static::$instances[static::class] = new static();
        }

        return static::$instances[static::class];
    }

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    /**
     * 抛出一个异常,自动处理微服务的表单异常
     *
     * @param       $code
     * @param       $message
     * @param array $errors 来自微服务返回的errors
     *
     * @throws WrapedValidationException
     */
    protected function throwException($code, $message, $errors = [])
    {
        if ($code != 422) {
            abort($code, $message);
        }

        throw (new WrapedValidationException($message, $code))->setErrors($errors);
    }

    /**
     * 验证微服务返回的结果是否含有错误
     *
     * @param $response
     *
     * @throws WrapedValidationException
     */
    protected function validateResponse($response)
    {
        if (isset($response['status_code'])) {
            $this->throwException($response['status_code'], $response['message'],
                $response['errors']);
        }
    }

    /**
     * 缓存控制
     *
     * @param string   $cacheKey
     * @param callable $callback
     * @param bool     $updateCache
     * @param int      $expire
     *
     * @return mixed
     */
    protected function cacheControl(
        $cacheKey,
        callable $callback,
        $updateCache = false,
        $expire = 24 * 60 * 2
    ) {

        if (env('APP_DEBUG', false)) {
            return $callback();
        }

        if ($updateCache) {
            Cache::put($cacheKey, $callback(), $expire);
        }

        return Cache::remember(
            $cacheKey, $expire,
            function () use ($callback) {
                return $callback();
            }
        );
    }

    /**
     * 批量查询缓存控制
     *
     * @param array    $cacheKeys
     * @param callable $callback 接收一个参数,数组类型,包含所有未命中的key
     * @param bool     $updateCache
     * @param int      $expire
     *
     * @return array
     */
    protected function cacheControlMany(
        array $cacheKeys,
        callable $callback,
        $updateCache = false,
        $expire = 24 * 60 * 2
    ) {

        if (empty($cacheKeys)) {
            return [];
        }

        if (env('APP_DEBUG', false)) {
            return $callback($cacheKeys);
        }

        if ($updateCache) {
            Cache::putMany($callback($cacheKeys), $expire);
        }

        $values = Cache::many($cacheKeys);
        $missed = [];
        foreach ($values as $key => &$value) {
            if (is_null($value)) {
                $missed[] = $key;
            }
        }

        if (!empty($missed)) {
            $valuesRetrived = $callback($missed);
            Cache::putMany($valuesRetrived, $expire);
            $values = array_merge($values, $valuesRetrived);
        }

        return array_values($values);
    }
}