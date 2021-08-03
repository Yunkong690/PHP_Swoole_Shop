<?php

include '../Server/carServer.php';

class carController
{
    function Action($request, $response)
    {
        $car_server = new carServer();
        if ($request->get['action']) {
            $action = $request->get['action'];
        } else {
            $action = $request->post['action'];
        }
        $result = '';
        switch ($action) {
            case 'getCar':
                $user_id = $request->get['user_id'];
                $result = $car_server->getCar($user_id);
                break;
            case 'addCar':
                $user_id = $request->get['user_id'];
                $good_id = $request->get['good_id'];
                $buy_num = $request->get['buynum'];
                $result = $car_server->addCar($user_id, $good_id, $buy_num);
                break;
            case 'deleteCar':
                $arr = array();
                $arr = $request->post['ids'];
                $result = $car_server->deleteCar($arr);
                break;
        }
        return $result;
    }
}