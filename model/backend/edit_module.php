<?php 
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	class editModule {

		private $moduleName;
		private $proceed;
		private $connect;
		private $status;
		private $module_setting;

		public function __construct(){

			$this->proceed = false;
			$this->connect = null;
			$this->module_setting = array();

			require_once("../connect.php");
			require_once("tools/getGroep.php");

			

			if(!empty(isset($_GET['edit_module']))){
				if($_GET['edit_module'] == "true"){
					$this->moduleName = $_GET['module'];

					$this->proceed = true;
				}else{
					$this->proceed = false;
				}
			}


			if(!empty(isset($_GET['status']))){
				$this->status = $_GET['status'];

				//$this->proceed = true;
				$this->changeStatus();
			}else{
				//$this->proceed = false;
			}

			if($this->proceed){
				$this->getModuleSettings();
			}


		}


		private function getModuleSettings(){

			try{


				$this->connect = new connect();

				$module_name = $this->returnModuleName();

				$dbh = $this->connect->returnConnection();


				$stmt = $dbh->prepare("SELECT module_status FROM module WHERE module_locatie=:module_locatie");

				$stmt->bindParam(":module_locatie", $module_name);

				$stmt->execute();

				if($stmt->rowCount() > 0){
					$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

					$this->module_setting = $result;
					
					

				}else{
					return false;
				}

			}catch(PDOException $e){
				return $e->getMessage();
			}
		}


		private function changeStatus(){
			try{
				$this->connect = new connect();

				$module_name = $_GET['module'];

				$dbh = $this->connect->returnConnection();

				$module_status = $this->returnStatus();

				//echo $module_status;



				//echo $module_name;

				$stmt = $dbh->prepare("UPDATE module SET module_status=:status WHERE module_locatie=:module_locatie");

				if($module_status == "OFF"){
					$status = 0;
				}elseif($module_status == "ON"){
					$status = 1;
				}

				$stmt->bindParam(":status", $status);
				$stmt->bindParam(":module_locatie", $module_name);

				if($stmt->execute()){

					header("Location: modules.php");
				}else{
					echo "oeps";
				}



				//echo $dbh->lastInsertId();

			}catch(PDOException $e){
				return $e->getMessage();
			}
		}


		public function getoModuleUsers(){

			try{
				$this->connect = new connect();

				$module_name = $this->returnModuleName();

				$dbh = $this->connect->returnConnection();


				$stmt = $dbh->prepare("SELECT module_id FROM module WHERE module_locatie=:modulename");

				$stmt->bindParam(":modulename", $module_name);

				$stmt->execute();

				if($stmt->rowCount() > 0){
					$result = $stmt->fetch(PDO::FETCH_ASSOC);


					$stmt = $dbh->prepare("SELECT DISTINCT groep_id FROM module_koppel WHERE module_id=:module_id");

					$stmt->bindParam(":module_id", $result['module_id']);

					$stmt->execute();

					$temp = array();

					$current_users = array();

					if($stmt->rowCount() > 0){
						$res = $stmt->fetchAll(PDO::FETCH_ASSOC);
						$list_group = new Group();
						foreach($res as $current){
							array_push($temp, $current['groep_id']);

							
						}


						foreach($temp as $currentGroups){
							$new_temp = $list_group->getgroupName($currentGroups);

							array_push($current_users, $new_temp);
						}

						return $current_users;


					}

				}



			}catch(PDOException $e){
				return $e->getMessage();
			}


		}


		public function getNewModuleuser(){
			try{
				$this->connect = new connect();
				$module_name = $this->returnModuleName();
				$dbh = $this->connect->returnConnection();

				$stmt = $dbh->prepare("SELECT module_id FROM module WHERE module_locatie=:module_name");


				$stmt->bindParam(":module_name", $module_name);

				$stmt->execute();

				if($stmt->rowCount() > 0){
					$res = $stmt->fetch(PDO::FETCH_ASSOC);

					$stmt = $dbh->prepare("SELECT groep_id from groep WHERE groep_id NOT IN(SELECT DISTINCT groep_id FROM module_koppel WHERE module_id=:module_id )");

					$stmt->bindParam(":module_id", $res['module_id']);

					$stmt->execute();

					if($stmt->rowCount() > 0){
						$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

						return $result;
					}
				

				}


				



			}catch(PDOException $e){
				return $e->getMessage();
			}

		}


		public function returnModuleName(){
			return $this->moduleName;
		}


		public function returnModuleSetting(){
			return $this->module_setting;
		}

		public function returnStatus(){
			return $this->status;
		}
	}

	$editModule = new editModule();

?>


