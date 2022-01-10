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
		//echo $lastId;
		///*
		if ($lastId == -1) {
			//illegal entrance. Nothing to show for him/her.
			//header('Location: recipes222.php'); // does it work if something gets printed before?
			die("Tau nepavyko. gal nieko nepasirinkai");
		}
		
		//retrieve products from $_GET
		$sqlProducts = "";
		$hasProducts = false;
		foreach($_GET as $key=>$value){
			//echo "<hr>$key => $value";
			if ($key > 0 && $key <= $lastId) {
				//valid product ID
				if ($value != true)
					continue;
				if (strlen($sqlProducts) > 0)
					$sqlProducts .= ",";
				$hasProducts = true;
				$hasProduct[intval($key)] = true; //intval returns from "420" the 420
				$sqlProducts .= $key;
			}//if key between valid Ids
		}//foreach keyValue;
		
		
		if ($hasProducts == false){
			//what if user really does not have products and 
			//is searching for recipes with the least amount of needed products?
			echo "<script type='text/javascript'>
			window.alert(\"hey. I do not see you owning any products. Check them again\"); //will not be seen anyways
			</script>";
			//header('Location: products.php'); //! meh
			die("Tau ir vel nepavyko. tipo ne produktus rinkaisi");
		}
		else {
			$name = $_GET['name'];
			if (strlen($name) < 1)
				die ("koki tu cia varda davei? " . $_GET['name']);
			$sql = "INSERT INTO Recipes (name, neededProducts) VALUES ('$name', '$sqlProducts');";
			$result = mysqli_query($con, $sql);
			
			if(! $result){
				echo "tried to submit " , $_GET['name'];
				echo "<br /> sql: $sql <br />";
				die("Nesitikejau, bet nepavyko insertinti. stai klaida: " . mysqli_error($con) ); 
			}
			echo "submitted <br />Now it looks like this:<br/><hr />";
			$recipes = Commands::loadRecipes();
			foreach($recipes as $recipe){
				echo $recipe->name , "<br />";
			}
			echo "<hr />";
		}
		
		
	?>
	
	<!--html tags -->
<?php
	require_once 'footer.php';
?>
