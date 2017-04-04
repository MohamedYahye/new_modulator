<?php 
	
	require_once("menu.php");


	class home_Beheerder{

		private $student;
		private $group;
		private $groupMembers;

		private $connect;

		public function __construct(){

			require_once("../connect.php");

			$this->student = array();
			$this->group = array();
			$this->groupMembers = array();
			$this->connect = null;


			$this->returnAllStudents();
			$this->getGroupMembers();

		}


		private function returnAllStudents(){


			try{

				$this->connect = new connect();

				$dbh = $this->connect->returnConnection();

				$stmt = $dbh->prepare("SELECT * FROM student WHERE recht_id != 0");

				$stmt->execute();

				if($stmt->rowCount() > 0){
					$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

					$this->student = $result;
				}else{
					return false;
				}


				$this->connect->closeConnection();
			}catch(PDOException $e){
				return $e->geMessage();
			}


		}


		public function getAllGroups($group_id){

			try{

				$this->connect = new connect();
				$dbh = $this->connect->returnConnection();


				$stmt = $dbh->prepare("SELECT * FROM groep WHERE groep_id=:groep_id");


				$stmt->bindParam(":groep_id", $group_id);

				$stmt->execute();



				if($stmt->rowCount() > 0){
					$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

					$this->group = $result;
				}else{
					return false;
				}

				$this->connect->closeConnection();
			}catch(PDOException $e){
				return $e->geMessage();
			}


		}

		private function getGroupMembers(){

			try{

				$this->connect = new connect();
				$dbh = $this->connect->returnConnection();

				$stmt = $dbh->prepare("SELECT DISTINCT student_id, groep_id FROM koppeltabel GROUP BY groep_id");

				$stmt->execute();

				if($stmt->rowCount() > 0){
					$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

					$this->groupMembers = $result;
				}else{
					return false;
				}


				$this->connect->closeConnection();
			}catch(PDOException $e){
				return $e->geMessage();
			}


		}



		public function returnStudent(){
			return $this->student;
		}
		public function returnAllGroups(){
			return $this->group;
		}


		public function returnGroupMembers(){
			return $this->groupMembers;
		}

	}

	$home_Beheerder = new home_Beheerder();

?>



<!DOCTYPE html>
<html>
<head>
	<title></title>

	<link rel="stylesheet" type="text/css" href="../../controller/css/pure-table.css">
	<link rel="stylesheet" type="text/css" href="../../controller/css/home_beheerder.css">

</head>
<body>

	<div class="wrap">
		<div class="students">

			<h3 id="student">Studenten</h3>

			<?php

				$students = $home_Beheerder->returnStudent();
				echo "<table class='pure-table pure-table-bordered'>
					    <thead>
					        <tr>
					            <th>Naam</th>
					            <th>Leerjaar</th>
					            <th>Uitstroom</th>
					        </tr>
					    </thead>

					    <tbody>";

				foreach($students as $student){

					echo "<tr><td>".$student['name']."</td><td>".$student['leerjaar']."</td><td>".$student['uitstroom']."</td></tr>";

				}

				echo " </tr>
					    </tbody>
					</table>";

			?>
		</div>

		<div class="groups">
			<h3 id="groepen">Groepen</h3>
			<?php 


				$groups = $home_Beheerder->returnGroupMembers();

				echo "<table class='pure-table pure-table-bordered'>
					    <thead>
					        <tr>
					            <th>Groepnaam</th>
					            <th>Antal leden</th>
					        </tr>
					    </thead><tbody>";

				foreach($groups as $group){

					$home_Beheerder->getAllGroups($group['groep_id']);

					$groupFound = $home_Beheerder->returnAllGroups();

					foreach($groupFound as $founGroup){
						echo "<tr><td>".$founGroup['groep_naam']."</td><td>".$group["student_id"]."</td></tr>";
					}
				}

				echo " </tr>
					    </tbody>
					</table>";

			?>
			<div class="modules">
				<h3>Modules</h3>
				<table class='pure-table pure-table-bordered'>
				    <thead>
				        <tr>
				            <th>Beschikbare modules</th>
				            <th>Status</th>
				            <th>Module aanpassen</th>
				        </tr>
				    </thead>
				    <tbody>
				    	<?php 

				    	$path = '../../modules';

						$dirs = array();

						// directory handle
						$dir = dir($path);

						while (false !== ($entry = $dir->read())) {
						    if ($entry != '.' && $entry != '..') {
						       if (is_dir($path . '/' .$entry)) {
						            $dirs[] = $entry; 
						       }
						    }
						}

						//.echo "<pre>"; print_r($dirs);

						foreach($dirs as $_dir){
							echo "<tr><td><a href=modules.php?module=".$_dir.">".$_dir."</a></td><td>OFF</td><td><a href=modules.php?module=".$_dir."&edit_module=true>Aanpssen</a></td></tr>";
						}
						


				    	?>
				    </tbody>
				</table>
			</div>
		</div>

	</div>

</body>
</html>