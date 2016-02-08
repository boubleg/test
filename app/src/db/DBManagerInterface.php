<?php

namespace Khaibullin\db;

/**
 * Interface DBManagerInterface
 * @package Khaibullin\db
 */
interface DBManagerInterface
{
    public static function query(string $sql);
}