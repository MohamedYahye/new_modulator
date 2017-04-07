<?php 

	class API_VIEW {


		public static function returnAllUSers(){
			try{
				require_once("../connect.php");

				$connect = new connect();


				$dbh = $connect->returnConnection();

				$stmt = $dbh->prepare("SELECT name,email FROM student WHERE recht_id=1 AND student_id NOT IN (SELECT student_id FROM api)");

				$stmt->execute();

				if($stmt->rowCount() > 0){
					$returnAllUSers = $stmt->fetchAll(PDO::FETCH_ASSOC);


					return $returnAllUSers;
				}else{
					throw new Exception("Error Processing Request", 1);
					
				}

			}catch(PDOException $e){
				return $e->getMessage();
			}
		}



		public static function hashApi(){
			try{
				require_once("../connect.php");

				$connect = new connect();


				$dbh = $connect->returnConnection();

				$stmt = $dbh->prepare("SELECT name, email,student_id FROM student WHERE student_id IN(SELECT student_id FROM api)");

				$stmt->execute();

				if($stmt->rowCount() > 0){
					$returnApiHolders = $stmt->fetchAll(PDO::FETCH_ASSOC);

					return $returnApiHolders;


				}else{
					throw new Exception("Error Processing Request", 1);
					
				}

			}catch(PDOException $e){
				return $e->getMessage();
			}

		}
	}


?>