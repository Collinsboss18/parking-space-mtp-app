<?php

/**
 * @file This file that handles request to the database for parking
 * @author COLLINS <abadaikecollins@gmail.com> <11/09/2020>
 *  Last Modified: Collins <abadaikecollins@gmail.com> <11/09/2020>
 */

if (isset($databasePath)) require_once ($databasePath);

// require_once('./Database.class.php');

class Space {
    protected $db;

    /** Construct __construct */
    public function __construct() {
		$this->db = new Database();
	}
    
   /**
   * This function all available parking space
   * @param spaceId
   * @param statusCode 
   * @return Array
   */
    public function getSpaceById($spaceId, $statusCode = 200){
        try{
            return $this->db->query("SELECT * FROM `space` WHERE `space`.`id` = $spaceId")->fetchAll();
        }catch (Exception $e) {
            // throw new Exception($e->errorMessage());
            return $e;
        }
    }

    /**
   * This function all available parking space
   * @param statusCode 
   * @return Array
   */
    public function getAllSpace($statusCode = 200){
        try{
            return $this->db->query('SELECT * FROM `space`')->fetchAll();    
        }catch (Exception $e) {
            // throw new Exception($e->errorMessage());
            return $e;
        }
    }

    /**
   * This function to create a location
   * @param locationId
   * @param spotNo
   * @return Array
   */
    public function createSpot($locationId, $spaceNo, $statusCode = 200){
        if (empty($locationId)) return "Please fill required method params";
        try{
            $res = $this->db->query("INSERT INTO `space` (`location_id`,`space_no`,`status`,`date_available`) VALUES (?,?,?,now());", array($locationId, $spaceNo, 1));
            $spaceId = $this->db->lastInsertID($res);
            return $this->getSpaceById($spaceId);
        }catch (Exception $e) {
            // throw new Exception($e->errorMessage());
            return $e;
        }
    }
    
    /**
   * This function all available parking space
   * @param $statusCode 
   * @return Array
   */
    public function updateSpaceStatus($spaceId, $statusCode = 200){
        try{
            $this->db->query("UPDATE `space` SET `status` = ? WHERE `space`.`id` = $spaceId", array(0));
        }catch (Exception $e) {
            // throw new Exception($e->errorMessage());
            return $e;
        }
    }

}

// $parking = new Space();
// $parking->createSpot(5, 6);