<?php 
	
	class DeleteBeheerder{

		private $beheerder;

		private $connect;

		private $response;

		private $proceed;


		public function __construct(){
			$this->proceed = false;
			$this->connect = null;
			$this->response = array();

			if(!empty(isset($_POST))){
				if(isset($_POST['beheerder'])){
					$this->beheerder = $_POST['beheerder'];

					$this->proceed = true;
				}else{
					$this->proceed = false;
				}
			}



			if($this->proceed){
				echo json_encode($this->DeleteBeheerder());
			}


		}


		private function DeleteBeheerder(){
			try{
				require_once("../../connect.php");

				$this->connect = new connect();

				$dbh = $this->connect->returnConnection();

				$returnBeheerder = $this->returnBeheerder();

				$stmt = $dbh->prepare("DELETE FROM beheerder WHERE name=:name");

				$stmt->bindParam(":name", $returnBeheerder);
				$stmt->execute();

				if($stmt->rowCount() > 0){
					$this->response['beheerder_deleted'] = true;
				}else{
					$this->response['beheerder_deleted'] = true;
				}


				return $this->response;


			}catch(PDOException $e){
				return json_encode($e->getMessage());
			}
		}


		public function returnBeheerder(){
			return $this->beheerder;
		}
	}

	new DeleteBeheerder();


?>