<?php

include_once '../utils/DbUtils.php';
include_once '../utils/msg.php';
include_once "../utils/redisUtils.php";;


class orderServer
{
    function makeOrderId()
    {
        $str = date('Ymd') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
        $msg = new msg(0, "订单号生成成功", $str);
        return json_encode($msg->getJson());
    }

    function makeOrder($data, $Item)
    {
        $redis = $GLOBALS['redis'];
        $dbutils = new dbUtils();
        $sql = 'insert into orders set order_id=?,goodItem=?,bill=?,num=?,buyername=?,buyeraddress=?, buyertel=?,user_id=?';
        $res = $dbutils->my_exec($sql, $data);
        if ($res) {
            $msg = new msg(0, "提交成功", $res);
            $redis->setex($data[0], 5 * 60, $data[0]);
            $in = str_repeat('?,', count($Item) - 1) . '?';
            $sql = "update car set status=1 where id in ($in)";
            $res1 = $dbutils->my_exec($sql, $Item);
            return json_encode($msg->getJson());
        } else {
            $msg = new msg(500, "提交失败", $data);
            return json_encode($msg->getJson());
        }

    }


    function setGoodsNum($arr, $user_id)
    {
        $redis = $GLOBALS['redis'];
        $in = str_repeat('?,', count($arr) - 1) . '?';
        $sql = "select good_id, buynum,price  from car where id in ($in)";
        $dbutils = new dbUtils();
        $res = $dbutils->my_query($sql, $arr, false);

        $dbutils->pdo->setAttribute(PDO::ATTR_AUTOCOMMIT, false);

        try {
            $flag = false;
            $dbutils->pdo->beginTransaction();
            foreach ($res as $value) {
                $sql1 = "select num  from goods where id =? for update ";
                $stmt = $dbutils->pdo->prepare($sql1);
                $stmt->execute([$value['good_id']]);
                $num = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($num > $value['buynum']) {
                    $sql = "update goods set num=num-? where id =?";
                    $stmt1 = $dbutils->pdo->prepare($sql);
                    $stmt1->execute([$value['buynum'], $value['good_id']]);
                    $rowCount = $stmt1->rowCount();
                    if ($rowCount) {
                        $sql = "select money from user where id=?";
                        $res = $dbutils->my_query($sql, [$user_id]);
                        $money = $res['money'];
                        if ((float)$money > (float)$value['buynum'] * (float)$value['price']) {
                            $bill = (float)$value['buynum'] * (float)$value['price'];
                            $sql2 = 'update user set money=money-? where id=?';
                            $stmt2 = $dbutils->pdo->prepare($sql2);
                            $stmt2->execute([$bill, $user_id]);
                            $rowCount1 = $stmt2->rowCount();
                            if ($rowCount1) {
                                $flag = true;
                                $redis->hIncrBy("goods_" . $value['good_id'], 'num', 0 - $value['buynum']);
                            } else {
                                $flag = false;
                            }
                        }
                    } else {
                        $flag = false;
                    }
                }
            }
            if ($flag) {
                $dbutils->pdo->commit();
                return true;
            }
        } catch (PDOException $e) {
            $dbutils->pdo->rollback(); // 执行失败，事务回滚
            return false;
        }
    }

    function getOrderGoods($order_id)
    {
        $dbutils = new dbUtils();
        $sql1 = 'select goodItem from orders where order_id=?';
        $res1 = $dbutils->my_query($sql1, [$order_id]);
        $arr = unserialize($res1['goodItem']);
        $in = str_repeat('?,', count($arr) - 1) . '?';
        $sql = "select *  from car where id in ($in)";
        $res = $dbutils->my_query($sql, $arr, false);
        $msg = new msg(0, "获取订单商品", $res, count($res));
        return json_encode($msg->getJson());
    }


    function payOrder($user_id, $order_id,  $Item,$bill)
    {
        $redis = $GLOBALS['redis'];
        $dbutils = new dbUtils();
        $sql = "select money from user where id=?";
        $res = $dbutils->my_query($sql, [$user_id]);
        $money = $res['money'];
        if($money<$bill){
            $msg = new msg(500, "余额不足");
            return json_encode($msg->getJson());
        }
        $flag = $this->setGoodsNum($Item, $user_id);
        if ($flag) {
            $sql1 = 'update orders set status=1 where order_id=?';
            $res1 = $dbutils->my_exec($sql1, [$order_id]);
            if ($res1) {
                $msg = new msg(0, "下单成功");
                $redis->del($order_id);
                return json_encode($msg->getJson());
            } else {
                $msg = new msg(500, "下单失败");
                return json_encode($msg->getJson());
            }
        } else {
            $msg = new msg(400, "下单失败");
            return json_encode($msg->getJson());
        }
    }


    function getAllOrderById($id)
    {
        $dbutils = new dbUtils();
        $sql = 'select * from orders where user_id=? ';
        $res = $dbutils->my_query($sql, [$id], false);
        if ($res) {
            $msg = new msg(0, "获取订单成功", $res, count($res));
        } else {
            $msg = new msg(400, "没有订单");
        }
        return json_encode($msg->getJson());
    }
}