<?php
include('security.php');
include('language.php');

$elem = isset( $_GET['elem'] ) ? (string) $_GET['elem'] : '';

$result = $db->query("SELECT * FROM game WHERE active=1 ");

foreach( $result AS $row ){

	$game = json_decode($row['data']);
	$_SESSION['current_game_id'] = $row['game_id'];
	$char_str = json_encode( $game->chars[0] );

}

if( !isset( $char_str ) ){

	echo "<h3>No game active or no players in active game</h3>";
	die();

}

?>


<div id="tree1" style="width:50%;float:left;"></div><br />
<br />
Andere variablen:

<h5 id="tier_lvl" style="font-size:1rem;">tier_lvl (Current tier of the item or weapon)</h5>
<h5 id="tier_lvl_array" style="font-size:1rem;">tier_lvl[1,2,3,4,5] (Array with values for each tier level of the item or weapon)</h5>
<h5 id="attribut" style="font-size:1rem;">attribute (Highest skilled attribute of the action)</h5>
<br />
<br />
<h3>Variable: <span id="variable"></span></h3>
<script type="text/javascript">

	var char2 = <?=$char_str;?>;
	var elem = "<?=$elem;?>";

	data2 = object_walk( char2, data2, 'char' );
	
	var $tree = $('#tree1');
	
	$tree.tree({
		data: data2,
		autoOpen: false,
	    dragAndDrop: false,
		onCreateLi: function(node, $li) {
			// Append a link to the jqtree-element div.
			// The link has an url '#node-[id]' and a data property 'node-id'.
			
			$li.find('.jqtree-element').append(
				'<a href="#'+ node.path +'" class="edit" data-node-path="'+
				node.path +'"> '+(typeof(node.value)=='undefined'?'':node.value)+'</span></a>'
			).click( function(){

				console.log( node );
				
				if( isInt( node.value ) ){
				
					//var textarea = $('textarea[name*=\''+elem+'\']');
					//var data_path = textarea.attr("name").replace(/\[/g, '.').replace(/\]/g, '');
					//editor.getEditor(data_path).setValue(textarea.val() + ' ' + node.path);
					$('#variable').text( node.path );
					//tb_remove();
					
				}else if( isInt(node.name) ){

					var count = "ANZAHL( "+node.path.replace(/\[(\d+)\]/,"["+node.value+"]")+")";

					//var textarea = $('textarea[name*=\''+elem+'\']');
					//var data_path = textarea.attr("name").replace(/\[/g, '.').replace(/\]/g, '');
					//editor.getEditor(data_path).setValue(textarea.val() + ' ' + count);
					$('#variable').text( count );

				}
				
			});;
		}
	});

</script>