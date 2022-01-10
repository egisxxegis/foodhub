<?php
	if(isset($disableBackgroundContainer) && $disableBackgroundContainer)
		echo "";
	else {
	?>
  </div>
  </div>
	<?php } ?>
  <footer>
  
<head>
	<!-- Bootstrap JS START -->
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
    integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
  </script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
    integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
  </script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
    integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
  </script>
  <!-- Bootstrap JS END -->
</head>

<body>
    <div class="container-fluid pl-5">
        <div class="footer-info pt-3">
            <div class="">
                Made by Dovydas, Kornelis, Mantas and Egidijus
            </div>
            <div class="pt-3">
                All rights reserved ©
            </div>

        </div>
    </div>


</body>

<?php
	/*if (isset($con) )
		mysqli_close($con);
	*/
?>

</footer>
</body>
</html>