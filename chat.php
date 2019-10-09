<?php
include('security.php');
include('language.php');

if( isset( $_POST['new_chattext'] ) || isset( $_GET['new_chattext'] ) ){

	$new_chattext = isset( $_POST['new_chattext'] ) ? $_POST['new_chattext']."\n" : $_GET['new_chattext']."\n";
	
	$user_id = isset( $_POST['user_id'] ) ? $_POST['user_id'] : 0;
	
	$db->query('INSERT INTO `chat` (`text`, `user_id`, `date` ) VALUES( \''.$new_chattext.'\', '.$user_id.', NOW() ) ');
	
	//file_put_contents( 'chat.txt', $text, FILE_APPEND | LOCK_EX);
	//$db->query('INSERT INTO `timestamp` (`timestamp`) VALUES( CURRENT_TIMESTAMP() ) ');
	
}

//echo file_get_contents( 'chat.txt');

$result = array();

foreach ($db->query('SELECT * FROM `chat` WHERE `user_id`=0 OR `user_id`='.$_SESSION["user_id"].' ORDER BY `date` DESC, `id` DESC LIMIT 50') as $results){
	
	$result[] = $results['text'];
	
}

echo implode( '', array_reverse( $result ) );