<?php

include_once '../utils/DbUtils.php';
include_once '../utils/msg.php';
include_once "../utils/redisUtils.php";

class adminServer
{
    function Login($username, $password, $autologin, $response)
    {
        $dbutils = new dbUtils();
        $res = $dbutils->my_query('select *  from admin where username=? and password=?', array($username, $password));
        if ($res) {//登录信息返回
            $admin['id'] = $res['id'];
            $admin['username'] = $res['username'];
            $admin['name'] = $res['name'];
            $admin['tel'] = $res['tel'];
            $admin['note'] = $res['note'];
            $_SESSION['admin'] = $admin;
            $_SESSION['autologin'] = $autologin;
            if ($autologin == true) {// 如果记住登陆，则记录登录状态，把用户名和加密的密码放到cookie里面
                $response->cookie("admin", serialize($admin), time() + 7 * 24 * 3600, '/');
                $response->cookie("code", md5($admin), time() + 7 * 24 * 3600, '/');
            } else {
                $response->cookie("admin", "", time() - 1, '', '/');
                $response->cookie("code", "", time() - 1, '/', '/');
            }
            $msg = new msg(200, "登录成功");
            return json_encode($msg->getJson());

        } else {
            $msg = new msg(403, "用户名或密码错误");
            return json_encode($msg->getJson());
        }
    }

    function checkLogin($cookie)
    {

        if (isset($cookie['admin'])) {
            $_SESSION['isLogin'] = 1;
        }
        if (isset($_SESSION['isLogin'])) {
            $admin = unserialize($cookie['admin']);
            $username = $admin['username'];
            $msg = new msg(200, "已经登录", $username);
            return json_encode($msg->getJson());
        } else {
            $msg = new msg(403, "未登录");
            return json_encode($msg->getJson());
        }
    }


    function logout($response)
    {
        unset ($_SESSION ['admin']);
        unset($_SESSION['isLogin']);
        if (!empty ($_COOKIE ['admin']) || !empty ($_COOKIE ['code'])) {
            $response->cookie("admin", null, time() - 1);
            $response->cookie("code", null, time() - 1);
        }
        $msg = new msg(200, "退出成功");
        return json_encode($msg->getJson());
    }

    function getAdmin_Info($cookie)
    {
        if (isset($_SESSION['isLogin'])) {
            $admin = unserialize($cookie['admin']);
            $msg = new msg(200, "Success", $admin);
            return json_encode($msg->getJson());
        }
    }

    function updateAdmin_Info($request, $response)
    {
        $cookie = $request->cookie;
        $dbutils = new dbUtils();
        $admin = unserialize($cookie['admin']);
        $id = $admin['id'];
        $username = $request->post['username'];
        $name = $request->post['name'];
        $tel = $request->post['tel'];
        $note = $request->post['note'];
        $res = $dbutils->my_exec('update admin set username=? , name =? , tel =? , note=? where id=?', array($username, $name, $tel, $note, $id));
        if ($res) {
            //更新成功
            $msg = new msg(200, "保存成功");

            //更新session数据
            $admin['id'] = $id;
            $admin['username'] = $username;
            $admin['name'] = $name;
            $admin['tel'] = $tel;
            $admin['note'] = $note;
            $_SESSION['admin'] = $admin;
            if ($_SESSION['autologin'] == true) {
                $response->cookie("admin", serialize($admin), time() + 7 * 24 * 3600);
                $response->cookie("code", md5($admin), time() + 7 * 24 * 3600);
            }
            return json_encode($msg->getJson());
        } else {
            $msg = new msg(500, "信息有误或未修改，保存失败");
            return json_encode($msg->getJson());
        }
    }

}