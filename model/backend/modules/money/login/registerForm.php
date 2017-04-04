<html>
<head>
<link rel="shortcut icon" type="image/x-icon" href="../euro.gif">
<title>Budgetify</title>
	<LINK href="css/loginCss.css" rel="stylesheet" type="text/css">

	<link rel='stylesheet prefetch' href='http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css'>

</head>
<body>
	<div class="login-form">
	<h1>Register</h1>
	<form action="register.php" method="post">
		<div class="form-group log-status-username log-status">
		  <input type="text" class="form-control" placeholder="Gebruikersnaam " name="username" autocomplete="off" id="input_username">
		  <i class="fa fa-user"></i>
		</div>
		<div class="form-group log-status-pwd log-status">
		  <input type="password" class="form-control" placeholder="Wachtwoord" name="password" id="input_password">
		  <i class="fa fa-lock"></i>
		</div>
		<div class="form-group log-status-rpwd log-status">
		  <input type="password" class="form-control" placeholder="Wachtwoord" name="password2" id="input_rpassword">
		  <i class="fa fa-lock"></i>
		</div>
<!-- 		<div class="form-group log-status-email log-status">
		  <input type="text" class="form-control" placeholder="Email" name="email" autocomplete="off" id="input_email">
		  <i class="fa fa-envelope"></i>
		</div>
 -->		
 		<div id="optional_text">Optioneel</div>
		<div class="form-group log-status">
		  <input type="text" class="form-control" placeholder="Beveiligingsvraag " name="secQuestion" autocomplete="off" id="secQuestion">
		  <i class="fa fa-lock"></i>
		</div>
		<div class="form-group log-status">
		  <input type="text" class="form-control" placeholder="Antwoord" name="secQuestionAnswer" autocomplete="off" id="secQuestionAnswer">
		  <i class="fa fa-lock"></i>
		</div>

		<div id="centerSpan" style="text-align: center;">
		<span class="alert"></span>
		</div>
		<input id="login"  class="log-btn" type="submit" value="Registreren">
	</form>
	<form action="loginForm.php">
	    <input class="log-btn extra" type="submit" value="Inloggen">
	</form>

	</div>
	<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>

	<script type="text/javascript">
		(function() {
			$('.form-control').keypress(function(){
				$('.log-status').removeClass('wrong-entry');
			});

			var usernameAvailable = null;
			var password1length   = null;
			var password2length   = null;
			var pwEqual			  = null;

			$('#input_username').on('keyup', function(e) {
				var target = $(e.currentTarget);
				var username = target.val();
				var data = { username: username };

				$.ajax({
					type: 'POST',
					url: 'api/checkUsernameExists.php',
					data: data,
					success: function(data, status) {
						if(data.count) {
							// bestaat al
							$('.log-status-username').addClass('wrong-entry');
							$(".alert").text("Gebruikersnaam bestaat al.");
							$('.alert').fadeIn(1500);
							setTimeout( "$('.alert').fadeOut(1500);",3000 );
							usernameAvailable = false;
						} else {
							usernameAvailable = true;
						}
					}
				});
			});

			// $('#input_email').on('keyup', function(e) {
			// 	var target = $(e.currentTarget);
			// 	var email = target.val();
			// 	var data = { email: email };

			// 	$.ajax({
			// 		type: 'POST',
			// 		url: 'api/checkEmailExists.php',
			// 		data: data,
			// 		success: function(data, status) {
			// 			console.log(data.count);
			// 			if(data.count) {
			// 				// bestaat al
			// 				$('.log-status-email').addClass('wrong-entry');
			// 				$(".alert").text("Email adres bestaat al.");
			// 				$('.alert').fadeIn(1500);
			// 				setTimeout( "$('.alert').fadeOut(1500);",3000 );
			// 				emailAvailable = false;
			// 			} else {
			// 				emailAvailable = true;
			// 			}
			// 		}
			// 	});
			// });

			$("#input_password").focusout(function(){
				var value = $(this).val();
				if ( value.length < 5 ) {
					$('.log-status-pwd').addClass('wrong-entry');
					$(".alert").text("Wachtwoord moet minimaal 5 karakters lang zijn.");
					$('.alert').fadeIn(1500);
					setTimeout( "$('.alert').fadeOut(1500);",3000 );
					password1length = false;
				} else {
					password1length = true;
				}
			  });

			$("#input_rpassword").focusout(function(){
				var inputPassword = $('#input_password').val();
				var inputPassword2 = $('#input_rpassword').val();
				var value = $(this).val();

				if(value.length < 5){
					$('.log-status-rpwd').addClass('wrong-entry');
					$(".alert").text("Wachtwoord moet minimaal 5 karakters lang zijn.");
					$('.alert').fadeIn(1500);
					setTimeout( "$('.alert').fadeOut(1500);",3000 );
					password2length = false;
				} else {
					password2length = true;

					if(inputPassword !== inputPassword2){
						$('.log-status-pwd').addClass('wrong-entry');
						$('.log-status-rpwd').addClass('wrong-entry');
						$(".alert").text("Wachtwoorden moeten gelijk zijn.");
						$('.alert').fadeIn(1500);
						setTimeout( "$('.alert').fadeOut(1500);",3000 );
						pwEqual = false;
					} else {
						pwEqual = true;
					}
				}

				
			  });

			$('#login').on('click', function(e) {
				e.preventDefault();
				var inputUsername = $('#input_username').val();
				var inputPassword = $('#input_password').val();
				var inputPassword2 = $('#input_rpassword').val();
				// var input_email = $('#input_email').val();
				var input_question = $('#secQuestion').val();
				var input_answer = $('#secQuestionAnswer').val();

				
				


				
				// if(inputPassword !== inputPassword2) {
				// 	$('.log-status-pwd').addClass('wrong-entry');
				// 	$('.log-status-rpwd').addClass('wrong-entry');
				// 	$(".alert").text("Passwords must match");
				// 	$('.alert').fadeIn(1500);
				// 	setTimeout( "$('.alert').fadeOut(1500);",3000 );

				// } else {

					// if(inputPassword.length >= 5) {
						//&& emailAvailable
						if(usernameAvailable && password1length && password2length && pwEqual) {
							var register_data = {
								username: inputUsername,
								password: inputPassword,
								password2:inputPassword2,
								// email: input_email,
								question: input_question,
								answer: input_answer
							};
							
						    $.ajax({
						        type: 'POST',
						        url: 'register.php',
						        data: register_data,
						        dataType: 'json',
						        success: function(data) {
						            if(data.success) {
						            	console.log(data);
						                window.location.href = "loginForm.php";
						            } else {
						                //no succes
						            }
						        },
						        error: function(err) {
						            console.log(err);
						        }
						    });
						} else {
							if(!usernameAvailable){
								var error = 'Gebruikersnaam bestaat al.';
								var className = '.log-status-username';
							} //else if(!emailAvailable){
								// var error = 'Email adres bestaat al.';
								// var className = '.log-status-email';
							//} 
							else if(!password1length){
								var error = 'Het eerste wachtwoord is te kort.';
								var className = '.log-status-pwd';
							} else if(!password2length){
								var error = 'Het tweede wachtwoord is te kort.';
								var className = '.log-status-rpwd';
							} else if(!pwEqual){
								$('.log-status-pwd').addClass('wrong-entry');
								$('.log-status-rpwd').addClass('wrong-entry');
								$(".alert").text("Wachtwoorden moeten gelijk zijn.");
								$('.alert').fadeIn(1500);
								setTimeout( "$('.alert').fadeOut(1500);",3000 );
							}
								$(className).addClass('wrong-entry');
								$(".alert").text(error);
								$('.alert').fadeIn(1500);
								setTimeout( "$('.alert').fadeOut(1500);",3000 );

						}

						
						
					// } else {						
					// 	$('.log-status-pwd').addClass('wrong-entry');
					// 	$('.log-status-rpwd').addClass('wrong-entry');
					// 	$(".alert").text("Passwords must be at least 5 characters long");
					// 	$('.alert').fadeIn(1500);
					// 	setTimeout( "$('.alert').fadeOut(1500);",3000 );
					// }


				//}


			});
		}());
	</script>

</body>
</html>