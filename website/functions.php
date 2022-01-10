<?php
	
	require_once 'mysqliConnect.php'; //connect once is enough
	require_once 'essentialClasses.php'; //product, recipe

	//
	
	function buttonOrTextIfCondition($button, $text, $condition){
		if($condition)
			return $button;
		else
			return $text;
	}
	
?>