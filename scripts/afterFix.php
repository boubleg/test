<?php

$start = microtime(true);

use \KhaibullinTest\db\DBManager;
use \KhaibullinTest\Repository\ScheduleRepository as sr;

/**
 * Class Main
 */
final class Main
{
    /**
     * Main constructor.
     */
    public function __construct()
    {
        spl_autoload_register(
            function ($class) {
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
            }
        );

        $this->main();
    }

    private function main()
    {
        $backupTableName = DBManager::getDBConfig('backupTableName');
        echo "Restoring data from the backup\n";
        sr::restoreBackupSchedules($backupTableName);
        echo "Done\n";
    }
}

$main = new Main();

echo "Time spent: " . (microtime(true) - $start) . "s\n";
die;