<?php
$databasePath = '../utilities/classes/Database.class.php';
$encryptionPath = '../utilities/classes/Encryption.class.php';
$clientPath = '../utilities/classes/Client.class.php';
$spacePath = '../utilities/classes/Space.class.php';

require_once('../utilities/classes/Action.class.php');
require_once('../utilities/classes/Space.class.php');
require_once('../utilities/classes/Client.class.php');
require_once('../utilities/classes/Location.class.php');
require_once('../utilities/classes/Ticket.class.php');

$action = new Action();
$client = new Client();
$space = new Space();
$location = new Location();
$ticket = new Ticket();

if (!isset($_SESSION['client'])) $action->redirect('../login.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Dashboard</title>
    <?php include_once('../utilities/includes/style.php') ?>
</head>
<body>
    <div class="container">
        <div class="m-5" />
        <div class="jumbotron">
            <h1>Welcome <?= $_SESSION['client']['name'] ?></h1>
            <p class="lead">
                Welcome <?= $_SESSION['client']['name'] ?> to Packing Space MTP App. You can buy tickets, book and view available packing space.
            </p>
            <form action="../utilities/handler/formHandler.php" method="post">
                <button class="btn btn-danger btn-sm" name='logout'>Logout</button>
            </form>
        </div>

        <br>
        <?php if (isset($_SESSION['cmsg'])) { ?>
            <div class="alert alert-secondary" role="alert">
                <?php 
                    echo $_SESSION['cmsg'];
                    $action->unsetMsg(); 
                ?>
            </div>
        <?php } ?>
        <br>

        <h1>Tickets: <?php $cTicket = $client->getUserTicket($_SESSION['client']['id']); echo $cTicket; ?></h1>

        <h3>Available Parking Space</h3>
        <table class="table">
            <thead>
                <tr>
                <th scope="col">#</th>
                <th scope="col">Location</th>
                <th scope="col">Space No</th>
                <th scope="col">Date Available</th>
                <th scope="col">Book With Ticket</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $result = $space->getAllSpace();
                    $int = 1;
                    foreach($result as $res){
                ?>
                    <tr>
                        <th scope="row"><?= $int ?></th>
                        <td><?php 
                            echo $location->getLocationById($res['location_id']);
                        ?></td>
                        <td><?= $res['space_no'] ?></td>
                        <td><?php
                            $dateTime = strtotime($res['date_available']);
                            echo date('y-F-Y h:i:s', $dateTime);
                        ?></td>
                        <td>
                            <?php if ($res['status'] === 0) { ?>
                            <form action="../utilities/handler/formHandler.php" method="post">
                                <button type="submit" name="book" class="btn btn-danger btn-sm disabled" title='Not available' disabled>Booked X</button>
                            </form>
                            <?php } elseif ($cTicket <= 0) { ?>
                                <form action="../utilities/handler/formHandler.php" method="post">
                                    <button type="submit" name="book" class="btn btn-warning btn-sm disabled" title='You are out of tickets' disabled>No Ticket</button>
                                </form>
                            <?php } else { ?>
                                <form action="../utilities/handler/formHandler.php" method="post">
                                    <input type="hidden" name="spaceId" value="<?= $res['id'] ?>">
                                    <input type="hidden" name="locationId" value="<?= $res['location_id'] ?>">
                                    <input type="hidden" name="clientId" value="<?= $_SESSION['client']['id'] ?>">
                                    <button type="submit" name="book" class="btn btn-success btn-sm" title='Book parking space with ticket'>Book =></button>
                                </form>
                            <?php } ?>
                        </td>
                    </tr>
                <?php $int++; }  ?>
            </tbody>
        </table>

        <h3>My Parking Space</h3>
        <table class="table">
            <thead>
                <tr>
                <th scope="col">#</th>
                <th scope="col">Location</th>
                <th scope="col">Space No</th>
                <th scope="col">Date Available</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $result = $ticket->getClientSpace($_SESSION['client']['id']);
                    $int = 1;
                    foreach($result as $res){
                ?>
                    <tr>
                        <th scope="row"><?= $int ?></th>
                        <td><?php 
                            echo $location->getLocationById($res[0]['location_id']);
                        ?></td>
                        <td><?= $res[0]['space_no'] ?></td>
                        <td><?php 
                            $dateTime = strtotime($res[0]['date_available']);
                            echo date('y-F-Y h:i:s', $dateTime);
                        ?></td>
                    </tr>
                <?php $int++; }  ?>
            </tbody>
        </table>

    </div>

<?php include_once('../utilities/includes/script.php') ?>
</body>
</html>