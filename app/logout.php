<?php include('./functions.php'); ?>
<html>
    <?php include('head.html') ?>
    <body>
        <?php include('header.php') ?>
        <div class='content'>
            <?php
                session_destroy();
                redirect('/?logged_out=1');
            ?>
            <div class='alert alert-success' role='alert'>Logged out.</div>
        </div>
    </body>
</html>
