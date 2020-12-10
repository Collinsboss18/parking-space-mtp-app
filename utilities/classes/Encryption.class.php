<?php
/**
 * @file This class handles encryption(password)
 * @author COLLINS <abadaikecollins@gmail.com> <11/09/2020>
 *  Last Modified: Collins <abadaikecollins@gmail.com> <11/09/2020>
 */

class Encryption {
    protected $db;

    public function __construct() {
		$this->db = new Database();
	}
    /**
   * This function encodes
   * @param $value Value of what is to be hash
   * @return Hashed value
   */
    public function encode($value){ 
        return password_hash($value, PASSWORD_BCRYPT);
    }

    /**
   * This function decodes
   * @param $value Value of what is to be verified
   * @return Boolean
   */
    public function verifyPassword($value, $id){
        $res = $this->db->query('SELECT `password` FROM `clients` WHERE id = ? LIMIT 1', array($id))->fetchArray();
        if (password_verify($value, $res['password'])) return true;
        return false;
    }
}
