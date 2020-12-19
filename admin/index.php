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

        <div class="col-md-8">
            <?php if (isset($_SESSION['amsg'])) { ?>
                <div class="alert alert-secondary" role="alert">
                    <?php 
                        echo $_SESSION['amsg'];
                        $action->unsetMsg(); 
                    ?>
                </div>
            <?php } ?>
            <br>

            <h3>Available Clients</h3>
            <table class="table">
                <thead>
                    <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
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
                            <td><?php
                                if($res['is_active'] == true) { echo 'User Active'; } else { echo 'User Disabled'; };
                            ?></td>
                            <td>
                                <form action="../utilities/handler/formHandler.php" method="post">
                                    <input type="hidden" name="clientId" value="<?= $res['id'] ?>">
                                    <button type="text" name="tActive" class="btn btn-danger btn-sm">Toggle</button>
                                </form>
                            </td>
                        </td>
                        </tr>
                    <?php $int++; }  ?>
                </tbody>
            </table>
        </div>

    </div>

<?php include_once('../utilities/includes/script.php') ?>
</body>
</html>