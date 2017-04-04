<html>
<head>
	<link rel="shortcut icon" type="image/x-icon" href="../euro.gif">
	<title>Budgetify</title>
	<LINK href="css/loginCss.css" rel="stylesheet" type="text/css">

	<link rel='stylesheet prefetch' href='http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css'>

</head>
<body>
<!-- <img src="http://wallpapercave.com/wp/ffh1UYR.jpg" style="
    width: 100%;
    height: 98%;
">
 -->	<div class="login-form">
	<h1>Inloggen</h1>
	<form action="login.php" method="post" autocomplete="off">
		<div class="form-group log-status-username log-status">
		  <input type="text" class="form-control" placeholder="Gebruikersnaam " name="username" autocomplete="off" id="input_username">
		  <i class="fa fa-user"></i>
		</div>
		<div class="form-group log-status-pwd log-status">
		  <input type="password" class="form-control" placeholder="Wachtwoord" name="password" id="input_password">
		  <i class="fa fa-lock"></i>
		</div>
		<a class="link" href="lostPW.php">Wachtwoord vergeten?</a>

		<div id="centerSpan" style="text-align: center;">
		<span class="alert"></span>
		</div>

		<input id="login"  class="log-btn" type="submit" value="Inloggen">

	</form>
	<form action="registerForm.php">
	    <input class="log-btn extra" type="submit" value="Registreren">
	</form>
	</div>
	<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>

	<script type="text/javascript">
		(function() {

			$('#login').on('click', function(e) {
				e.preventDefault();
				var inputUsername = $('#input_username').val();
				var inputPassword = $('#input_password').val();

				var login_data = {
					username: inputUsername,
					password: inputPassword
				};


				$('.form-control').keypress(function(){
					$('.log-status').removeClass('wrong-entry');
				});


				$.ajax({
					type: 'POST',
					url: 'login.php',
					data: login_data,
					success: function(data, status) {
						if(data.success) {
							console.log('inloggen');
							//window.location.href = "../index.php?type=question";
							window.location.href = "../home.php";

						} else {
							if(data.errorCode === 1) {
								// User does not exist.
								$('.log-status-username').addClass('wrong-entry');
								$(".alert").text(data.error);
								$('.alert').fadeIn(1500);
								setTimeout( "$('.alert').fadeOut(1500);",3000 );
							} else if(data.errorCode === 3){
								$('.login-form').addClass('wrong-entry');
								$(".alert").text(data.error);
								$('.alert').fadeIn(1500);
								setTimeout( "$('.alert').fadeOut(1500);",3000 );
							} else {
								$('.log-status-pwd').addClass('wrong-entry');
								$(".alert").text(data.error);
								$('.alert').fadeIn(1500);
								setTimeout( "$('.alert').fadeOut(1500);",3000 );
								// Password wrong
							}
							console.log(data.error);
						}
					}, error:function(err){
						console.log(err);
					}
				});

			});
		}());
	</script>

</body>
</html>