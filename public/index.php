<?php

use Bchain\Blockchain\Block;
use Bchain\Blockchain\Blockchain;

require "../vendor/autoload.php";

$app = new \Bchain\App();
$blockchain = new Blockchain();

$app->get("/chain", function () use ($blockchain) {
    return $blockchain->getChain();
});

$app->post("/add/block", function () use ($blockchain) {
    $data = file_get_contents("php://input");
    $data = json_decode($data, true);
    
    if (!is_array($data["data"])) {
        throw new Exception("Informe o body corretamente");
    }

    $blockchain->addBlock(new Block($data["data"]));
    
    return $blockchain->getChain();
});

$app->run();