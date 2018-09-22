<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use \Firebase\JWT\JWT as JWT;

$app->get('/api/jwt', function(Request $request, Response $response){

    $token = GenerateUserToken(123456789, GenerateSessionID(uniqid()));

    /*$header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
    $payload = json_encode(['userID' => 123456789, 'userName' => 'AdminTester']);

    $header = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
    $payload = str_replace(['+', '/', '='],['-', '_', ''], base64_encode($payload));

    $signature = hash_hmac('sha256', $header . "." . $payload, 'HelloThere', true);
    $signature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

    $jwt = $header . "." . $payload . "." . $signature;*/

    $res = array(
        "Encoded" => $token,
        "UserID" => GetUserIdFromToken($token)
    );

    echo json_encode($res);
});

