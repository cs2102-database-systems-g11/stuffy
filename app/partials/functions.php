<?php
    function redirect_if_unauthed() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['username'])) {
            header('Location: ' . '/login.php');
        }
    }
?>
