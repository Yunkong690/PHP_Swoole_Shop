<?php

include '../Server/typeServer.php';

class typeController
{
    function Action($request, $response)
    {
        $type_server = new typeServer();
        if ($request->get['action']) {
            $action = $request->get['action'];
        } else {
            $action = $request->post['action'];
        }
        $result = '';
        switch ($action) {
            case 'getAllType':
                $result = $type_server->getAllType();
                break;
        }
        return $result;
    }
}