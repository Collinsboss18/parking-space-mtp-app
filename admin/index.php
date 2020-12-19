<?php
$databasePath = '../utilities/classes/Database.class.php';
$encryptionPath = '../utilities/classes/Encryption.class.php';
$clientPath = '../utilities/classes/Client.class.php';
$spacePath = '../utilities/classes/Space.class.php';

require_once('../utilities/classes/Action.class.php');
require_once('../utilities/classes/Admin.class.php');
require_once('../utilities/classes/Space.class.php');
require_once('../utilities/classes/Client.class.php');
require_once('../utilities/classes/Location.class.php');
require_once('../utilities/classes/Ticket.class.php');

$action = new Action();
$admin = new Admin();
$client = new Client();
$space = new Space();
$location = new Location();
$ticket = new Ticket();

if (!isset($_SESSION['admin']['name'])) $action->redirect('../adminLogin.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <?php include_once('../utilities/includes/style.php') ?>
</head>
<body>
    <div class="container">
        <div class="m-5" />
        <div class="jumbotron">
            <h1>Welcome <?= $_SESSION['admin']['name'] ?></h1>
            <p class="lead">
                Welcome <?= $_SESSION['admin']['name'] ?> to Packing Space MTP App. You can buy tickets, book and view available packing space.
            </p>
            <form action="../utilities/handler/formHandler.php" method="post">
                <button class="btn btn-danger btn-sm" name='logout'>Logout</button>
            </form>
        </div>

        </br>

        <?php if (isset($_SESSION['amsg'])) { ?>
            <div class="alert alert-secondary" role="alert">
                <?php 
                    echo $_SESSION['amsg'];
                    $action->unsetMsg(); 
                ?>
            </div>
        <?php } ?>
        <div class='row'>    
            <div class="col col-md-8">
                <br>

                <h3>Available Clients</h3>
                <table class="table">
                    <thead>
                        <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">No of ticket</th>
                        <th scope="col">Status</th>
                        <th scope="col">Toggle Active</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $result = $admin->getAllClients();
                            $int = 1;
                            foreach($result as $res){
                        ?>
                            <tr>
                                <th scope="row"><?= $int ?></th>
                                <td><?= $res['name'] ?></td>
                                <td><?= $res['email'] ?></td>
                                <td>
                                    <form action="../utilities/handler/formHandler.php" method="post">
                                        <input type="hidden" name="clientId" value="<?= $res['id'] ?>">
                                        <input type="number" name="ticket" value='<?= $res['no_ticket'] ?>' required />
                                        <input type="submit" value="change" name='uTicket' class="btn btn-primary btn-sm">
                                    </form>
                                </td>
                                <td><?php
                                    if($res['is_active'] == true) { echo 'User Active'; } else { echo 'User Disabled'; };
                                ?></td>
                                <td>
                                    <form action="../utilities/handler/formHandler.php" method="post">
                                        <input type="hidden" name="clientId" value="<?= $res['id'] ?>">
                                        <button type="submit" name="tActive" class="btn btn-danger btn-sm">Toggle</button>
                                    </form>
                                </td>
                            </td>
                            </tr>
                        <?php $int++; }  ?>
                    </tbody>
                </table>

                </br>

                <h3>Parking Locations</h3>
                <table class="table">
                    <thead>
                        <tr>
                        <th scope="col">#</th>
                        <th scope="col">Locations</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $int = 1;
                            $locs = $location->getLocations();
                            foreach ($locs as $loc) { 
                            ?>
                            <tr>
                                <th scope="row"><?= $int ?></th>
                                <td><?= $loc['name'] ?> </td>
                            </tr>
                        <?php $int++; }  ?>
                    </tbody>
                </table>

                </br>

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
                                            <button type="submit" name="book" class="btn btn-warning btn-sm disabled" title='You have no ticket' disabled>No Ticket</button>
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
            </div>
            <div class="col col-md-4">
                </br>
                <h3>Create park location</h3>
                <form action="../utilities/handler/formHandler.php" method="post">
                    <div class="form-group">
                        <label for="location">Location</label>
                        <input type="text" class='form-control' name='location' placeholder="eg. Center Park Kubwa">
                        <small>Create parking location</small>
                    </div>
                    <input type="submit" name='cLocation' class="btn btn-warning btn-sm" value="Create Location">
                </form>
                </br>
                <h3>Create parking spots</h3>
                <form action="../utilities/handler/formHandler.php" method="post">
                <div class="form-group">
                    <label for="location">Location</label>
                    <select class="form-control" name="location">
                        <option value="">Select Location</option>
                        <?php $locations = $location->getLocations();
                            foreach ($locations as $location) { ?>
                            <option value="<?= $location['id'] ?>"><?= $location['name'] ?></option>
                        <?php } ?>
                        </select>
                        <small>Select location</small>
                    </div>
                    <div class="form-group">
                        <label for="spot">Spot Number</label>
                        <input type="number" class='form-control' name='spot' placeholder="27">
                        <small>Create parking spot</small>
                    </div>
                    <input type="submit" name='cSpot' class="btn btn-warning btn-sm" value="Create Spot">
                </form>
            </div>
        </div>

    </div>

<?php include_once('../utilities/includes/script.php') ?>
</body>
</html>