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
	/**
	 * @param int $id
	 * @return Vendor|bool
	 */
	public static function getById(int $id) : Vendor
	{
		$sql = 'SELECT * FROM vendor v WHERE v.id = ' . $id;
		$result = self::query($sql);

		if (!empty($result)) {
			return new Vendor($result[0]['id'], $result[0]['name']);
		} else {
			return false;
		}
	}

	/**
	 * @return array
	 */
	public static function getAll() : array
	{
		$sql =
			'SELECT
				v.id, v.name, vs.weekday, vs.all_day, vs.start_hour, vs.stop_hour
			FROM
				vendor v
			RIGHT JOIN -- we need only vendors with schedule --
				vendor_schedule vs
			ON
				v.id = vs.vendor_id
			ORDER BY
				v.id';

		$result = self::query($sql);
		$vendors = [];
		foreach ($result as $row) {
			if (!isset($vendors[$row['id']])) {
				$vendors[$row['id']] = new Vendor(
					(int)$row['id'],
					$row['name']
				);
			}

			$vendors[$row['id']]->addSchedule(
				new Schedule(
					$row['weekday'],
					$row['all_day'] ? (bool)$row['all_day'] : false,
					$row['start_hour'] ?? '',
					$row['stop_hour'] ?? ''
				)
			);
		}

		return $vendors;
	}

	/**
	 * @return \SplFixedArray
	 */
	public static function getScheduleForVendor(Vendor $vendor) : \SplFixedArray
	{
		$sql = 'SELECT * FROM vendor_schedule vs WHERE vs.vendor_id = ' . $vendor->getId();
		$result = self::query($sql);

		$schedules = new \SplFixedArray(count($result));
		for ($i = 0; $i < count($result); $i++) {
			$row = $result[$i];
			$schedules[$i] = new Schedule(
				$row['weekday'],
				$row['all_day'] ? (bool)$row['all_day'] : false,
				$row['start_hour'] ?? '',
				$row['stop_hour'] ?? ''
			);
		}

		return $schedules;
	}
}