<?php

class ApiLib
{
    static function successResponse($data): array
    {
        return array("data"=>$data);
    }
    static function errorResponse($code, $message): array
    {
        return array("error"=>array("code"=>$code, "message"=>$message));
    }
}