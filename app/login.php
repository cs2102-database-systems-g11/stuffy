<?php include('./functions.php'); ?>
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
                    <h3 class="panel-title">Login</h3>
                </div>
                <div class="panel-body">
                    <form method='post'>
                        <div class="form-group">
                            <label for="input-username">Username</label>
                            <input type="text" name='username' class="form-control" id="input-username" placeholder="Username" autofocus>
                        </div>
                        <div class="form-group">
                            <label for="input-password">Password</label>
                            <input type="password" name='password' class="form-control" id="input-password" placeholder="Password">
                        </div>
                        <button type="submit" name='login-submit' class="btn btn-default">Login</button>
                    </form>
                </div>
            </div>
        </div>

        <?php if (isset($_GET['reg_success'])) {
            create_notification('success', 'Registration successful.');
        } ?>
        <?php if(isset($_POST['login-submit']))
        {
            $params = array($_POST["username"], $_POST["password"]);
            $query = "SELECT * FROM users WHERE username = $1 AND password = $2;";
            $result = pg_query_params($dbconn, $query, $params) or die("Query failed: " . pg_last_error());
            if (pg_num_rows($result) > 0) {
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION['username'] = $params[0];
                redirect('/');
            } else {
                create_notification('success', 'Invalid username or password.');
            }
        }
        ?>
    </body>
</html>
