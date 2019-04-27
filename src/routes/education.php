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

// Get List of Schools and School ID
$app->get('/api/education/schools', function(Request $request, Response $response){

    $sql = "SELECT school, schoolID FROM education";

    try{
        // Get DB Object
        $db = new DB();
        // Call Connect function
        $db = $db->connect();
        // Create PDO prepared Statement
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $schools = $stmt->fetchAll(PDO::FETCH_OBJ);

        $db = null;
        echo json_encode($schools);
    } catch(PDOException $e) {
        echo '{"error" : {"text" : '. $e->getMessage() .'}}';
    }
});

// Get An Education
$app->get('/api/education/{id}', function(Request $request, Response $response){
    $id = $request->getAttribute('id');
    $sql = "SELECT * FROM education WHERE id = :id";

    try{
        // Get DB Object
        $db = new DB();
        // Call Connect function
        $db = $db->connect();
        // Create PDO prepared Statement
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $education = $stmt->fetchAll(PDO::FETCH_OBJ);
        $schoolID = $education[0]->schoolID;

        // Get Awards
        $sql = "SELECT * FROM educationawards WHERE schoolID = :schoolID";
        $stmt =$db->prepare($sql);
        $stmt->bindParam(':schoolID', $schoolID);
        $stmt->execute();
        $awards = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $results = array($education, $awards);

        $db = null;
        echo json_encode($results);
    } catch(PDOException $e) {
        echo '{"error" : {"text" : '. $e->getMessage() .'}}';
    }
});

// Update Education
$app->put('/api/education/update/{id}', function(Request $request, Response $response){
    $id = $request->getAttribute('id');
    $school = $request->getParam('school');
    $course = $request->getParam('course');
    $gradeDate = $request->getParam('gradDate');

    $sql = "UPDATE education SET
            school = :school,
            course = :course,
            gradDate = :gradDate
            WHERE id = :id
    ";

    try{
        // Get DB Object
        $db = new DB();
        // Call Connect function
        $db = $db->connect();
        // Create PDO prepared Statement
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':school', $employer);
        $stmt->bindParam(':course', $startDate);
        $stmt->bindParam(':gradDate', $endDate);

        $stmt->execute();

        echo '{"code": 200, "message" : "Education has been Updated"}';
    } catch(PDOException $e) {
        echo '{"code": 500, "error" : {"text" : '. $e->getMessage() .'}}';
    }
})->add(new AuthMiddleWare());

// Delete Education
$app->delete('/api/education/delete/{id}', function(Request $request, Response $response){
    $id = $request->getAttribute('id');

    $sql = "DELETE FROM education WHERE id = :id";

    try{
        // Get DB Object
        $db = new DB();
        // Call Connect function
        $db = $db->connect();
        // Create PDO prepared Statement
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $db = null;

        echo '{"code": 200, "notice" : "Education has been deleted"}';
    } catch(PDOException $e) {
        echo '{"code": 500, "error" : {"text" : '. $e->getMessage() .'}}';
    }
})->add(new AuthMiddleWare());
?>