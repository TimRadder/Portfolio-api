<?php
    use Res;

    class Experience {
        // Experience Properties
        private $db;

        public function __construct()
        {
            $this->db = new DB();
            $this->db = $this->db->connect();
        }

        public function __destruct()
        {
            $this->db = null;
        }

        // Get All The Experiences
        public function GetExperiences($sql) {
            $res = new Res();

            try{
                $stmt = $this->db->query($sql);
                $success = $stmt->execute();

                if($success) {
                    $experiences = $stmt->fetchAll(PDO::FETCH_OBJ);
                    $res->SetCode(200);
                    $res->SetJSONData($experiences);
                } else {
                    $res->SetCode(500);
                    $res->SetMessage("Error loading Experiences");
                }
            }catch(PDOException $e) {
                $res->SetCode(500);
                $res->SetMessage($e->getMessage());
            }

            return $res->GetResponse();
        }

        // Get Single Experience
        public function GetExperience($id, $sql) {
            $res = new Res();

            try{
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':id', $id);
                $success = $stmt->execute();

                if($success){
                    $experience = $stmt->fetchAll(PDO::FETCH_OBJ);
                    $res->SetCode(200);
                    $res->SetJSONData($experience);
                } else{
                    $res->SetCode(500);
                    $res->SetMessage("Failed to load Experience with id of " + $id);
                }
            } catch(PDOException $e){
                $res->SetCode(500);
                $res->SetMessage($e->getMessage());
            }

            return $res->GetResponse();
        }

        // Add new experience to the Portfolio
        public function AddExperience($exp, $sql) {
            $res = new Res();

            try{
                $startDate = date("F Y", strtotime($exp['startDate']));
                $endDate = date("F Y", strtotime($exp['endDate']));
                $d_startDate = date("Y-m-d", strtotime($startDate));

                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':employer', $exp['employer']);
                $stmt->bindParam(':startDate', $startDate);
                $stmt->bindParam(':endDate', $endDate);
                $stmt->bindParam(':jobTitle', $exp['jobTitle']);
                $stmt->bindParam(':description', $exp['description']);
                $stmt->bindParam(':d_startDate', $d_startDate);

                $success = $stmt->execute();
                if($success){
                    $res->SetCode(200);
                    $res->SetMessage("Experience Added Successfully");
                } else {
                    $res->SetCode(500);
                    $res->SetMessage("Error Adding Experience");
                }
            } catch (PDOException $e){
                $res->SetCode(500);
                $res->SetMessage($e->getMessage());
            }

            return $res->GetResponse();
        }

        // Update specific Experience
        public function UpdateExperience($id, $exp, $sql) {
            $res = new Res();

            try{
                $stmt = $this->db->prepare($sql);

                $startDate = date("F Y", strtotime($exp['startDate']));
                $endDate = date("F Y", strtotime($exp['endDate']));
                $d_startDate = date("Y-m-d", strtotime($startDate));

                $success = $stmt->execute([
                    ":employer" => $exp['employer'],
                    ":startDate" => $startDate,
                    ":endDate" => $endDate,
                    ":jobTitle" => $exp['jobTitle'],
                    ":description" => $exp['description'],
                    ":d_startDate" => $d_startDate
                ]);

                if($success){
                    $res->SetCode(200);
                    $res->SetMessage("Experience has been updated Successfully");
                } else{
                    $res->SetCode(500);
                    $res->SetMessage("Error occured while updating Message");
                }

            } catch (PDOException $e){
                $res->SetCode(500);
                $res->SetMessage($e->getMessage());
            }

            return $res->GetResponse();
        }

        public function DeleteExperience($id, $sql) {
            $res = new Res();

            try{
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':id', $id);
                $success = $stmt->execute();

                if($success){
                    $res->SetCode(200);
                    $res->SetMessage('Experience Successfully Deleted');
                } else {
                    $res->SetCode(500);
                    $res->SetMessage('Error Deleting Experience');
                }
            } catch(PDOException $e) {
                $res->SetCode(500);
                $res->SetMessage($e->getMessage());
            }

            return $res->GetResponse();
        }
    }