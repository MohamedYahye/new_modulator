<?php
header('Content-Type: application/json');

//connects to the databaseO
include('api/database.php');
// $db = new PDO('mysql:host=localhost;dbname=money','root', '');	
// $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


$username = $_POST['username'];
$password = $_POST['password'];
// $email    = $_POST['email'];
$question = $_POST['question'];
$answer   = $_POST['answer'];


	//creates pepper
$pepper = 'K:Xa8GV}p[Fjr>8;Ez$z,3%b?L^*@M29';

$salt = hash("sha256", $_POST['username'] . 'r>8;Ez$z,3%b?L');

//hashes the pepper and salt
$hash = hash("sha256" , hash("sha256" , $_POST['password'] . $pepper) . $salt);
$seq_answer_hash = hash("sha256" , hash("sha256" , $_POST['answer'] . $pepper) . $salt);

//if everything is correct insert into databaseO.
//email, :EMAIL, $sth->bindparam(":EMAIL", $email);
$sth = $db->prepare("INSERT INTO login (username, hash, salt,  seq_question, seq_answer) VALUES (:USERNAME, :HASH, :SALT, :SQ, :SQA) ");
$sth->bindparam(":USERNAME", $username);
$sth->bindparam(":HASH", $hash);
$sth->bindparam(":SALT", $salt);
$sth->bindparam(":SQ", $question);
$sth->bindparam(":SQA", $seq_answer_hash);
$sth->execute();

echo json_encode(array("success" => true));