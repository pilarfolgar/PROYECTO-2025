<?php
session_start();
if (!isset($_SESSION['cedula'])) {
    header("location:iniciosesion.php");
    exit();
} else {
    $cedula= $_SESSION["cedula"];
    $usr= $_SESSION["nombrecompleto"];


}