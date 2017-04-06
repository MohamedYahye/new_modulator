<?php require_once("menu.php");?>


<!DOCTYPE html>
<html>
<head>
	<title>Beheer</title>
	<link href="https://fonts.googleapis.com/css?family=Varela+Round" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="../../controller/css/beheer.css">
	<script type="text/javascript" src="../../controller/js/jquery.js"></script>

</head>
<body>

	<div class="container">
		
	<div class="inner_container">
		
		<ul>
			<li><a class="button" href="#" id="add_beheer">Beheerder toevoegen</a></li>
			<li><a class="button" href="#" id="edit_beheer">Beheerders bewerken</a></li>
			<li><a class="button" href="#" id="edit_key">Api sleutel verlenen</a></li>
		</ul>



	</div>


	</div>


</body>


	<script type="text/javascript">

		$(document).ready(function(){
			$(".inner_container").append("<div class='_loader'></div>");
			$("#add_beheer").on("click", function(){
				console.log($(this).text());

				

				$("._loader").load("beheer_backend.php");

			})
			$("#edit_beheer").on("click", function(){
				console.log($(this).text());
				$("._loader").load("edit_beheerder.php");

			})
			$("#edit_key").on("click", function(){
				console.log($(this).text());
				$("._loader").load("api_beheer.php");
			})
		})

		

	</script>

</html>