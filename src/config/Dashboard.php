<?php
    use Res as Response;

    class Dashboard{
        private $db;

        public function __construct($db)
        {
            $this->db = $db;
        }

        public function __destruct() {
            $this->db = null;
        }

        public function GetDashboard()
        {
            $response = new Response();
            $data = array();
            $sqlArray = array();

            $sqlArray['skills'] = "SELECT * FROM skills ORDER BY type, name";
            $sqlArray['experience'] = "SELECT id, employer, jobTitle FROM experience";
            $sqlArray['education'] = "SELECT id, school, course FROM education";
            $sqlArray['interests'] = "SELECT * FROM interests";

            try{
                $stmt = $this->db->prepare($sqlArray['skills']);
                $stmt->execute();
                $skills = $stmt->fetchAll(PDO::FETCH_OBJ);
                $data['skills'] = $skills;

                $response->SetCode(200);
                $response->SetJSONData($data);
            } catch(PDOException $e) {
                $response->SetCode(500);
                $response->SetMessage($e->getMessage());
            }

            return $response->GetResponse();
        }
    }