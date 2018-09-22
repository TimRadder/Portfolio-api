<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/api/admin/dashboard', function(Request $request, Response $response){
    $db = new DB();
    $db = $db->connect();

    $dashboard = new Dashboard($db);

    $res =  $dashboard->GetDashboard();
    $dashboard = null;

    echo $res;
});