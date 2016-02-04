<?php

namespace KhaibullinTest\Repository;

use KhaibullinTest\db\DBManager;

abstract class RepositoryBase
{
	public static function query(string $sql) : array
	{
		return DBManager::query($sql);
	}
}