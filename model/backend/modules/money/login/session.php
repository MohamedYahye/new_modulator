<?php
session_start();
$user_id = 	$_SESSION['id'];

//if the session is not set (user is not logged in) go back to the login form.
if(!isset($_SESSION['id'])) {
	header('Location: ../login/loginForm.php');
	die();
}