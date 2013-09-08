<?php
// Current configuration
$config = array(
	'debug' => true,
	'db' 	=> array(
		'path' 		=> '/var/www/google/log.db',
		'driver' 	=> 'pdo_sqlite'
	)
);

return $config;