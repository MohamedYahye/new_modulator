<?php
header('Content-Type: application/json');

//connects to the databaseO
include('../login/api/database.php');
// $db = new PDO('mysql:host=localhost;dbname=money','root', '');	
// $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$uitgave = $_POST['uitgave'];
$bedrag = $_POST['bedrag'];
$datum = $_POST['datum'];
$categorie   = $_POST['categorie'];
$user_id = $_POST['user_id'];
$type = $_POST['type'];
//if everything is correct insert into databaseO.

$sth = $db->prepare("INSERT INTO uitgaven (naam, bedrag, categorie, datum, user_id, type) VALUES (:NAAM, :BEDRAG, :CATEGORIE, :DATUM, :U_ID, :TYPE) ");
$sth->bindparam(":NAAM", $uitgave);
$sth->bindparam(":BEDRAG", $bedrag);
$sth->bindparam(":CATEGORIE", $categorie);
$sth->bindparam(":DATUM", $datum);
$sth->bindparam(":U_ID", $user_id);
$sth->bindparam(":TYPE", $type);
$sth->execute();

echo json_encode(array("success" => true));