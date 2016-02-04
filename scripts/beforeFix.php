<?php

$start = microtime(true);

#TODO: rename namespace
spl_autoload_register(function($class) {
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

//for($i = 1; $i < 40000; $i++)
//{
//	\KhaibullinTest\db\DBManager::query('
//		insert into vendor_schedule (vendor_id, weekday, all_day, start_hour, stop_hour)
//		values(' . rand(1,5900) . ', ' . rand(1,7) . ', ' . rand(0,1) . ', "12:00:00", "22:00:00")
//	');
//}
//die;
$vendors = \KhaibullinTest\Repository\VendorRepository::getAll();
var_dump(microtime(true) - $start);die;