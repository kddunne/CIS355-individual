<?php
//Filename: reqLogin.php, Kyle Dunne, CIS355, 2015-04-20
//This file is included in a page to prevent access 
//      to a page without logging in
    session_start();
    $sess_id = "loggedin";
    if ($_SESSION["id"]!=$sess_id)
    header("Location: login.php");
?>

