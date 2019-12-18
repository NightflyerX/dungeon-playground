<?php
error_reporting( E_ALL );

function get_users($db){
	$result = $db->query("SELECT * FROM users");
	$my_arr = array();
	while($row = $result->fetch(PDO::FETCH_ASSOC)) {
    	$my_arr[] = array( 'user_id' => $row['user_id'], 'username' => $row['username'], 'email' => $row['email'], 'color' => $row['color'] );
	}
	return $my_arr;
}
	