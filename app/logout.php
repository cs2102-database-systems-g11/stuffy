<html>
    <?php include('head.html') ?>
    <body>
        <?php include('header.php') ?>
        <div class='content'>
            <?php
                session_destroy();
                echo "<script>redirect('/?logged_out=1')</script>";
            ?>
            <div class='alert alert-success' role='alert'>Logged out.</div>
        </div>
    </body>
</html>
