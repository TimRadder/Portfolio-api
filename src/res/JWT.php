<?php
use \Firebase\JWT\JWT as JWT;

const JWTSECRET = 'JDJ5JDEwJG9FWmRlL2cycUE3QkljUUsxTU50WWVhWWJhcFF4Q1hyLkl1M2RibXo5YkZFMWxxM3Q2T1ph';
const JWTHASHALG = 'HS256';

function GenerateUserToken($userID, $sessionID) {
    $token = array(
        'userID' => $userID,
        'sessionID' => $sessionID,
        'iat' => time(),
        'exp' => time() + (2 * 24 * 60 * 60),
        'iss' => 'http://portfolioapi/api/jwt'
    );

    return JWT::encode($token, JWTSECRET, JWTHASHALG);
}

function DecodeToken($token) {
    try {
        $decoded = JWT::decode($token, JWTSECRET, array(JWTHASHALG));
        $decoded = (array)$decoded;
        return $decoded;
    } catch (Exception $e) {
        return false;
    }
}

function GetUserIdFromToken($token) {
    $decoded = JWT::decode($token, JWTSECRET, array(JWTHASHALG));
    $decoded = (array)$decoded;
    return $decoded['userID'];
}

function GetSessionIdFromToken($token) {
    $decoded = JWT::decode($token, JWTSECRET, array(JWTHASHALG));
    $decoded = (array)$decoded;
    return $decoded['sessionID'];
}

function GenerateSessionID($uniqueID) {
    $options = ['cost' => 10 ];
    return password_hash($uniqueID, PASSWORD_BCRYPT, $options);
}

function UserLoggedIn($userID, $sessionID) {
    $sql = 'SELECT * FROM session WHERE sessionID = :sessionID AND userID = :userID';
        $array = array();

        try {
            $db = new DB();
            $db = $db->connect();
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':userID', $userID);
            $stmt->bindParam(':sessionID', $sessionID);

            if($stmt->execute()) {
                $rows = $stmt->fetchAll();
                if(count($rows) == 1) {
                    return true;
                } else {
                    return false;
                }
            }

            return false;
        } catch (PDOException $e) {
            return false;
        }
}