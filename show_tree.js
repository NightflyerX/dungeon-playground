var data2 = [];

function isInt(value) {
  return !isNaN(value) && (function(x) { return (x | 0) === x; })(parseFloat(value))
}

function object_walk( input, output, path ){

	var properties = typeof input == "object" ||  typeof input == "array" ? Object.getOwnPropertyNames( input ) : input;
	var pre_path = path;

	for( x = 0, y = properties.length; x<y; x++ ){

		var h = properties[x]
		var i = input[h];
		var path = isInt(h) === true ? pre_path + "["+h+"]" : pre_path + "." + h;

		if( typeof i == "object" ){

			output[x] = { name : h, children : [], path : path };

			var tmp = { x : x, y : y, path : path };
			output[x].children = object_walk( i, output[x].children, path );
			x = tmp.x; 
			y = tmp.y;
			path = tmp.path;

		}else if( typeof i == "string" || typeof i == "number" ){
		
			output[x] = { name : h, path : path, value : i };

		}

	}

	return output;

}

$('#tier_lvl').click( function(){

	var textarea = $('textarea[name*=\''+elem+'\']');
	var data_path = textarea.attr("name").replace(/\[/g, '.').replace(/\]/g, '');
	editor.getEditor(data_path).setValue(textarea.val() + ' ' + 'tier_lvl');

	tb_remove();

});
$('#tier_lvl_array').click( function(){

	var textarea = $('textarea[name*=\''+elem+'\']');
	var data_path = textarea.attr("name").replace(/\[/g, '.').replace(/\]/g, '');
	editor.getEditor(data_path).setValue(textarea.val() + ' ' + 'tier_lvl[1,2,3,4,5]');

	tb_remove();

});
$('#attribut').click( function(){

	var textarea = $('textarea[name*=\''+elem+'\']');
	var data_path = textarea.attr("name").replace(/\[/g, '.').replace(/\]/g, '');
	editor.getEditor(data_path).setValue(textarea.val() + ' ' + 'attribut');

	tb_remove();

});