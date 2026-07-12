<?php
session_start();
if (!isset($_SESSION['id'])) {
 header("Location: loginApartamento");
 exit();
} else {
 header("Location: apartamento");
 exit();
}
