<?php

/**
 * @file This file that handles request to the database for a client
 * @author COLLINS <abadaikecollins@gmail.com> <11/09/2020>
 *  Last Modified: Collins <abadaikecollins@gmail.com> <11/09/2020>
 */

if (isset($databasePath)) require_once ($databasePath);
if (isset($encryptionPath)) require_once ($encryptionPath);

// include ('./Database.class.php');
// include ('./Encryption.class.php');

class Admin {
    protected $db;
    protected $encrypt;

    /** Construct __construct */
    public function __construct() {
		$this->db = new Database();
        $this->encrypt = new Encryption();
	}

   /**
   * This function login an admin
   * @param email
   * @param password
   * @param $statusCode 
   * @return Array
   */
    public function adminLogin($email, $password, $statusCode = 200){
        if (!$email || !$password) return 'Fill all available input';
        try {
            $res = $this->db->query('SELECT * FROM `clients` WHERE `email` = ? AND `is_admin` = ? LIMIT 1', array($email, 1))->fetchAll();
            if(empty($res)) return 'This client is not an admin';
            foreach ($res as $client) {
                $newRes = $this->encrypt->verifyPassword($password, $client['id']);
                if ($newRes) return $res;
                return 'Invalid password';
            }
        }catch (Exception $e) {
            // throw new Exception($e);
            return $e;
        }
    }

    /**
     * This function that get all clients
     * @param id Id of the client
     * @param $statusCode 
     * @return Array
   */
    public function getAllClients($statusCode = 200){
        try{
            $res = $this->db->query('SELECT * FROM `clients` WHERE `is_admin`= ?', array(0))->fetchAll();
            if (is_array($res) && !empty($res)){
                return $res;
            }
            return "User is not an admin";
        }catch (Exception $e) {
            // throw new Exception($e);
            return $e;
        }
    }
    
    /**
     * This function that get all clients
     * @param id Id of the client
     * @param $statusCode 
     * @return Array
   */
    public function getClientsById($id, $statusCode = 200){
        try{
            $res = $this->db->query('SELECT * FROM `clients` WHERE id = ? LIMIT 1', array($id))->fetchArray();
            if (is_array($res) && !empty($res)){
                return $res;
            }
            return "Cannot find user with that Id";
        }catch (Exception $e) {
            // throw new Exception($e);
            return $e;
        }
    }

   /**
   * This function deactivate and activate clients
   * @param id Id of the client
   * @param $statusCode 
   * @return Boolean
   */
    public function toggleActive($clientId, $statusCode = 200){
        try{
            $res = $this->getClientsById($clientId);
            if ($res['active'] == 1) {
                $this->db->query("UPDATE `clients` SET `active`= ? WHERE `id` = $clientId", array(0));
                return FALSE;
            }
            if ($res['active'] == 0) {
                $this->db->query("UPDATE `clients` SET `active`= ? WHERE `id` = $clientId", array(1));
                return TRUE;
            }
            return "User is not an admin";
        }catch (Exception $e) {
            // throw new Exception($e);
            return $e;
        }
    }
   
    /**
   * This function add and removes clients as an admin
   * @param id Id of the client
   * @param $statusCode 
   * @return Boolean
   */
    public function toggleAdmin($id, $statusCode = 200){
        try{
            $res = $this->db->query('SELECT * FROM `clients` WHERE id = ? LIMIT 1', array($id))->fetchArray();
            if ($this->isAdmin($res['is_admin']) == TRUE) {
                $res['is_admin'] == TRUE ? $is_admin = 0 : $is_admin = 1;
                $this->db->query("UPDATE `clients` SET `is_admin`= $is_admin WHERE `id` = ?", array($id));
                return $is_admin;
            }
            return "User is not an admin";
        }catch (Exception $e) {
            // throw new Exception($e);
            return $e;
        }
    }
   
    /**
   * This function add and removes clients as an admin
   * @param id Id of the client
   * @param $statusCode 
   * @return Boolean
   */
    public function isAdmin($id, $statusCode = 200){
        try{
            $res = $this->db->query('SELECT `is_admin` FROM `clients` WHERE id = ?', array($id))->fetchArray();
            return $res['is_admin'];
        }catch (Exception $e) {
            // throw new Exception($e);
            return $e;
        }
    }
}

// $admin = new Admin();
// $admin->getAllClients();