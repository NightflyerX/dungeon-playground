<?php
include('security.php');
include('language.php');
include('header.php');

?>
<style>
#editor_holder{
	margin-top: 50px;
	width: 1140px;
}
</style>
<div id="editor_holder" class="player card clearfix"></div>

<script>

var damage_types_all_array = [];

function mk_damage_array( damage_types, magic_types, magic_classes ){
	
	damage_types_all_array = [];

	damage_types_all_array.push( { 'name':'Physical general', 'color':'#ffffff' } );
	
	damage_types.forEach(function(ele) {
		
		damage_types_all_array.push( { 'name':'Physical '+ele.name, 'color': ele.color } );
		
	});
	
	damage_types_all_array.push( { 'name':'Magic general', 'color':'#ccccff' } );

	magic_classes.forEach(function(ele){
		damage_types_all_array.push( { 'name':'Magic '+ele.name, 'color':ele.color } );
	});
	
	magic_types.forEach(function(ele) {
		
		damage_types_all_array.push( { 'name':'Magic '+ele.magic_type_name, 'color':ele.color } );
		
	});
	
	return damage_types_all_array;
	
}
     
     var startval = $.getJSON( "getdata.php", { page : 'get_types' } )
		    .done(function( json ) {
			    
			    console.log(json);
			    mk_damage_array( json.damage_types, json.magic_types, json.magic_classes );

				$("#editor_holder").jsoneditor({
					    schema: {
							"title": "Datenbank",
							"type": "object",
							"properties": {
								"type_names": {
									"type": "array",
									"format": "table",
									"title": "<?=$l[$lang]['LIB_TYPES_TYPES'];?>",
									"uniqueItems": true,
									"options": {
										"collapsed": true
									},
									"items": {
										"type": "object",
										"title": "Name",
										"properties": {
											"name": {
												"type": "string",
												"title": "class name",
											}
										}
									}
								},
								"races_names": {
									"type": "array",
									"format": "table",
									"title": "<?=$l[$lang]['LIB_TYPES_RACES'];?>",
									"uniqueItems": true,
									"options": {
										"collapsed": true
									},
									"items": {
										"type": "object",
										"title": "Name",
										"properties": {
											"name": {
												"type": "string",
												"title": "race name",
											}
										}
									}
								},
								"attributes": {
									"type": "array",
									"format": "table",
									"title": "<?=$l[$lang]['LIB_TYPES_ATTRIBUTES'];?>",
									"uniqueItems": true,
									"options": {
										"collapsed": true
									},
									"items": {
										"type": "object",
										"title": "Name",
										"properties": {
											"name": {
												"type": "string",
												"title": "attribute name",
											}
										}
									}
								},
								"action_types": {
									"type": "array",
									"format": "table",
									"title": "<?=$l[$lang]['LIB_TYPES_ACTION_TYPES'];?>",
									"uniqueItems": true,
									"options": {
										"collapsed": true
									},
									"items": {
										"type": "object",
										"title": "Name",
										"properties": {
											"key": {
												"type": "string",
												"title": "action type key",
											},
											"name": {
												"type": "string",
												"title": "action type name",
											}
										}
									}
								},
								"damage_types": {
									"type": "array",
									"format": "table",
									"title": "<?=$l[$lang]['LIB_TYPES_DAMAGE_TYPES'];?>",
									"uniqueItems": true,
									"options": {
										"collapsed": true
									},
									"items": {
										"type": "object",
										"title": "Name",
										"properties": {
											"name": {
												"type": "string",
												"title": "damage type name",
											},
											"color": {
												"type": "string",
												"title": "Farbe",
												"format":"color"
											}
										}
									}
								},
								"weapon_types": {
									"type": "array",
									"format": "table",
									"title": "<?=$l[$lang]['LIB_TYPES_WEAPON_TYPES'];?>",
									"uniqueItems": true,
									"options": {
										"collapsed": true
									},
									"items": {
										"type": "object",
										"title": "Name",
										"properties": {
											"name": {
												"type": "string",
												"title": "weapon type name",
											}
										}
									}
								},
								"pools": {
									"type": "array",
									"format": "table",
									"title": "<?=$l[$lang]['LIB_TYPES_POOLS'];?>",
									"uniqueItems": true,
									"options": {
										"collapsed": true
									},
									"items": {
										"type": "object",
										"title": "Name",
										"properties": {
											"id": {
												"type": "string",
												"title": "pool id",
											},
											"name": {
												"type": "string",
												"title": "pool name",
											}
										}
									}
								},
						
								"mods": {
									"type": "array",
									"format": "table",
									"title": "<?=$l[$lang]['LIB_TYPES_MODIFICATORS'];?>",
									"uniqueItems": true,
									"options": {
										"collapsed": true
									},
									"items": {
										"type": "object",
										"title": "Value",
										"properties": {
											"attribute_value": {
												"type": "number",
												"title": "attribute value"
											},
											"mod_value": {
												"type": "number",
												"title": "mod value"
											}
										}
									}
								},
								"defensive": {
									"type": "array",
									"format": "table",
									"title": "<?=$l[$lang]['LIB_TYPES_DEFENSIVE_SKILLS'];?>",
									"uniqueItems": true,
									"options": {
										"collapsed": true
									},
									"items": {
										"type": "object",
										"title": "defensive skill name",
										"properties": {
											"name": {
												"type": "string",
												"title": "object name",
											}
										}
									}
								},
								"magic_classes": {
									"type": "array",
									"format": "table",
									"title": "<?=$l[$lang]['LIB_TYPES_MAGIC_CLASSES'];?>",
									"uniqueItems": true,
									"options": {
										"collapsed": true
									},
									"items": {
										"type": "object",
										"properties": {
											"name": {
												"title": "magic class name",
												"type": "string"
											},
											"color": {
												"type": "string",
												"title": "color",
												"format":"color"
											}
										}
									}
								},
								"magic_types": {
									"type": "array",
									"format": "table",
									"title": "<?=$l[$lang]['LIB_TYPES_MAGIC_TYPES'];?>",
									"uniqueItems": true,
									"options": {
										"collapsed": true
									},
									"items": {
										"type": "object",
										"title": "magic type name",
										"properties": {
											"magic_class_name": {
												"type": "string",
												"title": "magic class",
												"watch": {
													"magic_classes": "magic_classes"
												},
												"enumSource": [{
													"source": "magic_classes",
													"value": "{{item.name}}"
												}]
											},
											"magic_type_name": {
												"title": "magic type name",
												"type": "string"
											},
											"color": {
												"type": "string",
												"title": "color",
												"format":"color"
											},
											"glyph": {
												"type": "string",
												"title": "glyph"
											}
										}
									}
								},
								"skill_classes": {
									"type": "array",
									"format": "table",
									"title": "<?=$l[$lang]['LIB_TYPES_SKILL_CLASSES'];?>",
									"uniqueItems": true,
									"options": {
										"collapsed": true
									},
									"items": {
										"type": "object",
										"properties": {
											"name": {
												"title": "skill class name",
												"type": "string"
											}
										}
									}
								},
								"skill_types": {
									"type": "array",
									"format": "table",
									"title": "<?=$l[$lang]['LIB_TYPES_SKILL_TYPES'];?>",
									"uniqueItems": true,
									"options": {
										"collapsed": true
									},
									"items": {
										"type": "object",
										"title": "skill type name",
										"properties": {
											"skill_class_name": {
												"type": "string",
												"title": "skill class",
												"watch": {
													"skill_classes": "skill_classes"
												},
												"enumSource": [{
													"source": "skill_classes",
													"value": "{{item.name}}"
												}]
											},
											"skill_type_name": {
												"title": "skill type name",
												"type": "string"
											}
										}
									}
								},
								"states": {
									"type": "array",
									"format": "table",
									"title": "States",
									"uniqueItems": true,
									"options": {
										"collapsed": true
									},
									"items": {
										"type": "object",
										"properties": {
											"state_name": {
												"title": "state_name",
												"type": "string"
											},
											"state_id": {
												"title": "state_id",
												"type":"string"
											},
											"vars": {
												"type": "array",
												"format":"table",
												"options": {
													"collapsed": true
												},
												"items": {
													"type":"object",
													"properties": {
														"path": {
															"title":"path",
															"type":"string"
														},
														"modifier": {
															"title":"modifier",
															"type":"string"
														}
													}
												}
											}
										}
									}
								},
								"states_effects": {
									"type": "array",
									"format": "table",
									"title": "states effects",
									"uniqueItems": true,
									"description": "",
									"options": {
										"collapsed": true
									},
									"items": {
										"type": "object",
										"title": "state_effect_name",
										"properties": {
											"state_name": {
												"type": "string",
												"title": "Zustand",
												"watch": {
													"states": "states"
												},
												"enumSource": [{
													"source": "states",
													"value": "{{item.name}}"
												}]
											},
											"skill_class_name": {
												"type": "string",
												"title": "Attribut",
												"watch": {
													"attributes": "attributes"
												},
												"enumSource": [{
													"source": "attributes",
													"value": "{{item.name}}"
												}]
											},
											"states_effect_value": {
												"title": "modificator",
												"type": "string"
						
											}
										}
									}
								},
								"states_effects2": {
									"type": "array",
									"format": "table",
									"title": "<?=$l[$lang]['LIB_TYPES_EFFECTS'];?>",
									"uniqueItems": true,
									"description": "The modifier modifies the variable in the tree (+2,-2,*2,120%)",
									"options": {
										"collapsed": true
									},
									"items": {
										"type": "object",
										"title": "state effect name",
										"properties": {
											"state_name": {
												"type": "string",
												"title": "state",
											},
											"state_id": {
												"type": "string",
												"title": "state ID",
											},
											"variable": {
												"type": "string",
												"title": "variable",
												"format": "textarea"
											},
											"modifier": {
												"title": "modificator",
												"type": "string"
											}
										}
									}
								},
								"damage_types_all": {
									"type":"array",
									"format":"table",
									"title":"<?=$l[$lang]['LIB_TYPES_ALL_DAMAGE_TYPES'];?>",
									"uniqueItems":"true",
									"options":{
										"collapsed":"true",
										"disable_array_add":"true",
										"disable_array_delete":"true",
										"disable_array_delete_all_rows":"true",
										"disable_array_delete_last_row":"true",
										"disable_array_reorder":"true"
									},
									"items": {
										"type": "object",
										"title": "damage type",
										"properties": {
											"name": {
												"type": "string",
												"title": "damage type",
												"value": "{{item.name}}"
											},
											"color": {
												"type": "string",
												"title": "color",
												"value": "{{item.color}}",
												"format":"color"
											}
										}
									}
								},
								"special_token": {
									"type": "array",
									"format": "table",
									"title": "<?=$l[$lang]['LIB_TYPES_SPECIAL_TOKEN'];?>",
									"uniqueItems": true,
									"options": {
										"collapsed": true
									},
									"items": {
										"type": "object",
										"title": "token name",
										"properties": {
											"name": {
												"type": "string",
												"title": "token name",
											},
											"color": {
												"type": "string",
												"title": "color",
												"format":"color"
											}
										}
									}
								},
								"events" : {
									"type": "array",
									"format": "table",
									"title": "<?=$l[$lang]['LIB_TYPES_EVENTS'];?>",
									"uniqueItems": true,
									"options": {
										"collapsed": true
									},
									"items": {
										"type": "object",
										"title": "event name",
										"properties": {
											"event": {
												"type": "string"
											}
										}
									}
								}
			
							}
						},
					    theme: 'bootstrap3',
					    ajax : true,
					    startval : json
					  }).on( 'change', function(){
						  $(this).jsoneditor('value').damage_types_all = mk_damage_array( json.damage_types, json.magic_types, json.magic_classes );
						$.post( 'setdata.php?page=set_types', { data : JSON.stringify( $(this).jsoneditor('value'),null,2) });		
				});

		  })
		  .fail(function( jqxhr, textStatus, error ) {
		    var err = textStatus + ", " + error;
		    console.log( "Request Failed: " + err );
		});
		
    </script>

<?
include("footer_nochat.php");