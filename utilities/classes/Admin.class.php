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
            $res = $this->db->query('SELECT * FROM `user` WHERE `email` = ? AND `role` = ? LIMIT 1', array($email, 0))->fetchAll();
            if(empty($res)) return 'User is not an admin';
            foreach ($res as $admin) {
                $newRes = $this->encrypt->verifyPassword($password, $admin['id']);
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
            $res = $this->db->query('SELECT * FROM `user` WHERE `role`= ?', array(1))->fetchAll();
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
            $res = $this->db->query('SELECT * FROM `user` WHERE id = ? LIMIT 1', array($id))->fetchArray();
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
            if ($res['is_active'] == 1) {
                $this->db->query("UPDATE `user` SET `is_active`= ? WHERE `id` = $clientId", array(0));
                return FALSE;
            }
            if ($res['is_active'] == 0) {
                $this->db->query("UPDATE `user` SET `is_active`= ? WHERE `id` = $clientId", array(1));
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
// $admin->adminLogin('admin@gmail.com', 'password');