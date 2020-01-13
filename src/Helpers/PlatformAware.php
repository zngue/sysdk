<?php
namespace ShangYou\Helpers;

trait PlatformAware
{

    /**
     * 当前平台ID
     *
     * 如果无法获取平台id,则返回null
     *
     * @return int|null
     */
    protected function getPlatformId()
    {
        return app('ShangYou.platform_aware')->getPlatformId();
    }

    /**
     * 获取平台mappers
     *
     * @return array
     */
    protected function getPlatformMappers()
    {
        return app('ShangYou.platform_mapper')['mappers'];
    }

    /**
     * 获取平台域名列表
     *
     * @return array
     */
    protected function getPlatformDomains()
    {
        static $domains = [];

        if (empty($domains)) {
            $mappers = $this->getPlatformMappers();
            foreach ($mappers as $key => $platform) {
                $domains[$key] = 1;
                if (!empty($platform['domain'])) {
                    $domains[trim($platform['domain'], '/')] = 1;
                }
            }
        }

        return array_keys($domains);
    }
}