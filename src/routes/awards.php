<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// Get An Award
$app->get('/api/education/awards/{id}', function(Request $request, Response $response){
    $id = $request->getAttribute('id');
    $sql = "SELECT * FROM educationawards WHERE id = :id";

    try{
        // Get DB Object
        $db = new DB();
        // Call Connect function
        $db = $db->connect();
        // Create PDO prepared Statement
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_OBJ);

        $db = null;
        echo json_encode($results);
    } catch(PDOException $e) {
        echo '{"error" : {"text" : '. $e->getMessage() .'}}';
    }

})->add(new AuthMiddleWare());

// Add new Award
$app->post('/api/education/awards/add', function(Request $request, Response $response){
    $json = $request->getBody();
    $data = json_decode($json, true);

    $type = $data['award']['type'];
    $description = $data['award']['description'];
    $schoolID = $data['award']['schoolID'];


    $sql = "INSERT INTO educationawards (type, description, schoolID) VALUES (
        :type,
        :description,
        :schoolID
    )";

    try{
        // Get DB Object
        $db = new DB();
        // Call Connect function
        $db = $db->connect();
        // Create PDO prepared Statement
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':schoolID', $schoolID);

        if($stmt->execute()) {
            echo '{"code": 200,"message" : "Award has been added"}';
        } else {
            echo '{"code": 500,"notice" : {"text": "Experience has not been added"}}';
        }


    } catch(PDOException $e) {
        echo '{"code": 500, error" : {"text" : '. $e->getMessage() .'}}';
    }
})->add(new AuthMiddleWare());

// Update Award
$app->put('/api/education/awards/update/{id}', function(Request $request, Response $response){
    $id = $request->getAttribute('id');
    $type = $request->getParam('type');
    $description = $request->getParam('description');

    $sql = "UPDATE educationawards SET
            type = :type,
            description = :description
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
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':description', $description);

        $stmt->execute();

        echo '{"code": 200, "message" : "Award has been Updated"}';
    } catch(PDOException $e) {
        echo '{"code": 500, "error" : {"text" : '. $e->getMessage() .'}}';
    }
})->add(new AuthMiddleWare());

// Delete Award
$app->delete('/api/education/awards/delete/{id}', function(Request $request, Response $response){
    $id = $request->getAttribute('id');

    $sql = "DELETE FROM educationawards WHERE id = :id";

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

        echo '{"code": 200, "message" : "Award has been deleted"}';
    } catch(PDOException $e) {
        echo '{"code": 500, "error" : {"text" : '. $e->getMessage() .'}}';
    }
})->add(new AuthMiddleWare());
?>