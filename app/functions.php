<?php
    function redirect_if_unauthed() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['username'])) {
            header('Location: ' . '/login.php');
        }
    }

    function create_notification($type, $msg) {
        echo "<script>$(document).ready(function() { notify('" . $type . "', '" . $msg . "'); });</script>";
    }

    function redirect($url) {
        echo "<script>redirect('" . $url . "');</script";
    }
?>
