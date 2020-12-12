<?php

$databasePath = '../classes/Database.class.php';
$encryptionPath = '../classes/Encryption.class.php';
$parkingPath = '../classes/Parking.class.php';

require_once('../classes/Action.class.php');
require_once('../classes/Client.class.php');
require_once('../classes/Ticket.class.php');

$action = new Action();
$client = new Client();
$ticket = new Ticket();

if (isset($_POST['signup'])) {
    try {
        $action->flash('Fill all required input');
        if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['password'])) $action->redirect('../../signup.php');
        $res = $client->clientSignUp($_POST['name'], $_POST['email'], $_POST['password']);
        $action->flash('User successfully created please login');
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
            $action->redirect('../../client/Index.php');
        };
        $action->flash('Invalid email or password');
        $action->redirect('../../login.php');
    } catch (Exception $e) {
        // throw new Exception($e->errorMessage());
        return $e;
    }
}

if (isset($_POST['buy'])) {
    try {
        if (empty($_POST['id']) || empty($_POST['no'])) $action->flash('Fill input with No. of tickets you\'re purchasing');
        $res = $ticket->buyTicket($_SESSION['client']['id'], $_POST['id'], $_POST['no']);
        if (is_string($res)) $action->flash($res);
        if (is_array($res)) $action->flash('Successful ticket bought');
        $action->redirect('../../client/Index.php');
    } catch (Exception $e) {
        // throw new Exception($e->errorMessage());
        return $e;
    }
}

if (isset($_POST['reverse'])) {
    try {
        if (empty($_POST['ticketId'])) $action->flash('Invalid ticketId');
        $res = $ticket->reversePurchase($_POST['ticketId']);
        if (is_string($res)) $action->flash($res);
        $action->redirect('../../client/Index.php');
    } catch (Exception $e) {
        // throw new Exception($e->errorMessage());
        return $e;
    }
}

// $action->redirect('../../client/Index.php');