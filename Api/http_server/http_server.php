<?php

include '../Controller/goodsController.php';
include '../Controller/userController.php';
include '../Controller/orderController.php';
include '../Controller/typeController.php';
include '../Controller/carController.php';
include '../Controller/adminController.php';
include '../Controller/uploadController.php';

$http = new Swoole\Http\Server('0.0.0.0', 9501);

$http->set([
    'enable_static_handler' => true,
    'document_root' => '/usr/local/nginx/html/Shop_Swoole/Web',
]);
$http->on('request', function ($request, $response) {
    if ('/favicon.ico' === $request->server['path_info'] || '/favicon.ico' === $request->server['request_uri']) {
        $response->end();
        return;
    }
    $response->header('Content-Type', 'text/html; charset=utf-8');
    list($controller, $action) = explode('/', trim($request->server['request_uri'], '/'));
    //根据 $controller, $action 映射到不同的控制器类和方法
    $result = (new $controller())->{$action}($request, $response);
    $response->end($result);
});

$http->start();
