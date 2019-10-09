<?php
include('security.php');
include('language.php');
include('header.php');

$char_id = (int) $_GET['char_id'];

if( isset( $_POST['new_name'] ) && !empty( $_POST['new_name'] ) ){

	$_GET['page'] = 'copy_char';
	include( 'setdata.php' );

	?>
<div class="modal show">
  <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Copy char</h5>
      </div>
      <div class="modal-body">
		New char created!
	</div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>
	<?

}else{


?>

<style type="text/css">

#dialog ul li{
	border: 1px solid white;
	margin: 2px;
	clear:both;
	height: 90px;
}

#dialog ul li a{
	
	display:block;
	float:left;
	margin: 5px;
	
}

</style>



<div class="modal show">
  <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Copy char</h5>
      </div>
      <div class="modal-body">
	<?
	
	$result = $db->query("SELECT `data` FROM chars WHERE `char_id`=1");
	$row = $result->fetch(PDO::FETCH_ASSOC);
	$chars = json_decode( $row['data'] );
	
	foreach( $chars AS $char ){
		
		if( $char->char_id == $char_id ){

			echo '<h4><img src="server/php/files/thumbnail/'.$char->img_url.'"/>'.$char->name.'</a></h4>';

			?>

			<form action="" method="post">

				<legend for="new_name">New char name</legend>
				<input type="text" id="new_name" name="new_name" value="" class="form-control" />
				<input type="submit" name="submit" value="submit" class="form-control" />

			</form>

			<?

		}
		
	}
	
	?>

</div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>

<?

}

include( 'footer.php' );
