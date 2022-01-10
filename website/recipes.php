<head>
	<title>cook 'em all</title>
</head>



<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$title = "cook 'em all";
$mainTitle = "Food Organiser";
$subTitle  = "Recipes by your products";

require_once 'header.php';
require_once 'session.php';
require_once 'mysqliConnect.php';
require_once 'essentialClasses.php';
require_once 'functions.php';


//sorting get
$currentSorting = (isset($_GET['sortBy'])) ? $_GET['sortBy'] : 'missing products';
$currentSorting = (isset(SortBy::$options[$currentSorting])) ? $currentSorting : 'missing products';
$currentOrderIsASC = (isset($_GET['ASC'])) ? boolval($_GET['ASC']) : true;
$currentSortBy = new SortBy(SortBy::$options[$currentSorting], $currentOrderIsASC);

?>

<form action='recipes.php' method='GET'>
	<span> Sort By: </span><br />
	<?php

	echo buttonOrTextIfCondition(
		"<input type='submit' name='sortBy' value='missing products'>",
		"  <u>missing products</u> ",
		$currentSorting != 'missing products'
	);
	echo buttonOrTextIfCondition(
		"<input type='submit' name='sortBy' value='expenses'>",
		"  <u>expenses</u> ",
		$currentSorting != 'expenses'
	);
	echo buttonOrTextIfCondition(
		"<input type='submit' name='sortBy' value='calories'>",
		"  <u>calories</u> ",
		$currentSorting != 'calories'
	);
	echo "<input type='hidden' name='ASC' value='$currentOrderIsASC'>";
	?>
	<span> In
		<?php
		if ($currentOrderIsASC)
			echo "ASC";
		else {
			echo "<a href='recipes.php?sortBy=$currentSorting&ASC=1'>
						ASC
						</a>";
		}

		echo " / ";

		if ($currentOrderIsASC) {
			echo "<a href='recipes.php?sortBy=$currentSorting&ASC='>
						DESC
						</a>";
		} else {
			echo "DESC";
		}
		?>
		Order</span>
</form>

<?php
//end sorting get

//$ASC = true;
//$sortBy = new SortBy(SortBy::$options['expenses'], $ASC);
$sortBy = $currentSortBy; //go to //sorting get

$greenRecipes = Commands::getGreenRecipesBy($sortBy);
$yellowRecipes = Commands::getYellowRecipesBy($sortBy);
//@@ bookmark for you. do not get deceived by name
foreach ($yellowRecipes as $recipeM) {
	$recipeM->boolArray = Commands::extractBoolArrayToProductArray($recipeM->boolArray);
}

//$productsFromDb = Commands::loadProducts();

//green 

/*
echo "<div style='background-color: lime'>";
foreach ($greenRecipes as $recipeM) {
	// echo "<pre>";
	// print_r($recipeM);
	// echo "</pre>";
	echo "<div style='border:5px solid green;'><b>", $recipeM->recipe->name, "</b>
				<div style='float:right; text-align:right;'>
					", $recipeM->recipe->price, '€ / ', $recipeM->recipe->price, '€ <br />
					', $recipeM->recipe->calories, " calories 
				</div>
				<div class='product-selector'>
					<div class='product frame mt-3 mb-3' style = 'background-image: url(", $recipeM->recipe->getImagePath(), "); background-size: 100% 100%;'>
					</div>
				</div><hr /><i>";
	$notFirst = false;
	foreach ($recipeM->recipe->arrProduct as $product) {
		if ($notFirst)
			echo ", ";
		echo $product->name;
		$notFirst = true;
	}
	echo "</i></div><br />";
}
echo "</div><hr />";

//yellow


echo "<div style='background-color: khaki'>";
foreach ($yellowRecipes as $recipeM) {
	echo "<div style='border:5px solid yellow;'><b>", $recipeM->recipe->name, "</b>
				<div style='float:right; text-align:right;'>
					(", $recipeM->recipe->price - $recipeM->priceDiff, '€ + ',
		'<span style="color:red;">',
		$recipeM->priceDiff,
		'</span>',
		'€) / ',
		$recipeM->recipe->price,
		'€ <br />
					',
		$recipeM->recipe->calories,
		" calories 
				</div><hr /><i>";
	$notFirst = false;
	foreach ($recipeM->matchedArray as $product) {
		if ($notFirst)
			echo ", ";
		echo $product->name;
		$notFirst = true;
	}
	echo "</i><i style='color:red;'>";
	//@@ go to bookmark. it is not a bool array anymore
	foreach ($recipeM->boolArray as $product) {
		if ($notFirst)
			echo ", ";
		echo $product->name;
		$notFirst = true;
	}
	echo "</i></div><br />";
}
echo "</div><hr />";
*/

?>

<!-- <div class="container mb-3">
	<div class="recipe-box" style='background-image: url("images/Recipes/8.jpg")'>
		<div class="recipe-title mb-5">
			Kepti agurkai
			<div class="recipe-price">
				12.12€
			</div>
			<br>
			<div class="recipe-calories">
				1337 cal
			</div>
		</div>
		<div class="recipe-products">
			Agurkai, keptuve, aliejus
		</div>
	</div>
</div> -->

<?php
foreach ($greenRecipes as $recipeM) {
	$products = '';
	$notFirst = false;
	foreach ($recipeM->matchedArray as $product) {
		if ($notFirst)
			$products = $products . ", ";
		$products = $products . $product->name;
		$notFirst = true;
	}
	echo '
<div class="container mb-3">
	<div class="recipe-box" style="border:10px solid green; padding-bottom:160px; background-image: url(', $recipeM->recipe->getImagePath(), ');">
		<div class="recipe-title mb-5">
			' , $recipeM->recipe->name , '
			<div class="recipe-price">
				' , $recipeM->recipe->price , '€
			</div>
			<br>
			<div class="recipe-calories">
				' , $recipeM->recipe->calories , ' cal
			</div>
		</div>
		<div class="recipe-products">
			' , $products , '
		</div>
	</div>
</div>

';
	$products = '';
}

echo '<hr /><hr />';

foreach ($yellowRecipes as $recipeM) {
	$products = '';
	$notFirst = false;
	foreach ($recipeM->boolArray as $product) {
		if ($notFirst)
			$products = $products . ", ";
		$products = $products . $product->name;
		$notFirst = true;
	}
	echo '
<div class="container mb-3">
	<div class="recipe-box" style="padding-bottom:160px; background-image: url(', $recipeM->recipe->getImagePath(), ');">
		<div class="recipe-title mb-5">
			' , $recipeM->recipe->name , '
			<div class="recipe-price">
				' , $recipeM->recipe->price , '€
			</div>
			<br>
			<div class="recipe-calories">
				' , $recipeM->recipe->calories , ' cal
			</div>
		</div>
		<div class="recipe-products">
			' , $products , '
		</div>
	</div>
</div>

';
	$products = '';
}



?>


<?php include('footer.php') ?>