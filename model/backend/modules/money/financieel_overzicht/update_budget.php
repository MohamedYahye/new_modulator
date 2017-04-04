<?php
header('Content-Type: application/json');

//connects to the databaseO
include('../login/api/database.php');
// $db = new PDO('mysql:host=localhost;dbname=money','root', '');	
// $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// $uitgave = $_POST['uitgave'];
$bedrag = $_POST['bedrag'];
$budget = $_POST['budget'];
$user_id = $_POST['user_id'];
$type = $_POST['type'];

if($type == 'Uitgave'){
	$new_budget = $budget - $bedrag;
} else {
	$new_budget = $budget + $bedrag;
}

$sth = $db->prepare("UPDATE login
SET budget=".$new_budget."
WHERE id=".$user_id.";
");
$sth->execute();

echo json_encode(array("success" => true));;