<?php

include '../Server/goodsServer.php';

class goodsController
{
    function Action($request)
    {
        $good_server = new GoodsServer();
        if ($request->get['action']) {
            $action = $request->get['action'];
        } else {
            $action = $request->post['action'];
        }
        $result = '';
        switch ($action) {
            case 'getGoodbyId':
                $id = $request->get['id'];
                $result = $good_server->getGoodbyId($id);
                break;
            case 'getAllGoods':
                $result = $good_server->getAllGoods();
                break;
            case 'updateGoodbyId':
                $goods['id'] = $request->post['id'];
                $goods['name'] = $request->post['good_title'];
                $goods['type'] = $request->post['type'];
                $goods['type_id'] = $request->post['type_id'];
                $goods['num'] = $request->post['good_num'];
                $goods['price'] = $request->post['price'];
                $goods['specs'] = $request->post['good_specs'];
                $goods['cover'] = $request->post['cover'];
                $goods['detail'] = $request->post['good_detail'];
                $old_type = $request->post['old_type'];
                $data = array(
                    $goods['name'],
                    $goods['type'],
                    $goods['num'],
                    $goods['price'],
                    $goods['cover'],
                    $goods['detail'],
                    $goods['specs'],
                    $goods['type_id'],
                    $goods['id']
                );

                $result = $good_server->updateGoodbyId($data, $old_type, $goods);
                break;
            case 'addGoods':
                $goods['name'] = $request->post['good_title'];
                $goods['type'] = $request->post['type'];
                $goods['type_id'] = $request->post['type_id'];
                $goods['num'] = $request->post['good_num'];
                $goods['price'] = $request->post['price'];
                $goods['specs'] = $request->post['good_specs'];
                $goods['cover'] = $request->post['cover'];
                $goods['detail'] = $request->post['good_detail'];
                $data = array(
                    $goods['name'],
                    $goods['type'],
                    $goods['num'],
                    $goods['price'],
                    $goods['cover'],
                    $goods['detail'],
                    $goods['specs'],
                    $goods['type_id']
                );
                $result = $good_server->addGoods($data, $goods);
                break;
            case 'deleteGoodsById':
                $arr = array();
                $type_id = array();
                //$arr=$request->get['good_id'];
                $arr = $request->post['good_id'];
                //$type_id=$request->get['type_id'];
                $type_id = $request->post['type_id'];
                $result = $good_server->deleteGoodsById($arr, $type_id);
                unset($arr);
                break;
            case 'getGoodsByType':
                $type_id = $request->get['type_id'];
                $result = $good_server->getGoodsByType($type_id);
                break;
            case 'GoodsLimit':
                $limit = $request->get['limit'];
                $page = $request->get['page'];
                $result = $good_server->GoodsLimit($limit, $page);
                break;
        }
        return $result;
    }


}