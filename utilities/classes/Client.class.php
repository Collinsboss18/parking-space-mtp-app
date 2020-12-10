<?php

/**
 * @file This file that handles request to the database for a client
 * @author COLLINS <abadaikecollins@gmail.com> <11/09/2020>
 *  Last Modified: Collins <abadaikecollins@gmail.com> <11/09/2020>
 */

include ('./Database.class.php');
include ('./Encryption.class.php');

class Client {
    protected $db;
    protected $encrypt;

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
        if (!$name || !$email || !$password) throw new Exception("Please fill all available inputs");
        try {
            $newPassword = $this->encrypt->encode($password);
            $client = $this->db->query('INSERT INTO clients (`name`,`email`,`password`) VALUES (?,?,?)', array($name, $email, $newPassword));
            $insertedId = $client->lastInsertID();
            return $this->getClientById($insertedId);
        }catch (Exception $e) {
            echo $e->errorMessage();
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
        if (!$email || !$password) throw die('Fill all available input');
        try {
            $res = $this->db->query('SELECT * FROM `clients` WHERE `email` = ? LIMIT 1', array($email))->fetchAll();
            if(empty($res)) throw die('Invalid email');
            foreach ($res as $client) {
                $newRes = $this->encrypt->verify($password, $client['id']);
                if ($newRes) return $res;
                throw die('Invalid password');
            }
        }catch (Exception $e) {
            echo $e->errorMessage();
        }
    }

   /**
   * This function gets a client by id
   * @param $id Id of the client
   * @param $statusCode 
   * @return Array
   */
    public function getClientById($id, $statusCode = 200){
        $client = $this->db->query('SELECT * FROM `clients` WHERE id = ?', array($id))->fetchArray();
        if(empty($client)) throw die('Cant find client with that id');
        return $client;
    }
}

$client = new Client();
