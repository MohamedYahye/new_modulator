<div id="buttons">
<link rel='stylesheet prefetch' href='http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css'>
<link rel="stylesheet" type="text/css" href="css/ActiveDeactive.css" />
<style>
button {
	width:12%

}
@media screen and (max-width:1000px){
	button {
		width: 33%;
	}
}
</style>
<?php
include 'init.php';

if(!empty($_GET['hash'])){
	$hash = $_GET['hash'];
	if(isset($_GET['reset'])){
		$userhash = $_GET['userHash'];
		$username = $_GET['username'];
		$password = 12345;

		$pepper = 'K:Xa8GV}p[Fjr>8;Ez$z,3%b?L^*@M29';
		$salt = hash("sha256", $username . 'r>8;Ez$z,3%b?L');

		//hashes the pepper and salt
		$hash = hash("sha256" , hash("sha256" , $password. $pepper) . $salt);

		$sql = "UPDATE login SET hash=:hash, salt=:salt WHERE username=:username";
		$query = $db->prepare($sql);
		$query->execute(array(
			":hash" => $hash,
			":salt" => $salt,
			":username" => $username
		));
		?>
		<div id="text"><p><?php echo $username ?> zijn/haar wachtwoord is gereset.</p>
		<a href="javascript:history.go(-1)"><input class="log-btn extra" type="submit" value="Terug"></a>
		<?php
	} else {
	?>
	<div id="buttons">
		<a class="buttonA" href="index.php?type=question"><button type="button" class="button button1">Terug</button></a>
	</div>
	<a class="button button1 logout" href="login/logout.php">Uitloggen</a>

	<?php

	$gethash = $db->prepare("SELECT * FROM login WHERE active < 2");
	$gethash->execute();
	$gethash = $gethash->fetchAll(PDO::FETCH_ASSOC);

	if(!empty($gethash)){

			?>

		<table style="	margin-top: 5%;">
		  <tr>
		    <th>Naam</th>
		    <th>Reset wachtwoord</th>
		  </tr>
		 </table>
		 <table class="paginated">
		  	<?php
		  		foreach($gethash as $user){
		  			$text = '<span class="textDiv"></span><a href="resetPW.php?reset=yes?&hash='.$hash.'&username='.$user['username'].'&userHash='.$user['hash'].'" style="text-decoration:none;color:black;"class="ja removeDiv fa fa-refresh" data-activate=deactivate data-hash='.$user['hash'].'></a>';

		  			echo '<tr>
		  					<td>'.$user['username'].'</td>
		  					<td>'.$text.'</td>
		  				</tr>';
		  		}
		  		?>
		</table>
			<?php
		} else {
				?> <div id="text"><p>er zijn geen gebruikers</p>
				<a class="buttonA" href="ActivateUser.php?hash=<?php echo $hash ?>"><button type="button" class="button button1">Terug</button></a>
					</div><?php
			}
		}
	}
} else {
	header('Location:index.php?type=question');
}

?>
</div>
<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
<script src="js/pagination.js"></script>