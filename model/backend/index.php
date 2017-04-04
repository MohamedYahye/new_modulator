<?php

	require_once("../user.php");

	$currentUserObj = new User();


	$currentUser = $currentUserObj->returnUserInfo();


	foreach($currentUser as $user){
		if($user['recht_id'] == 0){

			require_once("home_beheerder.php");


		}else{

			require_once("home_student.php");


		}
	}



?>


