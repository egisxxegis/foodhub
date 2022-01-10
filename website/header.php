<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<?php 
	if (isset($title) )
		echo "<title>$title</title>";
	?>
  <!-- Bootstrap CSS START-->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
    integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <!-- Bootstrap CSS END -->

  <link rel="stylesheet" type="text/css" href="style.css" />
  <link href="https://fonts.googleapis.com/css?family=Roboto:100,400,900&display=swap" rel="stylesheet">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>


</head>

<body>
  <header>
    <div class="text-center mb-5 pt-3">
	<a href="index.php">
		<div class="main-title">
			
			<?php echo isset($mainTitle)? $mainTitle:"Food Organiser"; ?>
			
		</div>
	</a>
    </div>
  </header>
  <?php if(isset($disableBackgroundContainer) && $disableBackgroundContainer)
	  echo "";
  else {
	  ?>
  <div class="gray-background">
    <div class="container product-container pb-3">
		<?php if(isset($disableSubTitle) && $disableSubTitle)
			
			echo "";
		else
			echo '<div class="text-center mb-5">
					<div class="sub-title">', isset($subTitle)? $subTitle:"subtitle", '</div>
				</div>';
		?>
	  <?php
   }
   ?>
<?php
	include 'session.php';
?>
