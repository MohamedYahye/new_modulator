<?php 
	
	/**
	* add new beheerder
	*/
	class AddBeheerder{


		private $name;
		private $username;
		private $email;
		private $password;

		private $proceed;

		private $connect;

		
		function __construct(){


			$this->connect = false;

			require_once("../../connect.php");

			foreach($_POST as $post){
				if(strlen($post['name']) > 0){
					$this->name = $post['name'];
					$this->proceed = true;
				}else{
					$this->proceed = false;
				}
				if(strlen($post['username']) > 0){
					$this->username = $post['username'];
					$this->proceed = true;
				}else{
					$this->proceed = false;
				}
				if(strlen($post['email']) > 0){
					$this->email = $post['email'];
					$this->proceed = true;
				}else{
					$this->proceed = false;
				}
				if(strlen($post['password']) >= 6){
					$this->password = $post['password'];
					$this->proceed = true;

				}else{
					$this->proceed = false;
				}

				if($this->password == $post['repeat']){
					$this->proceed = true;
				}else{
					$this->proceed = false;
				}
				
			}



			if($this->proceed){

				echo json_encode($this->doesBeheerderExists());
			}else{
				echo json_encode(array("continue" => false));
			}
		}




		private function new_beheerder(){
			try{
				$response = array();

				$this->connect = new connect();

				$email = $this->returnEmail();

				$dbh = $this->connect->returnConnection();

				$returnEmail = $this->returnEmail();
				$returnName = $this->returnName();
				$returnUserName = $this->returnUserName();
				$returnPassword = $this->returnPassword();
				$recht_id = 0;

				require_once("../../passHash.php");

				$passHash = new passhash();

				$hashed = $passHash->hash($returnPassword);

				$stmt = $dbh->prepare("INSERT INTO beheerder (name, username, email, password) VALUES(:name, :username, :email, :password)");

				$stmt->bindParam(":name", $returnName);
				$stmt->bindParam(":username", $returnUserName);
				$stmt->bindParam(":email", $returnEmail);
				$stmt->bindParam(":password", $hashed);

				$stmt->execute();

				if($stmt->rowCount() > 0){
					$response['beheerder_created'] = true;
				}else{
					$response['beheerder_created'] = false;
				}


				return $response;

			}catch(PDOException $e){
				return json_encode($e->getMessage());
			}
		}

		public function doesBeheerderExists(){

			try{
				$response = array();

				$this->connect = new connect();

				$email = $this->returnEmail();
				$returnName = $this->returnName();
				$returnUserName = $this->returnUserName();

				$dbh = $this->connect->returnConnection();

				$stmt = $dbh->prepare("SELECT email, username, name from beheerder WHERE email=:email OR username=:username OR name=:name");
				$stmt->bindParam(":email", $email);
				$stmt->bindParam(":username", $returnUserName);
				$stmt->bindParam(":name", $returnName);
				$stmt->execute();

				if($stmt->rowCount() > 0){
					$response['doesUserExist'] = true;
				}else{

					$this->new_beheerder();

					$response['doesUserExist'] = false;
				}


				return $response;

			}catch(PDOException $e){
				return json_encode($e->getMessage());
			}


		}


		public function returnName(){
			return $this->name;
		}
		public function returnUserName(){
			return $this->username;
		}
		public function returnEmail(){
			return $this->email;
		}
		public function returnPassword(){
			return $this->password;
		}


	}

	new AddBeheerder();


?>