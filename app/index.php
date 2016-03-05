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
            echo "<script>notify('success', 'Logged out.');</script>";
        } ?>
    </body>
</html>
