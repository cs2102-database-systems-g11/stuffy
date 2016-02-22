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
                            <input type="text" name='username' class="form-control" id="input-username" placeholder="Username" required="true">
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
                        <button type="submit" name="register_submit" class="btn btn-default">Register</button>
                    </form>
                </div>
            </div>
        </div>
        <?php if(isset($_POST['register_submit']))
        {
            $params = array($_POST["username"], $_POST["password"], $_POST["email"], $_POST["gender"], '');
            $query = "INSERT INTO users VALUES ($1, $2, $3, $4, $5)";
            $result = pg_query_params($dbconn, $query, $params) or die("Query failed: " . pg_last_error());
        }
        ?>
    </body>
    <?php
        pg_close($dbconn);
    ?>
</html>
