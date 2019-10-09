<?php
include('security.php');
include('language.php');
include('header.php');

$char_id = $_GET['char_id'];

if( !isset( $_GET['really'] ) ){
	?>
<div class="modal show">
  <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Delete char</h5>
      </div>
      <div class="modal-body">
		<a href="char-del.php?char_id=<?=$_GET['char_id'];?>&really=true">Character really delete</a>
	</div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>
	<?
	
}else{

	$result = $db->query("SELECT `data` FROM chars WHERE `char_id`=1");
	$row = $result->fetch(PDO::FETCH_ASSOC);
	$chars = json_decode( $row['data'] );
	
	$new_chars = array();

	foreach( $chars AS $char ){
	
		if( $char->char_id != $_GET['char_id'] ){
		
			$new_chars[] = $char;
			
		}
		
	}
	
	$data = json_encode( $new_chars );
	
	if( is_JSON( $data ) ){
			
	try {

			$stmt = $db->prepare("UPDATE `chars` SET data=:data WHERE `char_id`=1");
			$stmt->bindParam(':data', $data, PDO::PARAM_STR);
			$stmt->execute();
	
		}catch(PDOException $ex) {
				echo "An Error occured! "; //user friendly message
				echo $ex->getMessage();
		}
		
	}
	?>
<div class="modal show">
  <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Char deleted</h5>
      </div>
      <div class="modal-body">
		<h5>Character deleted</h5>
	</div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>
	<?
}

include( 'footer_nochat.php' );