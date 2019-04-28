<?php
    use Res as Response;

    class Skill{
        // Skill Properties
        private $name;
        private $type;
        private $db;

        public function __construct() 
        {
            $this->db = new DB();
            $this->db = $this->db->connect();
        }

        public function __destruct() {
            $this->db = null;
        }

        // Get ALL Skills from Database
        public function GetSkills($sql)
        {
            $response = new Response();

            try{
                $stmt = $this->db->query($sql);
                $success = $stmt->execute();
                if($success)
                {
                    $skills = $stmt->fetchAll(PDO::FETCH_OBJ);
                    $response->SetCode(200);
                    $response->SetJSONData($skills);
                } else {
                    $response->SetCode(500);
                    $response->SetMessage('Error Loading Skills');
                }
                
            } catch(PDOException $e) {
                $response->SetCode(500);
                $response->SetMessage($e->getMessage());
            }

            return $response->GetResponse();
        }

        // Get ONE Skills from Database
        public function GetSkill($id, $sql)
        {
            $response = new Response();

            try{
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':id', $id);
                $success = $stmt->execute();
                if($success)
                {
                    $skill = $stmt->fetchAll(PDO::FETCH_OBJ);
                    $response->SetCode(200);
                    $response->SetJSONData($skill);
                } else {
                    $response->SetCode(500);
                    $response->SetMessage('Error Loading Skill');
                }
            } catch(PDOException $e) {
                $response->SetCode(500);
                $response->SetMessage($e->getMessage());
            }

            return $response->GetResponse();
        }

        // Add a Skill to the Database
        public function AddSkill($name, $type, $sql) 
        {
            $response = new Response();

            $this->name = $name;
            $this->type = $type;

            try{
                $stmt = $this->db->prepare($sql);
        
                $success = $stmt->execute([
                    ":name" => $this->name,
                    ":type" => $this->type
                ]);

                if($success){
                    $response->SetCode(200);
                    $response->SetMessage('Skill Added');
                } else{
                    $response->SetCode(500);
                    $response->SetMessage('Could not Add new Skill');
                }
        
            } catch(PDOException $e){
                $response->SetCode(500);
                $response->SetMessage($e->getMessage());
            }

            return $response->GetResponse();
        }

        public function UpdateSkill($id, $skill, $sql) {
            $response = new Response();

            try{
                $stmt = $this->db->prepare($sql);

                $success = $stmt->execute([
                    ":name" => $skill['name'],
                    ":type" => $skill['type'],
                    ":id" => $id
                ]);

                if($success)
                {
                    $response->SetCode(200);
                    $response->SetMessage('Skill Updated'); 
                } else {
                    $response->SetCode(500);
                    $response->SetMessage('Could not update Skill');
                }

            } catch(PDOException $e) {
                $response->SetCode(500);
                $response->SetMessage($e->getMessage());
            }

            return $response->GetResponse();
        }

        public function DeleteSkill($id, $sql) 
        {
            $response = new Response();

            try{
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':id', $id);
                $success = $stmt->execute();

                if($success){
                    $response->SetCode(200);
                    $response->SetMessage('Skill Successfully Deleted');
                } else {
                    $response->SetCode(500);
                    $response->SetMessage('Error Deleting Skill');
                }
            } catch(PDOException $e) {
                $response->SetCode(500);
                $response->SetMessage($e->getMessage());
            }

            return $response->GetResponse();
        }
    }