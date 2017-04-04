<?php
header('Content-Type: application/json');

//connects to the databaseO
include('../login/api/database.php');
// $db = new PDO('mysql:host=localhost;dbname=money','root', '');	
// $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$naam = $_POST['naam'];

$sth = $db->prepare("INSERT INTO categorie (cat_naam) VALUES (:NAAM) ");
$sth->bindparam(":NAAM", $naam);
$sth->execute();

echo json_encode(array("success" => true));