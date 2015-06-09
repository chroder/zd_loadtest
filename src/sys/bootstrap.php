<?php

require __DIR__.'/../../vendor/autoload.php';

$loader = new \Composer\Autoload\ClassLoader();
$loader->add('ZdLoadTest', __DIR__.'/../');
$loader->register();