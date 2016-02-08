<?php

namespace Khaibullin\Repository;

use Khaibullin\Entities\Schedule;

/**
 * Class VendorRepository
 * @package Repository
 */
final class ScheduleRepository extends RepositoryBase
{
    public static function deleteAllSchedulesForSpecials()
    {
        $sql = "SELECT
                    DISTINCT(vs.id)
                FROM
                    vendor_schedule vs
                INNER JOIN
                    vendor_special_day vsd
                ON
                    vs.vendor_id = vsd.vendor_id
                AND
                    vs.weekday = DAYOFWEEK(vsd.special_date - 1) -- in DAYOFWEEK Sunday = 1, Monday = 2, etc.
                WHERE
                    vsd.special_date
                BETWEEN
                    '2015-12-21'
                AND
                    '2015-12-27'";

        $ids = self::query($sql);

        array_walk(
            $ids,
            function (&$x) {
                $x = $x['id'];
            }
        );

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
    public static function getAllSpecialDaysAsSchedules() : array
    {
        $sql =
            "SELECT
                v.id,
                vsd.special_date,
                vsd.event_type,
                vsd.all_day,
                vsd.start_hour,
                vsd.stop_hour
            FROM
                vendor_special_day vsd
            INNER JOIN
                vendor v
            ON
                v.id = vsd.vendor_id
            WHERE
                vsd.special_date
            BETWEEN
                '2015-12-21'
            AND
                '2015-12-27';";

        $result = self::query($sql);
        $schedules = [];

        foreach ($result as $row) {
            if (!$schedule = Schedule::createFromSpecialDay(
                $row['id'],
                $row['special_date'],
                $row['event_type'],
                $row['all_day'],
                $row['start_hour'] ?? '',
                $row['stop_hour'] ?? ''
            )
            ) {
                continue;
            }

            $schedules[] = $schedule;
        }

        return $schedules;
    }

    /**
     * @param array $schedules
     * @return bool
     */
    public static function writeSchedulesToDB(array $schedules) : bool
    {
        $sql = 'INSERT INTO vendor_schedule(vendor_id, weekday, all_day, start_hour, stop_hour) VALUES ';

//        $sql .= array_reduce(
//            $schedules,
//            function (string $a, string $b) {
//                return $a . $b;
//            },
//            ''
//        );

        foreach ($schedules as $schedule) {
            $sql .= $schedule;
        }

        $sql = substr_replace($sql, '', -1);

        $result = self::query($sql);

        return $result;
    }


    /**
     * Generally I would prefer to have the whole DB backed up somewhere
     *
     * @param string $backupTableName
     */
    public static function backupSchedules(string $backupTableName)
    {
        $sql = "DROP TABLE IF EXISTS $backupTableName;";
        self::query($sql);
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
        $sql = "TRUNCATE TABLE vendor_schedule";
        self::query($sql);
        $sql = "REPLACE INTO vendor_schedule SELECT * FROM $backupTableName;";
        self::query($sql);
        $sql = "DROP TABLE $backupTableName;";
        self::query($sql);
    }
}