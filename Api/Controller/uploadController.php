<?php

include '../Server/uploadServer.php';

class uploadController
{
    function Action($request, $response)
    {
        $upload_server = new uploadServer();
        $file = $request->files;
        return $upload_server->upload($file);
    }
}