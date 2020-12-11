<?php
require_once('../utilities/classes/Action.class.php');
$action = new Action();
if (!isset($_SESSION['admin']['name'])) $action->redirect('../login.php');
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
    <h1>Welcome <?= $_SESSION['admin']['name'] ?></h1>

<?php include_once('../utilities/includes/script.php') ?>
</body>
</html>