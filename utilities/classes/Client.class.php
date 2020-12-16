<?php

/**
 * @file This file that handles request to the database for a client
 * @author COLLINS <abadaikecollins@gmail.com> <11/09/2020>
 *  Last Modified: Collins <abadaikecollins@gmail.com> <11/09/2020>
 */


if (isset($databasePath)) require_once ($databasePath);
if (isset($encryptionPath)) require_once ($encryptionPath);

// require_once('./Database.class.php');
// require_once('./Encryption.class.php');

class Client {
    protected $db;
    protected $encrypt;

    /** Construct __construct */
    public function __construct() {
		$this->db = new Database();
		$this->encrypt = new Encryption();
	}

   /**
   * This function that create a client
   * @param $name
   * @param $email
   * @param $password
   * @param $statusCode 
   * @return Array
   */
    public function clientSignUp(String $name, $email, $password, $statusCode = 201){
        if (!$name || !$email || !$password) return "Please fill all available inputs";
        try {
            $newPassword = $this->encrypt->encode($password);
            $client = $this->db->query('INSERT INTO clients (`name`,`email`,`password`, `active`, `is_admin`) VALUES (?,?,?)', array($name, $email, $newPassword, 1, 0));
            $insertedId = $client->lastInsertID();
            return $this->getClientById($insertedId);
        }catch (Exception $e) {
            // throw new Exception($e->errorMessage());
            return $e;
        }
    }

   /**
   * This function that create a client
   * @param $email
   * @param $password
   * @param $statusCode 
   * @return Array
   */
    public function clientLogin($email, $password, $statusCode = 200){
        if (!$email || !$password) return 'Fill all available input';
        try {
            $res = $this->db->query('SELECT * FROM `clients` WHERE `email` = ? LIMIT 1', array($email))->fetchAll();
            if(empty($res)) return 'Invalid email';
            foreach ($res as $client) {
                $newRes = $this->encrypt->verifyPassword($password, $client['id']);
                if ($newRes) {
                    $clientActive = $this->db->query('SELECT * FROM `clients` WHERE `email` = ? AND `active` = ? LIMIT 1', array($email, 1))->fetchAll();
                    if (!empty($clientActive) && is_array($clientActive)) return $res;
                    return 'Client has been disabled';
                }
                return 'Invalid password';
            }
        } catch (Exception $e) {
            // throw new Exception($e->errorMessage());
            return $e;
        }
    }

   /**
   * This function gets a client by id
   * @param $clientId Id of the client
   * @param $statusCode 
   * @return Array
   */
    public function getClientById($clientId, $statusCode = 200){
        try {
            $client = $this->db->query('SELECT * FROM `clients` WHERE id = ?', array($clientId))->fetchArray();
            if(empty($client)) return 'Cannot find client with that id';
            return $client;
        } catch (Exception $e) {
            // throw new Exception($e->errorMessage());
            return $e;
        }
    }

   /**
   * This function gets a client by id
   * @param $clientId Id of the client
   * @param $statusCode 
   * @return Array
   */
    public function getUserTicket($clientId, $statusCode = 200){
        try {
            return $this->db->query('SELECT `tickets` FROM `clients` WHERE id = ?', array($clientId))->fetchArray();
        } catch (Exception $e) {
            // throw new Exception($e->errorMessage());
            return $e;
        }
    }

    /**
   * This function gets a client by id
   * @param $clientId Id of the client
   * @param $statusCode 
   * @return Array
   */
    public function updateClientTicket($clientId, $tickets, $statusCode = 200){
        try {
            $client = $this->getClientById($clientId);
            if (($client['tickets'] - $tickets) < 0) return "You don't have enough tickets";
            $newTicket = $client['tickets'] - $tickets;
            $this->db->query("UPDATE `clients` SET `tickets` = ? WHERE `clients`.`id` = $clientId", array($newTicket));
            return $client;
        } catch (Exception $e) {
            // throw new Exception($e->errorMessage());
            return $e;
        }
    }
}

// $client = new Client();
// $client->updateTicket(2, 1);