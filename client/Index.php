<?php
require_once('../utilities/classes/Action.class.php');
$action = new Action();
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

        <!-- <table class="table">
            <thead>
                <tr>
                <th scope="col">#</th>
                <th scope="col">Location</th>
                <th scope="col">Price</th>
                <th scope="col">Available tickets</th>
                <th scope="col">Availability</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th scope="row">1</th>
                    <td>Mark</td>
                    <td>Otto</td>
                    <td>@mdo</td>
                </tr>
            </tbody>
        </table> -->

    </div>

<?php include_once('../utilities/includes/script.php') ?>
</body>
</html>