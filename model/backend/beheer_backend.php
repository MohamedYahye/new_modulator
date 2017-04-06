<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link href="https://fonts.googleapis.com/css?family=Varela+Round" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="../../controller/css/beheer_backend.css">
	<script type="text/javascript" src="../../controller/js/jquery.js"></script>
</head>
<body>

	<div class="con">
		<div class="inner_con">
		
			<h3>Voeg een nieuwe beheerder toe</h3>	
			<span>*= Verplicht</span><br /><br />

			<form name="new-beheerder" method="POST" class="form">
				<input type="text" name="name" id="name" placeholder="* naam"><br /><br />
				<input type="text" name="username" id="username" placeholder="* gebruikersnaam"><br /><br />
				<input type="email" name="email" id="email" placeholder="* email"><br /><br />
				<input type="password" name="password" id="password" placeholder="* wachtwoord"><br /><br />
				<input type="password" name="repeat" id="repeat" placeholder="* herhaal wachtwoord"><br /><br />
				<input type="submit" id="submit" value="Beheerder toevoegen"><br /><br />
			</form>


		</div>
		
	</div>



	<script type="text/javascript">
		
	$(document).ready(function(){
		

		$(".form").submit(function(event){
			var proceed = false;
			var name = $("#name");
			var username = $("#username");
			var email = $("#email");
			var password = $("#password");
			var repeat = $("#repeat");


			if(name.val().length > 0){
				name.removeClass("error");
				proceed = true;
			}else{
				name.addClass("error");
				proceed = false;
			}

			if(username.val().length > 0){
				proceed = true;
				username.removeClass("error");
			}else{
				username.addClass("error");
				proceed = false;
			}

			if(email.val().length > 0){
				proceed = true;
				email.removeClass("error");
			}else{
				email.addClass("error");
				proceed = false;
			}

			if(password.val().length > 0 && password.val().length >= 6){
				password.removeClass("error");
				proceed = true;
			}else{
				password.addClass("error");
				proceed = false;
			}

			if(repeat.val().length >= 6 && repeat.val() == password.val()){
				repeat.removeClass("error");
				proceed = true;
			}else{	
				repeat.addClass("error");
				proceed = false;
			}



			if(proceed){
				event.preventDefault();
				console.log("yeeey");
				var jsonResponse;

				
					var data = {name: name.val(),
								username: username.val(),
								email: email.val(),
								password: password.val(),
								repeat: repeat.val()}

					$.ajax({
				    type: "POST",
				    data: {
				    	data:data
				    },
				    url: "tools/add_beheerder.php",
				    dataType: "html",
				    async: false,
				    success: function(data) {

				    	jsonResponse = data;

				    	var parse = JSON.parse(jsonResponse);
				    	console.log(parse);
				      if(parse.doesUserExist){
				      	email.addClass("error");
				      	username.addClass("error");
				      	name.addClass("error");
				      }else{
				      	$("#submit").val("Beheerder wordt toegevoegd!....");


				      	$("._loader").append("<span class='_success' >Beheerder met success toegevoegd</span>");

				      	setTimeout(function(){

				      		$(".form > input").val("");

				      		$("#submit").val("Beheerder toevoegen");

				      		$("._success").remove();
						},1000); 
				      }
				      
				    }
				  });

			}else{
				console.log("oeps missing values");
				event.preventDefault();
			}


			

		})



	})


	</script>

</body>
</html>