<?php

$databasePath = '../classes/Database.class.php';
$encryptionPath = '../classes/Encryption.class.php';

require_once('../classes/Action.class.php');
require_once('../classes/Ticket.class.php');

$action = new Action();
$ticket = new Ticket();



$action->redirect('../../login.php');