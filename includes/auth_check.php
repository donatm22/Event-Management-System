<?php
session_start();

if(!isset($_SESSION["user_Id"])){
    die("Qasje e pa autorizuar!");
}

?>