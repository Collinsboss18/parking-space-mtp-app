<?php
session_start();
/**
 * @file This file handles the logics
 * @author COLLINS <abadaikecollins@gmail.com> <11/09/2020>
 *  Last Modified: Collins <abadaikecollins@gmail.com> <11/09/2020>
 */

class Action {
    
   /**
   * This function redirects to $url
   * @param $url Url to the page you are redirecting to
   * @param $statusCode 
   */
    function redirect($url, $statusCode = 303){
        header('Location: ' . $url, true, $statusCode);
        die();
    }

    /**
     * This function creates a flash message
     * @param $msg
     */
    function flash(String $msg, $statusCode = 200){
        try {
            if (empty($msg)) return 'Flash message is required';
            if (isset($_SESSION['msg'])) unset($_SESSION['msg']);
            $_SESSION['msg'] = $msg;
            return $_SESSION['msg'];
        } catch (Exception $e) {
            // throw new Exception($e->errorMessage());
            return $e;
        }
    }

    /**
     * This function unset session
     */
    function unsetMsg($statusCode = 200){
        unset($_SESSION['msg']);
    }

}