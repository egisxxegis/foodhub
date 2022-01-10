<head>
  <?php include('header.php');
	ini_set('display_errors', 1);  			// I
	ini_set('display_startup_errors', 1);	// Want to
	error_reporting(E_ALL);					// See Errors
		
	require_once 'mysqliConnect.php';		//database connection
	require_once 'essentialClasses.php';	//class Product, class Commands
  
  ?>
  <script src="recipe222Handler.js"></script>
</head>

<body>
  <header>
    <div class="text-center mb-5 pt-3">
      <div class="main-title">Food Organiser</div>
    </div>
  </header>
  <div class="gray-background">
    <div class="container product-container pb-3">
      <div class="text-center mb-5">
        <div class="sub-title">Products</div>
      </div>

      <?php 
		
		$productsInPossession = Commands::getSessionProducts();
		
		$newJSArray = 'var arrSelected = new Array(' . Commands::getLastProductId() . ');';
		if (count($productsInPossession) > 0) {
			//we have products in session. Let JS know about it
			foreach ($productsInPossession as $id=>$truth) {
				if ($truth)
					$newJSArray .= "\narrSelected[$id] = true;";
			}
		}
		
	//next few lines will insert array of products in possession. empty or not //for js
	//' means no evaluation
	//" means yes evaluation
	echo "
	<script>
		$newJSArray
	</script>";
	
			echo '<span>Recepto pavadinimas: </span> <input type="text" id="recipeName"> <br />';
		
			$allProducts = Commands::loadProducts();
			foreach ($allProducts as $product )  {

                    echo "      
                    <div class='text-center'>
                        <div class='product-selector' onclick='eventTrigger(", $product->id ,")'>
                            <div class='product frame mt-3 mb-3' id='id-", $product->id , "' style = 'background-image: url(",$product->getImagePath(),");'>
                                <div class='product-name col-3'>
                                    " , $product->name , "
                                </div>
                                <div class='product-pic'>
                                </div>
                            </div>
                        </div>
                    </div>";
			}
	?>
			
					<div class='text-center'>
                        <div class='product-selector' onclick='submitSelection()'>
                            <div class='product frame mt-3 mb-3' id='id--1' style = 'background-color: orange;'>
                                <div class='product-name col-3'>
                                    KurtiRecepta
                                </div>
                                <div class='product-pic'>
                                </div>
                            </div>
                        </div>
                    </div>
    </div>
  </div>
  <footer>
    <?php include('footer.php') ?>
  </footer>
</body>
<script>
	triggerFromArray();
</script>

