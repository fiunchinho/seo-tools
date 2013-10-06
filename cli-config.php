<?php

$config = require_once __DIR__ . '/config/config.php';
$app 	= require_once __DIR__ . '/src/DomainFinderSilex/app.php';

$GLOBALS[] = new \Symfony\Component\Console\Helper\HelperSet(array(
    'db' => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($app["db"]),
    'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($app["orm.em"])
));