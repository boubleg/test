<?php

namespace Khaibullin\db;

/**
 * Class DBManager
 * @package db
 */
class DBManager implements DBManagerInterface
{
    private function __construct()
    {
    }

    private function __clone()
    {
    }

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
                $className = 'Khaibullin\db\MysqlDBManager';
                break;
        }
        return call_user_func("$className::query", $sql);
    }

    /**
     * @return string
     */
    public static function getDBTimeZone()
    {
        $sql = "SELECT @@system_time_zone;";
        $result = self::query($sql);
        return $result[0]["@@system_time_zone"];
    }

    /**
     * @param $key string|null
     * @return array|string
     */
    public static function getDBConfig($key = null)
    {
        try {
            $config = json_decode(file_get_contents(__DIR__ . '/../../../app/config/db.json'), true);
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