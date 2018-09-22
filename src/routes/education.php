<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// Get All Education and rewards
$app->get('/api/education', function(Request $request, Response $response){
    $sql = 'SELECT * FROM education ORDER BY gradDate DESC';

    try{
        // Get DB Object
        $db = new DB();
        // Call connection function
        $db = $db->connect();
        // Create PDO prepared statement
        $stmt = $db->query($sql);
        $schoolArray = array();
        if($stmt->execute()){
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $schoolArray[] = $row;
            }
        }

        $sql = 'SELECT * FROM educationAwards';
        $stmt = $db->query($sql);

        $awardArray = array();
        if($stmt->execute()){
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $awardArray[] = $row;
            }
        }
        $db = null;

        $awardTypes = array();

        foreach($awardArray as $award){
            if(array_key_exists($award['schoolID'], $awardTypes)){
                if(!in_array($award['type'], $awardTypes[$award['schoolID']])){
                    $awardTypes[$award['schoolID']][] = $award['type'];
                }
            }else {
                $awardTypes[$award['schoolID']][] = $award['type'];
            }            
        }

        foreach($awardArray as $award){

            for($i = 0; $i < count($schoolArray); $i++){
                if($schoolArray[$i]['schoolID'] == $award['schoolID']){
                    $schoolArray[$i]['awards'][] = $award;
                    $schoolArray[$i]['awardTypes'] = $awardTypes[$award['schoolID']];
                    break;
                }
            }
        }

        echo json_encode($schoolArray);
    } catch(PDOException $e) {
        echo '{"notice": '.$e->getMessage().'}';
    }
});