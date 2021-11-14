<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__. '/..');
$dotenv->load();

require_once __DIR__. '/../src/Db/init.php';
require_once __DIR__. '/../src/Routing/routes.php';