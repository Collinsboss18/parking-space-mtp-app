<?php
session_start();
/**
 * @file This file handles the logics
 * @author COLLINS <abadaikecollins@gmail.com> <11/09/2020>
 *  Last Modified: Collins <abadaikecollins@gmail.com> <11/09/2020>
 */

class Action {
    
   /**
   * This method redirects to $url
   * @param $url Url to the page you are redirecting to
   * @param $statusCode 
   */
    function redirect($url, $statusCode = 303){
        header('Location: ' . $url, true, $statusCode);
        die();
    }

    /**
     * This method logout a user
     * @param $statusCode 
     */
    function logout($statusCode = 303){
        if (isset($_SESSION['client'])) unset($_SESSION['client']);
        if (isset($_SESSION['admin'])) unset($_SESSION['admin']);
    }

    /**
     * This method creates a flash message
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
     * This method creates a flash message for client
     * @param $msg
     */
    function cFlash(String $msg, $statusCode = 200){
        try {
            if (empty($msg)) return 'Flash message is required';
            if (isset($_SESSION['cmsg'])) unset($_SESSION['cmsg']);
            $_SESSION['cmsg'] = $msg;
            return $_SESSION['cmsg'];
        } catch (Exception $e) {
            // throw new Exception($e->errorMessage());
            return $e;
        }
    }

    /**
     * This method creates a flash message for admin user
     * @param $msg
     */
    function aFlash(String $msg, $statusCode = 200){
        try {
            if (empty($msg)) return 'Flash message is required';
            if (isset($_SESSION['amsg'])) unset($_SESSION['amsg']);
            $_SESSION['amsg'] = $msg;
            return $_SESSION['amsg'];
        } catch (Exception $e) {
            // throw new Exception($e->errorMessage());
            return $e;
        }
    }

    /**
     * This method unset session
     */
    function unsetMsg($statusCode = 200){
        if (isset($_SESSION['msg'])) unset($_SESSION['msg']);
        if (isset($_SESSION['cmsg'])) unset($_SESSION['cmsg']);
        if (isset($_SESSION['amsg'])) unset($_SESSION['amsg']);
    }

}