<?php


include '../Server/orderServer.php';


class orderController
{
    function Action($request, $response)
    {
        $order_server=new orderServer();
        if($request->get['action']){
            $action =  $request->get['action'];
        }else{
            $action =  $request->post['action'];
        }
        $result = '';
        switch ($action) {
            case 'makeOrder':
                $Item = $request->post['item_id'];
                $data = array(
                    $request->post['order_id'],
                    serialize($request->post['item_id']),
                    $request->post['totalPrice'],
                    $request->post['totalNum'],
                    $request->post['postname'],
                    $request->post['province'] . $request->post['city'] . $request->post['county'] . $request->post['address'],
                    $request->post['tel'],
                    $request->post['user_id'],
                );
                $result=$order_server->makeOrder($data, $Item);
                break;
            case 'makeOrderId':
                $result=$order_server->makeOrderId();
                break;
            case 'getAllOrderById':
                $user_id = $request->get['user_id'];
                $result=$order_server-> getAllOrderById($user_id);
                break;
            case 'payOrder':
                $Item = $request->post['item_id'];
                $user_id = $request->post['user_id'];
                $bill = $request->post['totalPrice'];
                $order_id = $request->post['order_id'];

                $result=$order_server->payOrder($user_id, $order_id,$Item,$bill);
                break;
            case'getOrderGoods':
                $order_id = $request->get['order_id'];
                $result=$order_server->getOrderGoods($order_id);

        }
        return $result;
    }
}