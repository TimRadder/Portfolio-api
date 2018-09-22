<?php

class Res{
    // Properties
    private $code = 500;
    private $message = '';
    private $jsonData = array();
    private $jwt = '';
    private $auth = false;

    public function SetCode($code) 
    {
        $this->code = $code;
    }

    public function SetMessage($message) 
    {
        $this->message = $message;
    }

    public function SetJSONData($jsonData) 
    {
        $this->jsonData = $jsonData;
    }

    public function SetJWT($jwt) 
    {
        $this->jwt = $jwt;
    }

    public function SetAuth($auth) 
    {
        $this->auth = $auth;
    }

    public function GetResponse()
    {
        $arr = array();
        
        $arr['code'] = $this->code;
        $arr['message'] = $this->message;
        $arr['jsonData'] = $this->jsonData;
        $arr['jwt'] = $this->jwt;
        $arr['auth'] = $this->auth;

        return json_encode($arr);
    }
}