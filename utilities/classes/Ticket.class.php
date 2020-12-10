<?php

/**
 * @file This file that handles request to the database for parking
 * @author COLLINS <abadaikecollins@gmail.com> <11/09/2020>
 *  Last Modified: Collins <abadaikecollins@gmail.com> <11/09/2020>
 */

include ('./Database.class.php');

class Ticket {
    protected $db;

    public function __construct() {
		$this->db = new Database();
	}

   /**
   * This function gets all parks
   * @param $clientId 
   * @param $parkingId 
   * @param $noOfTickets 
   * @param $statusCode 
   * @return Array
   */
    public function purchaseTicket($clientId,$parkingId, $noOfTickets, $statusCode = 200){
        if (!$clientId || !$parkingId || !$noOfTickets) throw new Exception("Please fill all available inputs");
        try{
            $cTicket = $this->db->query('INSERT INTO `ticket` (`client_id`,`parking_id`,`tickets`) VALUES (?,?,?)', array($clientId, $parkingId, $noOfTickets));
            $insertedId = $cTicket->lastInsertID();
            return $this->getTicketById($insertedId);
        }catch (Exception $e) {
            echo $e->errorMessage();
        }
    }
   
    /**
   * This function gets a park by id
   * @param $id Id of the ticket
   * @param $statusCode 
   * @return Array
   */
    public function getTicketById($id, $statusCode = 200){
        $ticket = $this->db->query('SELECT * FROM `ticket` WHERE id = ?', array($id))->fetchArray();
        if(empty($ticket)) throw die('Cant find ticket with that id');
        return $ticket;
    }

   /**
   * This function reverses purchase
   * @param $id Id of the ticket
   * @return Array
   */
    public function reversePurchase($id, $statusCode = 200){
        if (!$id) throw new Exception("Please fill all required details");
        try{
            $this->db->query('DELETE FROM `ticket` WHERE `id`=?', array($id));
            return ["msg"=>"Successfully reversed purchased"];
        }catch (Exception $e) {
            echo $e->errorMessage();
        }
    }
}

$parking = new Ticket();
