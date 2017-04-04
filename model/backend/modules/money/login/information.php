<?php
session_start();

?>
<html>
<head>
	<title>Account gegevens</title>
	<LINK href="css/loginCss.css" rel="stylesheet" type="text/css">
	<LINK href="css/ActiveDeactive.css" rel="stylesheet" type="text/css">

	<link rel='stylesheet prefetch' href='http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css'>

</head>
<body>
<?php 
error_reporting(E_ERROR | E_WARNING | E_PARSE);

include 'api/database.php';

$hash = $_GET['hash'];
$sql = $db->prepare("SELECT * FROM login WHERE hash = :hash");
$sql->execute(array(":hash" => $hash));
$sql = $sql->fetch();

?>	
	<div class="login-form" style="text-align: center">
	 
	<?php 
	if(!empty($_GET['action'])){
		?>
		<h1 style="font-size: 35px;">Verander wachtwoord</h1>
			<?php
		if($_GET['action'] == 'ChangePW'){
			if(!empty($_GET['lostpw'])){
				if($_GET['lostpw'] == $sql['salt']){
					$lostpwhashsalt = $_GET['lostpw'];
					?>
					<form action="information.php?action=verander&hash=<?php echo $hash ?>&lostpw=<?php echo $lostpwhashsalt ?>" method="post">
					<div class="form-group log-status-pwd log-status">
					<input type="hidden" class="form-control" placeholder="Wachtwoord" name="password" id="input_password" value="<?php // ?>"><?php
				}
			} else {
				?>
				<form action="information.php?action=verander&hash=<?php echo $hash ?>" method="post">
				<div class="form-group log-status-pwd log-status">
				<input type="password" class="form-control" placeholder="Wachtwoord" name="password" id="input_password">
				<i class="fa fa-lock" aria-hidden="true"></i><?php
			}
			?>
				 <i class="fa fa-lock"></i>
				</div>
				<div class="form-group log-status-pwd log-status">
				  <input type="password" class="form-control" placeholder="Nieuw wachtwoord" name="password_new" id="input_password">
				  <i class="fa fa-lock"></i>
				</div>
				<div class="form-group log-status-pwd log-status">
				  <input type="password" class="form-control" placeholder="Nieuw wachtwoord opnieuw" name="password_new_again" id="input_password">
				  <i class="fa fa-lock"></i>
				</div>
				<div id="centerSpan" style="text-align: center;">
				<span class="alert"></span>
				</div>

				<input id="login"  class="log-btn" type="submit" value="Wijzigen">

			</form>
			<?php 
				$user_id = 	$_SESSION['id'];

				if(!isset($user_id)){
					?><form action="loginForm.php">
						<input class="log-btn extra" type="submit" value="Login scherm"></form><?php
				} else {
					?><a href="javascript:history.go(-1)"><input class="log-btn extra" type="submit" value="Terug"></a><?php
				}

		}
	} 
	if(!empty($_GET['action']) && $_GET['action'] == 'verander'){
		$pepper = 'K:Xa8GV}p[Fjr>8;Ez$z,3%b?L^*@M29';
		$password = $_POST['password'];

		$passwordHash = hash("sha256", $password . $pepper);
		$passwordHash = hash("sha256", $passwordHash . $sql['salt']);

		if($passwordHash === $sql['hash'] || $_GET['lostpw'] == $sql['salt']){
			if($_POST['password_new'] == $_POST['password_new_again']){
				if(strlen($_POST['password_new']) >= 5){
					$NewSalt = hash("sha256", $sql['username'] . 'r>8;Ez$z,3%b?L');
					$NewHash = hash("sha256" , hash("sha256" , $_POST['password_new'] . $pepper) . $NewSalt);

					$query= $db->prepare('UPDATE login SET salt=:SALT, hash=:HASH WHERE hash=:HASHOLD');
					$query->bindparam(":SALT",$NewSalt);
					$query->bindparam(":HASH",$NewHash);
					$query->bindparam(":HASHOLD",$hash);
					$query->execute();

					?>
					<h3 style="text-align: center;">Wachtwoord is veranderd!</h3>
					<?php 
					if(isset($_GET['lostpw'])){
						?>
						<form action="loginForm.php">
						    <input class="log-btn extra" type="submit" value="Inloggen">
						</form>
						<?php
					} else {
						?>
						<form action="index.php?type=question" method="post">
						    <input class="log-btn extra" type="submit" value="Terug">
						</form>
						<?php
					}
				} else {
					?>
						<h3 style="text-align: center;">Wachtwoord moet minimaal 5 charachters lang zijn.</h3>
						<a href="javascript:history.go(-1)"><input class="log-btn extra" type="submit" value="Opnieuw"></a>
					<?php
				}
			} else {
				?>
				
				<h3 style="text-align: center;">Wachtwoorden zijn niet gelijk!</h3>
				<a href="javascript:history.go(-1)"><input class="log-btn extra" type="submit" value="Opnieuw"></a>
				<?php
		}
	} else {
		echo 'Wachtwoord is onjuist';
		echo '<br />';
		?><br /><br /><a href="javascript:history.go(-1)"><input class="log-btn extra" type="submit" value="Opnieuw proberen"></a><?php
	}
}

	?>
	</div>
	</body>
</html>