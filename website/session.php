<?php
	session_start();
	function logout($redirect=true){
		session_unset(); //unset variables
		session_destroy(); //destroy session
		if ($redirect)
			header('Location: index.php') or die('Error. failed to disconnect of SESSION');
	}
	
	function newSessionStart(){ //completely for testing
		if (! (isset($_SESSION['notFirstTime']) ) ){
			//I do not remember you
			$_SESSION['notFirstTime'] = true;
		}
	}
	
	function markForProductsReset($trueOrFalse){
		if ($trueOrFalse == true)
			$_SESSION['toResetProducts'] = true;
		else
			$_SESSION['toResetProducts'] = false;
	}
	
	newSessionStart();
?>
