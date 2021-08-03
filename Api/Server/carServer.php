<?php


include_once '../utils/DbUtils.php';
include_once '../utils/msg.php';
include_once "../utils/redisUtils.php";

class carServer
{
    function getCar($user_id){
        $dbutils = new dbUtils();
        $res = $dbutils->my_query('select *  from car where user_id=? and status=0', array($user_id), false);
        if ($res) {
            $msg = new msg(0, "获取购物车信息成功", $res, count($res));
        } else {
            $msg = new msg(0, "","");
        }
        return json_encode($msg->getJson());
    }

    function addCar($user_id,$good_id,$buy_num){
        $dbutils = new dbUtils();
        $res = $dbutils->my_query('select *  from goods where id=?', array($good_id));
        if($res){
            $cover=$res['cover'];
            $name=$res['name'];
            $price=$res['price'];
            $specs=$res['specs'];
            $sql='insert into car set cover=?,name=?,buynum=?,price=?,specs=? ,good_id=?,user_id=?';
            $res1=$dbutils->my_exec($sql,array($cover,$name,$buy_num,$price,$specs,$good_id,$user_id));
            $msg = new msg(0, "添加购物车成功", $res1);
            return json_encode($msg->getJson());
        }
    }

    function deleteCar($car_ids){
        $dbutils = new dbUtils();
        $in = str_repeat('?,', count($car_ids) - 1) . '?';
        $sql = "delete from car where id in ($in)";
        $res = $dbutils->my_exec($sql, $car_ids);
        if($res){
            $msg = new msg(0, "删除购物车商品成功", $res);
        } else {
            $msg = new msg(404, "删除购物车商品失败");
        }
        return json_encode($msg->getJson());
    }
}