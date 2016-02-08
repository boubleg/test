<?php

namespace KhaibullinTest\Repository;

use KhaibullinTest\db\DBManager;

/**
 * Class RepositoryBase
 * @package KhaibullinTest\Repository
 */
abstract class RepositoryBase
{
    /**
     * @param string $sql
     * @return array|bool
     */
    public static function query($sql)
    {
        return DBManager::query($sql);
    }

    /**
     * @param string $str
     * @return string
     */
    public static function encloseString($str)
    {
        return "'$str'";
    }
}