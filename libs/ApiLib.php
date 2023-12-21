<?php

namespace libs;

class ApiLib
{
    static function WriteErrorResponse($code, $message, $exitWhenDone=true) {
        http_response_code($code);
        $response = array("error"=>array("code"=>$code, "message"=>$message));

        echo stripslashes(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        if ($exitWhenDone) {
            exit;
        }
    }

    static function WriteResponse($data, $exitWhenDone=true) {
        header('Content-Type: application/json');
        $response = array("data"=>$data);
        echo stripslashes(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        if ($exitWhenDone) {
            exit;
        }
    }
}