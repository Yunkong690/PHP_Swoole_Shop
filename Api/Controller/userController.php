<?php

include '../Server/userServer.php';

class userController
{
    function Action($request,$response)
    {
        $user_server=new userServer();
        if($request->get['action']){
            $action =  $request->get['action'];
        }else{
            $action =  $request->post['action'];
        }
        $result = '';
        switch ($action) {
            case 'Login' :
                $username =  $request->post['username'];
                $password =  $request->post['password'];
                $password=md5($password);
                $autologin =  $request->post['auto_Login'];
                $result=$user_server->Login($username,$password,$autologin,$response);
                break;
            case 'Logout' :
                $user_id =  $request->get['user_id'];
               $user_server->Logout($user_id,$response);
                break;
            case 'getAddress' :
                $user_id =  $request->get['user_id'];
                $result=$user_server->getAddress($user_id);
                break;
            case 'getMoney':
                $user_id =  $request->get['user_id'];
                $result=$user_server->getMoney($user_id);
                break;
            case 'checkLogin':
                $user_id =  $request->get['user_id'];
                $token =  $request->get['token'];
                $cookie=$request->cookie;
                $result=$user_server->checkLogin($user_id, $token,$cookie);
                break;
            case 'getAllUser' :
                $result=$user_server->getAllUser();
                break;
        }
        return $result;
    }

}