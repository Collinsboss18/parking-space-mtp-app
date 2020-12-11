<?php

/**
 * @file This file that handles request to the database for a client
 * @author COLLINS <abadaikecollins@gmail.com> <11/09/2020>
 *  Last Modified: Collins <abadaikecollins@gmail.com> <11/09/2020>
 */

include ('./Database.class.php');
include ('./Encryption.class.php');

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
   * @param $email
   * @param $password
   * @param $statusCode 
   * @return Array
   */
    public function adminLogin($email, $password, $statusCode = 200){
        if (!$email || !$password) return 'Fill all available input';
        try {
            $res = $this->db->query('SELECT * FROM `clients` WHERE `email` = ? AND `is_admin` = ? LIMIT 1', array($email, 1))->fetchAll();
            if(empty($res)) return 'This client is not an admin';
            foreach ($res as $client) {
                $newRes = $this->encrypt->verify($password, $client['id']);
                if ($newRes) return $res;
                return 'Invalid password';
            }
        }catch (Exception $e) {
            // throw new Exception($e->errorMessage());
            return $e->errorMessage();
        }
    }

    /**
     * This function add and removes clients as an admin
     * @param $id Id of the client
     * @param $statusCode 
     * @return Boolean
   */
  public function getAllClients($id, $statusCode = 200){
    try{
        $res = $this->db->query('SELECT * FROM `clients` WHERE id = ? LIMIT 1', array($id))->fetchArray();
        if ($this->isAdmin($res['is_admin']) == TRUE) {
            return $this->db->query('SELECT * FROM `clients` WHERE <> `id`=?', array($user_id))->fetchAll();
        }
        return "User is not an admin";
    }catch (Exception $e) {
        // throw new Exception($e->errorMessage());
        return $e->errorMessage();
    }
}

   /**
   * This function deactivate and activate clients
   * @param $id Id of the client
   * @param $statusCode 
   * @return Boolean
   */
    public function toggleActive($id, $statusCode = 200){
        try{
            $res = $this->db->query('SELECT * FROM `clients` WHERE id = ? LIMIT 1', array($id))->fetchArray();
            if ($this->isAdmin($res['is_admin']) == TRUE) {
                $res['active'] == true ? $active = 0 : $active = 1;
                $this->db->query("UPDATE `clients` SET `active`= $active WHERE `id` = ?", array($id));
                return $active;
            }
            return "User is not an admin";
        }catch (Exception $e) {
            // throw new Exception($e->errorMessage());
            return $e->errorMessage();
        }
    }
   
    /**
   * This function add and removes clients as an admin
   * @param $id Id of the client
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
            // throw new Exception($e->errorMessage());
            return $e->errorMessage();
        }
    }
   
    /**
   * This function add and removes clients as an admin
   * @param $id Id of the client
   * @param $statusCode 
   * @return Boolean
   */
    public function isAdmin($id, $statusCode = 200){
        try{
            $res = $this->db->query('SELECT `is_admin` FROM `clients` WHERE id = ?', array($id))->fetchArray();
            return $res['is_admin'];
        }catch (Exception $e) {
            // throw new Exception($e->errorMessage());
            return $e->errorMessage();
        }
    }
}
