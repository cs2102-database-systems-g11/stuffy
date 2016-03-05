<nav class="navbar navbar-default">
    <div class="container-fluid">
        <?php
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            if (isset($_SESSION['username'])) {
                include('partials/header_authed.html');
            } else {
                include('partials/header_public.html');
            }
        ?>
        <!-- Brand and toggle get grouped for better mobile display -->
    </div><!-- /.container-fluid -->
</nav>
