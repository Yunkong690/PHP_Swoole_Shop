<?php

include_once '../utils/DbUtils.php';
include_once '../utils/msg.php';
include_once "../utils/redisUtils.php";


ini_set("session.save_handler", "redis");
ini_set("session.save_path", "tcp://127.0.0.1:6379?auth=Sjx1457");

session_start();

class userServer
{

    function Login($username, $password,$autologin,$response)
    {
        $dbutils = new DbUtils();
        $res = $dbutils->my_query('select *  from user where username=? and password=?', [$username, $password]);
        if ($res) {
            $uniqid = md5(uniqid(microtime(true), true));
            $token = md5($username . $password . $uniqid);
            $user['id'] = $res['id'];
            $user['username'] = $res['username'];
            $user['nickname'] = $res['nickname'];
            $user['sex'] = $res['sex'];
            $user['address'] = $res['address'];
            $user['tel'] = $res['tel'];
            $user['name'] = $res['name'];
            $user['money'] = $res['money'];
            $user['token'] = $token;
            $_SESSION['user'] = $user;
            $redis = $GLOBALS['redis'];
            $redis->set($user['id'] . '_token', $token);

            $redis->expireAt($user['id'] . '_token',time() + 7 * 24 * 3600);
            if ($autologin === 'true') {// 如果记住登陆，则记录登录状态，把用户名和token放到cookie里面
                $response->cookie('user', serialize($user), time() + 7 * 24 * 3600, '/');
                $response->cookie('token', $token, time() + 7 * 24 * 3600, '/');
                $response->cookie('user_id', $user['id'], time() + 7 * 24 * 3600, '/');
                $response->cookie('username', $username,time() + 7 * 24 * 3600,'/');

            } else {
                $response->cookie('username', $username,"",'/');
                $response->cookie('user_id', $user['id'], "", '/');
                $response->cookie('user', serialize($user), time() - 1);
                $response->cookie('token', $token, "",'/');

            }
            $msg = new msg(200, "登录成功");
            return json_encode($msg->getJson());
        } else {
            $msg = new msg(403, '用户名或密码错误');
            return json_encode($msg->getJson());
        }
    }

    function checkLogin($user_id, $token,$cookie)
    {
        $redis = $GLOBALS['redis'];
        $redis_token = $redis->get($user_id . '_token');
        if ($redis_token == $token) {
            $_SESSION['isLogin'] = 1;
        }
        if (isset($_SESSION['isLogin'])) {
            $user = unserialize($cookie['user']);
            $username = $user['username'];
            $msg = new msg(200, "已经登录", $username);
            return json_encode($msg->getJson());
        } else {
            $msg = new msg(403, "未登录");
            return json_encode($msg->getJson());
        }
    }

    function getAddress($user_id)
    {
        $dbutils = new DbUtils();
        $res = $dbutils->my_query('select name,tel,address from user where id=?', [$user_id]);
        if($res){
            $msg = new msg(0, "获取收货地址成功", $res);
            return json_encode($msg->getJson());
        }else{
            $msg = new msg(404, "获取收货地址失败");
            return json_encode($msg->getJson());
        }
    }

    function getMoney($user_id){
        $dbutils = new dbUtils();
        $res = $dbutils->my_query('select money from user where id=?', [$user_id]);
        if($res){
            $msg = new msg(0, "获取余额成功", $res);
            return json_encode($msg->getJson());
        }else{
            $msg = new msg(404, "获取余额失败");
            return json_encode($msg->getJson());
        }
    }

    function Logout($user_id,$response){
        $response->cookie('username', '',time() - 1,'/');
        $response->cookie('user_id','', time() - 1,'/');
        $response->cookie('user', '', time() - 1,'/');
        $response->cookie('token', '', time() - 1,'/');
        $redis = $GLOBALS['redis'];
        $redis->del($user_id . '_token');
        Header("Location:   ../../Web/pay/index.html");
    }

    function getAllUser(){
        if(isset($_SESSION['isLogin'])){
            $dbutils = new dbUtils();
            $sql="select id,username,nickname,sex,address,tel,name,money,status  from user ";
            $res=$dbutils->my_query($sql,"",false);
            $msg = new msg(0, "Success",$res,count($res));
            return json_encode($msg->getJson());
        }
    }
}