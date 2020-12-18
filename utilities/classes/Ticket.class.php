<?php

/**
 * @file This file that handles request to the database for parking
 * @author COLLINS <abadaikecollins@gmail.com> <11/09/2020>
 *  Last Modified: Collins <abadaikecollins@gmail.com> <11/09/2020>
 */

if (isset($databasePath)) require_once ($databasePath);
if (isset($clientPath)) require_once ($clientPath);
if (isset($spacePath)) require_once ($spacePath);

// require_once('./Database.class.php');
// require_once('./Client.class.php');
// require_once('./Space.class.php');

class Ticket {
    protected $db;
    protected $space;
    protected $client;

    public function __construct() {
        $this->db = new Database();
        $this->client = new Client();
		$this->space = new Space();
	}
   
    /**
   * This function gets all ticket
   * @param $statusCode 
   * @return Array
   */
    public function getAllTickets($statusCode = 200){
        try {
            return $this->db->query('SELECT * FROM `ticket`')->fetchAll();
        } catch (Exception $e) {
            // throw new Exception($e->errorMessage());
            return $e;
        }
    }
   
   
    /**
   * This function gets user ticket
   * @param $statusCode 
   * @return Array
   */
    public function getUserTicket($userId, $statusCode = 200){
        try {
            return $this->db->query('SELECT `tickets` FROM `ticket` WHERE `id`=? LIMIT 1', array($userId))->fetchArray();
        } catch (Exception $e) {
            // throw new Exception($e->errorMessage());
            return $e;
        }
    }
   
    /**
   * This function gets a ticket by id
   * @param $id Id of the ticket
   * @param $statusCode 
   * @return Array
   */
    public function getTicketById($id, $statusCode = 200){
        try {
            return $this->db->query('SELECT * FROM `ticket` WHERE `ticket`.`id` = ?', array($id))->fetchArray();
        } catch (Exception $e) {
            // throw new Exception($e->errorMessage());
            return $e;
        }
    }
   
   /**
   * This function gets ticket by space id
   * @param $id Id of the ticket
   * @param $statusCode 
   * @return Array
   */
    public function getTicketBySpaceId($spaceId, $statusCode = 200){
        try {
            return $this->db->query('SELECT * FROM `ticket` WHERE `ticket`.`space_id` = ?', array($spaceId))->fetchAll();
        } catch (Exception $e) {
            // throw new Exception($e->errorMessage());
            return $e;
        }
    }
   
   /**
   * This function gets ticket by client id
   * @param id Id of the ticket
   * @param statusCode 
   * @return Array
   */
    public function getTicketByClientId($clientId, $statusCode = 200){
        try {
            return $this->db->query('SELECT * FROM `ticket` WHERE `ticket`.`client_id` = ?', array($clientId))->fetchAll();
        } catch (Exception $e) {
            // throw new Exception($e->errorMessage());
            return $e;
        }
    }

    /**
   * This function gets with a particular location id
   * @param id Id of the ticket
   * @param statusCode 
   * @return Array
   */
    public function getTicketByLocationId($locationId, $statusCode = 200){
        try {
            return $this->db->query('SELECT * FROM `ticket` WHERE `ticket`.`location_id` = ?', array($locationId))->fetchAll();
        } catch (Exception $e) {
            // throw new Exception($e->errorMessage());
            return $e;
        }
    }

    /**
   * This function gets with a particular location id
   * @param client Id of the login client
   * @param statusCode 
   * @return Array
   */
    public function getClientSpace($clientId, $statusCode = 200){
        try {
            $tickets = $this->getTicketByClientId($clientId);
            $result = array();
            foreach ($tickets as $ticket) {
                $result[] = $this->space->getSpaceById($ticket['space_id']);
            }
            if (!empty($result)) return $result;
            return 'Book a ticket';
        } catch (Exception $e) {
            // throw new Exception($e->errorMessage());
            return $e;
        }
    }

    /**
   * This function add user data to the ticket table
   * @param spaceNo Number of parking space
   * @param clientId Id of client
   * @param locationId Location id
   * @return Array
   */
    public function bookParking($spaceId, $clientId, $locationId, $statusCode = 200){
        if (empty($spaceId) || empty($clientId) || empty($locationId)) return "Please retry!!";
        try{
            $this->db->query('INSERT INTO `ticket` (`client_id`,`location_id`,`space_id`) VALUES (?,?,?)', array($clientId, $locationId, $spaceId));
            $res = $this->client->updateClientTicket($clientId);
            $this->space->updateSpaceStatus($spaceId);
            if (is_string($res)) return $res;
            if (is_array($res)) return $res;
            return "Process failed please try again";
        }catch (Exception $e) {
            // throw new Exception($e->errorMessage());
            return $e;
        }
    }

   /**
   * This function reverses purchase
   * @param $id Id of the ticket
   * @return Array
   */
    public function reversePurchase($id, $statusCode = 200){
        if (!$id) return "Please fill all required details";
        try{
            $this->db->query('DELETE FROM `ticket` WHERE `id`=?', array($id));
            return "Successfully reversed purchased";
        }catch (Exception $e) {
            // throw new Exception($e->errorMessage());
            return $e;
        }
    }
}
