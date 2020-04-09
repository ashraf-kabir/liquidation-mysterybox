<?php
defined('BASEPATH') or exit('No direct script access allowed');
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * Cache Class
 */
class Cache_service
{
  public $_type = 'file';
    /**
     * Cache Adapter.
     *
     * @var mixed
     */
    public $_adapter = null;

    /**
     * Cache time
     *
     * @var mixed
     */
    public $_cache_time = 3600;

    public function set_adapter($type, $cache_time=3600)
    {
        $this->_type = $type;
        $this->_cache_time = $cache_time;

        switch ($type)
        {
            case 'file':
                $this->_adapter = null;
                break;
            default:
                break;
        }
    }

    /**
     * Set cache Key
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function set($key, $value)
    {
      file_put_contents(__DIR__ . '/../config/' . md5($key) . '.json', json_encode($value));
    }

    public function get($key)
    {
      $file_path = __DIR__ . '/../config/' . md5($key) . '.json';

      if (file_exists($file_path))
      {
        if (time() - $this->_cache_time < filemtime($file_path))
        {
          $data = file_get_contents($file_path);
          return json_decode($data, TRUE);
        }
        else
        {
          unlink($file_path);
        }
      }
        return NULL;
    }

    public function remove($key)
    {
      $file_path = __DIR__ . '/../config/' . md5($key) . '.json';

      if (file_exists($file_path))
      {
          unlink($file_path);
          return TRUE;
      }
        return NULL;
    }
}