<?php

include_once '../utils/msg.php';

class uploadServer
{
    function upload($file){

        $allowedExts = array("gif", "jpeg", "jpg", "png");
        $temp = explode(".", $file["file"]["name"]);
        $extension = end($temp);     // 获取文件后缀名
        if ((($file["file"]["type"] == "image/gif")
                || ($file["file"]["type"] == "image/jpeg")
                || ($file["file"]["type"] == "image/jpg")
                || ($file["file"]["type"] == "image/jpeg")
                || ($file["file"]["type"] == "image/x-png")
                || ($file["file"]["type"] == "image/png"))
            && in_array($extension, $allowedExts))
        {
            if ($file["file"]["error"] > 0)
            {
                $msg = new msg(200, "错误：: " . $file["file"]["error"]);
                return json_encode($msg->getJson());
            }
            else
            {
//        return "上传文件名: " . $file["file"]["name"] . "<br>";
//        return "文件类型: " . $file["file"]["type"] . "<br>";
//        return "文件大小: " . ($file["file"]["size"] / 1024) . " kB<br>";
//        return "文件临时存储的位置: " . $file["file"]["tmp_name"] . "<br>";

                // 判断当前目录下的 upload 目录是否存在该文件
                // 如果没有 upload 目录，你需要创建它，upload 目录权限为 777
                if (file_exists("/usr/local/nginx/html/Shop_Swoole/Web/pay/uploads/" . $file["file"]["name"]))
                {
                    $url= "http://10.0.0.100:9501/pay/uploads/" . $file["file"]["name"];
                    $msg = new msg(0, $file["file"]["name"] . " 文件已经存在。 ",$url);
                    return json_encode($msg->getJson());
                }
                else
                {
                    // 如果 upload 目录不存在该文件则将文件上传到 upload 目录下
                    move_uploaded_file($file["file"]["tmp_name"], "/usr/local/nginx/html/Shop_Swoole/Web/pay/uploads/" . $file["file"]["name"]);
                    $url= "http://10.0.0.100:9501/pay/uploads/" . $file["file"]["name"];
                    $msg = new msg(0, "上传成功: " .$file["file"]["name"],$url);
                    return json_encode($msg->getJson());

                }
            }
        }
        else
        {
            $msg = new msg(500, "非法的文件格式");
            return json_encode($msg->getJson());
        }

    }
}