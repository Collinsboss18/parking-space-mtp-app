<?php

/**
 * @file This file that handles request to the database for parking
 * @author COLLINS <abadaikecollins@gmail.com> <11/09/2020>
 *  Last Modified: Collins <abadaikecollins@gmail.com> <11/09/2020>
 */

if (isset($databasePath)) require_once ($databasePath);
if (isset($encryptionPath)) require_once ($encryptionPath);

// require_once('./Database.class.php');
// require_once('./Encryption.class.php');

class Location {
    protected $db;

    /** Construct __construct */
    public function __construct() {
		$this->db = new Database();
	}
    
    /**
   * This function gets all parks
   * @param $statusCode 
   * @return Array
   */
    public function getLocations($statusCode = 200){
        try{
            return $this->db->query('SELECT * FROM `location`')->fetchAll();
        }catch (Exception $e) {
            // throw new Exception($e->errorMessage());
            return $e;
        }
    }

    /**
   * This function gets a park by id
   * @param $id Id of the park
   * @param $statusCode 
   * @return Array
   */
    public function getLocationById($id, $statusCode = 200){
        try {
            $park = $this->db->query('SELECT `name` FROM `location` WHERE id = ?', array($id))->fetchArray();
            return $park['name'];
        } catch (Exception $e) {
            // throw new Exception($e->errorMessage());
            return $e;
        }
    }
   
    /**
   * This function gets a park by id
   * @param $id Id of the park
   * @param $statusCode 
   * @return Array
   */
    public function getAllClientPark($id, $statusCode = 200){
        try {
            $tickets = $this->db->query('SELECT `parking_id`, `tickets`, `status` FROM `ticket` WHERE `client_id` = ?', array($id))->fetchAll();
            $parks = array();
            foreach ($tickets as $ticket){
                $park =  $this->db->query('SELECT * FROM `parking` WHERE `id` = ?', array($ticket['parking_id']))->fetchAll();
                $res = array();
                $res[] = $park;
                $res[] = $ticket['tickets'];
                $res[] = $ticket['status'];
                $parks[] = $res;
            }
            if(empty($parks)) return 'Book a park';
            return $parks;
        } catch (Exception $e) {
            // throw new Exception($e->errorMessage());
            return $e;
        }
    }

   /**
   * This function updates a park
   * @param $id Id of the park
   * @param $location
   * @param $price
   * @param $available
   * @param $availableTicket
   * @param $alwaysAvailable
   * @param $timeAvailable
   * @return Array
   */
    public function updatePark($id, $location, $price, $available, $availableTicket, $alwaysAvailable, $timeAvailable, $statusCode = 200){
        if (!$location || !$timeAvailable || $price || $availableTicket) return"Please fill required data";
        try{
            $this->db->query("UPDATE `parking` SET `location`= ?, `price`=?, `available`=?, `available_ticket`=? `always_available`=?, `time_available`=? WHERE `id` = $id", array($location, $price, $available, $availableTicket, $alwaysAvailable, $timeAvailable));
            // return $this->getParkById($id);
        }catch (Exception $e) {
            // throw new Exception($e->errorMessage());
            return $e;
        }
    }

   /**
   * This function updates a park
   * @param $id Id of the park
   * @param $available
   * @return Array
   */
    public function updateAvailability($id, $available, $statusCode = 200){
        try{
            $this->db->query("UPDATE `parking` SET `available` = ? WHERE `parking`.`id` = $id", array($available));
            // return $this->getParkById($id);
        }catch (Exception $e) {
            // throw new Exception($e->errorMessage());
            return $e;
        }
    }
   
    /**
   * This function to create park
   * @param $id Id of the park
   * @param $location
   * @param $availableTicket
   * @param $alwaysAvailable
   * @param $timeAvailable
   * @return Array
   */
    public function createPark($location, $price, $availableTicket, $alwaysAvailable, $timeAvailable, $statusCode = 201){
        if (!$location || !$timeAvailable || $price || $availableTicket) return "Please fill required data";
        try{
            $cPark = $this->db->query('INSERT INTO `parking` (`location`,`price`,`available_ticket`,`always_available`,`time_available`) VALUES (?,?,?,?,?)', array($location, $price, $availableTicket, $alwaysAvailable, $timeAvailable));
            $insertedId = $cPark->lastInsertID();
            // return $this->getParkById($insertedId);
        }catch (Exception $e) {
            // throw new Exception($e->errorMessage());
            return $e;
        }
    }
   
    /**
   * This function to delete a park
   * @param $id Id of the park that is to be deleted
   * @return String
   */
    public function deletePark($id, $statusCode = 200){
        if (!$id) return "Please fill required data";
        try{
            $this->db->query('DELETE FROM `parking` WHERE `id`=?', array($id));
            return "Successfully deleted park";
        }catch (Exception $e) {
            // throw new Exception($e->errorMessage());
            return $e;
        }
    }

    /**
   * This function to update number of parking spot
   * @param parkId Id of the park that is to be updated
   * @param tickets number of tickets
   * @return Array
   */
    public function updateParkingSpot($parkId, $tickets, $statusCode = 200){
        if (empty($parkId) || empty($tickets)) return "Please fill all required function params";
        try{
            // $park = $this->getParkById($parkId);
            if ($park['available_spot'] <= 0) return "All spots are already booked";
            $noSpot = $park['available_spot'] - $tickets;
            $this->db->query("UPDATE `parking` SET `available_spot` = ? WHERE `parking`.`id` = $parkId", array($noSpot));
            return $park;
        }catch (Exception $e) {
            // throw new Exception($e->errorMessage());
            return $e;
        }
    }
}

// $parking = new Parking();
// $parking->getAllUserPark(1);