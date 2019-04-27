<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// Get All Experience
$app->get('/api/exps', function(Request $request, Response $response){
    $sql = "SELECT * FROM experience ORDER BY d_startDate DESC";

    try{
        // Get DB Object
        $db = new DB();
        // Call Connect function
        $db = $db->connect();
        // Create PDO prepared Statement
        $stmt = $db->query($sql);
        $experience = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        echo json_encode($experience);
    } catch(PDOException $e) {
        echo '{"error" : {"text" : '. $e->getMessage() .'}}';
    }
});

// Get An Experience
$app->get('/api/exp/{id}', function(Request $request, Response $response){
    $id = $request->getAttribute('id');
    $sql = "SELECT * FROM experience WHERE id = :id";

    try{
        // Get DB Object
        $db = new DB();
        // Call Connect function
        $db = $db->connect();
        // Create PDO prepared Statement
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $experience = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        echo json_encode($experience);
    } catch(PDOException $e) {
        echo '{"error" : {"text" : '. $e->getMessage() .'}}';
    }
})->add(new AuthMiddleWare());

// Add Experience
$app->post('/api/exp/add', function(Request $request, Response $response){
    $json = $request->getBody();
    $data = json_decode($json, true);
    
    $employer = $data['employer'];
    $startDate = date("F Y", strtotime($data['startDate']));
    $endDate = date("F Y", strtotime($data['endDate']));
    $jobTitle = $data['jobTitle'];
    $description = $data['description'];
    $d_startDate = date("Y-m-d", strtotime($startDate));

    $sql = "INSERT INTO experience (employer, startDate, endDate, jobTitle, description, d_startDate) VALUES (
        :employer,
        :startDate,
        :endDate,
        :jobTitle,
        :description,
        :d_startDate
    )";

    try{
        // Get DB Object
        $db = new DB();
        // Call Connect function
        $db = $db->connect();
        // Create PDO prepared Statement
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':employer', $employer);
        $stmt->bindParam(':startDate', $startDate);
        $stmt->bindParam(':endDate', $endDate);
        $stmt->bindParam(':jobTitle', $jobTitle);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':d_startDate', $d_startDate);

        if($stmt->execute()) {
            echo '{"code": 200,"notice" : {"text": "Experience has been added"}}';
        } else {
            echo '{"code": 500,"notice" : {"text": "Experience has not been added"}}';
        }


    } catch(PDOException $e) {
        echo '{"code": 500, error" : {"text" : '. $e->getMessage() .'}}';
    }
})->add(new AuthMiddleWare());

// Update Experience
$app->put('/api/exp/update/{id}', function(Request $request, Response $response){
    $id = $request->getAttribute('id');
    $employer = $request->getParam('employer');
    $startDate = $request->getParam('startDate');
    $endDate = $request->getParam('endDate');
    $jobTitle = $request->getParam('jobTitle');
    $description = $request->getParam('description');

    $sql = "UPDATE experience SET
            employer = :employer,
            startDate = :startDate,
            endDate = :endDate,
            jobTitle = :jobTitle,
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
        $stmt->bindParam(':employer', $employer);
        $stmt->bindParam(':startDate', $startDate);
        $stmt->bindParam(':endDate', $endDate);
        $stmt->bindParam(':jobTitle', $jobTitle);
        $stmt->bindParam(':description', $description);

        $stmt->execute();

        echo '{"code": 200, "message" : "Experience has been Updated"}';
    } catch(PDOException $e) {
        echo '{"code": 500, "error" : {"text" : '. $e->getMessage() .'}}';
    }
})->add(new AuthMiddleWare());

// Delete Experience
$app->delete('/api/exp/delete/{id}', function(Request $request, Response $response){
    $id = $request->getAttribute('id');

    $sql = "DELETE FROM experience WHERE id = :id";

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

        echo '{"code": 200, "notice" : "Experience has been deleted"}';
    } catch(PDOException $e) {
        echo '{"code": 500, "error" : {"text" : '. $e->getMessage() .'}}';
    }
})->add(new AuthMiddleWare());

?>