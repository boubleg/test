<?php

namespace KhaibullinTest\Repository;

use KhaibullinTest\Entities\Schedule;

/**
 * Class VendorRepository
 * @package Repository
 */
final class VendorRepository extends RepositoryBase
{
    public static function deleteAllSchedulesForSpecials()
    {
        $sql = 'SELECT
                    DISTINCT(vs.id)
                FROM
                    vendor_schedule vs
                INNER JOIN
                    vendor_special_day vsd
                ON
                    vs.vendor_id = vsd.vendor_id
                AND
                    vs.weekday = DAYOFWEEK(vsd.special_date)';

        $ids = self::query($sql);

        array_walk($ids, function(&$x) {
            $x = $x['id'];
        });

        $sql = 'DELETE FROM vendor_schedule WHERE id IN (' . implode(',', $ids) . ');';

//        $sql =
//            'DELETE FROM
//                vendor_schedule
//            WHERE id IN
//                (SELECT * FROM
//                    (SELECT
//                        DISTINCT(vs.id)
//                    FROM
//                        vendor_schedule vs
//                    INNER JOIN
//                        vendor_special_day vsd
//                    ON
//                        vs.vendor_id = vsd.vendor_id
//                    AND
//                    	vs.weekday = DAYOFWEEK(vsd.special_date)) t)';

        self::query($sql);
    }

    /**
     * @return array
     */
    public static function getAllSpecialDays() : array
    {
        $sql =
            "SELECT
				v.id, v.name, vsd.special_date, vsd.event_type, vsd.all_day, vsd.start_hour, vsd.stop_hour
			FROM
				vendor v
			RIGHT JOIN -- we need only vendors with special days --
				vendor_special_day vsd
			ON
				v.id = vsd.vendor_id
			WHERE
			    vsd.special_date
			BETWEEN
			    '2015-12-21'
            AND
                '2015-12-27'
			ORDER BY
				v.id, vsd.special_date";

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