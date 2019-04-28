<?php
use Res as Response;

class Interest {
    // Interest Properties
    private $activity;
    private $type;
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

    // Get all interests from Database
    public function GetInterests($sql){
        $res = new Response();

        try{
            $stmt = $this->db->query($sql);
            $success = $stmt->execute();

            if($success){
                $interests = $stmt->fetchAll(PDO::FETCH_OBJ);
                $res->SetCode(200);
                $res->SetJSONData($interests);
            } else {
                $res->SetCode(500);
                $res->SetMessage('Error loading Interests');
            }
        } catch (PDOException $e) {
            $res->SetCode(500);
            $res->SetMessage($e->getMessage());
        }

        return $res->GetResponse();
    }

    // Add Interest to Database
    public function AddInterest($activity, $type, $sql){
        $res = new Response();

        $this->activity = $activity;
        $this->type = $type;

        try{
            $stmt = $this->db->prepare($sql);

            $success = $stmt->execute([
                ":activity" => $this->activity,
                ":type" => $this->type
            ]);

            if($success){
                $res->SetCode(200);
                $res->SetMessage('Interest Added');
            } else{
                $res->SetCode(500);
                $res->SetMessage('Could not Add new Interest');
            }
        } catch (PDOException $e) {
            $res->SetCode(500);
            $res->SetMessage($e->getMessage());
        }

        return $res->GetResponse();
    }

    // Update Interest in Database
    public function UpdateInterest($interest, $sql){
        $res = new Response();

        try{
            $stmt = $this->db->prepare($sql);

            $success = $stmt->execute([
                ":activity" => $interest['activity'],
                ":type" => $interest['type'],
                ":id" => $interest['id']
            ]);

            if($success)
            {
                $res->SetCode(200);
                $res->SetMessage('Interest Updated');
            } else {
                $res->SetCode(500);
                $res->SetMessage('Could not update Interest');
            }

        } catch(PDOException $e) {
            $res->SetCode(500);
            $res->SetMessage($e->getMessage());
        }

        return $res->GetResponse();
    }

    // Delete Interest from Database
    public function DeleteInterest($id, $sql){
        $res = new Response();

        try{
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id);
            $success = $stmt->execute();

            if($success){
                $res->SetCode(200);
                $res->SetMessage('Interest Successfully Deleted');
            } else {
                $res->SetCode(500);
                $res->SetMessage('Error Deleting Interest');
            }
        } catch(PDOException $e) {
            $res->SetCode(500);
            $res->SetMessage($e->getMessage());
        }

        return $res->GetResponse();
    }
}