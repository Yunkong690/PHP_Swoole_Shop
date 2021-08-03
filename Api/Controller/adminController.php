<?php

include '../Server/adminServer.php';


class adminController
{
    function Action($request, $response)
    {
        $admin_server = new adminServer();
        $cookie = $request->cookie;
        if ($request->get['action']) {
            $action = $request->get['action'];
        } else {
            $action = $request->post['action'];
        }
        $result = '';
        switch ($action) {
            case 'Login':
                $username = $request->post['username'];
                $password = $request->post['password'];
                $password = md5($password);
                $autologin = $request->post['auto_Login'];

                $result = $admin_server->Login($username, $password, $autologin, $response);
                break;
            case 'checkLogin' :
                $result = $admin_server->checkLogin($cookie);
                break;
            case 'logout':
                $result = $admin_server->logout($response);
                break;
            case 'getAdmin_Info' :
                $result = $admin_server->getAdmin_Info($cookie);
                break;
            case 'updateAdmin_Info':
                $result = $admin_server->updateAdmin_Info($request, $response);
                break;
        }
        return $result;
    }
}
