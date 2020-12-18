<?php
$databasePath = '../utilities/classes/Database.class.php';
$encryptionPath = '../utilities/classes/Encryption.class.php';
$clientPath = '../utilities/classes/Client.class.php';

require_once('../utilities/classes/Action.class.php');
require_once('../utilities/classes/Admin.class.php');
require_once('../utilities/classes/Ticket.class.php');

$action = new Action();
$ticket = new Ticket();
$admin = new Admin();

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
            <h1>Welcome Admin <?= $_SESSION['client']['name'] ?></h1>
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
                    // var_dump($result);
                    $int = 1;
                    foreach($result as $res){
                ?>
                    <tr>
                        <th scope="row"><?= $int ?></th>
                        <td><?= $res['name'] ?></td>
                        <td><?= $res['email'] ?></td>
                        <td><?php
                            if($res['active'] == true) { echo 'User Active'; } else { echo 'User Disabled'; };
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

<?php include_once('../utilities/includes/script.php') ?>
</body>
</html>