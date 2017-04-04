<?php
header('Content-Type: application/json');

//connects to the databaseO
include('../login/api/database.php');
// $db = new PDO('mysql:host=localhost;dbname=money','root', '');	
// $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$budget = $_POST['budget'];
$user_id= $_POST['user_id'];
//if everything is correct insert into databaseO.
//email, :EMAIL, $sth->bindparam(":EMAIL", $email);

$sth = $db->prepare("UPDATE login
SET budget=".$budget."
WHERE id=".$user_id.";
");
$sth->execute();

echo json_encode(array("success" => true));