<?php
	//require_once 'header.php';
	ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
	
	require_once('session.php'); //doubtful line. should we really crash if session load fails? header.php will inc session.php
	require_once('mysqliConnect.php');
	require_once('essentialClasses.php');
	
		//echo "labas";
		
		$lastId = ($_GET['li'] >= 1)? $_GET['li'] : -1;
		$realLastId = Commands::getLastProductId();
		//echo $lastId;
		///*
		if ($lastId == -1 && $lastId > $realLastId) {
			//illegal entrance. Nothing to show for him/her.
			header('Location: products.php'); // does it work if something gets printed before?
			die("Why am I still here?<br><a href='products.php'>Get me back</a>");
		}
		
		$allRecipes = Commands::loadRecipes();	
		
		//retrieve products from $_GET
		$hasProducts = false;
		foreach($_GET as $key=>$value){
			//echo "<hr>$key => $value";
			if ($key > 0 && $key <= $lastId) {
				//valid product ID
				if ($value != true)
					continue;
				$hasProducts = true;
				$hasProduct[intval($key)] = true; //intval returns from "420" the 420
			}//if key between valid Ids
		}//foreach keyValue;
		
		//print_r($hasProduct);
		Commands::setSessionProducts($hasProduct); //hasProduct[i] //and fill empty with false
		//print_r($_SESSION);
		
		if ($hasProducts == false){
			//what if user really does not have products and 
			//is searching for recipes with the least amount of needed products?
			echo "<script type='text/javascript'>
			window.alert(\"hey. I do not see you owning any products. Check them again\"); //will not be seen anyways
			</script>";
			header('Location: products.php'); //! meh
			die("Why am I still here?<br><a href='products.php'>Get me back</a>");
		}
		else {
			header('Location: recipes.php');
			echo "Congratulations. You made so far.<br>";
			echo "Nothing to see here.<br><br />";
			echo "Your selected products were loaded into your session.<br />";
			echo "<a href='recipes.php'>See suggestions</a><br /><hr>";
		}
		
		
	?>
	
	<!--html tags -->
<?php
	require_once 'footer.php';
?>
