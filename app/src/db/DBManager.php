<?php

namespace KhaibullinTest\db;

/**
 * Class DBManager
 * @package db
 */
class DBManager implements DBManagerInterface
{
    /**
     * @param string $sql
     * @return bool|array
     */
    public static function query($sql)
    {
        $adapter = '' === self::getDBConfig('adapter') ? self::getDBConfig('adapter') : 'mysql';
        switch ($adapter) {
            case 'mysql':
            default:
                $className = 'KhaibullinTest\db\MysqlDBManager';
                break;
        }
        return call_user_func("$className::query", $sql);
    }

    /**
     * @param $key string|null
     * @return array|string
     */
    public static function getDBConfig($key = null)
    {
        try {
            $config = json_decode(file_get_contents('/vagrant/app/config/db.json'), true);
            if (null === $key) {
                return $config;
            } else {
                return isset($config[$key]) ? $config[$key] : '';
            }
        } catch (\Exception $e) {
            die('Unable to read DB config file');
        }
    }
}