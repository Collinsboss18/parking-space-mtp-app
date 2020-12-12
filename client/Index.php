<?php
$databasePath = '../utilities/classes/Database.class.php';

require_once('../utilities/classes/Action.class.php');
require_once('../utilities/classes/Parking.class.php');
require_once('../utilities/classes/Ticket.class.php');

$action = new Action();
$parking = new Parking();
$ticket = new Ticket();
if (!isset($_SESSION['client']['name'])) $action->redirect('../login.php');
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

        <h3>Parking Space</h3>
        <table class="table">
            <thead>
                <tr>
                <th scope="col">#</th>
                <th scope="col">Location</th>
                <th scope="col">Price</th>
                <th scope="col">Available tickets</th>
                <th scope="col">Availability</th>
                <th scope="col">Buy Ticket</th>
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
                        <td><?= $res['price'] ?></td>
                        <td><?= $res['available_ticket'] ?></td>
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
                                <button type="submit" name="buy" class="btn btn-primary btn-sm">Buy</button>
                            </form>
                        </td>
                        <?php } else { ?>
                        <td>Currently not available</td>
                        <?php } ?>
                    </tr>
                <?php $int++; }  ?>
            </tbody>
        </table>

        <h3>My Tickets</h3>
        <table class="table">
            <thead>
                <tr>
                <th scope="col">#</th>
                <th scope="col">Location</th>
                <th scope="col">Price</th>
                <th scope="col">No of ticket</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $result = $ticket->getTicketByClientId($_SESSION['client']['id']);
                    $int = 1;
                    foreach($result as $res){
                ?>
                <tr>
                    <th scope="row"><?= $int ?></th>
                    <td><?= $res['location'] ?></td>
                    <td><?= $res['price'] ?></td>
                </tr>
                <?php $int++; }  ?>
            </tbody>
        </table>

    </div>

<?php include_once('../utilities/includes/script.php') ?>
</body>
</html>