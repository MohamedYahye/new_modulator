<?php 
	
	require("menu.php");
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);


	require("tools/new_module.php");

	$new_module = new NEW_MODULE();


	$does_module_exist = $new_module->does_module_exist();

	if(isset($_GET['edit_module'])){
		require_once("edit_module.php");
		die();
	}


	if(isset($_GET['module'])){


		$path = "../../modules/".$_GET['module'];

		if(glob($path . "/*")){
			echo "<div id='frame'><iframe src=".$path." style='width: 1000px; height: 1000px;'></iframe></div>";
		}else{
			echo "file dir is empty(var)";
		}
		//die();
	}

?>



<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="../../controller/css/pure-table.css">
	<link rel="stylesheet" type="text/css" href="../../controller/css/home_beheerder.css">
	<link rel="stylesheet" type="text/css" href="../../controller/css/modules.css">
</head>
<body>

<?php 

	require_once("../connect.php");

	$connect = new connect();

	$dbh = $connect->returnConnection();


	$stmt = $dbh->prepare("SELECT * FROM module");

	$stmt->execute();

	$temp = array();

	if($stmt->rowCount() > 0){
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);


		require_once("../session.php");

		$session = new session();

		$currentUser = $session->returnUsername();


		$stmt = $dbh->prepare("SELECT student_id,recht_id FROM student WHERE student_id=:student_id");

		$stmt->bindParam(":student_id", $currentUser);

		$stmt->execute();


		$recht_id;

		if($stmt->rowCount() > 0){
			$res = $stmt->fetchAll(PDO::FETCH_ASSOC);


			foreach($res as $newRes){
				$recht_id = $newRes['recht_id'];
			}

			if($recht_id == 0){
				echo "<table class='pure-table pure-table-bordered'>
				    <thead>
				        <tr>
				            <th>Beschikbare modules</th>
				            <th>Status</th>
				            <th>Module aanpassen</th>
				        </tr>
				    </thead>
				    <tbody>";

				foreach($result as $_dir){

					if($_dir['module_status'] == 0){
						$status = "OFF";
					}else{
						$status = "ON";
					}

					echo "<tr><td><a href=modules.php?module=".$_dir['module_locatie'].">".$_dir['module_locatie']."</a></td><td>".$status."</td><td><a href=modules.php?module=".$_dir['module_locatie']."&edit_module=true>Aanpssen</a></td></tr>";
				}

				echo "</tbody>
						</table>";
			}else{

				require_once('tools/getModule_currentuser.php');

				$user_modules = getModuleCurrentUser::getModule($currentUser);


				if(!empty(is_array($user_modules))){
					echo "<div id='modules'><table class='pure-table pure-table-bordered'>
				    <thead>
				        <tr>
				            <th>Beschikbare modules</th>
				        </tr>
				    </thead>
				    <tbody>";

					foreach($user_modules as $module){
						$path = "../../modules/".$module;


						if(glob($path . "/*")){
							echo "<tr><td><a href=modules.php?module=".$module.">".$module."</a></td></tr>";
						}else{
							return false;
						}

						
					}

					echo "</tbody>
							</table></div>";
				}else{
					return false;
				}
			}

				

				// die();

				// echo "<table class='pure-table pure-table-bordered'>
				//     <thead>
				//         <tr>
				//             <th>Beschikbare modules</th>
				//         </tr>
				//     </thead>
				//     <tbody>";

				// foreach($result as $_dir){
				// 	if($_dir['module_status'] != 0){
				// 		echo "<tr><td><a href=modules.php?module=".$_dir['module_locatie'].">".$_dir['module_locatie']."</a></td></tr>";
				// 	}

					
				// }

				// echo "</tbody>
				// 		</table>";
				// 	}

		}

		// echo "<table class='pure-table pure-table-bordered'>
		// 		    <thead>
		// 		        <tr>
		// 		            <th>Beschikbare modules</th>
		// 		            <th>Status</th>
		// 		            <th>Module aanpassen</th>
		// 		        </tr>
		// 		    </thead>
		// 		    <tbody>";

		// foreach($result as $_dir){

		// 	if($_dir['module_status'] == 0){
		// 		$status = "OFF";
		// 	}else{
		// 		$status = "ON";
		// 	}

		// 	echo "<tr><td><a href=modules.php?module=".$_dir['module_locatie'].">".$_dir['module_locatie']."</a></td><td>".$status."</td><td><a href=modules.php?module=".$_dir['module_locatie']."&edit_module=true>Aanpssen</a></td></tr>";
		// }

		// echo "</tbody>
		// 		</table>";
	}



?>


</body>
</html>