<?php
//connects to the databaseO
include('../login/api/database.php');
// $db = new PDO('mysql:host=localhost;dbname=money','root', '');	
// $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

session_start();
$user_id =  $_SESSION['id'];

// $sth = $db->prepare("INSERT INTO categorie (cat_naam) VALUES (:NAAM) ");
// $sth->bindparam(":NAAM", $naam);
// $sth->execute();
$sth = $db->prepare('DELETE FROM login WHERE id='.$user_id.';');
$sth->execute();
$sth = $db->prepare('DELETE FROM overzicht WHERE user_id='.$user_id.';');
$sth->execute();
$sth = $db->prepare('DELETE FROM uitgaven WHERE user_id='.$user_id.';');
$sth->execute();

if($sth){
	header('Location: ../login/logout.php');
}