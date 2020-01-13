<?php
namespace ShangYou\Helpers;


/**
 * 域名到平台的映射
 *
 * @package ShangYou\Helpers
 */
class PlatformMapper
{

    private $_mapper;
    private $_platformId;

    public function __construct(array $configs)
    {
        $host = app('request')->getHttpHost();
        if (isset($configs['mappers'][$host])) {
            $this->_mapper = $configs['mappers'][$host];
        } else {
            foreach ($configs['mappers'] as $platform) {
                if (isset($platform['domain']) && $host == $platform['domain']) {
                    $this->_mapper = $platform;
                    break;
                }
            }
        }

    }

    /**
     * 获取平台ID
     *
     * @return null|int
     */
    public function getPlatformId()
    {
        if (!empty($this->_platformId)) {
            return $this->_platformId;
        }

        if (!empty($this->_mapper)) {
            return $this->_mapper['id'];
        }

        return null;
    }

    /**
     * 设置平台ID
     *
     * @param int $platformId
     */
    public function setPlatformId($platformId)
    {
        $this->_platformId = $platformId;
    }

}