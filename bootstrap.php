<?php

use Bchain\App;
use Bchain\Blockchain\Block;
use Bchain\Blockchain\Blockchain;

require_once __DIR__ . '/vendor/autoload.php';

$app = new App();
$blockchain = new Blockchain($GLOBALS["node"], $GLOBALS["node_principal"]);

$app->get("/chain", function () use ($blockchain) {
    return $blockchain->getChain();
});

$app->post("/chain", function ($data) use ($blockchain) {
    if (!is_array($data["chain"])) {
        throw new Exception("Informe o body corretamente");
    }
    
    $blockchain->replaceChain($data["chain"]);
    
    return $blockchain->getChain();
});

$app->put("/block", function ($data) use ($blockchain) {
    if (!is_array($data["data"])) {
        throw new Exception("Informe o body corretamente");
    }
    
    $blockchain->addBlock(new Block($data["data"]));
    
    return $blockchain->getChain();
});

$app->get("/nodes", function ($data) use ($blockchain) {
    return $blockchain->getNodes();
});

$app->put("/node", function ($data) use ($blockchain) {
    $blockchain->setNode($data["address"]);
    
    return $blockchain->getNodes();
});

$app->get("/sync", function () use ($blockchain) {
    $blockchain->sync($GLOBALS["node_principal"]);
    
    return $blockchain->getChain();
});