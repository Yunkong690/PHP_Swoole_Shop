<?php

include_once '../utils/DbUtils.php';
include_once '../utils/msg.php';
include_once "../utils/redisUtils.php";

class goodsServer
{
    function getGoodbyId($id)
    {

        $redis = $GLOBALS['redis'];
        $res = $redis->hgetall("goods_" . $id);
//    $dbutils = new dbUtils();
//    $res = $dbutils->my_query('select *  from goods where id=?', array($id));
        if ($res) {
            $msg = new msg(200, "获取商品信息成功", $res);
            return json_encode($msg->getJson());
        } else {
            $msg = new msg(400, "获取商品信息失败");
            return json_encode($msg->getJson());
        }

    }

    function updateGoodbyId($data, $old_type, $goods)
    {
        $dbutils = new DbUtils();
        $sql = 'update goods set name=?,type=?,num=?,price=?,cover=?,detail=? ,specs=?,type_id=? where id=?';
        $res = $dbutils->my_exec($sql, $data);
        if ($res) {
            $redis = $GLOBALS['redis'];
            $redis->lrem("type_" . $old_type, $goods['id'], 0);//旧分类删除
            $redis->hmset("goods_" . $goods['id'], $goods);
            $redis->lpush("type_" . $goods['type_id'], $goods['id']);
            $msg = new msg(0, "修改商品信息成功");
            return json_encode($msg->getJson());
        } else {
            $msg = new msg(404, "修改商品信息失败");
            return json_encode($msg->getJson());
        }
    }


    function getAllGoods()
    {
        $redis = $GLOBALS['redis'];
        $goodlist = $redis->lrange("all_goods", 0, -1);
        $res = (array)null;
        foreach ($goodlist as $id) {
            $res[] = $redis->hgetall("goods_" . $id);
        }
        if ($res) {
            $msg = new msg(0, "获取商品信息成功", $res, count($res));
        } else {
            $dbutils = new DbUtils();
            $res1 = $dbutils->my_query('select *  from goods', "", false);
            if ($res1) {
                $redis = $GLOBALS['redis'];
                foreach ($res1 as $value) {
                    $redis->hmset("goods_" . $value['id'], $value);
                    $redis->lpush("type_" . $value['type_id'], $value['id']);
                    $redis->lpush("all_goods", $value['id']);
                }
                $msg = new msg(0, "获取商品信息成功", $res1, count($res));
            } else {
                $msg = new msg(404, "获取商品信息失败");
            }
        }
        return json_encode($msg->getJson());
    }

    function addGoods($data, &$goods)
    {
        $redis = $GLOBALS['redis'];
        $dbutils = new DbUtils();
        $sql = 'insert into goods set name=?,type=?,num=?,price=?,cover=?,detail=? ,specs=?,type_id=?';
        $res = $dbutils->my_exec($sql, $data);
        $id = $dbutils->my_last_insert_id();
        $goods['id'] = $id;
        if ($res) {
            $redis->hmset("goods_" . $goods['id'], $goods);
            $redis->lpush("type_" . $goods['type_id'], $goods['id']);
            $redis->lpush("all_goods", $goods['id']);
            $msg = new msg(0, "添加商品成功" . $id, $res);
            return json_encode($msg->getJson());
        } else {
            $msg = new msg(404, "添加商品失败");
            return json_encode($msg->getJson());
        }
    }


    function deleteGoodsById($arr, $type_id)
    {
        $redis = $GLOBALS['redis'];
        $dbutils = new DbUtils();
        $in = str_repeat('?,', count($arr) - 1) . '?';
        $sql = "delete from goods where id in ($in)";
        $res = $dbutils->my_exec($sql, $arr);

        if ($res) {
            $msg = new msg(0, "删除商品成功", $res);
            foreach ($arr as $key => $value) {
                $redis->del("goods_" . $value);
                $redis->lrem('all_goods', $value, 0);
                $redis->lrem("type_" . $type_id[$key], $value, 0);
            }
        } else {
            $msg = new msg(404, "删除商品失败", $type_id);
        }
        return json_encode($msg->getJson());
    }

    function getGoodsByType($type_id)
    {
        $redis = $GLOBALS['redis'];
        $goodlist = $redis->lrange("type_" . $type_id, 0, -1);
        $dbutils = new DbUtils();
        $res = (array)null;
        foreach ($goodlist as $id) {
            $res[] = $redis->hgetall("goods_" . $id);
        }
        if ($res) {
            $msg = new msg(0, "获取商品信息成功", $res, count($res));
        } else {
            $sql = 'select * from goods where type_id =?';
            $res1 = $dbutils->my_query($sql, [$type_id], false);
            if ($res1) {
                $msg = new msg(0, "获取商品信息成功", $res1, count($res));
            }
        }
        return json_encode($msg->getJson());
    }

    function GoodsLimit($limit, $page)
    {
        $dbutils = new DbUtils();
        $redis = $GLOBALS['redis'];
        $goodlist = $redis->lrange("all_goods", 0, -1);
        $res = (array)null;
        foreach ($goodlist as $id) {
            $res[] = $redis->hgetall("goods_" . $id);
        }
        if ($res) {
            $rows=$res;
        } else {
            $dbutils = new DbUtils();
            $res1 = $dbutils->my_query('select *  from goods', "", false);
            if ($res1) {
                $redis = $GLOBALS['redis'];
                foreach ($res1 as $value) {
                    $redis->hmset("goods_" . $value['id'], $value);
                    $redis->lpush("type_" . $value['type_id'], $value['id']);
                    $redis->lpush("all_goods", $value['id']);
                }
               $rows=$res1;
            } else {
                $msg = new msg(404, "获取商品信息失败");
            }
        }
# 按某字段排序，$rows为数据数组
//        $rows = $dbutils->my_query('select *  from goods', "", false);
        $sort_num = array_column($rows, 'type_id');
        array_multisort($sort_num, SORT_DESC, $rows, SORT_DESC);
        $datas = array();
        $datas = $this->showpage($rows, $limit, $page);
        $items = array();
        $msg = new msg(0, "获取商品信息成功", $datas['rows'], $datas['tot']);
        return json_encode($msg->getJson());
    }


    function showpage($rows, $count, $page)
    {
        $tot = count($rows); // 总数据条数

        // $count = $count; # 每页显示条数

        $countpage = ceil($tot / $count); # 计算总共页数

        $start = ($page - 1) * $count; # 计算每页开始位置

        $datas = array_slice($rows, $start, $count); # 计算当前页数据

        # 获取上一页和下一页
        if ($page > 1) {
            $uppage = $page - 1;
        } else {
            $uppage = 1;
        }

        if ($page < $countpage) {
            $nextpage = $page + 1;
        } else {
            $nextpage = $countpage;
        }

        $pages['countpage'] = $countpage;
        $pages['page'] = $page;
        $pages['uppage'] = $uppage;
        $pages['nextpage'] = $nextpage;
        $pages['tot'] = $tot;

        //循环加入序号 , 避免使用$i引起的序号跳位
        $n = 1;
        foreach ($datas as &$data) {
            $data['n'] = $n;
            $n++;
        }

        $pages['rows'] = $datas;

        return $pages;
    }

}