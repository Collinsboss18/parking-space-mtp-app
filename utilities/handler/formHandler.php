<?php

$databasePath = '../classes/Database.class.php';
$encryptionPath = '../classes/Encryption.class.php';
$clientPath = '../classes/Client.class.php';
$spacePath = '../classes/Space.class.php';

require_once('../classes/Action.class.php');
// require_once('../classes/Admin.class.php');
require_once('../classes/Client.class.php');
require_once('../classes/Ticket.class.php');

$action = new Action();
// $admin = new Admin();
$client = new Client();
$ticket = new Ticket();

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
            $action->redirect('../../client/Index.php');
        };
        $action->flash('Invalid email or password');
        if (is_string($res)) $action->flash($res);
        $action->redirect('../../login.php');
    } catch (Exception $e) {
        // throw new Exception($e->errorMessage());
        return $e;
    }
}

// if (isset($_POST['aLogin'])) {
//     try {
//         $action->flash('Fill all required input');
//         if (empty($_POST['email']) || empty($_POST['password'])) $action->redirect('../../login.php');
//         $res = $admin->adminLogin($_POST['email'], $_POST['password']);
//         if (is_array($res)) {
//             $_SESSION['admin']['id'] = $res[0]['id'];
//             $_SESSION['admin']['name'] = $res[0]['name'];
//             $_SESSION['admin']['email'] = $res[0]['email'];
//             $action->redirect('../../admin/index.php');
//         };
//         $action->flash('Invalid email or password');
//         if (is_string($res)) $action->flash($res);
//         $action->redirect('../../adminLogin.php');
//     } catch (Exception $e) {
//         // throw new Exception($e->errorMessage());
//         return $e;
//     }
// }

if (isset($_POST['book'])) {
    try {
        if (empty($_POST['spaceId']) || empty($_POST['clientId']) || empty($_POST['locationId'])) $action->flash('Please retry!!');
        $res = $ticket->bookParking($_POST['spaceId'], $_POST['clientId'], $_POST['locationId']);
        if (is_string($res)) $action->cFlash($res);
        if (is_array($res)) $action->cFlash('Successful parking space booked');
        $action->redirect('../../client/Index.php');
    } catch (Exception $e) {
        // throw new Exception($e->errorMessage());
        return $e;
    }
}

if (isset($_POST['logout'])) {
    $action->logout();
    $action->redirect('../../client/Index.php');
}

// if (isset($_POST['reverse'])) {
//     try {
//         if (empty($_POST['ticketId'])) $action->flash('Invalid ticketId');
//         $res = $ticket->reversePurchase($_POST['ticketId']);
//         if (is_string($res)) $action->flash($res);
//         $action->redirect('../../client/Index.php');
//     } catch (Exception $e) {
//         // throw new Exception($e->errorMessage());
//         return $e;
//     }
// }

// if (isset($_POST['tActive'])) {
//     try {
//         $admin->toggleActive($_POST['clientId']);
//         $action->redirect('../../admin/Index.php');
//     } catch (Exception $e) {
//         // throw new Exception($e->errorMessage());
//         return $e;
//     }
// }

// $action->redirect('../../client/Index.php');