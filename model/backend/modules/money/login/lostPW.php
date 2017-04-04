<?php
include 'api/database.php';
?>
<html>
<head>
<link rel="shortcut icon" type="image/x-icon" href="../euro.gif">
<title>Budgetify</title>
	<LINK href="css/loginCss.css" rel="stylesheet" type="text/css">

	<link rel='stylesheet prefetch' href='http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css'>

</head>
<body>
<img src="http://wallpapercave.com/wp/ffh1UYR.jpg" style="
    width: 100%;
    height: 100%;
">
<?php
	if(isset($_GET['go'])){
		$username = $_POST['username'];
		$getInfo = $db->prepare("SELECT * FROM login WHERE username = :username");
		$getInfo->execute(array(":username" => $username));
		$getInfo = $getInfo->fetch();

		if($_GET['go'] == 'true'){

			if(empty($getInfo)){
				?>
				<script type="text/javascript">
				  window.location="lostPW.php?username=empty"; 
				</script>

				<?php
			} else {
				?>
				<div class="login-form">

				<?php
				if(!$getInfo['seq_question']){
					echo '<h2 style="text-align:center;">Nog geen beveiligings vraag gezet!</h2>';
					echo '<h3 style="text-align:center;">Vraag de admin om uw wachtwoord te resetten</h3>';
					?><form action="loginForm.php" method="post">
						<input id="login"  class="log-btn" type="submit" value="Home">
					</form>
					<?php
				} else {
					

				?>
				<h1>Recover wachtwoord</h1>
				<form action="lostPW.php?go=check" method="post">
					<div class="form-group log-status-username log-status">
						<input type="text" class="form-control" name="username"  id="input_username" value="<?php echo $username ?>" readonly />
					  <i class="fa fa-user"></i>
					</div>
					<div class="form-group log-status">
						<input type="text" class="form-control" name="seq_question"  id="seq_question" value="<?php echo $getInfo['seq_question'] ?>" readonly />
					  <i class="fa fa-question-circle"></i>
					</div>
					<div class="form-group log-status">
					  <input type="password" class="form-control" placeholder="Antwoord" name="seq_answer" autocomplete="off" id="seq_answer">
					  <i class="fa fa-comment-o"></i>
					</div>
					<input id="login"  class="log-btn" type="submit" value="Recover">
				</form>
				<form action="lostPW.php">
				    <input class="log-btn extra" type="submit" value="Terug">
				</form>
				</div>
				<?php
				}
			}
			?>

		<?php
	} 
	if($_GET['go'] == 'check'){
		$pepper = 'K:Xa8GV}p[Fjr>8;Ez$z,3%b?L^*@M29';
		$hash = hash("sha256", $_POST['seq_answer'] . $pepper);
		$hash = hash("sha256", $hash . $getInfo['salt']);
		if($hash === $getInfo['seq_answer']){
		// if($_POST['seq_answer'] == $getInfo['seq_answer']){
			echo $_POST['seq_question'];
			echo $getInfo['seq_question'];
			?>
			<script type="text/javascript">
			console.log('gelukt');
			  window.location="information.php?action=ChangePW&lostpw=<?php echo $getInfo['salt']?>&hash=<?php echo $getInfo['hash']?>&salt=<?php echo $getInfo['salt']?>"; 
			</script>

			<?php
			// header('Location: tuintest.kweekvijvernoord.nl/test/information.php?action=ChangePW&lostpw='.$getInfo['salt'].'&hash='.$getInfo['hash'].'&salt='.$getInfo['salt'].'');
		} else {
			?>
				<?php $username = $_POST['username'] ?>
				<div class="login-form">
					<form action="lostPW.php?go=true" method="post">
					  <input type="text" name="username" autocomplete="off" id="input_username" value="<?php echo $username ?>" hidden>
						<h2 style="text-align: center;">Het gegeven antwoord is niet correct.</h2>
					    <input class="log-btn extra" type="submit" value="Opnieuw">
					</form>
				</div>
			<?php
		}
	}
} else {

?>
<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'>
	$('.form-control').keypress(function(){
		$('.log-status').removeClass('wrong-entry');
	});
</script>
<div class="login-form">
<h1 style="font-size: 40px;">Recover wachtwoord</h1>
<form action="lostPW.php?go=true" method="post">
	<div class="form-group log-status-username log-status">
	  <input type="text" class="form-control" placeholder="Gebruikersnaam " name="username" autocomplete="off" id="input_username">
	  <i class="fa fa-user"></i>
	</div>
	<span class="alert"></span>
	<input id="login"  class="log-btn" type="submit" value="Verder">
</form>
<form action="loginForm.php">
    <input class="log-btn extra" type="submit" value="Terug">
</form>
</div>
<?php 
	if(isset($_GET['username'])){
		if($_GET['username'] == 'empty'){
			?>
			<script>
				$('.log-status-username').addClass('wrong-entry');
				$(".alert").text("Gebruikersnaam bestaat niet.");
				$('.alert').fadeIn(1500);
				setTimeout( "$('.alert').fadeOut(1500);",3000 );
				usernameAvailable = false;
			</script>
			<?php
		} 
	}
?>
<?php

}
?>
</body>
</html>