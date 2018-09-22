<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// Get All Skills
$app->get('/api/skills', function(Request $request, Response $response){
    // Create DB Object and connect to DB
    $db = new DB();
    $db = $db->connect();
    // Create new Skill Object and pass in the DB Object
    $skill = new Skill($db);
    $db = null;

    $sql = "SELECT * FROM skills";

    $res = $skill->GetSkills($sql);
    $skill = null;

    echo $res;
});

// Get Single Skill
$app->get('/api/skill/{id}', function(Request $request, Response $response){
    $id = $request->getAttribute('id');
    // Create DB Object and connect to DB
    $db = new DB();
    $db = $db->connect();
    // Create new Skill Object and pass in the DB Object
    $skill = new Skill($db);
    $db = null;

    $sql = "SELECT * FROM skills WHERE id = :id";

    $res = $skill->GetSkill($id, $sql);
    $skill = null;

    echo $res;
});

//Add Skill
$app->post('/api/skill/add', function(Request $request, Response $response){
    // Create new Skill Object and pass in the DB Object
    $skill = new Skill();

    $json = $request->getBody();
    $data = json_decode($json, true);

    $name = $data['skill']['name'];
    $type = $data['skill']['type'];

    $sql = 'INSERT INTO `skills` (`name`, `type`) VALUES (:name, :type)';

    $res = $skill->AddSkill($name, $type, $sql);
    $skill = null;

    echo $res;
    
})->add(new AuthMiddleWare());

// Update Skill
$app->put('/api/skill/update/{id}', function(Request $request, Response $response){
    $id = $request->getAttribute('id');

    $json = $request->getBody();
    $data = json_decode($json, true);

    $sql = "UPDATE skills SET 
            name = :name, 
            type = :type 
            WHERE id = :id";

    // Create new Skill Object and pass in the DB Object
    $skill = new Skill();

    $res = $skill->UpdateSkill($id, $data, $sql);
    $skill = null;

    echo $res;

    /* $name = $data['skill']['name'];
    $type = $data['skill']['type'];

    try{
        // Get DB Object
        $db = new DB();
        // Call Connect function
        $db = $db->connect();
        // Create PDO prepared Statement
        $stmt = $db->prepare($sql);

        $stmt->execute([
            ":id" => $id,
            ":skill" => $skill,
            ":type" => $type
        ]);

        echo '{"code": 200, "message" : "Skill has been updated"}';
    } catch(PDOException $e) {
        echo '{"code": 500, "error" : text" : '. $e->getMessage() .'}';
    } */
});

// Delete Skill
$app->delete('/api/skill/delete/{id}', function(Request $request, Response $response){
    // Create new Skill Object and pass in the DB Object
    $skill = new Skill();
    
    $id = $request->getAttribute('id');

    $sql = "DELETE FROM skills WHERE id = :id";

    $res = $skill->DeleteSkill($id, $sql);
    $skill = null;

    echo $res;
});
?>