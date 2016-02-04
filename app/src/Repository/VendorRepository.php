<?php

namespace KhaibullinTest\Repository;

use KhaibullinTest\Entities\Schedule;
use KhaibullinTest\Entities\Vendor;

/**
 * Class VendorRepository
 * @package Repository
 */
final class VendorRepository extends RepositoryBase
{
	const VENDOR_TABLE = 'vendor';
	const VENDOR_FIELD_ID = 'id';
	const VENDOR_FIELD_NAME = 'name';

	const VENDOR_SCHEDULE_TABLE = 'vendor_schedule';
	const VENDOR_SCHEDULE_ID = 'id';
	const VENDOR_SCHEDULE_VENDOR_ID = 'vendor_id';
	const VENDOR_SCHEDULE_WEEKDAY = 'weekday';
	const VENDOR_SCHEDULE_ALL_DAY = 'all_day';
	const VENDOR_SCHEDULE_START_HOUR = 'start_hour';
	const VENDOR_SCHEDULE_STOP_HOUR = 'stop_hour';

	/**
	 * @param int $id
	 * @return Vendor|bool
	 */
	public static function getById(int $id) : Vendor
	{
		$sql = 'SELECT * FROM ' . self::VENDOR_TABLE . ' WHERE ' . self::VENDOR_FIELD_ID . ' = ' . $id;
		$result = self::query($sql);

		if (!empty($result)) {
			return new Vendor($result[0][self::VENDOR_FIELD_ID], $result[0][self::VENDOR_FIELD_NAME]);
		} else {
			return false;
		}
	}

	/**
	 * @return \SplFixedArray
	 */
	public static function getAll() : \SplFixedArray
	{
		$sql = 'SELECT * FROM ' . self::VENDOR_TABLE;
		$result = self::query($sql);

		$vendors = new \SplFixedArray(count($result));
		for ($i = 0; $i < count($result); $i++) {
			$row = $result[$i];
			$vendors[$i]= new Vendor(
				$row[self::VENDOR_FIELD_ID],
				$row[self::VENDOR_FIELD_NAME] ?? ''
			);
		}
	}

	/**
	 * @return \SplFixedArray
	 */
	public static function getScheduleForVendor(Vendor $vendor) : \SplFixedArray
	{
		$sql = 'SELECT * FROM ' . self::VENDOR_SCHEDULE_TABLE . ' WHERE ' . self::VENDOR_SCHEDULE_VENDOR_ID . ' = ' . $vendor->getId();
		$result = self::query($sql);

		$schedules = new \SplFixedArray(count($result));
		for ($i = 0; $i < count($result); $i++) {
			$row = $result[$i];
			$schedules[$i]= new Schedule(
				$row[self::VENDOR_SCHEDULE_WEEKDAY],
				$row[self::VENDOR_SCHEDULE_ALL_DAY] ? (bool)$row[self::VENDOR_SCHEDULE_ALL_DAY] : false,
				$row[self::VENDOR_SCHEDULE_WEEKDAY] ?? '',
				$row[self::VENDOR_SCHEDULE_WEEKDAY] ?? ''
			);
		}

		return $schedules;
	}
}