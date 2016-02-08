<?php

namespace KhaibullinTest\db;

/**
 * Interface DBManagerInterface
 * @package KhaibullinTest\db
 */
interface DBManagerInterface
{
    public static function query(string $sql);
}