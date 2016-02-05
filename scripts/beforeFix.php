<?php

$start = microtime(true);

use \KhaibullinTest\db\DBManager;
use \KhaibullinTest\Repository\VendorRepository as vr;

/**
 * Class Main
 */
final class Main
{
    public function __construct()
    {
        spl_autoload_register(function ($class) {
            $prefix = 'KhaibullinTest\\';
            $baseDir = __DIR__ . '/../app/src/';
            if (strncmp($prefix, $class, strlen($prefix)) !== 0) {
                return;
            }
            $relative_class = substr($class, strlen($prefix));
            $file = $baseDir . str_replace('\\', '/', $relative_class) . '.php';
            if (file_exists($file)) {
                require $file;
            }
        });

        //$this->_populateTestDB();
        $this->_main();
    }

    private function _main()
    {
        $backupTableName = 'vendor_schedule_backup_' . date('Y_m_d_H_i_s');
        try {
            echo "Backing up vendor_schedule table into $backupTableName table\n";
            vr::backupSchedules($backupTableName);
            echo "Done\n";
            $specialDays = vr::getAllSpecialDays();
            echo "Retrieved " . count($specialDays) . " special days records\n";
            echo "Removing schedules to be replaced by special events\n";
            vr::deleteAllSchedulesForSpecials(array_keys($specialDays));
            echo "Done\nWriting new schedules based on the special days\n";
            vr::writeSchedulesToDB($specialDays);
            echo "Done\n";
        } catch (\Exception $e) {
            echo "Exception caught: " . $e->getMessage() . "\n";
            echo "Restoring data from the backup\n";
            vr::restoreBackupSchedules($backupTableName);
            echo "Done\n";
            die;
        }
    }


    private function _populateTestDB()
    {
        echo "Begin populating DB with test data\n";

        $vendorNameLength = 10;
        $vendorsAmount = 10000;
        $schedulesAmount = 7 * $vendorsAmount;
        $specialDaysAmount = 5 * $vendorsAmount;

        echo "Populating vendors\n";
        $sql = "INSERT INTO vendor(name) VALUES";
        for ($i = 0; $i < $vendorsAmount; $i++) {
            $randomString = substr(str_shuffle(implode('', range('a', 'z'))), 0, $vendorNameLength);
            $sql .= "('$randomString'),";
        }

        $sql = substr_replace($sql, '', -1);
        DBManager::query($sql);


        echo "Populating schedules\n";
        $sql = "INSERT INTO vendor_schedule(vendor_id, weekday, all_day, start_hour, stop_hour) VALUES";
        for ($i = 1; $i < $schedulesAmount; $i++) {
            $vendorId = mt_rand(1, $vendorsAmount);
            $allDay = mt_rand(0, 1);
            $weekday =  mt_rand(1, 7);

            if ($allDay) {
                $startHourString = $stopHourString = 'null';
            } else {
                $startHour = mt_rand(0, 19);
                $startHourString = "'" . $startHour . ":" . str_pad(mt_rand(0, 59), 2, '0', STR_PAD_LEFT) . "'";
                $stopHourString = "'" . ($startHour + 4)  . ":" . str_pad(mt_rand(0, 59), 2, '0', STR_PAD_LEFT) . "'";
            }

            $sql .= "($vendorId, $weekday, $allDay, $startHourString, $stopHourString),";
        }
        $sql = substr_replace($sql, '', -1);
        DBManager::query($sql);


        echo "Populating special days\n";
        $sql = "INSERT INTO vendor_special_day(vendor_id, special_date, event_type, all_day, start_hour, stop_hour) VALUES ";
        for ($i = 0; $i < $specialDaysAmount; $i++) {
            $openClosed = ["'opened'", "'closed'"];
            $vendorId = mt_rand(1, $vendorsAmount);
            $eventType = $openClosed[mt_rand(0, 1)];
            $specialDate = "'2015-12-" . mt_rand(21, 27) . "'";
            $allDay = mt_rand(0, 1);
            if ($allDay) {
                $startHourString = $stopHourString = 'null';
            } else {
                $startHour = mt_rand(0, 19);
                $startHourString = "'" . $startHour . ":" . str_pad(mt_rand(0, 59), 2, '0', STR_PAD_LEFT) . "'";
                $stopHourString = "'" . ($startHour + 4)  . ":" . str_pad(mt_rand(0, 59), 2, '0', STR_PAD_LEFT) . "'";
            }

            $sql .= "($vendorId, $specialDate, $eventType, $allDay, $startHourString, $stopHourString),";
        }
        $sql = substr_replace($sql, '', -1);
        DBManager::query($sql);

        echo "DB populated\n";
    }
}

$main = new Main();

echo "Time spent: " . (microtime(true) - $start) . "s\n";
die;