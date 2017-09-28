<?php

$container = require dirname(__DIR__) . '/app/bootstrap.php';

$container->getByType(Nette\Application\Application::class)->run();
