<?php

	require 'session.php';
	logout(false); //false to redirect
	newSessionStart(); //clean everything except notFirstTime
	
	echo "cleared";

?>
