<?php include('/functions.php'); ?>
<html>
    <?php include('head.html') ?>
    <body>
        <?php include('header.php') ?>
        <div class='content'>
            <div class="panel panel-default">
                <div class="panel-body">
                    nothing here
                </div>
            </div>
        </div>
        <?php if (isset($_GET['logged_out'])) {
            create_notification('success', 'Logged out.');
        } ?>
    </body>
</html>
