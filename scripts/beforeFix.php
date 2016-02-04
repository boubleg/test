<?php

$start = microtime(true);

use \KhaibullinTest\db\DBManager;

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

        \KhaibullinTest\Repository\VendorRepository::getAll();
    }


    private function _populateTestDB()
    {
        echo "Begin populating DB with test data\n";

        $vendorNameLength = 10;
        $vendorsAmount = 10000;
        $schedulesAmount = 7 * $vendorsAmount;

        for ($i = 0; $i < $vendorsAmount; $i++) {
            $randomString = substr(str_shuffle(implode('', range('a', 'z'))), 0, $vendorNameLength);
            $sql = "INSERT INTO vendor(name) VALUES ('$randomString')";
            DBManager::query($sql);
        }

        for ($i = 1; $i < $schedulesAmount; $i++) {
            $vendorId = mt_rand(1, $vendorsAmount);
            $allDay = mt_rand(0, 1);
            $weekday =  mt_rand(1, 7);
            $startHour = $allDay ? null : mt_rand(0, 19);
            $stopHour = $allDay? null : $startHour + 4;
            $startHourString = $startHour ? $startHour . ":" . str_pad(mt_rand(0, 59), 2, '0', STR_PAD_LEFT) : null;
            $stopHourString = $stopHour ? $stopHour . ":" . str_pad(mt_rand(0, 59), 2, '0', STR_PAD_LEFT) : null;

            $sql = "INSERT INTO vendor_schedule(vendor_id, weekday, all_day, start_hour, stop_hour) " .
                "VALUES ('$vendorId', '$weekday', '$allDay', '$startHourString', '$stopHourString');";

            \KhaibullinTest\db\DBManager::query($sql);
        }

        echo "DB populated\n";
    }
}

$main = new Main();

echo(microtime(true) - $start) . "s\n";
die;