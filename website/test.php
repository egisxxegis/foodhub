<?php

	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	function report($text, $success, $leVel=-1){
		global $level;
		$leVel = ($leVel == -1)? $level:$leVel;
		echo "<br />";
		while( $leVel-- > 0) //tabulation
			echo '___';
		if ($success)
			echo "<i style='color: green;'> [+] "; //+
		else
			echo "<i style='color: red;'> [-] "; //-
		echo $text . '</i>';
	}
	
	function assertReport($test, $expectedResult, $text, $leVel=-1) {
		if ($test == $expectedResult)
			report("$text . Got: $test",1,$leVel);
		else
			report("$text . Got: $test Expected: $expectedResult",0,$leVel);
	}
	
	function assertNotReport($test, $notExpectedResult, $text, $leVel=-1) {
		if ($test == $notExpectedResult)
			report("$text . Got: $test notExpected: $notExpectedResult",0,$leVel);
		else
			report("$text . Got: $test",1,$leVel);
	}
	
	$level = 0;
	$text  = "include essentials";
	//if (include('essentialClasses.php') == TRUE ){ //for magical reasons php manual does not reccommend it
											//https://www.php.net/manual/en/function.include.php#example-126
											//from there: // won't work, evaluated as include(('vars.php') == TRUE), i.e. include('')
	if ((include 'essentialClasses.php') == TRUE){ //who knows why this is superior to that if above me
		report($text,1,$level);
		$level++;
		//if you uncomment, further code will do nothing (it is like a crash)
		/*$proNoConstr = new Product(); //product no argument constructor
		$product = $proNoConstr;
		report("new product no arg construct. id:$product->id, name:$product->name, price:$product->price",1,$level);*/
		
		$id = -1; $name = "Egg"; $price = 2.24;
		$proYesConstr= new Product($id,$name,$price);
		$product = $proYesConstr;
		report("new product yes arg construct($id,$name,$price). id:$product->id, name:$product->name, price:$product->price",1,$level);
		assertReport(Commands::getImagePathForProduct($product), "images/Products/0.jpg", "check getImagePathForProduct wih invalid? id", $level);
		assertReport($product->getImagePath(), "images/Products/0.jpg", "check getImagePath from Product class wih invalid? id", $level);
		
		$id = 10; $name = "Egg"; $price = "";
		$proYesConstr= new Product($id,$name,$price	);
		$product = $proYesConstr;
		report("new product yes arg construct($id,$name,$price). id:$product->id, name:$product->name, price:$product->price",1,$level);
		assertReport(Commands::getImagePathForProduct($product), "images/Products/$id.jpg", "check getImagePathForProduct wih valid? id", $level);
		
		$id = 11; $name = "Hamburger"; $arrProduct = array(1,4,6,8);
		$recipeYesConstr = new Recipe($id, $name, $arrProduct);
		$recipe = $recipeYesConstr;
		report("new recipe yes arg construct($id, $name, array...). id:$recipe->id, name:$recipe->name, arrProduct:...(ignored)",1);
		assertReport($recipe->getImagePath(), "images/Recipes/0.jpg", "check getImagePath from Recipe class with invalid? id");
		
		//products
		//Commands::loadProducts(NULL);
		$level++;
		assertReport(Commands::loadProducts(), NULL, "loadProducts() without mysqli failed successfully? green - yes", $level);
		
		$text = "include mysqliConnect";
		if ( (include('mysqliConnect.php')) == TRUE) {
			report($text,1,0);
			
			$arrayToProducts = Commands::loadProducts();
			$text = "loadProducts no arg";
			if (count($arrayToProducts) > 0){
				report($text . count($arrayToProducts),1);
				$level++;
				assertReport($arrayToProducts[3]->id, 4, "4th row id value check.");
				assertReport(count($arrayToProducts), 10, "loaded all products.");
				#print_r($arrayToProducts); //#uncomment to see structure
				$level--;
			}
			else
				report($text,0);

			$text = "loadRecipes no arg";
			$arrayOfRecipes = Commands::loadRecipes();
			if (count($arrayOfRecipes) > 0) {
				report($text . count($arrayOfRecipes),1);
				$level++;
				//print_r($arrayOfRecipes);
				assertReport($arrayOfRecipes[1]->id, 2, "2nd row id value check.");
				assertReport(count($arrayOfRecipes[0]->arrProduct), 0, "[0]  ! means empty array.");
				assertNotReport(count($arrayOfRecipes[1]->arrProduct), 0, "[1]   it has not to be empty array");
				$level--;
			}
			else
				report($text,0);
			
			$text = "loadRecipes yes arg - formatValidCheck=false   ";
			$arrayOfRecipes2 = Commands::loadRecipes($con,false);
			if (count($arrayOfRecipes2) > 0) {
				report($text . count($arrayOfRecipes2), 1);
				$level++;
				assertReport(count($arrayOfRecipes2[0]->arrProduct), 0, "[0]  ! means empty array");
				assertNotReport(count($arrayOfRecipes2[1]->arrProduct), 0, "[1]   it must not be empty array");
				assertNotReport(count(array_diff($arrayOfRecipes[1]->arrProduct, $arrayOfRecipes2[1]->arrProduct)), 0, "formated array should not be equal to not formatted one. (check in source: //#x)   ");
				/* //#x files here. somehow they are equal but array_diff says otherwise
				print_r($arrayOfRecipes[1]->arrProduct);
				echo "<br>";
				print_r($arrayOfRecipes2[1]->arrProduct);
				*/
				$level--;
			}
			else
				report($text,0);
			
			assertReport(Commands::$lastProductId, 0, "Commands::\$lastProductId by default is 0 ");
			assertReport(Commands::getLastProductId(), $arrayToProducts[count($arrayToProducts)-1]->id, "getLastProductId works correctly ");
			assertNotReport(Commands::$lastProductId, 0, "Commands::\$lastProductId was updated after getter ");
			
			assertReport(isset($_SESSION), false, "our session is not yet set ");
			
			$level++;
			$text = "include session";
			if ( (include 'session.php') == true) {
				report($text,1,0);
				$letUsSayIHaveThese = array("1"=>1,0,0,0,0,1);
				ob_start();
				print_r($letUsSayIHaveThese);
				$temp = ob_get_contents();
				ob_end_clean();
				report("let us say we have an array of products' id like this. " . $temp, 1);
				
				logout(false); //false to redirect
				newSessionStart(); //clean everything except notFirstTime
				$arrayOfSession = Commands::getSessionProducts();
				ob_start();
				print_r($arrayOfSession);
				$temp = ob_get_contents();
				ob_end_clean();
				report("our session variables after logout and new session: " . $temp, 1);
				
				Commands::setSessionProducts($letUsSayIHaveThese);
				$arrayOfSession2= Commands::getSessionProducts();
				ob_start();
				print_r($arrayOfSession2);
				$temp = ob_get_contents();
				ob_end_clean();
				assertNotReport(count(array_diff($arrayOfSession2, $arrayOfSession)), 0, "after setting new product list ($temp), they should not be equal ");
			}
			else
				report($text,0,0);
			//level SESSION
			$level--;
			
			
			//level loadProducts, loadRecipes
		}
		else //include mysqliConnect.php
			report($text,0,0);
		$level--;
		//level essentials items (below)
		$level--;
	}
	else
		report($text,0,$level);
	//level include
	
	report("tests done",1,50);
		
?>
