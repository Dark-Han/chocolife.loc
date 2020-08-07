<?php

namespace Core;

class ExceptionHandler
{

    public function __construct()
    {
        if (DEBUG) {
            $this->turnOnErr();
        } else {
            $this->turnOffErr();
        }
        set_exception_handler([$this, 'handler']);
    }

    private function turnOnErr()
    {
        ini_set('error_reporting', E_ALL);
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
    }

    private function turnOffErr()
    {
        ini_set('display_errors', 0);
        ini_set('display_startup_errors', 0);
        error_reporting(0);
    }

    public function handler($e)
    {
        header("Content-type: application/json; charset=utf-8");
        $response = ['success' => false, 'msg' => $e->getMessage()];
        echo json_encode($response);
    }
}
