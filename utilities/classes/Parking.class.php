<?php

/**
 * @file This file that handles request to the database for parking
 * @author COLLINS <abadaikecollins@gmail.com> <11/09/2020>
 *  Last Modified: Collins <abadaikecollins@gmail.com> <11/09/2020>
 */

if (isset($databasePath)) require_once ($databasePath);
if (isset($encryptionPath)) require_once ($encryptionPath);

class Parking {
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
    public function getAllPark($statusCode = 200){
        try{
            return $this->db->query('SELECT * FROM `parking`')->fetchAll();
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
    public function getParkById($id, $statusCode = 200){
        try {
            $park = $this->db->query('SELECT * FROM `parking` WHERE id = ?', array($id))->fetchArray();
            if(empty($park)) return 'Cant find park with that id';
            return $park;
        } catch (Exception $e) {
            // throw new Exception($e->errorMessage());
            return $e;
        }
    }

   /**
   * This function updates a park
   * @param $id Id of the park
   * @param $location
   * @param $availableTicket
   * @param $alwaysAvailable
   * @param $timeAvailable
   * @return Array
   */
    public function updatePark($id, $location, $price, $availableTicket, $alwaysAvailable, $timeAvailable, $statusCode = 200){
        if (!$location || !$timeAvailable || $price || $availableTicket) throw new Exception("Please fill all available inputs");
        try{
            $this->db->query("UPDATE `parking` SET `location`= ?, `price`=?, `available_ticket`=? `always_available`=?, `time_available`=? WHERE `id` = $id", array($location, $price, $availableTicket, $alwaysAvailable, $timeAvailable));
            return $this->getParkById($id);
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
        if (!$location || !$timeAvailable || $price || $availableTicket) throw new Exception("Please fill all available inputs");
        try{
            $cPark = $this->db->query('INSERT INTO `parking` (`location`,`price`,`available_ticket`,`always_available`,`time_available`) VALUES (?,?,?,?,?)', array($location, $price, $availableTicket, $alwaysAvailable, $timeAvailable));
            $insertedId = $cPark->lastInsertID();
            return $this->getParkById($insertedId);
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
        if (!$id) return "Please fill all available inputs";
        try{
            $this->db->query('DELETE FROM `parking` WHERE `id`=?', array($id));
            return ["msg"=>"Successfully deleted park"];
        }catch (Exception $e) {
            // throw new Exception($e->errorMessage());
            return $e;
        }
    }
}
