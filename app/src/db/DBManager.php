<?php

namespace Khaibullin\db;

/**
 * Main class responsible for the DB connection, execution of the queries and so on
 *
 * Class DBManager
 * @package db
 */
class DBManager implements DBManagerInterface
{
    /**
     * To prevent creation the instances of that class
     */
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
     * Returns DB timezone which is required because of the usage of date() function in the Schedule class
     *
     * @return string
     */
    public static function getDBTimeZone()
    {
        $sql = "SELECT @@system_time_zone;";
        $result = self::query($sql);
        return $result[0]["@@system_time_zone"];
    }

    /**
     * Will return either a whole config file or a single value if $key is provided
     *
     * @param  string|null  $key a single key of the config that needs to be returned
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
