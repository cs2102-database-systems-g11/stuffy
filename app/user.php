<?php include('/functions.php'); ?>
<html>
    <?php include('head.html') ?>
    <?php
        $dbPassword = getenv("DB_PASSWORD");
        $dbconn = pg_connect("host=localhost port=5432 dbname=stuffy_db user=postgres
        password=" . $dbPassword)
        or die('Could not connect: ' . pg_last_error());
    ?>
    <?php
        $username = '';
        $name = '';
        $gender = '';
        $description = '';
        $exists = true;

        if (isset($_GET['user'])) {
            $username = $_GET['user'];
        } else {
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            $username = $_SESSION['username'];
        }

        $params = array($username);
        $query = "SELECT * FROM users WHERE username = $1;";
        $result = pg_query_params($dbconn, $query, $params) or die("Query failed: " . pg_last_error());
        if (pg_num_rows($result) > 0) {
            $row = pg_fetch_array($result);
            $firstName = $row['first_name'];
            $lastName = $row['last_name'];
            $name = $firstName . ' ' . $lastName;
            $name = trim($name) != '' ? $name : '(unset)';
            $gender = $row['gender'] == 'M' ? 'Male' : 'Female';
            $description = $row['description'] != '' ? $row['description'] : '(unset)';
        } else {
            $exists = false;
        }
    ?>

    <body>
        <?php include('header.php') ?>
        <div class='content'>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Profile for <?php echo $username ?></h3>
                </div>
                <div class="panel-body">
                    <div class="form-horizontal user-profile">
                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">Name: </label>
                            <div class="col-sm-10 form-group-content">
                                <?php echo $name ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="gender" class="col-sm-2 control-label">Gender: </label>
                            <div class="col-sm-10 form-group-content">
                                <?php echo $gender ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="description" class="col-sm-2 control-label">Description: </label>
                            <div class="col-sm-10 form-group-content">
                                <?php echo $description ?>
                            </div>
                        </div>
                    </div>
                    <a class="btn btn-default" href="/editprofile.php" role="button">Edit Profile</a>
                </div>
            </div>
            <?php 
                if (!$exists) {
                    echo "<script>document.querySelector('.panel').remove();</script>";
                    create_notification('danger', 'User does not exist.');
                }
            ?>
        </div>
    </body>
</html>
