<?php

include_once '../private/WebsiteEngine.php';

$engine = new DaveComputerGeek\WebsiteEngine();

echo $engine->getConfig()->getKey("random");