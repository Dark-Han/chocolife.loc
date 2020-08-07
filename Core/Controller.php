<?php

namespace Core;

class Controller
{

    protected function response($data = [])
    {
        header("HTTP/1.1 200 OK");
        header("Content-type: application/json; charset=utf-8");
        $res = ['success' => true];
        if (!empty($data)) {
            $res['data'] = $data;
        } else {
            $res['msg'] = 'Нет данных !';
        }
        echo json_encode($res);
    }
}
