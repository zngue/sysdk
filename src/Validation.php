<?php
namespace ShangYou;

use Illuminate\Contracts\Validation\Factory;

class Validation
{
    /**
     * @var Factory
     */
    protected $validator;

    public function __construct(Factory $validator)
    {
        $this->validator = $validator;
    }

    /**
     * 注册所有校验规则
     */
    public function registerAll()
    {
        $this->registerTel();
        $this->registerMultiStr();
        $this->registerMultiDigital();
        $this->registerArrayDigital();
        $this->registerChinese();
        $this->registerJWT();
    }

    public function registerJWT()
    {
        $this->validator->extend('jwt', function($attribute, $value, $parameters, $validator) {
            return !!preg_match("/^[\w_-]+\.[\w_-]+\.[\w_-]+$/", $value);
        });
        $this->validator->replacer('jwt', function($message, $attribute, $rule, $parameters) {
            if ($message == 'validation.jwt') {
                return "属性 {$attribute} 必须是合法的JWT格式.";
            }
            return $message;
        });
    }

    /**
     * 校验手机号码
     */
    public function registerTel()
    {
        $this->validator->extend('tel', function($attribute, $value, $parameters, $validator) {
            return !!preg_match("/^1[3578][0-9]{9}$/", $value);
        });
        $this->validator->replacer('tel', function($message, $attribute, $rule, $parameters) {
            if ($message == 'validation.tel') {
                return "The {$attribute} is not a valid Telephone Number.";
            }
            return $message;
        });
    }

    /**
     * 多值字符串列表,使用","分隔
     */
    public function registerMultiStr()
    {
        $this->validator->extend('multi_str', function($attribute, $value, $parameters, $validator) {
            return !!preg_match('/^([\w\.]+,?)+$/', $value);
        });
        $this->validator->replacer("multi_str", function($message, $attribute, $rule, $parameters) {
            if ($message == 'validation.multi_str') {
                return "The {$attribute} must be some strings separated by pipeline";
            }

            return $message;
        });
    }

    /**
     * 多值id列表,使用","分隔
     */
    public function registerMultiDigital()
    {
        $this->validator->extend('multi_digital', function($attribute, $value, $parameters, $validator) {
            return !!preg_match('/^(\d+,?)+$/', $value);
        });
        $this->validator->replacer("multi_digital", function($message, $attribute, $rule, $parameters) {
            if ($message == 'validation.multi_digital') {
                return "The {$attribute} must be some numbers separated by pipeline";
            }

            return $message;
        });
    }

    /**
     * 数字数组
     */
    public function registerArrayDigital()
    {
        $this->validator->extend('array_digits', function($attribute, $value, $parameters, $validator) {
            if (!is_array($value)) {
                return false;
            }

            foreach ($value as $val) {
                if (!is_numeric($val)) {
                    return false;
                }
            }

            return true;
        });
        $this->validator->replacer("array_digits", function($message, $attribute, $rule, $parameters) {
            if ($message == 'validation.array_digits') {
                return "The {$attribute} must be an array of numbers";
            }

            return $message;
        });
    }

    public function registerChinese()
    {
        $this->validator->extend('chinese', function($attribute, $value, $parameters, $validator) {
            return !!preg_match('/^[·\x{4e00}-\x{9fa5}]*$/u', $value);
        });
        $this->validator->replacer("chinese", function($message, $attribute, $rule, $parameters) {
            if ($message == 'validation.chinese') {
                return "属性 {$attribute} 必须是合法的中文";
            }

            return $message;
        });
    }
}