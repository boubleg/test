<?php

namespace KhaibullinTest\db;

/**
 * Class MysqlDBManager
 * @package KhaibullinTest\db
 */
final class MysqlDBManager extends DBManager
{
    /** @var \mysqli|null $connection */
    private static $connection = null;

    /**
     * @param string $sql
     * @return bool|array
     * @throws \Exception
     */
    public static function query(string $sql)
    {
        $result = self::_getConnection()->query($sql);
        if (false === $result) {
            self::_getConnection()->rollback();
            echo substr($sql, 0, 500) . '... failed: ' . self::_getConnection()->error . "\n";
            throw new \Exception('Could not execute db query');
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
        if (null !== self::$connection && !self::$connection->ping()) {
            if (self::$connection instanceof \mysqli) {
                self::$connection->close();
            }
            self::$connection = null;
        }

        if (null === self::$connection) {
            if (!class_exists('\mysqli')) {
                die('mysqli is not available.');
            }

            $config = self::getDBConfig();
            $connection = new \mysqli(
                $config['host'],
                $config['username'],
                $config['password'],
                $config['database']
            );

            if ($connection->connect_errno !== 0) {
                die('Database error: ' . $connection->error);
            }

            $connection->autocommit(false);
            self::$connection = $connection;
        }

        return self::$connection;
    }
}