<?php
/**
 * @file This class handles encryption(password)
 * @author COLLINS <abadaikecollins@gmail.com> <11/09/2020>
 *  Last Modified: Collins <abadaikecollins@gmail.com> <11/09/2020>
 */

class Encryption {
    protected $db;

    /** Construct __construct */
    public function __construct() {
		$this->db = new Database();
	}
    /**
   * This function encodes
   * @param value: Value of what is to be hash
   * @return Hashed: BCRYPT
   */
    public function encode($value){ 
        return password_hash($value, PASSWORD_BCRYPT);
    }

    /**
   * This function verifies password
   * @param userId 
   * @param password: Value of what is to be verified
   * @return Boolean
   */
    public function verifyPassword($password, $id){
        try {
            $res = $this->db->query('SELECT `password` FROM `user` WHERE id = ? LIMIT 1', array($id))->fetchArray();
            if (password_verify($password, $res['password'])) return true;
            return false;
        } catch (Exception $e) {
            // throw new Exception($e->errorMessage());
            return $e;
        }
    }
}
