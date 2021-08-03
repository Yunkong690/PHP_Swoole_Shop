<?php

include_once '../utils/DbUtils.php';
include_once '../utils/msg.php';
include_once "../utils/redisUtils.php";;

class typeServer
{
    function getAllType(){
        $dbutils = new DbUtils();
        $res = $dbutils->my_query('select *  from type', "", false);
        if ($res) {
            $msg = new msg(0, "获取商品类别", $res, count($res));
        } else {
            $msg = new msg(404, "获取商品类别");
        }
        return json_encode($msg->getJson());
    }
}