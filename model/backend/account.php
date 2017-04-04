<?php 
	
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);


?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="../../controller/css/pure-table.css">
	<link rel="stylesheet" type="text/css" href="../../controller/css/account.css">
	<script  type="text/javascript" src="../../controller/js/jquery.js"></script>
</head>
<body>

	<div class="user_info">


		<?php 

			require_once("../user.php");
			require_once("menu.php");

			$currentUserObj = new User();


			$currentUser = $currentUserObj->returnUserInfo();

			echo "<form name='user_info' class='user-info'>";

			foreach($currentUser as $user){
				
					echo "<label for='ov_nummer'>Ov-nummer: </label><br /><br />";
					echo "<input type='text' readOnly='true' value=".$user['ov_nummer']." name='currentUser'><br /><br />";
					echo "<label for='name'>naam: </label><br /><br />";
					echo "<input type='text' readOnly='true' value=".$user['name']."><br /><br />";
					echo "<label for='username'>Gebbruikersnaam: </label><br /><br />";
					echo "<input type='text' readOnly='true' value=".$user['username']."><br /><br />";
					echo "<label for='email'>email: </label><br /><br />";
					echo "<input type='text' readOnly='true' value=".$user['email']."><br /><br />";
					echo "<label for='opleiding'>opleiding: </label><br /><br />";
					echo "<input type='text' readOnly='true' value=".$user['opleiding']."><br /><br />";
					echo "<label for='leerjaar'>leerjaar: </label><br /><br />";
					echo "<input type='text' readOnly='true' value=".$user['leerjaar']."><br /><br />";
					echo "<label for='uitstroom'>uitstroom: </label><br /><br />";
					echo "<input type='text' readOnly='true' value=".$user['uitstroom']."><br /><br />";

					echo "<div class='changePassword'>
						<h3>Verander wachtwoord</h3>
						<input type='password' id='currentPassword' placeholder='huidig wachtwoord' name='current'><br /><br />


					</div>";

				
			}
		echo "</form>";
		?>
	</div>



</body>

<script type="text/javascript">
		
		$(document).ready(function(){
			

			$("#currentPassword").on("change", function(){
				var proceed = false;
				var data = $(this).val();
				var currentUser = $("input[name='currentUser']").val();
				var jsonResponse;
				$.ajax({
				    type: "POST",
				    data: {
				    	currentPassword:data,
				    	currentUser: currentUser
				    },
				    url: "tools/change_password.php",
				    dataType: "html",
				    async: false,
				    success: function(data) {
				      result=data;

				      jsonResponse = result;

				      console.log(result);
				      
				    }
				  });


				  var password = jQuery.parseJSON(jsonResponse);

				  if(password.password){
				  	$(this).prop("disabled", true);
				  	$(this).addClass("correct");


				  	$(".changePassword").append("<form name='change' class='change-password' method='post' action='tools/new_password.php'><input type='password' placeholder='nieuwe wachtwoord' name='newpassword'id='newpassword'><br /><br /><input type='password' name='repeatPassword'id='repeat' placeholder='Herhaal wachtwoord'><br /><br /><input type='submit' value='wachtwoord veranderen'></form>");
				  }else{
				  	$(this).addClass("incorrect");
				  }



				  $("#newpassword").on("change", function(){

				  	var data = $(this).val();
				  	var currentUser = $("input[name='currentUser']").val();

				  	$.ajax({
				    type: "POST",
				    data: {
				    	newpassword:data,
				    	currentUser:currentUser
				    },
				    url: "tools/new_password.php",
				    dataType: "html",
				    async: false,
				    success: function(data) {
				      result=data;

				      jsonResponse = result;

				      console.log(result);
				      
				    }
				  });


				  })

			})
		})



</script>

</html>