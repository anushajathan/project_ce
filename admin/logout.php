<?php
//Start session
session_start();

//Remove user data from session
unset($_SESSION['admin_name']);

//Destroy all session data
session_destroy();

//Redirect to the homepage
header("Location:index.php");
?>