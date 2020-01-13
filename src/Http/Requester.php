<?php
namespace ShangYou\Http;


use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Unirest\Request;
use Unirest\Response;
use ShangYou\Exceptions\NoSuchServiceException;
use ShangYou\Exceptions\WrapedValidationException;
use ShangYou\Helpers\PlatformAware;

class Requester
{
    use PlatformAware;

    /**
     * 校验器异常错误代码
     */
    const VALIDATION_ERROR = 422;
    /**
     * @var Container
     */
    protected $app;

    /**
     * @var array
     */
    protected $config;

    /**
     * @var array 服务域名配置,后续实现服务发现机制
     */
    protected $domains = [];

    /**
     * @var Response 最后一个请求的Response对象
     */
    protected $lastResponse;

    /**
     * @var callable 对返回值处理的回调函数,一次性
     */
    protected $callbackOnce;

    /**
     * @var bool 是否是抛出异常(校验返回结果)
     */
    protected $throwException = true;

    protected $withoutUserInfo = false;

    public function __construct(Container $app)
    {
        $this->app = $app;
        // 响应结果json->array
        Request::jsonOpts(true);
       // $this->config['apikey']=  env('YS_API_KEY', '');
        if( $app['config']['ShangYou']){
            $this->config = $app['config']['ShangYou'];
        }
        // 合并本地配置的服务域名
      //  $this->domains = array_merge($this->domains, $this->config['service_domains']);
        if(env('SY_IS_LOCAL','')){
            if($this->config['service_domains']){
                $this->domains=$this->config['service_domains'];
            }
        }else{
            $this->domainDeal();
        }

    }
    protected function domainDeal(){
        Redis::select(11);
        $res_SerName_arr=Redis::sMembers('register');
        if (!$res_SerName_arr || empty($res_SerName_arr) ){
            throw new NoSuchServiceException("注册服务器为空");
        }
        foreach ($res_SerName_arr as $v){
            if($v){
                $data = json_decode($v,true);
                $domainUrl = $data['host'];
                if($domainUrl){
                    if(strpos($domainUrl,'http://') !== false){
                        $domainUrl= trim($domainUrl,'/').'/';
                    }elseif( strpos($domainUrl,'https://') !== false ){
                        $domainUrl= trim($domainUrl,'/').'/';
                    }else{
                        $domainUrl= 'http://'.trim($domainUrl,'/').'/';
                    }
                    $this->domains[$data['serviceName']]=$domainUrl;
                }
            }
        }

    }

    public function withoutUserInfo($without = false)
    {
        $this->withoutUserInfo = $without;
    }

    /**
     * 配置是否抛出异常
     *
     * 关闭之后,将不会对请求结果进行校验,直接返回,由调用方自行处理
     *
     * @param bool $throw
     *
     * @return $this
     */
    public function throwException($throw = true)
    {
        $this->throwException = $throw;

        return $this;
    }

    /**
     * 发送一个Get请求
     *
     * @param string $serviceId
     * @param string $endpoint
     * @param array  $params
     * @param array  $restrictions
     * @param string $version
     *
     * @return array
     */
    public function get(
        $serviceId,
        $endpoint,
        $params = [],
        $restrictions = [],
        $version = 'v1'
    ) {
        return $this->_request('get', $serviceId, $endpoint, $params,
            $restrictions, $version,
            false);
    }
    /**
     * 发起一个POST请求
     *
     * @param string $serviceId
     * @param string $endpoint
     * @param array  $params
     * @param array  $restrictions
     * @param string $version
     *
     * @return array
     */
    public function postWithFile(
        $serviceId,
        $endpoint,
        $params = [],
        $restrictions = [],
        $version = 'v1'
    ) {
        return $this->_request('post', $serviceId, $endpoint, $params,
            '',
            $version,false);
    }
    /**
     * 发起一个POST请求
     *
     * @param string $serviceId
     * @param string $endpoint
     * @param array  $params
     * @param array  $restrictions
     * @param string $version
     *
     * @return array
     */
    public function post(
        $serviceId,
        $endpoint,
        $params = [],
        $restrictions = [],
        $version = 'v1'
    ) {
        return $this->_request('post', $serviceId, $endpoint, $params,
            $restrictions,
            $version);
    }

    /**
     * 发起一个PUT请求
     *
     * @param string $serviceId
     * @param string $endpoint
     * @param array  $params
     * @param array  $restrictions
     * @param string $version
     *
     * @return array
     */
    public function put(
        $serviceId,
        $endpoint,
        $params = [],
        $restrictions = [],
        $version = 'v1'
    ) {
        return $this->_request('put', $serviceId, $endpoint, $params,
            $restrictions, $version);
    }

    /**
     * 发起一个DELETE请求
     *
     * @param string $serviceId
     * @param string $endpoint
     * @param array  $params
     * @param array  $restrictions
     * @param string $version
     *
     * @return array
     */
    public function delete(
        $serviceId,
        $endpoint,
        $params = [],
        $restrictions = [],
        $version = 'v1'
    ) {
        return $this->_request('delete', $serviceId, $endpoint, $params,
            $restrictions,
            $version);
    }

