<?php 
	
	/**
	* add groups to module in module_koppel table;
	*/
	class ADDGROUPTOMODULE{

		private $moduleName;
		private $groupName;
		private $proceed;

		private $connect;

		private $response;
		
		function __construct(){


			$this->proceed = false;
			$this->connect = null;
			$this->response = array();

			require_once("../../connect.php");

			if(!empty(isset($_POST))){

				if(!empty(isset($_POST['groupName']))){
					$this->proceed = true;

					$this->groupName = $_POST['groupName'];

					$this->response['groupname'] = $this->groupName;
				}else{
					$this->proceed = false;
					$this->response['groupname'] = "Undefined";
				}


				if(!empty(isset($_POST['module_name']))){
					$this->proceed = true;

					$this->moduleName = $_POST['module_name'];

					$this->response['module_name'] = $this->moduleName;
				}

			}else{
				$this->response = false;

				$this->response['error'] = "missing values..!";
			}


			if($this->proceed){
				echo json_encode($this->ADDGROUPTOMODULE());
			}


		}



		public function ADDGROUPTOMODULE(){


			try{

				$getModuleId = $this->getModuleId();
				$getGroupId = $this->getGroupId();

				$this->connect = new connect();

				$dbh = $this->connect->returnConnection();

				$stmt = $dbh->prepare("INSERT INTO module_koppel (module_id, groep_id) VALUES(:module_id, :groep_id)");

				$stmt->bindParam(":module_id", $getModuleId);
				$stmt->bindParam(":groep_id", $getGroupId);

				$stmt->execute();

				$module_id;

				if($stmt->rowCount() > 0){
					
					$this->response['success'] = true;
				}else{
					$this->response['success'] = false;
				}


				return $this->response;

			}catch(PDOException $e){
				return $e->getMessage();
			}



		}


		private function getModuleId(){

			try{
				$returnModuleName = $this->returnModuleName();

				$this->connect = new connect();

				$dbh = $this->connect->returnConnection();

				$stmt = $dbh->prepare("SELECT module_id FROM module WHERE module_locatie=:module_name");

				$stmt->bindParam(":module_name", $returnModuleName);

				$stmt->execute();

				$module_id;

				if($stmt->rowCount() > 0){
					$result = $stmt->fetch(PDO::FETCH_ASSOC);
					
					$module_id = $result['module_id'];

					return $module_id;
				}else{
					return false;
				}


			}catch(PDOException $e){
				return $e->getMessage();
			}

		}


		private function getGroupId(){
			try{
				$returnGroupName = $this->returnGroupName();

				$this->connect = new connect();

				$dbh = $this->connect->returnConnection();

				$stmt = $dbh->prepare("SELECT groep_id FROM groep WHERE groep_naam=:groep_name");

				$stmt->bindParam(":groep_name", $returnGroupName);

				$stmt->execute();

				$module_id;

				if($stmt->rowCount() > 0){
					$result = $stmt->fetch(PDO::FETCH_ASSOC);
					
					$module_id = $result['groep_id'];

					return $module_id;
				}else{
					return false;
				}


			}catch(PDOException $e){
				return $e->getMessage();
			}

		}


		private function returnModuleName(){
			return $this->moduleName;
		}

		private function returnGroupName(){
			return $this->groupName;
		}
	}



	new ADDGROUPTOMODULE();



?>