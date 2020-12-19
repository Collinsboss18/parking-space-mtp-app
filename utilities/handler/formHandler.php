<?php

$databasePath = '../classes/Database.class.php';
$encryptionPath = '../classes/Encryption.class.php';
$clientPath = '../classes/Client.class.php';
$spacePath = '../classes/Space.class.php';

require_once('../classes/Action.class.php');
require_once('../classes/Admin.class.php');
require_once('../classes/Client.class.php');
require_once('../classes/Ticket.class.php');
require_once('../classes/Location.class.php');
require_once('../classes/Space.class.php');

$action = new Action();
$admin = new Admin();
$client = new Client();
$ticket = new Ticket();
$location = new Location();
$space = new Space();

if (isset($_POST['signup'])) {
    try {
        $action->flash('Fill all required input');
        if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['password'])) $action->redirect('../../signup.php');
        $res = $client->clientSignUp($_POST['name'], $_POST['email'], $_POST['password']);
        if (is_string($res)) {
            $action->flash($res);
            $action->redirect('../../signup.php');
        }
        if (is_array($res)) $action->flash('User successfully created please login');
        $action->redirect('../../login.php');
    } catch (Exception $e) {
        // throw new Exception($e->errorMessage());
        return $e;
    }
}

if (isset($_POST['login'])) {
    try {
        $action->flash('Fill all required input');
        if (empty($_POST['email']) || empty($_POST['password'])) $action->redirect('../../login.php');
        $res = $client->clientLogin($_POST['email'], $_POST['password']);
        if (is_array($res)) {
            $_SESSION['client']['id'] = $res[0]['id'];
            $_SESSION['client']['name'] = $res[0]['name'];
            $_SESSION['client']['email'] = $res[0]['email'];
            $action->redirect('../../client/index.php');
        };
        $action->flash('Invalid email or password');
        if (is_string($res)) $action->flash($res);
        $action->redirect('../../login.php');
    } catch (Exception $e) {
        // throw new Exception($e->errorMessage());
        return $e;
    }
}

if (isset($_POST['aLogin'])) {
    try {
        $action->flash('Fill all required input');
        if (empty($_POST['email']) || empty($_POST['password'])) $action->redirect('../../login.php');
        $res = $admin->adminLogin($_POST['email'], $_POST['password']);
        if (is_array($res)) {
            $_SESSION['admin']['id'] = $res[0]['id'];
            $_SESSION['admin']['name'] = $res[0]['name'];
            $_SESSION['admin']['email'] = $res[0]['email'];
            $action->redirect('../../admin/index.php');
        };
        $action->flash('Invalid email or password');
        if (is_string($res)) $action->flash($res);
        $action->redirect('../../adminLogin.php');
    } catch (Exception $e) {
        // throw new Exception($e->errorMessage());
        return $e;
    }
}

if (isset($_POST['book'])) {
    try {
        if (empty($_POST['spaceId']) || empty($_POST['clientId']) || empty($_POST['locationId'])) $action->flash('Please retry!!');
        $res = $ticket->bookParking($_POST['spaceId'], $_POST['clientId'], $_POST['locationId']);
        if (is_string($res)) $action->cFlash($res);
        if (is_array($res)) $action->cFlash('Successful parking space booked');
        $action->redirect('../../client/index.php');
    } catch (Exception $e) {
        // throw new Exception($e->errorMessage());
        return $e;
    }
}

if (isset($_POST['logout'])) {
    try {
        $action->logout();
        $action->redirect('../../client/index.php');
    } catch (Exception $e) {
        // throw new Exception($e->errorMessage());
        return $e;
    }
}

if (isset($_POST['tActive'])) {
    try {
        $admin->toggleActive($_POST['clientId']);
        $action->aFlash('User toggled successfully');
        $action->redirect('../../admin/index.php');
    } catch (Exception $e) {
        // throw new Exception($e->errorMessage());
        return $e;
    }
}

if (isset($_POST['cLocation'])) {
    try {
        $action->aFlash('Fill all required input');
        if (empty($_POST['location'])) $action->redirect('../../admin/index.php');
        $res = $location->createLocation($_POST['location']);
        if (is_string($res)) $action->aFlash($res);
        $action->redirect('../../admin/index.php');
    } catch (Exception $e) {
        // throw new Exception($e->errorMessage());
        return $e;
    }
}

if (isset($_POST['cSpot'])) {
    try {
        $action->aFlash('Fill all required input');
        if (empty($_POST['location']) || empty($_POST['spot'])) $action->redirect('../../admin/index.php');
        $res = $space->createSpot($_POST['location'], $_POST['spot']);
        if (is_string($res)) $action->aFlash($res);
        if (is_array($res)) $action->aFlash('Successful parking spot created');
        $action->redirect('../../admin/index.php');
    } catch (Exception $e) {
        // throw new Exception($e->errorMessage());
        return $e;
    }
}

// $action->redirect('../../client/index.php');