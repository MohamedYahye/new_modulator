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

			echo "<h3 id='module_name'>".$modulename."</h3>";

			$settings = $editModule->returnModuleSetting();

			foreach($settings as $_settings){

				if($_settings['module_status'] == 1){
					echo "<h4>ON</h4><a href=edit_module.php?module=".$modulename."&status=OFF>Turn OFF</a>";
				}else{
					echo "<h4>OFF</h4><a href=edit_module.php?module=".$modulename."&status=ON>Turn ON</a>";
				}
			}

		?>

	</div>


	<div class="list_group">
    
			
		<?php 
			require_once("tools/getGroep.php");

			$list_group = new Group();

			$group = $list_group->Group();


			echo "<table class='pure-table pure-table-horizontal'>
					    <thead>
					        <tr>
					            <th>Select group to add to module users</th>
					        </tr>
					    </thead>
					      <tbody>";



			foreach($group as $_group){

				$getgroupName = $list_group->getgroupName($_group['groep_id']);

				foreach($getgroupName as $groupName){
					echo "<tr class='group'><td>".$groupName."</td></tr>";
				}

			}

			echo "</tbody></table>";



		?>



	</div>

</body>


<script type="text/javascript">
		
		$(document).ready(function(){

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

						console.log("yeeeeeeyyy");
					}
				}

			})
		})


</script>
</html>