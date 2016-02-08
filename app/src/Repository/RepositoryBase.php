<?php

namespace Khaibullin\Repository;

use Khaibullin\db\DBManager;

/**
 * Class RepositoryBase
 * @package Khaibullin\Repository
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