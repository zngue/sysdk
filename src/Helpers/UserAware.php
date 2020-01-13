<?php
namespace ShangYou\Helpers;


trait UserAware
{
    /**
     * 查询限制条件
     *
     * @param string $key     限制条件key
     * @param mixed  $default 默认值
     *
     * @return mixed
     */
    protected function getRestriction($key, $default = null)
    {
        $restrictions = app('request')->input('_restrictions');
        if (empty($restrictions)) {
            return $default;
        }

        if (isset($restrictions[$key])) {
            return $restrictions[$key];
        }

        return $default;
    }

    /**
     * 获取当前请求的用户ID
     *
     * 仅起标识作用,不建议参与核心业务逻辑
     * 如果已登录用户执行的操作,则返回用户id,如果未登录用户操作,则返回null
     *
     * @return null|int
     */
    protected function getCurrentUserId()
    {
        return isset($_SERVER['HTTP_X_USER']) ? $_SERVER['HTTP_X_USER'] : null;
    }

    /**
     * 获取当前请求的企业ID
     *
     * 仅起标识作用,不建议参与核心业务逻辑
     *
     * @return null|int
     */
    protected function getCurrentEnterpriseId()
    {
        return isset($_SERVER['HTTP_X_ENTERPRISE']) ? $_SERVER['HTTP_X_ENTERPRISE'] : null;
    }

    /**
     * 是否使用超级用户身份操作
     *
     * 仅起标识作用,不建议参与核心业务逻辑
     * 如果为true,则model不应该再校验user_id或者enterprise_id等
     *
     * @return bool
     */
    protected function isUsingSuperPrivilege()
    {
        return isset($_SERVER['HTTP_X_SUPER']) && $_SERVER['HTTP_X_SUPER'] == '1';
    }

    /**
     * 获取当前平台ID
     *
     * 仅起标识作用,不建议参与核心业务逻辑
     *
     * @return null|int
     */
    protected function getCurrentPlatformId()
    {
        return isset($_SERVER['HTTP_X_PLATFORM']) ? $_SERVER['HTTP_X_PLATFORM'] : null;
    }

}