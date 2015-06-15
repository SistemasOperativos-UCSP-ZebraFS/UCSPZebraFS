<?php

use Knp\Provider\ConsoleServiceProvider;

$app = require_once __DIR__.'/../bootstrap.php';

$app['debug'] = true;

$app->run();