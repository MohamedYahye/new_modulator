<?php 

	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);


	class NEWPASSWORD{

		private $newPassword;
		private $proceed;
		private $response;
		private $connect;
		private $currentUser;
		private $passHash;

		public function __construct(){


			require_once("../../connect.php");
			require_once("../../passHash.php");
			require_once("bcrypt.php");
			header("Content-Type: text/html; charset=iso-8859-1");

			$this->response = array();
			$this->connect = null;

			if(!empty(isset($_POST))){
				if(isset($_POST['newPassword'])){
					$this->newPassword = $_POST['newPassword'];
					$this->proceed = true;
				}else{
					$this->proceed = false;
				}

				if(isset($_POST['currentUser'])){
					$this->currentUser = $_POST['currentUser'];
					$this->proceed = true;
				}
			}else{
				$this->proceed = false;
			}


			if($this->proceed){
				$this->NEWPASSWORD();


			}
		}

		private function NEWPASSWORD(){


			try{

				require_once("../../session.php");

				$session = new session();

				$user_id = $session->returnUsername();

				$this->response['user_id'] = $user_id;

				$newPassword = $this->returnNewPassword();

				$this->connect = new connect();

				//$this->passHash = new Bcrypt();

				$dbh = $this->connect->returnConnection();

				$stmt = $dbh->prepare("UPDATE student SET password=:password WHERE student_id=:student_id");

				$hashed = Bcrypt::hashPassword($newPassword);

				$stmt->bindParam(":password", $hashed);

				$stmt->bindParam(":student_id", $user_id);
				$stmt->execute();

				if($stmt->rowCount() > 0){
					$this->response['response'] = $hashed;
				}else{
					$this->response['response'] = "oeps....";
				}

				echo json_encode($this->response);



			}catch(PDOException $e){
				return $e->getMessage();
			}

		}


		


		public function returnNewPassword(){
			return $this->newPassword;
		}

		public function returnCurrentUser(){
			return $this->currentUser;
		}
	}

	new NEWPASSWORD();

?>