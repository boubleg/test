<?php

namespace Khaibullin\db;

/**
 * Interface DBManagerInterface
 * @package Khaibullin\db
 */
interface DBManagerInterface
{
    /**
     * @param string $sql
     * @return mixed
     */
    public static function query($sql);
}