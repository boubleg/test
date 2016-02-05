<?php

namespace KhaibullinTest\Repository;

use KhaibullinTest\Entities\Schedule;

/**
 * Class VendorRepository
 * @package Repository
 */
final class VendorRepository extends RepositoryBase
{
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

    /**
     * @return array
     */
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
        $sql = 'INSERT INTO vendor_schedule(vendor_id, weekday, all_day, start_hour, stop_hour) VALUES';

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


    /**
     * @param string $backupTableName
     */
    public static function backupSchedules(string $backupTableName)
    {
        $sql = "CREATE TABLE $backupTableName LIKE vendor_schedule;";
        self::query($sql);
        $sql = "INSERT INTO $backupTableName SELECT * FROM vendor_schedule;";
        self::query($sql);
    }

    /**
     * @param string $backupTableName
     */
    public static function restoreBackupSchedules(string $backupTableName)
    {
        $sql = "REPLACE INTO vendor_schedule SELECT * FROM $backupTableName;";
        self::query($sql);
        $sql = "DROP TABLE $backupTableName;";
        self::query($sql);
    }
}