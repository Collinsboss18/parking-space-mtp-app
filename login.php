<?php
    require_once('./utilities/classes/Action.class.php');
    $action = new Action();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Packing Space MTP App</title>
    <?php include_once('./utilities/includes/style.php') ?>
</head>
<body>
    <div class="container">
        <h1>Login</h1>
        <?php
            if(isset($_SESSION['msg'])) echo $_SESSION['msg'];
            $action->unsetMsg(); 
        ?>
        <form action="./utilities/handler/formHandler.php" method="post">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" name="email" id="email" placeholder="Enter email">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" name="password" id="password" placeholder="Password">
            </div>
            <button type="submit" name="login" class="btn btn-primary">Sign Up</button>
        </form>
    </div>
<?php include_once('./utilities/includes/script.php') ?>
</body>
</html>