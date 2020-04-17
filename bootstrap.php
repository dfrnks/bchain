<?php

use Bchain\App;
use Bchain\Blockchain\Block;
use Bchain\Blockchain\Blockchain;

require_once __DIR__ . '/vendor/autoload.php';

$app = new App();
$blockchain = new Blockchain();

$app->get("/", function (){
    return "Hello world";
});

$app->get("/chain", function () use ($blockchain) {
    return $blockchain->getChain();
});

$app->post("/add/block", function ($data) use ($blockchain) {
    if (!is_array($data["data"])) {
        throw new Exception("Informe o body corretamente");
    }
    
    $blockchain->addBlock(new Block($data["data"]));
    
    return $blockchain->getChain();
});
