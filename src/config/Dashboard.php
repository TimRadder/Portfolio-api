<?php
    class Dashboard{
        private $db;
        private $response;

        public function __construct($db)
        {
            $this->db = $db;
            $this->response = new Res();
        }

        public function __destruct() {
            $this->db = null;
            $this->response = null;
        }

        public function GetDashboard()
        {
            $data = array();
            $sqlArray = array();

            $sqlArray['skills'] = "SELECT * FROM skills ORDER BY type, name";
            $sqlArray['experience'] = "SELECT id, employer, jobTitle FROM experience ORDER BY d_startDate DESC ";
            $sqlArray['education'] = "SELECT id, school, course FROM education";
            $sqlArray['interests'] = "SELECT * FROM interests";

            try{
                // Get Skills from DB
                $stmt = $this->db->prepare($sqlArray['skills']);
                $stmt->execute();
                $skills = $stmt->fetchAll(PDO::FETCH_OBJ);
                $data['skills'] = $skills;

                // Get Experiences from DB
                $stmt = $this->db->prepare($sqlArray['experience']);
                $stmt->execute();
                $skills = $stmt->fetchAll(PDO::FETCH_OBJ);
                $data['experience'] = $skills;

                $this->response->SetCode(200);
                $this->response->SetJSONData($data);
            } catch(PDOException $e) {
                $this->response->SetCode(500);
                $this->response->SetMessage($e->getMessage());
            }

            return $this->response->GetResponse();
        }
    }