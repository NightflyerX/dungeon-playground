<?php
include('security.php');
include('language.php');

foreach($db->query('SELECT * FROM `chat` ORDER BY `date` DESC LIMIT 1') as $row) {
	
	if( !isset( $_SESSION['timestamp'] ) ){

		$_SESSION['timestamp'] = $row['date'];

	}else if( $_SESSION['timestamp'] != $row['date'] ){
		
		$_SESSION['timestamp'] = $row['date'];
		
	}
	
	echo $row['date'];
	
}