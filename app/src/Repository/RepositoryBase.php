<?php

namespace KhaibullinTest\Repository;

use KhaibullinTest\db\DBManager;

abstract class RepositoryBase
{
    /**
     * @param string $sql
     * @return array|bool
     */
	public static function query(string $sql)
	{
		return DBManager::query($sql);
	}
}