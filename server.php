<?php

use Workerman\Connection\ConnectionInterface;
use Workerman\Protocols\Http\Request;
use Workerman\Protocols\Http\Response;
use Workerman\Worker;

$GLOBALS["server"] = $argv[0];
$GLOBALS["node"] = isset($argv[2]) ? $argv[2] : '127.0.0.1:8000';
$GLOBALS["node_principal"] =  isset($argv[3]) ? $argv[3] : '127.0.0.1:8001';

require_once __DIR__ . '/bootstrap.php';
echo "\n";
// #### http worker ####
$http_worker = new Worker("http://" . $GLOBALS["node"]);

// 4 processes
$http_worker->count = 1;

// Emitted when data received
$http_worker->onMessage = function (ConnectionInterface $connection, Request $request) use ($app) {
//    var_dump($request->method(), $request->uri(), (json_decode($request->rawBody(), true) ? : $request->post()));
    
    [$status, $header, $body] = $app->exec($request->method(), $request->uri(), (json_decode($request->rawBody(), true) ? : $request->post()));
    
    $connection->send(new Response($status, $header, $body));
};

// Run all workers
Worker::runAll();