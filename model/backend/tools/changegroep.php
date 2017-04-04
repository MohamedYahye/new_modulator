<?php 

	
	/**
	* alter groep name and members
	*/
	class ChangeGroup {


		private $proceed;
		private $member;
		private $response;
		private $group_name;
		private $connect;
		
		function __construct() {

			$this->connect = null;
			$this->response = array();

			$this->proceed = false;

			require_once("../../connect.php");

			if(!empty(isset($_POST))){
				if(!empty(isset($_POST['member']))){
					$this->member = $_POST['member'];

					//$this->response['member'] = $this->member;

					$this->proceed = true;

				}else{

					$this->proceed = false;
				}

				if(!empty(isset($_POST['group_name']))){

					$this->group_name = $_POST['group_name'];

					//$this->response['group_name'] = $this->group_name;
					$this->proceed = true;
				}else{

					$this->proceed = false;
				}
			}


			

		}


		public function removeFromGroup(){
			try{
				$this->connect = new connect();

				$user_id = $this->getMemberId();
				$getGroupId = $this->getGroupId();

				$dbh = $this->connect->returnConnection();


				$stmt = $dbh->prepare("DELETE FROM koppeltabel WHERE student_id=:student_id AND groep_id=:groep_id");

				$stmt->bindParam(":student_id", $user_id);
				$stmt->bindParam(":groep_id", $getGroupId);


				$stmt->execute();

				if($stmt->rowCount() > 0){

					$this->response['success'] = true;

				}else{
					$this->response['success'] = false;
					return false;
				}


				echo json_encode($this->response);



			}catch(PDOException $e){
				return $e->getMessage();
			}

		}

		private function getMemberId($member = ""){

			try{

				$this->connect = new connect();

				$username = $this->returnGroupMember();

				$dbh = $this->connect->returnConnection();


				$stmt = $dbh->prepare("SELECT student_id FROM student WHERE username=:username");

				$param = "";

				if(strlen($member) > 0){
					$param = $member;
				}else{
					$param = $username;
				}


				$stmt->bindParam(":username", $param);

				$stmt->execute();

				$user_id;

				if($stmt->rowCount() > 0){
					$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

					foreach($result as $res){
						$user_id = $res['student_id'];
					}

					return $user_id;
					//$this->response['member_id'] = $user_id;
				}else{
					$this->response['member_id'] = "MEMBER ID NOT FOUND";
					return false;
				}


				return false;



			}catch(PDOException $e){
				return $e->getMessage();
			}


		}

		private function getGroupId($group = ""){



			try{

				$this->connect = new connect();

				$group_name = $this->returnGroupName();


				$dbh = $this->connect->returnConnection();


				$stmt = $dbh->prepare("SELECT groep_id FROM groep WHERE groep_naam=:groep_naam");

				$param = "";

				if(strlen($group) > 0 ){

					$param = $group;
				}else{
					$param = $group_name;
				}


				$stmt->bindParam(":groep_naam", $param);

				$stmt->execute();

				$groep_id;

				if($stmt->rowCount() > 0){
					$result = $stmt->fetchAll(PDO::FETCH_ASSOC);


					foreach($result as $res){
						$groep_id = $res['groep_id'];
					}

					return $groep_id;
					//$this->response['member_id'] = $user_id;
				}else{
					$this->response['groep_id'] = "MEMBER ID NOT FOUND";
					return false;
				}


				return false;



			}catch(PDOException $e){
				return $e->getMessage();
			}


		}



		public function addMember($member, $group_name){



			try{

				$this->connect = new connect();
				$getGroupId = $this->getGroupId($group_name);
				$getMemberId = $this->getMemberId($member);


				$dbh = $this->connect->returnConnection();

				$stmt = $dbh->prepare("INSERT INTO koppeltabel (student_id, groep_id) VALUES(:student_id, :groep_id)");

				$stmt->bindParam(":student_id", $getMemberId);
				$stmt->bindParam(":groep_id", $getGroupId);

				$stmt->execute();

				if($stmt->rowCount() > 0){
					$this->response['new_member'] = true; 
				}else{
					$this->response['new_member'] = false;
				}



				echo json_encode($this->response);

			}catch(PDOException $e){
				return $e->getMessage();
			}


		}

		public function returnGroupName(){
			return $this->group_name;
		}

		public function returnGroupMember(){
			return $this->member;
		}

		public function returnProceed(){
			return $this->proceed;
		}
	}


	$ChangeGroup = new ChangeGroup();

	if(isset($_POST['member'])){
		

		if($ChangeGroup->returnProceed()){
			$ChangeGroup->removeFromGroup();
		}
	}elseif(!empty(isset($_POST['add_member']))){
		if(!empty(isset($_POST['groep_naam']))){
			$ChangeGroup->addMember($_POST['add_member'], $_POST['groep_naam']);
		}
	}else{
		echo json_encode(array("proceed"=>false));
	}



?>