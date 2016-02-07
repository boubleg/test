<?php

namespace KhaibullinTest\db;

interface DBManagerInterface
{
    public static function query(string $sql);
}