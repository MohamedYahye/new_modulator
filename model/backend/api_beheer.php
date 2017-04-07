<?php

	require_once("api_view.php");

	$_api_view = new API_VIEW();

	$users = API_VIEW::returnAllUSers();

	$hashApi = $_api_view->hashApi();
?>



<!DOCTYPE html>
<html>
<head>
	<link href="https://fonts.googleapis.com/css?family=Varela+Round" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="../../controller/css/pure-table.css">
	<link rel="stylesheet" type="text/css" href="../../controller/css/api_view.css">
	<script type="text/javascript" src="../../controller/js/jquery.js"></script>
	<title>Users</title>
</head>
<body>

	<div class="users">
		<div class="inner">
			
			<h3>Alle gebruikers</h3>

			<?php 


				if(!empty(is_array($users))){
					echo "<table class='pure-table pure-table-bordered'>
						    <thead>
						        <tr>
						            <th>naam</th>
						            <th>email</th>
						            <th>API sleutel verlenen</th>
						        </tr>
						    </thead>

						    <tbody>
						        <tr class='add_key'>";

					foreach($users as $_user){
						echo "<td>".$_user['name']."</td><td>".$_user['email']."</td>
							<td class='give'>sleutel verlenen</td></tr>";
					}

					echo "</tbody></table>";
				}else{
				}
			?>


			<div class="api_holders">
					<h3>Deze studenten hebben een API sleutel</h3>

				<?php 

					if(!empty(is_array($hashApi))){
					echo "<table class='pure-table pure-table-bordered'>
						    <thead>
						        <tr>
						            <th>naam</th>
						            <th>email</th>
						            <th>API sleutel verlenen</th>
						        </tr>
						    </thead>

						    <tbody>
						        <tr>";

					foreach($hashApi as $_user){
						echo "<td>".$_user['name']."</td><td>".$_user['email']."</td>
							<td class='take'> sleutel verwijderen</td></tr>";
					}

					echo "</tbody></table>";
				}else{
				}



				?>



			</div>


		</div>
	</div>

</body>


	<script type="text/javascript">
		
	$(document).ready(function(){
		$('.give').on("click", function(){
			console.log('here');
			console.log($(this).closest("td").find('.add_key').text());
		})
	})


	</script>

</html>