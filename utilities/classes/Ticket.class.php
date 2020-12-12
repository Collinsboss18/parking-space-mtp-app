<?php

/**
 * @file This file that handles request to the database for parking
 * @author COLLINS <abadaikecollins@gmail.com> <11/09/2020>
 *  Last Modified: Collins <abadaikecollins@gmail.com> <11/09/2020>
 */

if (isset($databasePath)) require_once ($databasePath);
if (isset($parkingPath)) require_once ($parkingPath);

// require_once('./Database.class.php');
// require_once('./Parking.class.php');

class Ticket {
    protected $db;
    protected $parking;

    public function __construct() {
		$this->db = new Database();
		$this->parking = new Parking();
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
   * This function gets a park by id
   * @param $id Id of the ticket
   * @param $statusCode 
   * @return Array
   */
    public function getTicketById($id, $statusCode = 200){
        try {
            $ticket = $this->db->query('SELECT * FROM `ticket` WHERE id = ?', array($id))->fetchArray();
            if(empty($ticket)) return 'Cant find ticket with that id';
            return $ticket;
        } catch (Exception $e) {
            // throw new Exception($e->errorMessage());
            return $e;
        }
    }
   
   /**
   * This function gets a park by id
   * @param $id Id of the ticket
   * @param $statusCode 
   * @return Array
   */
    public function getTicketByParkingId($parkingId, $statusCode = 200){
        try {
            return $this->db->query('SELECT * FROM `ticket` WHERE `parking_id` = ?', array($parkingId))->fetchAll();
        } catch (Exception $e) {
            // throw new Exception($e->errorMessage());
            return $e;
        }
    }
   
   /**
   * This function gets a park by id
   * @param $id Id of the ticket
   * @param $statusCode 
   * @return Array
   */
    public function getTicketByClientId($clientId, $statusCode = 200){
        try {
            $result = $this->db->query('SELECT * FROM `ticket` WHERE `client_id` = ?', array($clientId))->fetchAll();
            $tickets = array();
            foreach ($result as $ticket) {
                $park = $this->parking->getParkById($ticket['parking_id']);
                if (!empty($park) && is_array($park)) array_push($tickets, $park);
            }
            return $tickets;
        } catch (Exception $e) {
            // throw new Exception($e->errorMessage());
            return $e;
        }
    }

    /**
   * This function gets all parks
   * @param $clientId 
   * @param $parkingId 
   * @param $noOfTickets 
   * @param $statusCode 
   * @return Array
   */
    public function buyTicket($clientId,$parkingId, $noOfTickets, $statusCode = 200){
        if (!$clientId || !$parkingId || !$noOfTickets) return "Please fill all available inputs";
        try{
            $park = $this->parking->getParkById($parkingId);
            $tickets = $this->getTicketByParkingId($parkingId);
            $data = 0;
            if (is_array($tickets)) foreach($tickets as $ticket){ $data +=$ticket['tickets']; };
            /** Check if parking space is available */
            if ($data >= $park['available_ticket']) {
                // Update parking availability
                $this->parking->updateAvailability($parkingId, 0);
                return 'Ticket not available';
            };
            if ($park['available'] == true) {
                $cTicket = $this->db->query('INSERT INTO `ticket` (`client_id`,`parking_id`,`tickets`) VALUES (?,?,?)', array($clientId, $parkingId, $noOfTickets));
                $insertedId = $cTicket->lastInsertID();
                return $this->getTicketById($insertedId);
            }
            return "Parking space is currently not available";
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

// $ticket = new Ticket();
// $ticket->getTicketByClientId(1);