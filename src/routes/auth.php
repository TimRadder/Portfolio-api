<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->post('/api/login', function(Request $request, Response $response) {
    $json = $request->getBody();
    $data = json_decode($json, true);

    $username = $data['username'];
    $password = $data['password'];

    $sql = 'SELECT * FROM users WHERE `username` = :username';

    try {
        // Get DB Object
        $db = new DB();
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':username', $username);

        if($stmt->execute()) {
            $userId = '';
            $userMatch = false;
            $res = array();
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if($row['username'] == $username && password_verify($password, $row['password'])) {
                    $userMatch = true;
                    $userId = $row['userID'];
                    break;
                }
            }
            if($userMatch) {
                // Generate Session ID and update the sessions DB with session ID
                $sessionID = GenerateSessionID(uniqid('', true));
                $expiraryDate = date('Y-m-d', strtotime('+2 days'));

                $sql = 'INSERT INTO session (sessionID, userID, expiraryDate) VALUES (:sessionID, :userID, :expiraryDate)';

                $stmt = $db->prepare($sql);
                $stmt->bindParam(':sessionID', $sessionID);
                $stmt->bindParam(':userID', $userId);
                $stmt->bindParam(':expiraryDate', $expiraryDate);
                if($stmt->execute()) {
                    $token = GenerateUserToken($userId, $sessionID);
                    $res = array(
                        "code" => 200,
                        "jwt" => $token,
                        "message" => 'You were logged in successfully'
                    );
                } else {
                    $res = array(
                        "code" => 500,
                        "message" => 'Error while creating Session'
                    );
                }
            } else {
                $res = array(
                    "code" => 500,
                    "error" => 'No User was found matching that Username and Password'
                );
            }
        } else {
            $res = array(
                "code" => 500,
                "error" => 'There was a problem querying the database'
            );
        }

        $db = null;
        echo json_encode($res);
    } catch(PDOException $exception) {
        $res = array(
            "code" => 500,
            "error" => $exception->getMessage()
        );
        $db = null;
        echo json_encode($res);
    }

});

$app->post('/api/checkAuth', function(Request $request, Response $response){

    $header = $request->getHeader('AUTHORIZATION');

    $token = implode("", $header);
    $token = substr($token, 7);

    if($decodedToken = DecodeToken($token)){
        $userId = $decodedToken['userID'];
        $sessionId = $decodedToken['sessionID'];

        if(UserLoggedIn($userId, $sessionId)) {
            return "true";
        } else {
            return "false";
        } 
    } else {
        return "false";
    }

});