<?php 
	
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);


	class NEW_MODULE{


		private $does_module_exist;
		private $connect;
		private $proceed;

		public function __construct(){

			$this->connect = null;
			$this->does_module_exist = false;
			$this->proceed = false;


			require_once("../connect.php");

			$this->checkModuleInDb();

			//$this->addModule();
			
		}


		private function checkModuleInDb(){
			


			try{

				$dirs = $this->getDirectory();

				$this->connect = new connect();

				$dbh = $this->connect->returnConnection();

				$stmt = $dbh->prepare("SELECT * FROM module");

				$stmt->execute();


				$temp = array();


				$result = $stmt->fetchAll(PDO::FETCH_ASSOC);


				foreach($result as $res){
					array_push($temp, $res['module_locatie']);
				}

				$match = array_diff($dirs, $temp);

			


				$stmt = $dbh->prepare("INSERT INTO module (module_locatie, module_status) 
						VALUES(:module_locatie, :module_status)");
					
				$module_status = 0;
				foreach($match as $dir){
					
					$stmt->bindParam(":module_locatie", $dir);
					$stmt->bindParam(":module_status", $module_status);
					

					$stmt->execute();
				}
				$this->removeFromDb();

				$this->connect->closeConnection();
			}catch(PDOException $e){
				return $e->getMessage();
			}



		}



		public function removeFromDb(){
			try{

				$this->connect = new connect();

				$dbh = $this->connect->returnConnection();


				$stmt = $dbh->prepare("SELECT module_locatie, module_id FROM module");
				$stmt->execute();

				$temp = array();

				if($stmt->rowCount() > 0){
					$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
					foreach($result as $res){
						array_push($temp, $res['module_locatie']);
					}

				}

				$getDirectory = $this->getDirectory();

				$diff = array_diff($temp, $getDirectory);



				$stmt = $dbh->prepare("DELETE FROM module WHERE module_locatie=:module_locatie");

				foreach($diff as $_diff){
					$stmt->bindParam(":module_locatie", $_diff);
				}

				$stmt->execute();

				if($stmt->rowCount() > 0){
					//$this->DELETE_FROM_MODULE_KOPPEL();
				}

			}catch(PDOException $e){
				return $e->getMessage();

			}
		}



		private function addModule($moduleArray){

			try{

				$moduleArray = array();
					

				var_export($moduleArray);
				die();

				$does_module_exist = $this->does_module_exist();


				if($does_module_exist){
					echo "module exists";

				}else{

					$this->connect = new connect();

					$dbh = $this->connect->returnConnection();

					$getDirectory = $this->getDirectory();


					$stmt = $dbh->prepare("INSERT INTO module (module_locatie, module_status) VALUES(:module_locatie, :module_status)");
					
					$module_status = 0;
					foreach($moduleArray as $dir){
						



						
						$stmt->bindParam(":module_locatie", $dir);
						$stmt->bindParam(":module_status", $module_status);
						

						$stmt->execute();

						echo $dir;
					}


					




				}

			}catch(PDOException $e){
				return $e->getMessage();
			}

		}


		private function DELETE_FROM_MODULE_KOPPEL(){
			try{

				

				$this->connect = new connect();

				$dbh = $this->connect->returnConnection();

				
				$stmt = $dbh->prepare("DELETE FROM module_koppel WHERE module_id NOT IN(SELECT module_id FROM module)");

				$stmt->execute();

				if($stmt->rowCount() > 0){
					return true;
				}else{
					return false;
				}

			}catch(PDOException $e){
				return $e->getMessage();
			}



		}



		private function getDirectory(){


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


				return $dirs;

		}


		public function does_module_exist(){
			return $this->does_module_exist;
		}
	}




?>