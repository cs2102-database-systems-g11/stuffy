<?php 
    include('/functions.php');
    redirect_if_unauthed();
?>
<html>
    <?php include('head.html') ?>
    <?php
        $dbPassword = getenv("DB_PASSWORD");
        $dbconn = pg_connect("host=localhost port=5432 dbname=stuffy_db user=postgres
        password=" . $dbPassword)
        or die('Could not connect: ' . pg_last_error());
    ?>

    <?php 
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $username = $_SESSION['username'];
        
        // on profile update
        if(isset($_POST['update-profile-submit'])) {
            $params = array($_POST["first-name"], $_POST["last-name"], $_POST["gender"], $_POST["description"], $username);
            $query = "UPDATE users SET first_name = $1, last_name = $2, gender = $3, description = $4 WHERE username = $5;";
            $result = pg_query_params($dbconn, $query, $params);
            if ($result) {
                create_notification('success', 'Profile updated!');
            } else {
                die("Query failed: " . pg_last_error());
            }
        }

        // on email update
        if(isset($_POST['update-email-submit'])) {
            $password = trim($_POST['confirm-password']);
            $email = trim($_POST['email']);
            $valid = true;

            if ($email == '') {
                create_notification('warning', 'Select a new email address.');
                $valid = false;
            } else if ($password == '') {
                create_notification('warning', 'Confirm your current password.');
                $valid = false;
            }

            // check if new email is unused
            if ($valid) {
                $params = array($email);
                $query = 'SELECT * from users WHERE email = $1';
                $result = pg_query_params($dbconn, $query, $params);
                if (pg_num_rows($result) > 0) {
                    create_notification('danger', 'Email is already being used.');
                    $valid = false;
                }
            }

            // check if password is correct
            if ($valid) {
                $params = array($password, $username);
                $query = 'SELECT * from users WHERE password = $1 AND username = $2';
                $result = pg_query_params($dbconn, $query, $params);
                if (pg_num_rows($result) == 0) {
                    create_notification('danger', 'Password is not correct.');
                    $valid = false;
                }
            }

            // finally update the row if all fields are validated
            if ($valid) {
                $params = array($email, $password, $username);
                $query = "UPDATE users SET email = $1 WHERE password = $2 AND username = $3";
                $result = pg_query_params($dbconn, $query, $params);

                if ($result) {
                    create_notification('success', 'Email updated!');
                } else {
                    die("Query failed: " . pg_last_error());
                }
            }
        }

        // on password update
        if(isset($_POST['update-password-submit'])) {
            $params = array($_POST["current-password"], $username);
            $query = "SELECT * FROM users WHERE password = $1 AND username = $2";
            $result = pg_query_params($dbconn, $query, $params);

            if (pg_num_rows($result) > 0) {
                $params = array($_POST["new-password"], $username);
                $query = "UPDATE users SET password = $1 WHERE username = $2";
                $result = pg_query_params($dbconn, $query, $params);
                if ($result) {
                    create_notification('success', 'Password updated!');
                } else {
                    die("Query failed: " . pg_last_error());
                }
            } else {
                create_notification('danger', 'Current password is not correct.');
            }

        }

        $firstName = '';
        $lastName = '';
        $gender = '';
        $description = '';

        $params = array($username);
        $query = "SELECT * FROM users WHERE username = $1;";
        $result = pg_query_params($dbconn, $query, $params) or die("Query failed: " . pg_last_error());
        if (pg_num_rows($result) > 0) {
            $row = pg_fetch_array($result);
            $firstName = $row['first_name'];
            $lastName = $row['last_name'];
            $gender = $row['gender'];
            $description = $row['description'];
        }
    ?>

    <body>
        <?php include('header.php') ?>
        <div class='content'>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Update profile details</h3>
                </div>
                <div class="panel-body">
                    <form method='post' class="form-horizontal user-profile">
                        <div class="form-group">
                            <label for="first-name" class="col-sm-2 control-label">First Name: </label>
                            <div class="col-sm-10">
                            <input type="text" name='first-name' class="form-control" value='<?php echo $firstName ?>' id="input-first-name" placeholder="First Name">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="last-name" class="col-sm-2 control-label">Last Name: </label>
                            <div class="col-sm-10">
                            <input type="text" name='last-name' class="form-control" value='<?php echo $lastName ?>' id="input-last-name" placeholder="Last Name">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="gender" class="col-sm-2 control-label">Gender: </label>
                            <div class="col-sm-10">
                                <label class="radio-inline">
                                <input type="radio" name="gender" <?php echo $gender == 'M' ? 'checked="true"' : ''; ?> id="gender-radio-male" value="M">Male</input>
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="gender" <?php echo $gender == 'F' ? 'checked="true"' : ''; ?> id="gender-radio-female"  value="F">Female</input>
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="description" class="col-sm-2 control-label">Description: </label>
                            <div class="col-sm-10 form-group-content">
                            <textarea type="text" name='description' class="form-control" id="input-description" placeholder="Description"><?php echo $description ?></textarea>
                            </div>
                        </div>
                        <button class="btn btn-default" name='update-profile-submit' type="submit">Update Profile</button>
                    </form>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Update email</h3>
                </div>
                <div class="panel-body">
                    <form method='post' class="form-horizontal user-profile">
                        <div class="form-group">
                            <label for="email" class="col-sm-3 control-label">Email: </label>
                            <div class="col-sm-9">
                                <input type="email" name='email' class="form-control" id="input-email" placeholder="Email address">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="confirm-password" class="col-sm-3 control-label">Confirm Password: </label>
                            <div class="col-sm-9">
                                <input type="password" name='confirm-password' class="form-control" id="input-confirm-password" placeholder="Current password">
                            </div>
                        </div>
                        <button class="btn btn-default" name='update-email-submit' type="submit">Update Email</button>
                    </form>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Update password</h3>
                </div>
                <div class="panel-body">
                    <form method='post' class="form-horizontal user-profile">
                        <div class="form-group">
                            <label for="current-password" class="col-sm-3 control-label">Current Password: </label>
                            <div class="col-sm-9">
                                <input type="password" name='current-password' class="form-control" id="input-current-password" placeholder="Current password">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="new-password" class="col-sm-3 control-label">New Password: </label>
                            <div class="col-sm-9">
                                <input type="password" name='new-password' class="form-control" id="input-new-password" placeholder="New password">
                            </div>
                        </div>
                        <button class="btn btn-default" name='update-password-submit' type="submit">Update Profile</button>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>
