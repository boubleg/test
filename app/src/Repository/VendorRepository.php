<?php

namespace Repository;

use Entities\Vendor;

final class VendorRepository extends RepositoryBase
{
	const VENDOR_TABLE = 'vendor';
	const VENDOR_FIELD_ID = 'id';
	const VENDOR_FIELD_NAME = 'name';

	/**
	 * @param int $id
	 * @return Vendor|bool
	 */
	public static function getById(int $id) : Vendor
	{
		$sql = 'SELECT * FROM ' . VENDOR_TABLE . ' WHERE ' . VENDOR_FIELD_ID . ' = ' . $id;
		$result = self::query($sql);

		if (!empty($result)) {
			return new Vendor($result[0][VENDOR_FIELD_ID], $result[0][VENDOR_FIELD_NAME]);
		} else {
			return false;
		}
	}

	/**
	 * @return \SplFixedArray
	 */
	public static function getAll() : \SplFixedArray
	{
		$sql = 'SELECT * FROM ' . VENDOR_TABLE;
		$result = self::query($sql);

		$vendors = new \SplFixedArray(count($result));
		foreach ($result as $row) {
			$vendors[]= new Vendor($row[VENDOR_FIELD_ID], $row[VENDOR_FIELD_NAME]);
		}
	}
}