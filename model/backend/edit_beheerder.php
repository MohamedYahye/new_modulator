<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link href="https://fonts.googleapis.com/css?family=Varela+Round" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="../../controller/css/edit_beheer_backend.css">
	<link rel="stylesheet" type="text/css" href="../../controller/css/pure-table.css">
	<script type="text/javascript" src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.13/api/fnReloadAjax.js"></script>
	<script type="text/javascript" src="../../controller/js/jquery.js"></script>
</head>
<body>


	<div class="container">
		<div class="_inner_con">

		

		<div class="all_beheerder">
			
			<?php 

			require_once("../connect.php");

			$connect = new connect();

			$dbh = $connect->returnConnection();

			$stmt = $dbh->prepare("SELECT name, username, email FROM beheerder");

			$stmt->execute();

			if($stmt->rowCount() > 0){
				echo "<h3>Beheerders bewerken</h3>";
				$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
				echo "<table class='pure-table pure-table-bordered' id='reload'>
					    <thead>
					        <tr>
					            <th>naam beheerder</th>
					            <th>Email</th>
					            <th>Type gebruiker</th>
					            <th>Verwijderen</th>
					        </tr>
					    </thead>

					    <tbody><tr class='beheerder'>";

				foreach($result as $res){
					echo "<td class='_beheerder'>".$res['name']."</td><td>".$res['email']."</td>
							<td>Beheerder</td><td class='delete_button'>Verwijderen</td></tr>";

				}

				echo "</body></table>";
			}else{
				echo "<h3>Geen beheerders</h3>";
			}






			?>


		</div>

		</div>
	</div>

</body>


	<script type="text/javascript">
		
		$(document).ready(function(){

			$(".delete_button").on("click", function(){
				//console.log($(this).parent().find("._beheerder").text());
				var data = $(this).parent().find("._beheerder").text();

				var jsonResponse;


				$.ajax({
				    type: "POST",
				    data: {
				    	beheerder:data
				    },
				    url: "tools/delete_beheerde.php",
				    dataType: "html",
				    async: false,
				    success: function(data) {

				    	jsonResponse = data;

				    	var parse = JSON.parse(jsonResponse);

				    	if(parse.beheerder_deleted){

				    		$(".all_beheerder").append("<span class='deleted-message'>Beheerder met success verwijderd</span>");

				    		setTimeout(function(){
				    			$(".deleted-message").fadeOut();

							},2000); 

				    	}else{
				    		console.log("no success");
				    	}

				      
				    }
				  });
			})
		})


	</script>

</html>