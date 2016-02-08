<?php

namespace KhaibullinTest\db;

/**
 * Interface DBManagerInterface
 * @package KhaibullinTest\db
 */
interface DBManagerInterface
{
    /**
     * @param string $sql
     * @return mixed
     */
    public static function query($sql);
}