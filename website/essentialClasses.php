<?php

	/*ini_set('display_errors', 1);			//no
	ini_set('display_startup_errors', 1);	// errors
	error_reporting(E_ALL);*/				//  to hide
	//-------------------------------------------------------------------------
	//----------------------Interface Has Image--------------------------------
	//-------------------------------------------------------------------------
	
	interface HasImage{
		public function getImagePath();
	}
	
	//-------------------------------------------------------------------------
	//----------------------Product--------------------------------------------
	//-------------------------------------------------------------------------
	
	//not final, not abstract class
	//just a simple class
	//public properties, no getters, no setters
	//yet not static methods
	//no code comment for products

	class Product implements HasImage{
		public $id;
		public $name;
		public $price;
		
		public function __construct($id, $name, $price){
			$price = (is_numeric($price) === false)? 0.01:$price;
			$this->id 	 = $id;
			$this->name  = $name;
			$this->price = $price;
		}
		
		public function getImagePath() {
			return Commands::getImagePathForProduct($this); //this is a caller's instance or caller itself?
		}
		
	}
	
	//-------------------------------------------------------------------------
	//----------------------Recipe---------------------------------------------
	//-------------------------------------------------------------------------
	//Simple class
	//here is a trick. look to next line
	#Ha I am a comment too. Same as you
	
	class Recipe implements HasImage{
		public $id;
		public $name;
		public $arrProduct;
		public $size;
		public $price;
		public $calories;
		static $yellowZone = 2;
		
		public function __construct($id, $name, $arrProduct, $calories=0){
			$arrProduct = (is_array($arrProduct) == true)? $arrProduct : array();
			$this->id = $id;
			$this->name = $name;
			$this->arrProduct = $arrProduct;
			$this->size = count($this->arrProduct);
			$this->price = self::calculatePrice($this->arrProduct);
			$this->calories = $calories;
		}
		
		/*public function extractOwnArrayToBoolArray(){
			$arrProduct = Commands::extractProductArrayToBoolArray();
		}*/
		
		static final function calculatePrice($arrProduct) {
			$sum = 0.0;
			foreach($arrProduct as $product) {
				$sum += $product->price;
			}
			return $sum;
		}
		
		public function getImagePath() {
			return Commands::getImagePathForRecipe($this);
		}
	}
	
	//-------------------------------------------------------------------------
	//----------------------Recipe Matching------------------------------------
	//-------------------------------------------------------------------------
	
	class RecipeMatching{
		public $recipe;
		public $diff;
		public $size;
		public $marked;
		public $boolArray;
		public $matchedArray;
		public $priceDiff; //fpriceDiff?
		//calories
		
		public function __construct($recipe){
			$this->recipe = $recipe;
			$this->diff = 0;
			$this->marked = false; //for yellow ?
			$this->matchedArray = array();
			$this->priceDiff = 0.0;
		}
	}
	
	//-------------------------------------------------------------------------
	//----------------------Sort By--------------------------------------------
	//-------------------------------------------------------------------------
	
	class SortBy {
		static $options = array("expenses" => 69, "calories" => 89, "missing products" => 8484);
		//change options key names and you will have bunch of errors. :)
		private $selectedOption;
		private $ASC;
		
		public function __construct($option, $inASC=true){
			if (in_array($option, self::$options) )
				$this->selectedOption = $option;
			else
				$this->selectedOption = self::$options["missing products"];
			if (is_bool($inASC))
				$this->ASC = $inASC;
			else
				$this->ASC = true;
		}
		
		//----------------------------------------------------------
		
		public function getSelected(){
			return $this->selectedOption;
		}
		
		public function getASC(){
			return $this->ASC;
		}
		
		//----------------------------------------------------------
		
		public function generateFunctionName(){
			$func = "SortBy::compare";
			$key = array_search($this->selectedOption,self::$options);
			if ($key == false)
				$key = "missing products";
			$key = ($key == "missing products")? 'products':$key;
			$func .= ucfirst($key);
			$func .= ($this->ASC)? "ASC":"DESC";
			return $func;
		}
		
		//----------------------------------------------------------
		
		static final function compareExpensesASC($recipeM1, $recipeM2){
			return $recipeM1->priceDiff > $recipeM2->priceDiff;
		}
		
		static final function compareExpensesDESC($recipeM1, $recipeM2){
			return $recipeM1->priceDiff < $recipeM2->priceDiff;
		}
		
		static final function compareCaloriesASC($recipeM1, $recipeM2){
			return $recipeM1->recipe->calories > $recipeM2->recipe->calories;
		}   
		
		static final function compareCaloriesDESC($recipeM1, $recipeM2){
			return $recipeM1->recipe->calories < $recipeM2->recipe->calories;
		}
		
		static final function compareProductsASC($recipeM1, $recipeM2){
			return $recipeM1->diff > $recipeM2->diff;
		}
		
		static final function compareProductsDESC($recipeM1, $recipeM2){
			return $recipeM1->diff < $recipeM2->diff;
		}
	}
	
	//-------------------------------------------------------------------------
	//----------------------Restaurant-----------------------------------------
	//-------------------------------------------------------------------------
	
	class Restaurant {
		public $id;
		public $name;
		public $link;
		
		public function __construct($id, $name, $link=null){
			$this->id = $id;
			$this->name = $name;
			$this->link = (isset($link) && strlen($link)>1)? $link
				:"https://www.google.com/maps/search/$name";
		}
	}
	
	//-------------------------------------------------------------------------
	//----------------------Commands-------------------------------------------
	//-------------------------------------------------------------------------
	
	//final abstract class is not allowed in php
	//below we can see an alternative way
	//a final class (it can not be extended)
	//with a private constructor (instance can be created only from within)
	//sounds like final abstract class so why not
	
	final class Commands { 
	
		static $lastProductId=0;
		static $productList=array();
		static $productIdAndObjectConnection=array();
		static $zeroProduct; 
		static $greenRecipes;
		static $yellowRecipes;
	
		private function __construct() {
			echo "ha, you can not construct me"; //what about static methods of this class?
												 //(it is not a request for test)
		}
		
		//----------------------------------------------------------
		
		static final function getZeroProduct(){
			if(isset(self::$zeroProduct) == true)
				return self::$zeroProduct;
			self::$zeroProduct = new Product(0, "???", 0);//id name price
			return self::$zeroProduct;
		}
		
		//-----------------------------------------------------------
	
		static final function getImagePathForProduct($product) {//# is ref passed or cloned object? //#check
			$path = "images/Products/" . $product->id . ".jpg"; //let the path be images/Products/12.jpg
			return (file_exists($path) === true)? $path : "images/Products/0.jpg";
		}
		
		//----------------------------------------------------------
		
		static final function getImagePathForRecipe($recipe) {//# ref ? (not byref keyword I mean)
			$path = "images/Recipes/" . $recipe->id . ".jpg";
			return (file_exists($path) === true)? $path : "images/Recipes/0.jpg";
		}
		
		//----------------------------------------------------------
		
		static final function loadProducts($connection='-', $force=false)	{  //ignore $fetchtype
			if (count(self::$productList) > 0 && $force == false)
				return self::$productList;
			global $con,$conFailed; //include $con, $conFailed variables to 
									//the scope of this function. Gets variables from outside of this function
			$connection = ($connection == '-')? $con:$connection;
			if (isset($connection) === false)
				return array();
			if ($conFailed === true) //$conFailed is global
				return array(); //absence of value 	//# change to NULL //#or no
			//alternative - $fetchType=MYSQLI_ASSOC; or MYSQLI_BOTH (//#check it))
			$sql    = "SELECT * FROM Products"; //to mess with case-sensitive stuff is bad.
												//only SQL reserved words are case-insensitive
			$result = mysqli_query($connection, $sql); //send query, get answer
			if (! $result) //empty answer
				return array();
			$arrayOfProducts = array(); //yes it is exactly the thing you have thought about
			$i = 0;
			while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC) ){ //was $fetchType. But let us keep it stable
				//mysqli_fetch_array explodes (parses) result into row (line (array)).
				//from Z x Y matrix to 1 x Y matrix
				//next iteration fetcher will give you next (i+1)th row (line (array));
				$id = $row['ID'];
				$name = $row['Name'];
				$price = $row['Price'];
				$newProduct = new Product($id,$name,$price);
				$arrayOfProducts[$i++] = $newProduct; //yes
			}//while
			mysqli_free_result($result); //is it needed? it does not hurt to call this function
			self::$productList = $arrayOfProducts;
			return self::$productList;
		}
		
		//----------------------------------------------------------
		
		static final function loadRecipes($connection='-', $productFormatCheck=true) { //about format. 
																	//checks for invalid characters in an array of products' id
																	//if finds, returns empty array
																	//without format it would explode and return sorted array
			//identical to loadProducts($connection);
			global $con,$conFailed; //include $con, $conFailed variables to 
									//the scope of this function. Gets variables from outside of this function
			$connection = ($connection == '-')? $con:$connection;
			if (isset($connection) === false) #$con not set or $connection is null
				return array();
			if ($conFailed === true) //$conFailed is global0
				return array(); //absence of value 	//# change to NULL //#or no
			//alternative - $fetchType=MYSQLI_ASSOC; or MYSQLI_BOTH (//#check it))
			$sql    = "SELECT * FROM Recipes"; //to mess with case-sensitive stuff is bad.
												//only SQL reserved words are case-insensitive
			$result = mysqli_query($connection, $sql); //send query, get answer
			if (! $result) //empty answer
				return array();
			$arrayOfRecipes = array(); //yes it is exactly the thing you have thought about
			$ithRecipe = 0;
			while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC) ){ //was $fetchType. But let us keep it stable
				//mysqli_fetch_array explodes (parses) result into row (line (array)).
				//from Z x Y matrix to 1 x Y matrix
				//next iteration fetcher will give you next (i+1)th row (line (array));
				//#to be implemented
				$id = $row['ID'];
				$name = $row['name'];
				$products = $row['neededProducts'];
				$calories = $row['calories'];
				//let us say $products == '!', when we do not know neededProducts for recipe
				if ($products == '!')
					$products = array();
				else{
					if ($productFormatCheck == false){
						$products = explode(",", $products); //parse
						sort($products);
					}
					else {
						//it is not empty
						$len = strlen($products); 
						$products2 = ""; //we will store here products' string without spaces
						$validFormat = true; //after for loop we will know truth
						for($i=0; $i<$len; $i++){
							if(is_numeric($products[$i] ) === true) //valid number
								$products2 .= $products[$i];
							else if ($products[$i] == ','){ //valid coma
								if ($products2[-1] != ',') #-1 is last char //when this is true <--
									$products2 .= $products[$i];
								else {
									$validFormat = false;
									break;
								}
							}//coma ,
							else if ($products[$i] == ' ') //valid space
								continue;
							else {
								$validFormat = false;
								break;
							}
						}//for every char
						if ($validFormat == false) //any format error?
							$products = array();
						else {
							$products = explode(",", $products2); //here is output format
							sort($products);
							//insert extract if you want
							$products = self::extractProductIdArrayToProductArray($products);
						}//valid format
					}//valid requested
				}//not empty
				$newRecipe = new Recipe($id, $name, $products, $calories); //yes
				//$newProduct = new Product($id,$name,$price);
				$arrayOfRecipes[$ithRecipe++] = $newRecipe; //yes
				//print_r($newRecipe);
				
			}//while row
			mysqli_free_result($result); //is it needed? it does not hurt to call this function
			return $arrayOfRecipes;
		}//loadRecipes(a,b)
		
		//----------------------------------------------------------
		
		static final function loadRestaurants($connection='-')	{
			global $con,$conFailed; //include $con, $conFailed variables to 
									//the scope of this function. Gets variables from outside of this function
			$connection = ($connection == '-')? $con:$connection;
			if (isset($connection) === false)
				return array();
			if ($conFailed === true) //$conFailed is global
				return array(); //absence of value 	//# change to NULL //#or no
			//alternative - $fetchType=MYSQLI_ASSOC; or MYSQLI_BOTH (//#check it))
			$sql    = "SELECT * FROM Restaurants"; //to mess with case-sensitive stuff is bad.
												//only SQL reserved words are case-insensitive
			$result = mysqli_query($connection, $sql); //send query, get answer
			if (! $result) //empty answer
				return array();
			$arrayOfRestaurants = array(); //yes it is exactly the thing you have thought about
			$i = 0;
			while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC) ){ //was $fetchType. But let us keep it stable
				//mysqli_fetch_array explodes (parses) result into row (line (array)).
				//from Z x Y matrix to 1 x Y matrix
				//next iteration fetcher will give you next (i+1)th row (line (array));
				$id = $row['ID'];
				$name = $row['name'];
				$link = $row['link'];
				$newRestaurant = new Restaurant($id,$name,$link);
				$arrayOfRestaurants[$i++] = $newRestaurant; //yes
			}//while
			mysqli_free_result($result); //is it needed? it does not hurt to call this function
			return $arrayOfRestaurants;
		}
		
		//----------------------------------------------------------
		
		static final function getLastProductId($connection='-') {
			if (self::$lastProductId != 0) //magical syntax. compare it to $this->lastProductId; (no to $this for static ...)
				return self::$lastProductId;
			//we must update our static variable $lastProductId;
			global $con,$conFailed;
			$connection = ($connection == '-')? $con:$connection;
			if (isset($connection) === false) #$con not set or $connection is null
				return 0;
			if ($conFailed === true) //$conFailed is global
				return 0; 
			
			$SQL = "SELECT MAX(ID) FROM Products"; //result will be 1 (not 1st) row 
			$result = mysqli_query($con, $SQL);
			if (! $result)
				return 0;
			
			$row = mysqli_fetch_array($result, MYSQLI_NUM); //notice that we do not loop
			self::$lastProductId = $row[0];
			return self::$lastProductId;
			
		}
		
		//----------------------------------------------------------
		
		static final function extractProductArrayToBoolArray($packedArray){
			//careful! below we have similar function
			$boolArray = array();
			//$boolArray[0] = true;
			
			$lastId = 0;
			foreach($packedArray as $product){
				$boolArray[intval($product->id)] = true; //this is true
				
				/*
					//everything behind this and previous will be false
					//after all product arr is sorted. unless you messed with it
					for($i = $product->id - 1; isset($boolArray[$i]) == false; $i--)
						$boolArray[$i] = false;
					$lastId = $product->id;
				*/
			}
			
			/*
				//counter case when highest Id product was not used
				$maxId = self::getLastProductId();
				for($i = $maxId; isset($boolArray[$i]) == false; $i--)
					$boolArray[$i] = false;
			*/
			
			return $boolArray;
		}
		
		//----------------------------------------------------------
		
		static final function extractBoolArrayToProductArray($boolArray){
			//first to productId array
			$productIdArray = array();
			$i = 0;
			foreach($boolArray as $productId => $has){
				if (! $has)
					continue; //how did it get here'
				$productIdArray[$i++] = $productId;
			}
			//now we have product id array
			//return product arrau
			return self::extractProductIdArrayToProductArray($productIdArray);
		}
		
		//----------------------------------------------------------
		
		static final function extractProductIdArrayToBoolArray($packedArray){
			//careful! above we have similar function
			$boolArray = array();
			//$boolArray[0] = true;
			
			$lastId = 0;
			foreach($packedArray as $productId){
				$boolArray[intval($productId)] = true; //this is true
				
				/*
					//everything behind this and previous will be false
					//after all product arr is sorted. unless you messed with it
					for($i = $productId-1; isset($boolArray[$i]) == false; $i--)
						$boolArray[$i] = false;
				$lastId = $productId;
				*/
			}
			
			/*
				//counter case when highest Id product was not used
				$maxId = self::getLastProductId();
				for($i = $maxId; isset($boolArray[$i]) == false; $i--)
					$boolArray[$i] = false;
			*/
			
			return $boolArray;
		}
		
		//----------------------------------------------------------
		
		static final function extractProductIdArrayToProductArray($packedArray){
			$products = self::loadProducts();
			$productArray = array();
			$countProducts = count($products);
			
			$i = 0;
			foreach($packedArray as $productId){
				//which object has that id 
				if(isset(self::$productIdAndObjectConnection[$productId]) ){
					if (self::$productIdAndObjectConnection[$productId] == -1) {
						$productArray[$i++] = self::getZeroProduct();
						continue;
					}
					else {
						$productArray[$i++] = $products[self::$productIdAndObjectConnection[$productId] ];
						continue;
					}
				}
				//let us find it out
				$last = min($countProducts-1, $productId-1);
				if ($last == -1)
					return $productArray;
				//try last
				if ($products[$last]->id == $productId){
					$productArray[$i++] = $products[$last];
					self::$productIdAndObjectConnection[$productId] = $last;
					continue;
				}
				//unfortunately, we will have to wander
				//____________________________________________________
				if ($products[$last]->id > $productId){
					//go down
					for($ii = $last-1; $ii >= 0; $ii--){
						if($products[$ii] == $productId){
							$productArray[$i++] = $products[$ii];
							self::$productIdAndObjectConnection[$productId] = $ii;
							break; //this for
						}
						if($products[$ii] < $productId) {
							$productsArray[$i++] = self::getZeroProduct();
							self::$productIdAndObjectConnection[$productId] = -1;
							break; //this for
						}
					}//for
					if (isset(self::$productIdAndObjectConnection[$productId]) == true)
						continue;
					else {
						//we reached the end and still nothing
						$productArray[$i++] = self::getZeroProduct();
						self::$productIdAndObjectConnection[$productId] = -1;
						continue;
					}
				}//if go down___________________________________________
				if ($products[$last]->id < $productId){
					//one of the wtf scenarios but what ever.
					//we tried $products[$id-1] and found a lower ID !!
					//ridiculous, isn't it ?
					//let us blame sorting thing for loadingProducts
					//and get the hell out of here
					return $productArray;
				}
			}//foreach productId
			return $productArray;
		}// function
		
		//----------------------------------------------------------
		
		static final function setSessionProducts($arrayOfBooleansOfProductsYouHave=array(), $fillEmptyWithFalse=true) {
			//array[5] = true   means that you have product which can be identified by number 5
			if ($fillEmptyWithFalse == false){ //easy approach
				foreach($arrayOfBooleansOfProductsYouHave as $idOfProduct=>$doYouHaveIt){ //no need to check for empty arrays
					$_SESSION["hasProduct$idOfProduct"] = boolval($doYouHaveIt); //let us store values same way
				}
			}
			else { //some more code
				$lastId = self::getLastProductId();
				for($i=1; $i<=$lastId; $i++){
					$label = "hasProduct$i";
					if (isset($arrayOfBooleansOfProductsYouHave[$i]) == false)
						$_SESSION[$label] = false;
					else
						$_SESSION[$label] = boolval($arrayOfBooleansOfProductsYouHave[$i]); //boolval evaluates to true or false
				}
			}
		}
		
		//----------------------------------------------------------
		
		static final function getSessionProducts() {
			if (isset($_SESSION) === false || $_SESSION['notFirstTime'] !== true)
				return array();
			$newArrayOfBooleans = array();
			$lastId = self::getLastProductId();
			for($i=1; $i<=$lastId; $i++) {
				$label = "hasProduct$i"; //hasProduct1 hasProduct8 ...
				if (isset($_SESSION[$label]) == false)
					continue;
				if ($_SESSION[$label]) {
					//yes, he,she,it has
					$newArrayOfBooleans[$i] = true;
				}//if has
			}//for i<lastId
			return $newArrayOfBooleans;
		}//sessionProducts
		
		//----------------------------------------------------------
		
		/*static final function unsetSessionProducts() {
			echo "good day";
		}*/
		
		//----------------------------------------------------------
		
		static final function createProductsMatching($recipesInBasicFormat){
			$allRecipes = array(); //recipe matching array
			$i = 0;
			foreach($recipesInBasicFormat as $recipe){
				//echo $recipe->name , "...";
				$recipeMatching = new RecipeMatching($recipe);
				$recipeMatching->boolArray = self::extractProductArrayToBoolArray($recipe->arrProduct); //false == ! isset() 
				//print_r($recipeMatching->boolArray);
				//echo "<br />";
				$recipeMatching->diff = count($recipeMatching->boolArray);
				$recipeMatching->size = $recipeMatching->diff;
				//$recipeMatching->matchedArray = array(); //constructor does this
				$allRecipes[$i++] = $recipeMatching; // pay attention here if you are lost
			}
			return $allRecipes;
		}
		
		//----------------------------------------------------------
		
		static final function matchRecipes($arrayOfRecipeMatching, $productsInPossession){ //void
			$i = 0;
			$ii = 0;
			$greenRecipes = array();
			$yellowRecipes = array();
			foreach($arrayOfRecipeMatching as $recipeM){
				foreach($productsInPossession as $id => $has){
					if($has == false)
						continue; //how did it get here?
					if (isset($recipeM->boolArray[$id]) && $recipeM->boolArray[$id] == true){
						$recipeM->diff -= 1;
						unset($recipeM->boolArray[$id]);//for yellow
						$recipeM->matchedArray[$id] = true;
					}
					if ($recipeM->diff <= 0){
						break; //no need to check other products
					}
				}//foreach product
				
				if ($recipeM->diff <= 0){
					$greenRecipes[$i++] = $recipeM;
					$recipeM->marked = true;
				}
				
				$recipeM->matchedArray = self::extractBoolArrayToProductArray($recipeM->matchedArray);
				$recipeM->priceDiff = $recipeM->recipe->price - Recipe::calculatePrice($recipeM->matchedArray);
				
				if ($recipeM->marked == false && $recipeM->diff <= Recipe::$yellowZone){
					$recipeM->marked = true;
					$yellowRecipes[$ii++] = $recipeM;
					continue;
				}
			
			}//foreach recipeMatching
			
			
			self::$greenRecipes  =  $greenRecipes;
			self::$yellowRecipes = $yellowRecipes;
			//void
			return;
		}
		
		//----------------------------------------------------------
		
		static final function getGreenRecipesBy($sortCrit){
			if (!isset(self::$greenRecipes) || !isset(self::$yellowRecipes)){
				//match green recipes and yellow
				$productsInPossession = self::getSessionProducts();
				$recipes = self::loadRecipes();
				$recipesMatching = self::createProductsMatching($recipes);
				self::matchRecipes($recipesMatching, $productsInPossession);
			}
			if($sortCrit != null)
				usort(self::$greenRecipes, $sortCrit->generateFunctionName() );
			return self::$greenRecipes;
		}
		
		//----------------------------------------------------------
		
		static final function getYellowRecipesBy($sortCrit){
			if (!isset(self::$greenRecipes) || !isset(self::$yellowRecipes))
				getGreenRecipesBy(null);
			if ($sortCrit != null)
			usort(self::$yellowRecipes, $sortCrit->generateFunctionName() );
			return self::$yellowRecipes;
		}
		
	}//Commands
	
	//echo "classes created";
	
?>
