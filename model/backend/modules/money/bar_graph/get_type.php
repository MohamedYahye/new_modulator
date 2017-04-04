<?php
header('Content-Type: application/json');

if(empty($_POST['type'])){
 $type = 'month';
 } else {
   $type = $_POST['type'];
}

session_start();
//sets session
$_SESSION['type'] = $type;

echo json_encode(array("success" => true, "type" => $type));

