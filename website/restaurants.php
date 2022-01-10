<?php
	$subTitle = "Restaurants";
	require_once 'header.php';
	require_once 'mysqliConnect.php';
	require_once 'essentialClasses.php';
	
	echo '<center><table border="1">';
		
	$restaurants = Commands::loadRestaurants();
	foreach ($restaurants as $rest){
		echo '<tr>
				<td>', $rest->name, '</td>
				<td><a target="_blank" href="', $rest->link, '">
					<button type="button" class="btn btn-success button-2">Visit</button>
				</td>
			</tr>';
	}
	
	echo '</table></center>';
	
	require_once 'footer.php';
?>