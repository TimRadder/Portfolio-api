<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/api/interests', function(Request $request, Response $response){
    $interests = new Interest();
    $sql = "SELECT * FROM interests ORDER BY type, activity";

    $res = $interests->GetInterests($sql);
    $interests = null;

    echo $res;
});

$app->post('/api/interests/add', function(Request $request, Response $response){
    $interest =new Interest();
    $sql = 'INSERT INTO `interests` (`activity`, `type`) VALUES (:activity, :type)';

    $json = $request->getBody();
    $data = json_decode($json, true);

    $res = $interest->AddInterest($data['activity'], $data['type'], $sql);
    $interest = null;

    echo $res;

})->add(new AuthMiddleWare());

$app->put('/api/interests/update/{id}', function(Request $request, Response $response){
    $interest = new Interest();
    $sql = 'UPDATE interests SET activity = :activity, type = :type WHERE id = :id';
    $json = json_decode($request->getBody(), true);

    $res = $interest->UpdateInterest($json, $sql);
    $interest = null;

    echo $res;
})->add(new AuthMiddleWare());

$app->delete('/api/interests/delete/{id}', function(Request $request, Response $response){
    $interest = new Interest();
    $id = $request->getAttribute('id');
    $sql = 'DELETE FROM interests WHERE id = :id';

    $res = $interest->DeleteInterest($id, $sql);
    $interest = null;

    echo $res;
})->add(new AuthMiddleWare());