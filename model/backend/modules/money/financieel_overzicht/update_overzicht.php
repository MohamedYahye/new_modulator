<?php
header('Content-Type: application/json');

//connects to the database
include('../login/api/database.php');
// $db = new PDO('mysql:host=localhost;dbname=money','root', '');	
// $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$uitgave = $_POST['uitgave'];
$bedrag = $_POST['bedrag'];
$datum = $_POST['datum'];
$categorie   = $_POST['categorie'];
$user_id = $_POST['user_id'];
$type = $_POST['type'];
if(isset($_POST['change_old'])) {
	$o_date = $_POST['o_date'];
	$o_cat_id = $_POST['o_cat_id'];
	$o_bedrag = $_POST['o_bedrag'];
}

$sth = $db->prepare('DELETE FROM overzicht WHERE totaal_bedrag="0.00"');
$sth->execute();

if($type == 'Inkomst') {
	if(isset($_POST['change_old'])) {

		$old_month = date("m",strtotime($o_date));
		$old_year = date("Y",strtotime($o_date));

		$old_insert_start = '01'.'-'.$old_month.'-'.$old_year;

		$old_start = $old_year.'-'.$old_month.'-'.'01';
		$old_end   = date("Y-m-t", strtotime($o_date));

		$query = $db->prepare('SELECT totaal_bedrag FROM overzicht WHERE categorie_id = '.$o_cat_id.'  AND maand BETWEEN "'.$old_start.'" AND "'.$old_end.'" AND user_id='.$user_id.'');
		$query->execute();
		$out = $query->fetch(PDO::FETCH_ASSOC);

		$new_total = $out['totaal_bedrag'] - $o_bedrag;

		$sth = $db->prepare('UPDATE overzicht
		SET totaal_bedrag='.$new_total.'
		WHERE categorie_id = '.$o_cat_id.' AND user_id='.$user_id.' AND maand BETWEEN "'.$old_start.'" AND "'.$old_end.'" AND user_id='.$user_id.';');
		$sth->execute();

	}
} else {
	if(isset($_POST['change_old'])) {

		$old_month = date("m",strtotime($o_date));
		$old_year = date("Y",strtotime($o_date));

		$old_insert_start = '01'.'-'.$old_month.'-'.$old_year;

		$old_start = $old_year.'-'.$old_month.'-'.'01';
		$old_end   = date("Y-m-t", strtotime($o_date));

		$query = $db->prepare('SELECT totaal_bedrag FROM overzicht WHERE categorie_id = '.$o_cat_id.'  AND maand BETWEEN "'.$old_start.'" AND "'.$old_end.'" AND user_id='.$user_id.'');
		$query->execute();
		$out = $query->fetch(PDO::FETCH_ASSOC);

		$new_total = $out['totaal_bedrag'] - $o_bedrag;

		$sth = $db->prepare('UPDATE overzicht
		SET totaal_bedrag='.$new_total.'
		WHERE categorie_id = '.$o_cat_id.' AND user_id='.$user_id.' AND maand BETWEEN "'.$old_start.'" AND "'.$old_end.'" AND user_id='.$user_id.';');
		$sth->execute();

	}

	$total = 0;

	$month = date("m",strtotime($datum));
	$year = date("Y",strtotime($datum));

	$start = $year.'-'.$month.'-'.'01';
	$insert_start = '01'.'-'.$month.'-'.$year;
	$end   = date("Y-m-t", strtotime($datum));

	$query = $db->prepare('SELECT bedrag , categorie FROM uitgaven WHERE categorie = '.$categorie.' AND datum BETWEEN "'.$start.'" AND "'.$end.'" AND user_id='.$user_id.'');
	$query->execute();
	$out = $query->fetchAll(PDO::FETCH_ASSOC);

	foreach($out as $number){
		$total = $total + $number['bedrag'];
	}
	$query = $db->prepare('SELECT * FROM overzicht WHERE maand = "'.$start.'" AND categorie_id = '.$categorie.' AND user_id = '.$user_id.'');
	$query->execute();
	$out = $query->fetch(PDO::FETCH_ASSOC);

	if($out){
		$sth = $db->prepare("UPDATE overzicht
		SET totaal_bedrag=".$total."
		WHERE categorie_id = ".$categorie." AND maand = '".$start."' AND user_id=".$user_id.";
		");
		$sth->execute();
	} else {
		add_new($categorie, $start, $total, $user_id);
	}
}
function add_new($categorie, $datum,$bedrag, $user_id){
	global $db;
	$sth = $db->prepare("INSERT INTO overzicht (categorie_id, maand, totaal_bedrag, user_id) VALUES (:CAT_ID, :DATUM, :TOTAAL, :U_ID) ");
	$sth->bindparam(":CAT_ID", $categorie);
	$sth->bindparam(":DATUM", $datum);
	$sth->bindparam(":TOTAAL", $bedrag);
	$sth->bindparam(":U_ID", $user_id);
	$sth->execute();
}
echo json_encode(array("success" => true));