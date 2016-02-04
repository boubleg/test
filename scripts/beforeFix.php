<?php

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


$vendor = \KhaibullinTest\Repository\VendorRepository::getById(1);
$vendor->setSchedules(\KhaibullinTest\Repository\VendorRepository::getScheduleForVendor($vendor));
var_dump($vendor);die;