<!DOCTYPE html>
<html>
<head>
	<title></title>

	<link rel="stylesheet" type="text/css" href="../../controller/css/edit_module.css">
	<script type="text/javascript" src="../../controller/js/jquery.js"></script>
	<link rel="stylesheet" type="text/css" href="../../controller/css/pure-table.css">
	<link href="https://fonts.googleapis.com/css?family=Varela+Round" rel="stylesheet">

</head>
<body>


	
	<div class="module_setting">
			
		<?php 
			$modulename = $editModule->returnModuleName();

			echo "<table class='pure-table pure-table-bordered'>
						    <thead>
						        <tr>
						            <th>Module</th>
						            <th>Module status</th>
						            <th>Status aanpassen</th>
						        </tr>
						    </thead>
						      <tbody>";

			

			$settings = $editModule->returnModuleSetting();

			echo "<tr><td id='module_name'>".$modulename."</td>";

			foreach($settings as $_settings){

				if($_settings['module_status'] == 1){
					echo "<td>ON</td><td><a href=edit_module.php?module=".$modulename."&status=OFF>Turn OFF</a></td>";
				}else{
					echo "<td>OFF</td><td><a href=edit_module.php?module=".$modulename."&status=ON>Turn ON</a></td></tr>";
				}
			}
			echo "</tbody></table>";
		?>

		<div class="current-module-users">
			
			<?php 

				$current_groups = $editModule->getoModuleUsers();

				if(!empty($current_groups)){
					foreach($current_groups as $subArray){
					    foreach($subArray as $val){
					        $newArray[] = $val['groep_naam'];
					    }
					}
					
					if(!empty(is_array($newArray))){
						echo "<table class='pure-table pure-table-horizontal'>
						    <thead>
						        <tr>
						            <th>Huidige module gebruikers</th>
						        </tr>
						    </thead>
						      <tbody>";


						foreach($newArray as $current){
							echo "<tr class='current_users'><td>".$current."</td></tr>";
						}
						echo "</tbody></table>";

					}else{
						return false;
					}		
				}

				


			?>



		</div>


	</div>


	<div class="list_group">
    
			
		<?php 


			$list_group = new Group();
			$group = $list_group->Group();

			// get groeps that dont allready have module_users;
			///SELECT groep_id from groep WHERE groep_id NOT IN(SELECT DISTINCT groep_id FROM module_koppel WHERE module_id=:module_id )"



			$newUsers = $editModule->getNewModuleuser();

			if(!empty(is_array($newUsers))){
				echo "<table class='pure-table pure-table-horizontal'>
					    <thead>
					        <tr>
					            <th>Select group to add to module users</th>
					        </tr>
					    </thead>
					      <tbody>";
					      
				foreach($newUsers as $_group){

					$getgroupName = $list_group->getgroupName($_group['groep_id']);

					foreach($getgroupName as $groupName){
						echo "<tr class='group'><td>".$groupName['groep_naam']."</td></tr>";
					}

				}

				echo "</tbody></table>";
			}else{
			}

			



		?>



	</div>

</body>


<script type="text/javascript">
		
		$(document).ready(function(){
			console.log('derp');

			$(".group").on("click", function(){
				//console.log($(this).children().text());

				$(this).toggleClass("group-selected");

				var data = $(this).children().text();
				var module_name = $("#module_name").text();
				var jsonResponse;

				if($(this).hasClass("group-selected")){

					$.ajax({
					    type: "POST",
					    data: {
					    	groupName:data,
					    	module_name:module_name
					    },
					    url: "tools/add_group_to_module.php",
					    dataType: "html",
					    async: false,
					    success: function(data) {

					      jsonResponse = data;
					      
					    }
					  });


					var parse = JSON.parse(jsonResponse);
					if(parse.success){
						location.reload();
						console.log("yeeeeeeyyy");
					}
				}

			})


			$(".current_users").on("click", function(){
				console.log("hello");
				$(this).toggleClass("group-selected-remove");

				var data = $(this).children().text();
				var module_name = $("#module_name").text();
				var jsonResponse;

				if($(this).hasClass("group-selected-remove")){
					console.log($(this).children().text());

					//1,4,5,6,7,14


					$.ajax({
					    type: "POST",
					    data: {
					    	groupName:data,
					    	module_name:module_name
					    },
					    url: "tools/remove_group_from_module.php",
					    dataType: "html",
					    async: false,
					    success: function(data) {
						    var parse = JSON.parse(data);
							if(parse.group_removed){
								location.reload();
							}else{
								console.log(parse);
							}
					      
					    }
					});


				}
			})
		})


</script>
</html>