<?php
include('security.php');
include('language.php');
include('header.php');

$camp_id = (int) $_GET['camp_id'];

if( isset( $_POST['edit_campaign'] ) ){

	$stmt = $db->query("SELECT * FROM campaign WHERE status='active' && camp_id !=".$camp_id." ");
	$row_count = $stmt->rowCount();

	if( $_POST['status'] == 'active' ){
		
		if( $row_count > 0 ){
			
			?>
<div class="modal show">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><?=$l[$lang]['CAMPAIGN_SHOP_ERROR_TITLE'];?></h5>
      </div>
      <div class="modal-body">
			<?=$l[$lang]['CAMPAIGN_SHOP_ERROR_MSG'];?>
	</div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>
<?
			die();
			
		}else{

			$_SESSION['camp_id'] = $camp_id;
			$_SESSION['is_dm'] = true;

		}
		
	}else{

		unset( $_SESSION['camp_id'] );
		$_SESSION['is_dm'] = false;

	}
	
	
	
	$yesno = isset( $_POST['yesno'] ) && $_POST['yesno'] == true ? true : false;
	
	try {

		$stmt = $db->prepare("UPDATE campaign SET name=:name, descr=:descr, public=:public, status=:status WHERE camp_id=:camp_id && master=:master");
		$stmt->bindParam(':name', $_POST['name'], PDO::PARAM_STR);
		$stmt->bindParam(':descr', $_POST['descr'], PDO::PARAM_STR);
		$stmt->bindParam(':public', $yesno, PDO::PARAM_BOOL);
		$stmt->bindParam(':status', $_POST['status'], PDO::PARAM_STR);
		$stmt->bindParam(':camp_id', $camp_id, PDO::PARAM_INT);
		$stmt->bindParam(':master', $_SESSION['user_id'], PDO::PARAM_INT);
		$stmt->execute();
		echo "camp-id: ".$db->lastInsertId()." <br />";


	}catch(PDOException $ex) {
			echo "An Error occured! "; //user friendly message
			echo $ex->getMessage();
	}
	
}


foreach($db->query('SELECT * FROM campaign LEFT JOIN users ON ( master=user_id ) WHERE camp_id='.$camp_id.' ') as $row) {
	
	if( $row['public'] == 0 && $row['master'] != $_SESSION['user_id'] ){
		
			?>
<div class="modal show">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Fehler</h5>
      </div>
      <div class="modal-body">
			<?=$l[$lang]['EDIT_CAMPAIGN_ERROR_MSG2'];?>
	</div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>
<?
		die();
		
	}
	
	?>
<div class="modal show">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><?=$l[$lang]['EDIT_CAMPAIGN_TITLE'];?></h5>
      </div>
      <div class="modal-body">
        <form action="" method="post">
        	<div class="form-group">
					<label for="name" class="text-white"><?=$l[$lang]['CAMPAIGN_CAMP_NAME'];?></label>
			    <input type="text" class="form-control" id="name" name="name" placeholder="<?=$l[$lang]['CAMPAIGN_CAMP_NAME'];?>" value="<?=$row['name'];?>">
				<small class="form-text">Dungeonmaster: <?=$row['username'];?></small>
			  </div>
				<div class="form-group">
					<label for="descr" class="text-white"><?=$l[$lang]['CAMPAIGN_CAMP_DESCR'];?></label>
			    <textarea name="descr" style="width:470px;height:200px;" class="form-control"><?=$row['descr'];?></textarea>
			  </div>
			  <div class="form-check">
				<input class="form-check-input" name="yesno" type="radio" value="0" id="priv" <? if( $row['public'] == 0 ){ echo ' checked="checked" '; }?>>
				<label class="form-check-label" for="priv">
					<small><?=$l[$lang]['CAMPAIGN_CAMP_PRIVATE'];?></small>
				</label>
			</div>
			  <div class="form-check">
				<input class="form-check-input" name="yesno" type="radio" value="1" id="open" <? if( $row['public'] == 1 ){ echo ' checked="checked" '; }?>>
				<label class="form-check-label" for="open">
					<small><?=$l[$lang]['CAMPAIGN_CAMP_PUBLIC'];?></small>
				</label>
			</div>
			<div class="form-group">
				<label for="status" class="text-white">Status</label>
				<select name="status" class="form-control" id="status">
					<option value="new"<? if( $row['status'] == 'new' ){ echo ' selected="selected" '; }?>><?=$l[$lang]['CAMPAIGN_STATUS_NEW'];?></option>
					<option value="active"<? if( $row['status'] == 'active' ){ echo ' selected="selected" '; }?>><?=$l[$lang]['CAMPAIGN_STATUS_ACTIVE'];?></option>
					<option value="completed"<? if( $row['status'] == 'completed' ){ echo ' selected="selected" '; }?>><?=$l[$lang]['CAMPAIGN_STATUS_FINISHED'];?></option>
				</select>
			</div>
			  <button type="submit" class="btn btn-primary" name="edit_campaign" value="Editieren" <? if( $_SESSION['user_id'] != $row['master'] ){ echo ' disabled="disabled" '; } ?>><?=$l[$lang]['CAMPAIGN_CAMP_EDIT'];?></button>
		</form>
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>

<script>
	$( "input[type='radio']" ).checkboxradio();
</script>

	<?
	
}

include( 'footer_nochat.php' );

?>