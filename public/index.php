<?php

use Bchain\Blockchain\Block;
use Bchain\Blockchain\Blockchain;

require "../vendor/autoload.php";

$app = new \Bchain\App();
$blockchain = new Blockchain();

$app->get("/chain", function () use ($blockchain) {
    return $blockchain->getChain();
});

$app->run();