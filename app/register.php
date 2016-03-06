<?php include('/functions.php'); ?>
<html>
    <?php include('head.html') ?>
    <?php
        $dbPassword = getenv("DB_PASSWORD");
        $dbconn = pg_connect("host=localhost port=5432 dbname=stuffy_db user=postgres
        password=" . $dbPassword)
        or die('Could not connect: ' . pg_last_error());
    ?>
    <body>
        <?php include('header.php') ?>
        <div class='content'>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Register</h3>
                </div>
                <div class="panel-body">
                    <form method='post'>
                        <div class="form-group">
                            <label for="input-username">Username</label>
                            <input type="text" name='username' class="form-control" id="input-username" placeholder="Username" required="true" autofocus>
                        </div>
                        <div class="form-group">
                            <label for="input-password">Password</label>
                            <input type="password" name='password' class="form-control" id="input-password" placeholder="Password" required="true">
                        </div>
                        <div class="form-group">
                            <label for="input-email">Email</label>
                            <input type="email" name='email' class="form-control" id="input-email" placeholder="Email" required="true">
                        </div>
                        <div class="form-group">
                            <label for="input-gender">Gender</label>
                            <div>
                                <label class="radio-inline">
                                    <input type="radio" name="gender" id="gender-radio-male" checked="true" value="M">Male</input>
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="gender" id="gender-radio-female" value="F">Female</input>
                                </label>
                            </div>
                        </div>
                        <button type="submit" name="register-submit" class="btn btn-default">Register</button>
                    </form>
                </div>
            </div>
        </div>
        <?php if(isset($_POST['register-submit']))
        {
            function validate_field_exists($dbconn, $column, $value) {
                $params = array($value);
                $query = "SELECT * FROM users WHERE " . $column . " = $1;";
                $result = pg_query_params($dbconn, $query, $params);
                if (pg_num_rows($result) > 0) {
                    create_notification('danger', ucFirst($column) . ' already exists.');
                    die();
                }
            }

            validate_field_exists($dbconn, 'username', $_POST['username']);
            validate_field_exists($dbconn, 'email', $_POST['email']);

            $params = array($_POST["email"], $_POST["username"], $_POST["password"], '', '', $_POST["gender"], '', '', '');
            $query = "INSERT INTO users VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9)";
            $result = pg_query_params($dbconn, $query, $params);
            if ($result) {
                echo "<script>redirect('/login.php?reg_success=1')</script>";
            } else {
                create_notification('danger', 'Registration error.');
                die("Query failed: " . pg_last_error());
            }
        }
        ?>
    </body>
    <?php
        pg_close($dbconn);
    ?>
</html>
