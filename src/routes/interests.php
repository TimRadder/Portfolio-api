<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/api/interests', function(Request $request, Response $response){
    $sql = "SELECT * FROM interests";

    try {
        $db = new DB();
        $db = $db->connect();

        $stmt = $db->query($sql);
        $interests = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        echo json_encode($interests);
    } catch(PDOException $e) {
        echo '{"code": 500, "notice": '.$e->getMessage().'}';
    }
});