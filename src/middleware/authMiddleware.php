<?php
use \Firebase\JWT\JWT;

class AuthMiddleWare
{
    public function __invoke($request, $response, $next)
    {
        $header = $request->getHeader('AUTHORIZATION');

        $token = implode("", $header);
        $token = substr($token, 7);

        if($decodedToken = DecodeToken($token)) {
            $userId = $decodedToken['userID'];
            $sessionId = $decodedToken['sessionID'];

            if(UserLoggedIn($userId, $sessionId)) {
                return $next($request, $response);
            } else {
                $res = array(
                    "code" => 403,
                    "error" => "Access Denied TODO: Remove: Not Logged In"
                );

                return (new Slim\http\Response())
                        ->withStatus(403)
                        ->withJson($res);
            }
        } else {
            $res = array(
                "code" => 403,
                "error" => "Access Denied TODO: Remove: Token"
            );

            return (new Slim\http\Response())
                    ->withStatus(403)
                    ->withJson($res);
        }
    }
}