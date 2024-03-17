<?php 
	$connection = new mysqli('localhost', 'root','','dbevangelistaf1');
	
	if (!$connection){
		die (mysqli_error($mysqli));
	}
		
?>