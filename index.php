<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: login");
    exit();
} else {
    header("Location: dashboard");
    exit();
}
?>