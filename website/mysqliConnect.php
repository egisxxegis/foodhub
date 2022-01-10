<?php
    //connect
	//global $con, $conFailed;
    $con = mysqli_connect("sql307.epizy.com", "epiz_24504808", "45zNqlWVZRk1pt", "epiz_24504808_foodorganiser");
							// host, 				user, 			pass, 				db_name

    $conFailed = false; //no explanation
	//should it be changed to constant? (with define(); )
    if (mysqli_connect_errno()) //only errno 0 == success (errno is error No what is error number)
    {
        $conFailed = true;
        echo "Failed to connect to MySQL: " . mysqli_connect_error(); //#is it ok to echo in random places?
    }

	//no need to read further
    /*if (!$conFailed){
        mysqli_set_charset($con,"utf8");

    }*/
	/*ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);\*/
	//echo "I exist";
?>