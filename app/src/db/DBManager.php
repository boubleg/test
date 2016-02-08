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
    public static function query(string $sql)
    {
        $adapter = self::getDBConfig('adapter') ?? 'mysql';
        switch ($adapter) {
            case 'mysql':
            default:
                $className = 'Khaibullin\db\MysqlDBManager';
                break;
        }
        return call_user_func("$className::query", $sql);
    }

    /**
     * @param $key string|null
     * @return array|string
     */
    public static function getDBConfig(string $key = null)
    {
        try {
            $config = json_decode(file_get_contents('/vagrant/app/config/db.json'), true);
            return null === $key ? $config : $config[$key] ?? '';
        } catch (\Exception $e) {
            die('Unable to read DB config file');
        }
    }
}