<?php

namespace Bchain;

class App {
    
    private $routes = [];
    
    public function get(string $path, $function) : App {
        $this->routes["GET"][$path] = $function;
        
        return  $this;
    }
    
    public function post(string $path, $function) : App {
        $this->routes["POST"][$path] = $function;
        
        return  $this;
    }
    
    public function put(string $path, $function) : App {
        $this->routes["PUT"][$path] = $function;
        
        return  $this;
    }
    
    public function delete(string $path, $function) : App {
        $this->routes["DELETE"][$path] = $function;
        
        return  $this;
    }
    
    public function run() {
        $method = $_SERVER["REQUEST_METHOD"];
        $uri = $_SERVER["REQUEST_URI"];
        
        if (!key_exists($method, $this->routes) || !key_exists($uri, $this->routes[$method])) {
            header("Content-Type: application/json");
            http_response_code(404);
            exit(json_encode([ "code" => 404, "message" => "Page not found" ]));
        }
        
        $rota = $this->routes[$_SERVER["REQUEST_METHOD"]][$_SERVER["REQUEST_URI"]];
        
        try {
            $data = $rota();
        } catch (\Exception $exception) {
            header("Content-Type: application/json");
            http_response_code(500);
            exit(json_encode([ "code" => $exception->getCode() ? : 500, "message" => $exception->getMessage() ]));
        }
        
        if(is_array($data) || is_object($data)) {
            header("Content-Type: application:json");
            
            exit(json_encode($data));
        }
        
        exit($data);
    }
}