<?php
    session_start();
    session_unset();
    session_destroy();
    header("Location: ../Joemol-8912316/index.php");
    exit();
?>
