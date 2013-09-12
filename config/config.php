<?php
// Current configuration
$config = array(
	'debug' => true,
	'db' 	=> array(
		'path' 		=> __DIR__ . '/../log.db',
		'driver' 	=> 'pdo_sqlite'
	)
);

return $config;
