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
   * This function to create a location
   * @param location location name
   * @return Array
   */
    public function createLocation($location, $statusCode = 200){
    if (empty($location)) return "Please fill required method params";
        try{
            $res = $this->db->query("INSERT INTO `location` (`name`) VALUES (?);", array($location));
            $locationId = $this->db->lastInsertID($res);
            return $this->getLocationById($locationId);
        }catch (Exception $e) {
            // throw new Exception($e->errorMessage());
            return $e;
        }
    }

    /**
   * This function to update number of parking spot
   * @param locationId Id of the location
   * @param location location name
   * @return Array
   */
    public function updateLocation($locationId, $location, $statusCode = 200){
        if (empty($locationId) || empty($location)) return "Please fill required method params";
        try{
            $this->db->query("UPDATE `location` SET `name` = ? WHERE `location`.`id` = $locationId", array($location));
            return $this->getLocationById($locationId);
        }catch (Exception $e) {
            // throw new Exception($e->errorMessage());
            return $e;
        }
    }
   
    /**
   * This function to delete a park
   * @param locationId Id of the park that is to be deleted
   * @return String
   */
    public function deleteLocation($locationId, $statusCode = 200){
        if (!$locationId) return "Please fill required method param";
        try{
            $this->db->query('DELETE FROM `location` WHERE `location`.`id`=?', array($locationId));
            return "Successfully deleted location";
        }catch (Exception $e) {
            // throw new Exception($e->errorMessage());
            return $e;
        }
    }

}

// $location = new Location();
// $location->createLocation('Kubwa center park');