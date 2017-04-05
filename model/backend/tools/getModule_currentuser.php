<? 
	

	class getModuleCurrentUser{


		public static function getModule($user_id){

			try{
				require_once("../connect.php");

				$connect = new connect();

				$dbh = $connect->returnConnection();

				if(strlen($user_id) > 0){
					$stmt = $dbh->prepare("SELECT DISTINCT groep_id FROM koppeltabel WHERE student_id=:student_id");

					$stmt->bindParam(":student_id", $user_id);

					$stmt->execute();

					if($stmt->rowCount() > 0 ){
						$res = $stmt->fetch(PDO::FETCH_ASSOC);


						$stmt = $dbh->prepare("SELECT module_id FROM module_koppel WHERE groep_id=:groep_id");

						$stmt->bindParam(":groep_id", $res['groep_id']);

						$stmt->execute();

						$module_array = array();

						if($stmt->rowCount() > 0){
							$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

							$stmt = $dbh->prepare("SELECT module_locatie FROM module WHERE module_id=:module_id AND module_status!= 0");

							foreach($result as $res){
								$stmt->bindParam(":module_id", $res['module_id']);

								$stmt->execute();
								if($stmt->rowCount() > 0){
									$module = $stmt->fetchAll(PDO::FETCH_ASSOC);

									foreach($module as $_module){
										array_push($module_array, $_module['module_locatie']);
									}


								
								}
							
							}

							return $module_array;
							
						}
					}



				}else{
					echo "user_id cant be empty";
					return false;
				}

				



			}catch(PDOException $e){
				return $e->getMessage();
			}
		}
	}



?>