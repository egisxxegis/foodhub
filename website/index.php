
  <?php 
  $title = "Food Organiser";
  $mainTitle = "Food Organiser";
  $disableBackgroundContainer = true;
  include('header.php')  ?>

  <div class="background pt-2">
    <div class="container">
      <div class="text-center mb-5 mt-5">
        <a href="products.php">
          <button type="button" class="btn btn-primary button-1">Select products</button>
        </a>
      </div>
      <div class="text-center mb-5 mt-5">
        <a href="recipes.php">
          <button type="button" class="btn btn-success button-2">Get recipes</button>
        </a>
      </div> 
	  <div class="text-center mb-5 mt-5">
        <a href="restaurants.php">
          <button type="button" class="btn btn-danger button-3">I am not in mood to cook</button>
        </a>
      </div>
    </div>
  </div>
    <?php include('footer.php') ?>
