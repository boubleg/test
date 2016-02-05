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

    /**
     * @param array $ids
     * @return bool
     */
    public static function deleteAllSchedulesForSpecials(array $ids) : bool
    {
        if (empty($ids)) {
            return false;
        }
        $idsString = implode(', ', $ids);
        $sql = "DELETE FROM vendor_schedule WHERE vendor_id IN ($idsString)";
        return self::query($sql);
    }

    public static function getAllSpecialDays() : array
    {
        $sql =
            'SELECT
				v.id, v.name, vsd.special_date, vsd.event_type, vsd.all_day, vsd.start_hour, vsd.stop_hour
			FROM
				vendor v
			RIGHT JOIN -- we need only vendors with special days --
				vendor_special_day vsd
			ON
				v.id = vsd.vendor_id
			ORDER BY
				v.id, vsd.special_date';

        $result = self::query($sql);
        $vendors = [];

        foreach ($result as $row) {
            $schedule = Schedule::createFromSpecialDay(
                $row['special_date'],
                $row['event_type'],
                $row['all_day'],
                $row['start_hour'] ?? '',
                $row['stop_hour'] ?? ''
            );

            if (null === $schedule) {
                continue;
            }


            if (!isset($vendors[$row['id']])) {
                $vendors[$row['id']] = [];
            }

            $vendors[$row['id']][] = $schedule;
        }

        return $vendors;
    }

    /**
     * @param array $schedulesVendors
     * @return bool
     */
    public static function writeSchedulesToDB(array $schedulesVendors) : bool
    {
        $sql = 'INSERT INTO vendor_schedule vs(vendor_id, weekday, all_day, start_hour, stop_hour) VALUES';

        /** @var Schedule $schedule */
        foreach ($schedulesVendors as $vendorId => $schedules) {
            foreach ($schedules as $schedule) {
                $sql .= '(' . $vendorId . ', ' . $schedule . '),';
            }
        }

        $sql = substr_replace($sql, '', -1);

        $result = self::query($sql);
        return $result;
    }
}