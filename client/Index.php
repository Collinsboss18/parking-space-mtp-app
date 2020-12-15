<?php
$databasePath = '../utilities/classes/Database.class.php';

require_once('../utilities/classes/Action.class.php');
require_once('../utilities/classes/Parking.class.php');
require_once('../utilities/classes/Ticket.class.php');

$action = new Action();
$parking = new Parking();
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
        </div>

        <br>
        <?php if (isset($_SESSION['msg'])) { ?>
            <div class="alert alert-secondary" role="alert">
                <?php 
                    echo $_SESSION['msg'];
                    $action->unsetMsg(); 
                ?>
            </div>
        <?php } ?>
        <br>

        <h1>Tickets: <?php 
            $noTicket = $ticket->getUserTicket($_SESSION['client']['id']); 
            echo $noTicket['tickets'];
        ?></h1>

        <h3>Available Parking Space</h3>
        <table class="table">
            <thead>
                <tr>
                <th scope="col">#</th>
                <th scope="col">Location</th>
                <th scope="col">Available Spot</th>
                <th scope="col">Time Available</th>
                <th scope="col">Book With Ticket</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $result = $parking->getAllPark();
                    $int = 1;
                    foreach($result as $res){
                ?>
                    <tr>
                        <th scope="row"><?= $int ?></th>
                        <td><?= $res['location'] ?></td>
                        <td><?= $res['available_spot'] ?></td>
                        <?php if ($res['always_available'] == true) { ?>
                            <td><b>Always Available</b></td>
                        <?php } else { ?>
                            <td><?= $res['time_available'] ?></td>
                        <?php } ?>
                        <?php if ($res['available'] == true) { ?>
                        <td>
                            <form action="../utilities/handler/formHandler.php" method="post">
                                <input type="number" name="no" id="no">
                                <input type="hidden" name="id" value="<?= $res['id'] ?>">
                                <button type="submit" name="book" class="btn btn-primary btn-sm">Book</button>
                            </form>
                        </td>
                        <?php } else { ?>
                        <td>Currently not available</td>
                        <?php } ?>
                    </tr>
                <?php $int++; }  ?>
            </tbody>
        </table>

        <h3>Booked Parking Space</h3>
        <table class="table">
            <thead>
                <tr>
                <th scope="col">#</th>
                <th scope="col">Location</th>
                <th scope="col">No Of Ticket</th>
                <th scope="col">Time Available</th>
                <th scope="col">Reverse Spot</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $result = $parking->getAllUserPark($_SESSION['client']['id']);
                    $int = 1;
                    foreach($result as $res){
                ?>
                    <tr>
                        <th scope="row"><?= $int ?></th>
                        <td><?= $res[0][0]['location'] ?></td>
                        <td><?= $res[1] ?></td>
                        <?php if ($res[0][0]['always_available'] == true) { ?>
                            <td><b>Always Available</b></td>
                        <?php } else { ?>
                            <td><?= $res[0][0]['time_available'] ?></td>
                        <?php } ?>
                        <td>
                            <form action="../utilities/handler/formHandler.php" method="post">
                                <input type="hidden" name="id" value="<?= $res[0][0]['id'] ?>">
                                <input type="hidden" name="noTicket" value="<?= $noTicket['tickets'] ?>">
                                <button type="submit" name="reverse" class="btn btn-primary btn-sm">Reverse</button>
                            </form>
                        </td>
                    </tr>
                <?php $int++; }  ?>
            </tbody>
        </table>

    </div>

<?php include_once('../utilities/includes/script.php') ?>
</body>
</html>