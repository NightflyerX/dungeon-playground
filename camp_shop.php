<?php
include('security.php');
include('language.php');
include('header.php');
?>

<style type="text/css">
#editor_holder{
	color: white;
}
form input,select{
	margin-top: 2px;
	margin-bottom: 2px;
}

form fieldset{
	margin: 5px;
	border: 1px solid white;
	padding: 10px;
}
form fieldset legend{
	width: 150px;
	border: 1px solid white;
	text-align: center;
}
#editor_holder{
	width:100%;
}

#editor_holder div ul{
	border: 1px solid white;
	min-height:200px;
	list-style-type: none;
}
#editor_holder div ul li{
	margin: 2px;
	padding:3px;
	border: 1px dotted white;
	width: 250px;
	background-color: #2A9FD6;
	opacity:0.9;
	float:left;
}

</style>

<?
	if( !isset( $_SESSION['camp_id'] ) ){

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

	}else{

?>

<div id="editor_holder">
<?
	try{

		$stmt = $db->prepare( 'SELECT * FROM campaign WHERE `camp_id`='.$_SESSION['camp_id'] );
		$stmt->execute();

	}catch(PDOException $ex) {
			echo "An Error occured! "; //user friendly message
			echo $ex->getMessage();
	}

	foreach($stmt as $row) {
	    echo "<h5>".$l[$lang]['CAMPAIGN_SHOP_TITLE']." ".$row['name']."</h5><br /><br />";
	}
?>

	<div style="width:50%;float:left;min-height:500px;padding:5px;">
		<h5><?=$l[$lang]['CAMPAIGN_SHOP_SHOP'];?></h5><br />
		<br />
		<?=$l[$lang]['CAMPAIGN_SHOP_WEAPONS'];?>:<br />
		<ul id="shop_weapons">
		</ul><br />
		<br />
		<?=$l[$lang]['CAMPAIGN_SHOP_EQUIPMENT'];?>:<br />

		<ul id="shop_equipment">
		</ul>
	</div>
	<div style="width:50%;float:left;min-height:500px;border-left:1px dotted white;padding:5px;">
		<h5><?=$l[$lang]['CAMPAIGN_SHOP_LIBRARY'];?></h5><br />
		<br />
		<?=$l[$lang]['CAMPAIGN_SHOP_WEAPONS'];?>:<br />

		<ul id="lib_weapons">
		</ul><br />
		<br />
		<?=$l[$lang]['CAMPAIGN_SHOP_EQUIPMENT'];?>:<br />

		<ul id="lib_equipment">
		</ul>
	</div>
	<div style="clear:both;">&nbsp;</div>
	
</div>

<script type="text/javascript">

$.getJSON("getdata.php", {
	page: 'get_weapons'
	})
	.done(function(json) {

		var weapons = json;

		for( x=0; x<weapons.length; x++ ){

			var tiersel = '<select name="tierselect" class="form-control" style="width:100px;display:inline;">';

			for( z=1; z<=5; z++ ){

				tiersel += '<option value="tier_'+z+'">tier_'+z+'</option>';

			}

			tiersel += '</select>';

			$('#lib_weapons').append( '<li weapon_id="'+x+'">'+weapons[x].weapon_name+' '+tiersel+'</li>' );

		}

		$('#lib_weapons').append( '<div style="clear:both;">&nbsp;</div>' );

		$('#lib_weapons li').draggable({
			start: function( event, ui ){

				console.log( $(this).children("select").val() );
				ui.helper.children("select").val( $(this).children("select").val() );
			},
			helper:"clone"
		});
	
	})
	.fail(function(jqxhr, textStatus, error) {
		var err = textStatus + ", " + error;
		console.log("Request Failed: " + err);
});

$.getJSON("getdata.php", {
	page: 'get_equipment'
	})
	.done(function(json) {

		var equipment = json;

		for( x=0; x<equipment.length; x++ ){

			var tiersel = '<select name="tierselect" class="form-control" style="width:100px;display:inline;">';

			for( z=0; z<equipment[x].tier_lvl.length; z++ ){

				tiersel += '<option value="'+equipment[x].tier_lvl[z].tier_lvl_name+'">'+equipment[x].tier_lvl[z].tier_lvl_name+'</option>';

			}

			tiersel += '</select>';
			
			if( z>0 ){

				$('#lib_equipment').append( '<li equipment_id="'+x+'">'+equipment[x].equipment_name+' '+tiersel+'</li>' );
				
			}

		}

		$('#lib_equipment').append( '<div style="clear:both;">&nbsp;</div>' );

		$('#lib_equipment li').draggable({
			start: function( event, ui ){
				ui.helper.children("select").val( $(this).children("select").val() );
			},
			helper:"clone"
		});
	
	})
	.fail(function(jqxhr, textStatus, error) {
		var err = textStatus + ", " + error;
		console.log("Request Failed: " + err);
});

$('#shop_weapons').droppable({
	drop: function( event, ui ){

		var weapon_id = ui.helper.attr("weapon_id");
		var tier_lvl = ui.helper.children("select").val();

		console.log(tier_lvl);

		$.get( 'setdata.php', { 'page':'add_weapon_from_lib_to_shop', weapon_id : weapon_id, tier_lvl : tier_lvl }, function(){
			load_shop_content();
		});
	}
});
$('#shop_equipment').droppable({
	drop: function( event, ui ){

		var equipment_id = ui.helper.attr("equipment_id");
		var tier_lvl = ui.helper.children("select").val();
		$.get( 'setdata.php', { 'page':'add_equipment_from_lib_to_shop', equipment_id : equipment_id, tier_lvl : tier_lvl }, function( data ){
			console.log( data );
			load_shop_content();
		});
	}
});

function load_shop_content(){

	$('#shop_weapons').html('');
	$('#shop_equipment').html('');

	$.getJSON("getdata.php", {
	page: 'get_shop'
	})
	.done(function(json) {

		shops = json;
	
		console.log( shops );

		for( y=0; y<shops.length; y++ ){


			if( shops[y].shop_id != <?=$_SESSION['camp_id'];?> ){
				
				continue;

			}

			if( shops && shops[y].weapons ){

				for( x=0; x<shops[y].weapons.length;x++ ){

					var tier_lvl = shops[y].weapons[x].tier_lvl;

					$('#shop_weapons').append('<li weapon_id="'+x+'">'+shops[y].weapons[x].weapon_name+' '+tier_lvl+'<span style="float:right;">Del</span></li>' );				

				}

			}

			if( shops && shops[y].equipment ){
			
				for( x=0; x<shops[y].equipment.length;x++ ){

					var index = Object.keys( shops[y].equipment[x].tier_lvl );
					var tier_lvl = shops[y].equipment[x].tier_lvl[index[0]].tier_lvl_name;

					$('#shop_equipment').append('<li equipment_id="'+x+'">'+shops[y].equipment[x].equipment_name+' '+tier_lvl+'<span style="float:right;">Del</span></li>' );				

				}

			}

		}

		$('#shop_weapons li span').click( function(){

			var weapon_id = $(this).parent().attr("weapon_id");
			$.get( 'setdata.php', { 'page':'del_weapon_from_shop', weapon_id : weapon_id }, function(){
				load_shop_content();
			});

		});
		$('#shop_equipment li span').click( function(){

			var equipment_id = $(this).parent().attr("equipment_id");
			$.get( 'setdata.php', { 'page':'del_equipment_from_shop', equipment_id : equipment_id }, function(){
				load_shop_content();
			});

		});

	})
	.fail(function(jqxhr, textStatus, error) {
		var err = textStatus + ", " + error;
		console.log("Request Failed: " + err);
});
}

load_shop_content();

</script>

<?

}

include( 'footer_nochat.php' );