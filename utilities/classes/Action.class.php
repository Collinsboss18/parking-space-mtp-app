<?php
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
}