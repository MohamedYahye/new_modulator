<?php 
	
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	class ChangePassword{

		private $password;

		private $proceed;
		private $currentUser;

		private $connect;

		private $passHash;

		private $responseArray;


		public function __construct(){

			require_once("../../connect.php");
			require_once("../../passHash.php");
			require_once("bcrypt.php");
			header("Content-Type: text/html; charset=iso-8859-1");

			$this->connect = null;
			$this->passHash = null;

			$this->responseArray = array();

			if(!empty(isset($_POST['currentPassword']))){

				if(strlen($_POST['currentPassword']) > 0 ){
					$this->password = $_POST['currentPassword'];

					$this->proceed = true;
				}else{
					$this->proceed = false;
				}


				if(!empty(isset($_POST['currentUser']))){
					$this->currentUser = $_POST['currentUser'];

					$this->proceed = true;

				}else{
					$this->proceed = false;
				}

			}else{
				return false;
			}



			if($this->proceed){
				$this->chnagePassword();
			}else{
				echo "oeps....";
			}
		}


		private function chnagePassword(){

			try{

				$this->connect = new connect();

				//$this->passHash = new Bcrypt();

				$dbh = $this->connect->returnConnection();

				$current_user = $this->returnCurrentUser();

				$current_password = $this->returnCurrentPassword();

				$stmt = $dbh->prepare("SELECT password FROM student WHERE ov_nummer=:ov_nummer");

				$stmt->bindParam(":ov_nummer", $current_user);

				$storedPassword = "";

				$stmt->execute();

				if($stmt->rowCount() > 0){
					$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

					foreach($result as $res){
						$storedPassword = $res["password"];
					}

					$check = Bcrypt::checkPassword($current_password, $storedPassword);

					if($check){

						$this->responseArray['password'] = true;


						
					}else{
						$this->responseArray['password'] = false;
					}



				}

				echo json_encode($this->responseArray);

			}catch(PDOException $e){
				return $e->getMessage();
			}


		}

		public function returnCurrentUser(){
			return $this->currentUser;
		}
		public function returnCurrentPassword(){
			return $this->password;
		}
	}

	new ChangePassword();

?>