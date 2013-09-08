<?php
require_once __DIR__.'/../vendor/autoload.php';
error_reporting(-1);
ini_set( 'html_errors', 'on' );
ini_set( 'display_errors', 'on' );

$config = require_once __DIR__ . '/../config/config.php';
$app 	= require __DIR__ . '/../src/DomainFinderSilex/app.php';
require_once __DIR__ . '/../src/DomainFinderSilex/controllers.php';
$app->run();
