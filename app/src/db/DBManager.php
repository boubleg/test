<?php

namespace KhaibullinTest\db;

/**
 * Class DBManager
 * @package db
 */
final class DBManager
{
    /** @var \mysqli $_connection */
    private static $_connection = null;

    /**
     * @param string $sql
     * @return array|bool
     */
    public static function query(string $sql)
    {
        $result = self::_getConnection()->query($sql);
        if ($result === false) {
            self::_getConnection()->rollback();
            echo $sql . ' failed: ' . self::_getConnection()->error . "\n";
            return $result;
        } elseif ($result instanceof \mysqli_result) {
            self::_getConnection()->commit();
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            self::_getConnection()->commit();
            return $result;
        }
    }

    /**
     * @return \mysqli
     */
    private static function _getConnection() : \mysqli
    {
        if (self::$_connection !== null && !self::$_connection->ping()) {
            if (self::$_connection instanceof \mysqli) {
                self::$_connection->close();
            }
            self::$_connection = null;
        } elseif (self::$_connection === null) {

            if (!class_exists('\mysqli')) {
                die('mysqli is not available.');
            }

            $config = self::_getDBConfig();
            $connection = new \mysqli($config['host'], $config['username'], $config['password'], $config['database']);

            if ($connection->connect_errno !== 0) {
                die('Database error: ' . $connection->error);
            }

            $connection->autocommit(false);
            self::$_connection = $connection;
        }
        return self::$_connection;
    }

    /**
     * @return array
     */
    private static function _getDBConfig() : array
    {
        return json_decode(file_get_contents('/vagrant/app/config/mysql.json'), true);
    }
}