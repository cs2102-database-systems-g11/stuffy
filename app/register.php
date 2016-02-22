<html>
    <?php include('head.html') ?>
    <body>
        <?php include('header.php') ?>
        <div class='content'>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Register</h3>
                </div>
                <div class="panel-body">
                    <form>
                        <div class="form-group">
                            <label for="input-username">Username</label>
                            <input type="email" class="form-control" id="input-username" placeholder="Username">
                        </div>
                        <div class="form-group">
                            <label for="input-password">Password</label>
                            <input type="password" class="form-control" id="input-password" placeholder="Password">
                        </div>
                        <div class="form-group">
                            <label for="input-email">Email</label>
                            <input type="email" class="form-control" id="input-email" placeholder="Email">
                        </div>
                        <div class="form-group">
                            <label for="input-gender">Gender</label>
                            <div>
                                <label class="radio-inline">
                                    <input type="radio" name="gender-radio-options" id="gender-radio-male" value="M">Male</input>
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="gender-radio-options" id="gender-radio-female" value="F">Female</input>
                                </label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-default">Register</button>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>
