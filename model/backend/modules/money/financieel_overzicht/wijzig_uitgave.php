<?php
header('Content-Type: application/json');

//connects to the database
include('../login/api/database.php');
// $db = new PDO('mysql:host=localhost;dbname=money','root', '');	
// $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// $db = new PDO('mysql:host=localhost;dbname=a9266710_money','a9266710_budget', '');
// $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// $query = 0;


$naam = $_POST['naam'];
$bedrag = $_POST['bedrag'];
$datum = $_POST['datum'];
$cat = $_POST['cat'];
$type = $_POST['type'];

$o_bedrag = $_POST['o_bedrag'];
$budget = $_POST['budget'];

$uitgave_id = $_POST['id'];
$user_id = $_POST['user'];

if($type == 'Uitgave'){
	$budget = $budget + $o_bedrag - $bedrag;
} else if($type == 'Inkomst') {
	$budget = $budget - $o_bedrag + $bedrag;
}

//update budget: huidig budget -/+ oud bedrag (op basis van type) -/+ nieuw bedrag (op basis van type)
//update uitgave

$sth = $db->prepare('UPDATE uitgaven
SET naam ="'.$naam.'", 
bedrag = "'.$bedrag.'",
categorie = "'.$cat.'",
datum = "'.$datum.'",
type = "'.$type.'"
WHERE id='.$uitgave_id.' AND user_id = '.$user_id.'');
$sth->execute();

$update_budget = $db->prepare("UPDATE login
SET budget=".$budget."
WHERE id=".$user_id.";
");
$update_budget->execute();

echo json_encode(array("success" => true));;