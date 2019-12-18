<?php
include('security.php');
include('language.php');
include('header.php');
?>

<div class="container">
	
	<?
	
	$result = $db->query("SELECT `data` FROM chars WHERE `char_id`=1");
	$row = $result->fetch(PDO::FETCH_ASSOC);
	$chars = json_decode( $row['data'] );
	
	$my_chars = array();
	$other_chars = array();
	
	foreach( $chars AS $char ){
		
		if( $char->creator == $_SESSION['username'] ){
			
			$my_chars[] = $char;
			
		}else{
			
			$other_chars[] = $char;
			
		}
		
	}
	
	?>


	<h5 class="title mt-5">My chars</h5>
	<ul class="list-group">
	<?
	foreach( $my_chars AS $char ){

		if( isset( $char->dm_only ) && $char->dm_only == 'dm_only' && $_SESSION['is_dm'] === false ){

			continue;

		}

		?>

		<li class="list-group-item container">
			<div class="row">
				<div class="col-3">
					<img class="img-thumbnail" src="server/php/files/thumbnail/<?=$char->img_url;?>" />
				</div>
				<div class="col-5">
					<a href="char_create.php?char_id=<?=$char->char_id;?>"><?=$char->name;?></a>
				</div>
				<div class="col-4">
					<div class="btn-group" role="group" aria-label="Basic example">
						<a href="char_shop.php?char_id=<?=$char->char_id;?>" class="btn btn-primary">Shop</a>
						<a href="char-del.php?char_id=<?=$char->char_id;?>" class="btn btn-warning">Delete</a>
						<a href="char_copy.php?char_id=<?=$char->char_id;?>" class="btn btn-success">Copy</a>
					</div>
				</div>
			</div>
		</li>



		<?
		
	}
	
	?>
	</ul>
	
	<h5 class="title mt-3">Other chars</h5>
	<ul class="list-group">
	<?
	
	foreach( $other_chars AS $char ){

		if( isset( $char->dm_only ) && $char->dm_only == 'dm_only' && $_SESSION['is_dm'] === false ){

			continue;

		}
		
		?>

		<li class="list-group-item container">
			<div class="row">
				<div class="col-1">
					<img src="server/php/files/thumbnail/<?=$char->img_url;?>" />
				</div>
				<div class="col-7">
					<a href="char_create.php?char_id=<?=$char->char_id;?>"><?=$char->name;?></a>
				</div>
				<div class="col-4">
					<div class="btn-group" role="group" aria-label="Basic example">
						<a href="char_shop.php?char_id=<?=$char->char_id;?>" class="btn btn-primary">Shop</a>
						<a href="char_shop.php?char_id=<?=$char->char_id;?>" class="btn btn-success">Copy</a>
					</div>
				</div>
			</div>
		</li>



		<?
		
	}
	?>
	</ul>

</div>


<?

include( 'footer_nochat.php' );