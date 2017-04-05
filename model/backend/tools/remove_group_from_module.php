<?php 
	
	class REMOVEFROMMODULE{

		private $connect;
		private $proceed;

		private $groupName;
		private $module_name;

		private $response;

		public function __construct(){

			$this->response = array();
			$this->connect = null;

			require_once("../../connect.php");

			if(!empty(isset($_POST))){
				if(isset($_POST['groupName'])){
					$this->groupName = $_POST['groupName'];

					$this->proceed = true;
				}else{
					$this->proceed = false;
				}

				if(!empty(isset($_POST['module_name']))){
					$this->module_name = $_POST['module_name'];

					$this->proceed = true;
				}else{
					$this->proceed = false;
				}
			}


			if($this->proceed){

				$this->REMOVEFROMMODULE();
			}else{
				echo json_encode(array("success"=>false));
			}


		}


		private function REMOVEFROMMODULE(){
			try{


				$this->connect = new connect();
				$getModuleID = $this->getModuleID();
				$getGroupId = $this->getGroupId();



				$dbh = $this->connect->returnConnection();

				$stmt = $dbh->prepare("DELETE FROM module_koppel WHERE module_id=:module_id AND groep_id=:groep_id");
				$stmt->bindParam(":module_id", $getModuleID);
				$stmt->bindParam(":groep_id", $getGroupId);

				$stmt->execute();

				if($stmt->rowCount() > 0){
					$this->response['group_removed'] = true;
				}else{
					$this->response['group_removed'] = false;
				}


				echo json_encode($this->response);


			}catch(PDOException $e){
				return json_encode($e->getMessage());
			}
		}


		private function getModuleID(){
			try{


				$this->connect = new connect();

				$returnModuleName = $this->returnModuleName();

				$dbh = $this->connect->returnConnection();

				$stmt = $dbh->prepare("SELECT module_id FROM module WHERE module_locatie=:module_name");

				$stmt->bindParam(":module_name", $returnModuleName);

				$stmt->execute();

				if($stmt->rowCount() > 0){
					$res = $stmt->fetch(PDO::FETCH_ASSOC);

					return $res['module_id'];
				}


			}catch(PDOException $e){
				return json_encode($e->getMessage());
			}
		}


		private function getGroupId(){
			try{


				$this->connect = new connect();

				$returnGroupName = $this->returnGroupName();

				$dbh = $this->connect->returnConnection();

				$stmt = $dbh->prepare("SELECT groep_id FROM groep WHERE groep_naam=:group_name");

				$stmt->bindParam(":group_name", $returnGroupName);

				$stmt->execute();

				if($stmt->rowCount() > 0){
					$res = $stmt->fetch(PDO::FETCH_ASSOC);

					return $res['groep_id'];
				}


			}catch(PDOException $e){
				return json_encode($e->getMessage());
			}
		}


		public function returnModuleName(){
			return $this->module_name;
		}

		public function returnGroupName(){
			return $this->groupName;
		}
	}



	new REMOVEFROMMODULE();
?>