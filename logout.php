<?php	
//Filename: logout.php, Kyle Dunne, CIS355, 2015-04-20
//This file is used to log out a user
//Code adapted from Multifile CRUD example done in class

session_start();   //  Must start a session before destroying it

if (isset($_SESSION))
{
    unset($_SESSION);
    session_unset();
    session_destroy();
    header('Location: index.php');
}
?>
