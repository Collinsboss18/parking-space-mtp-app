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
    public function clientSignUp($name, $email, $password, $statusCode = 201){
        if (!$email || !$password) return "Please fill required all method params";
        try {
            $newPassword = $this->encrypt->encode($password);
            $client = $this->db->query('INSERT INTO `user` (`name`, `email`,`password`, `is_active`, `role`, `no_ticket`) VALUES (?,?,?,?,?,?)', array($name, $email, $newPassword, 1, 0, 0));
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
        if (!$email || !$password) return 'Fill all required method params';
        try {
            $res = $this->db->query('SELECT * FROM `user` WHERE `email` = ? LIMIT 1', array($email))->fetchAll();
            if(empty($res)) return 'Invalid email';
            foreach ($res as $client) {
                $newRes = $this->encrypt->verifyPassword($password, $client['id']);
                if ($newRes) {
                    $clientActive = $this->db->query('SELECT * FROM `user` WHERE `email` = ? AND `is_active` = ? LIMIT 1', array($email, 1))->fetchAll();
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
            $client = $this->db->query('SELECT * FROM `user` WHERE id = ?', array($clientId))->fetchArray();
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
            $res =  $this->db->query('SELECT `no_ticket` FROM `user` WHERE id = ?', array($clientId))->fetchArray();
            return $res['no_ticket'];
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
    public function updateClientTicket($clientId, $statusCode = 200){
        try {
            $client = $this->getClientById($clientId);
            $newTicket = $client['no_ticket'] - 1;
            $this->db->query("UPDATE `user` SET `no_ticket` = ? WHERE `user`.`id` = $clientId", array($newTicket));
            return $client;
        } catch (Exception $e) {
            // throw new Exception($e->errorMessage());
            return $e;
        }
    }
}

// $client = new Client();
// $client->updateClientTicket(1);