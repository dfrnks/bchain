<?php

use Workerman\Connection\ConnectionInterface;
use Workerman\Protocols\Http\Request;
use Workerman\Protocols\Http\Response;
use Workerman\Worker;

require_once __DIR__ . '/bootstrap.php';

// #### http worker ####
$http_worker = new Worker('http://127.0.0.1:8000');

// 4 processes
$http_worker->count = 4;

// Emitted when data received
$http_worker->onMessage = function (ConnectionInterface $connection, Request $request) use ($app) {
    [$status, $header, $body] = $app->exec($request->method(), $request->uri(), (json_decode($request->rawBody(), true) ? : $request->post()));
    
    $connection->send(new Response($status, $header, $body));
};

// Run all workers
Worker::runAll();