    /**
     * 发起一个PATCH请求
     *
     * @param string $serviceId
     * @param string $endpoint
     * @param array  $params
     * @param array  $restrictions
     * @param string $version
     *
     * @return array
     */
    public function patch(
        $serviceId,
        $endpoint,
        $params = [],
        $restrictions = [],
        $version = 'v1'
    ) {
        return $this->_request('patch', $serviceId, $endpoint, $params,
            $restrictions,
            $version);
    }


    /**
     * 查询服务域名
     *
     * @param string $serviceId 服务ID
     *
     * @return string
     * @throws NoSuchServiceException
     */
    public function getServiceDomain($serviceId)
    {
        if (!isset($this->domains[$serviceId])) {
            throw new NoSuchServiceException("不存在服务{$serviceId}");
        }

        if ($this->config['service_reg_center'] != 'local') {
            // 先读取缓存,如果存在,覆盖本地配置,如果不存在,则从注册中心获取,更新缓存
            // TODO 注册中心暂未实现,实现后补全该部分
        }

        if($this->config['service_center_available']) {
            return rtrim($this->config['service_center'], '/') . '/'
                   . trim($this->domains[$serviceId], '/') . '/';
        } else {
            return trim($this->domains[$serviceId], '/') .'/';  //本地没有服务时，可以直接请求
        }
    }

    /**
     * 发起一个API请求
     *
     * @param string $requestMethod 请求方法(get/post/patch/delete/put)
     * @param string $serviceId     请求的服务ID
     * @param string $endpoint      服务路径
     * @param array  $params        请求参数
     * @param array  $restrictions  限制条件
     * @param string $version       服务版本
     * @param bool   $jsonRequest   请求参数是否编码为json
     *
     * @return array
     * @throws NoSuchServiceException
     * @throws WrapedValidationException
     */
    private function _request(
        $requestMethod,
        $serviceId,
        $endpoint,
        $params = [],
        $restrictions = [],
        $version = 'v1',
        $jsonRequest = true
    ) {
        $url = $this->getServiceDomain($serviceId) . ltrim($endpoint, '/');

        $subType      = env('API_SUBTYPE', 'ShangYou');
        $standardTree = env('API_STANDARDS_TREE', 'vnd');
        $headers      = [
            'Accept' => "application/{$standardTree}.{$subType}.{$version}+json",
            'apikey' => env('YS_API_KEY', ''),
        ];

        $params['_restrictions'] = $restrictions;

        if ($requestMethod == 'get') {
            if (is_array($params)) {
                foreach ($params as $index => $param) {
                    if (is_array($param)) {
                        foreach ($param as $key => $value) {
                            $params[$index][$key] = urlencode($value);
                        }
                    } else {
                        $params[$index] = urlencode($param);
                    }
                }
            } else {
                $params = urlencode($params);
            }
        }

        // if $jsonRequest is true, we send the request body as json string
        if (!empty($params) && $jsonRequest) {
            $headers['Content-Type'] = 'application/json;charset=utf-8';
            $params                  = json_encode($params);

        }

        $this->lastResponse = \Unirest\Request::$requestMethod($url, $headers,
            $params);
       
        Log::debug(
            "http_request_{$requestMethod}: {$url}",
            [
                'params'   => $params,
                'headers'  => $headers,
                'response' => $this->lastResponse->raw_body
            ]
        );

        
        $response = $this->lastResponse->body;
        
        if (empty($this->callbackOnce)) {
            $this->checkResponse($response);
        } else {
            $response = $this->executeCallbackOnce($response);
        }
//        dd($response);die;
        return $response;
    }

    /**
     * 检查Response,处理异常
     *
     * @param $response
     *
     * @throws WrapedValidationException
     */
    public function checkResponse($response)
    {
        if ($this->throwException && isset($response['status_code'])) {
            if ($response['status_code'] != self::VALIDATION_ERROR) {
                abort($response['status_code'], $response['message']);
            }

            throw (new WrapedValidationException($response['message'],
                $response['status_code']))
                ->setErrors(isset($response['errors']) ? $response['errors'] : []);
        }

        return $response;
    }

    /**
     * 执行一次性回调函数
     *
     * @param $resp
     *
     * @return mixed
     */
    protected function executeCallbackOnce($resp)
    {
        if (!empty($this->callbackOnce)) {
            $callback           = $this->callbackOnce;
            $resp               = $callback($resp, $this);
            $this->callbackOnce = null;
        }

        return $resp;
    }

    /**
     * 是指一次性的错误处理函数
     *
     * 只对下一次请求有效,使用后自动销毁
     *
     * 回调函数有两个参数: $response, Requester $this
     *
     * @param callable $callback
     */
    public function setErrorHandlerOnce(callable $callback)
    {
        $this->callbackOnce = $callback;
    }
}