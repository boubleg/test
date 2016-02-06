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
        if (null !== self::$_connection && !self::$_connection->ping()) {
            if (self::$_connection instanceof \mysqli) {
                self::$_connection->close();
            }
            self::$_connection = null;
        } elseif (null === self::$_connection) {

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
		try {
			return json_decode(file_get_contents('/vagrant/app/config/mysql.json'), true);
		} catch (\Exception $e) {
			die('Unable to read DB config file');
		}
    }
}