<?php
include('security.php');
include('language.php');
include('header.php');
?>

<style type="text/css">

#dialog{
padding-top: 40px;
}

</style>

<?

if( isset( $_POST['new_campaign'] ) ){
	
?>

<div class="modal show">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><?=$l[$lang]['CAMPAIGN_CAMP_TITLE'];?></h5>
      </div>
      <div class="modal-body">
        <form action="" method="post">
        	<div class="form-group">
					<label for="username" class="text-white"><?=$l[$lang]['CAMPAIGN_CAMP_NAME'];?></label>
			    <input type="text" class="form-control" id="name" name="name" placeholder="<?=$l[$lang]['CAMPAIGN_CAMP_NAME'];?>">
				<small class="form-text"><?=$l[$lang]['CAMPAIGN_CAMP_TEXT'];?></small>
			  </div>
				<div class="form-group">
					<label for="descr" class="text-white">Beschreibung</label>
			    <textarea name="descr" style="width:470px;height:200px;" class="form-control"><?=$l[$lang]['CAMPAIGN_CAMP_DESCR'];?></textarea>
			  </div>
			  <div class="form-check">
				<input class="form-check-input" name="yesno" type="radio" value="0" id="priv">
				<label class="form-check-label" for="priv">
					<small><?=$l[$lang]['CAMPAIGN_CAMP_PRIVATE'];?></small>
				</label>
			</div>
			  <div class="form-check">
				<input class="form-check-input" name="yesno" type="radio" value="1" id="open">
				<label class="form-check-label" for="open">
					<small><?=$l[$lang]['CAMPAIGN_CAMP_PUBLIC'];?></small>
				</label>
			</div>

			  <button type="submit" class="btn btn-primary" name="new_campaign2" value="Erstellen"><?=$l[$lang]['CAMPAIGN_CAMP_SUBMIT'];?></button>
		</form>
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>

<!--

<div id="dialog" title="<?=$l[$lang]['CAMPAIGN_CAMP_TITLE'];?>">
	<div>
		<form action="" method="post">
			<input type="text" name="name" value="<?=$l[$lang]['CAMPAIGN_CAMP_NAME'];?>" class="form-control" /> <br />
			<?=$l[$lang]['CAMPAIGN_CAMP_TEXT'];?> <br />
			<textarea name="descr" style="width:470px;height:200px;" class="form-control"><?=$l[$lang]['CAMPAIGN_CAMP_DESCR'];?></textarea><br />
			<fieldset>
				<legend><?=$l[$lang]['CAMPAIGN_CAMP_PUBLIC'];?></legend>
				<label for="no"><?=$l[$lang]['NO'];?></label>
				  <input type="radio" name="yesno" id="no" selected="selected" value="0" class="form-control">
				<label for="yes"><?=$l[$lang]['YES'];?></label>
				  <input type="radio" name="yesno" id="yes" value="1" class="form-control">
			</fieldset>
			<input type="submit" name="new_campaign2" value="<?=$l[$lang]['CAMPAIGN_CAMP_SUBMIT'];?>" class="form-control" />
		</form>
	</div>
</div>

-->

<script>
	$( "input[type='radio']" ).checkboxradio();
</script>

<?

}else if( isset( $_POST['new_campaign2'] ) ){
	
	$yesno = isset( $_POST['yesno'] ) && $_POST['yesno'] == true ? true : false;
	
	try {

		$date = date('Y-m-d');
		$new = 'new';

		$stmt = $db->prepare("INSERT INTO campaign ( name, master, date, descr, public, status ) VALUES( :name, :master, :date, :descr, :public, :status ) ");
		$stmt->bindParam(':name', $_POST['name'], PDO::PARAM_STR);
		$stmt->bindParam(':master', $_SESSION['user_id'], PDO::PARAM_INT);
		$stmt->bindParam(':date', $date, PDO::PARAM_STR);
		$stmt->bindParam(':descr', $_POST['descr'], PDO::PARAM_STR);
		$stmt->bindParam(':public', $yesno, PDO::PARAM_BOOL);
		$stmt->bindParam(':status', $new, PDO::PARAM_STR);
		$stmt->execute();
		echo "camp-id: ".$db->lastInsertId()." <br />";


	}catch(PDOException $ex) {
			echo "An Error occured! "; //user friendly message
			echo $ex->getMessage();
	}
	
	?>
	
<div class="modal show">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><?=$l[$lang]['CAMPAIGN_CREATED_MSG'];?></h5>
      </div>
      <div class="modal-body">
		<?=$l[$lang]['CAMPAIGN_CREATED_MSG'];?>
	</div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>
	
	<?
	
}else{

	$links = '';


	try{

		$stmt = $db->prepare( 'SELECT * FROM campaign LEFT JOIN users ON ( master=user_id )' );
		$stmt->execute();

	}catch(PDOException $ex) {
			echo "An Error occured! "; //user friendly message
			echo $ex->getMessage();
	}

	foreach($stmt as $row) {
	    $links .= '<li><a href="edit_campaign.php?camp_id='.$row['camp_id'].'"> '.$row['name'].' by '.$row['username'].', status:'.$row['status'].', public:'.$row['public'].'</a></li>';
	}
	?>

<div class="modal show">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><?=$l[$lang]['CAMPAIGN_CAMP_TITLE'];?></h5>
      </div>
      <div class="modal-body">

	<ul>
	<?=$links;?>
	</ul>

        <form action="" method="post">
        	
			  <button type="submit" class="btn btn-primary" name="new_campaign" value="Neue Kampagne"><?=$l[$lang]['CAMPAIGN_CAMP_SUBMIT'];?></button>
		</form>
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>

<?
} 

include( 'footer_nochat.php' );
?>