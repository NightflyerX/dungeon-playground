<?php
include('security.php');
include('language.php');
include('header.php');
?>
<style type="text/css">

.property-selector{
	color:black;
}

#lib_header{
	position: fixed;
	z-index: 1000;
	background-color: #666;
}
#editor_holder{
	margin-top: 50px;
	width: 1140px;
}
</style>

<div class="container" id="lib_header">
	<div class="row">
		<div class="col md-2">

			<button type="button" id="copy_tier" class="btn btn-info">copy tier data</button> 
			<button type="button" id="copy_whole" class="btn btn-primary">copy action</button>

		</div>
		<div class="col md-2">

			<a href="show_tree.php?height=500&width=1000" class="thickbox"><img src="formula.png" /></a>

		</div>

	</div>

</div>

<br />
<br />
<br />

<div id="editor_holder" class="player card"></div>


<script type="text/javascript">

var editor = false;

function mk_array(data, objectname) {

    if (objectname) {

        var tmp = new Array();
        for (i = 0, j = data.length; i < j; i++) {
            tmp.push(data[i][objectname]);
        }

    } else {

        var tmp = new Array();
        for (i = 0, j = data.length; i < j; i++) {
            tmp.push(data[i].name);
        }

    }

    return tmp;
}

$.getJSON("getdata.php", {
        page: 'get_types'
    })
    .done(function(lib) {

        console.log(lib.action_types);
        console.log(mk_array(lib.action_types));

        $.getJSON("getdata.php", {
                page: 'get_equipment'

            }).done(function(equipment) {

                $.getJSON("getdata.php", {
                        page: 'get_char'

                    }).done(function(chars) {


                        $.getJSON("getdata.php", {
                                page: 'get_fields'

                            }).done(function(fields) {


                                $.getJSON("getdata.php", {
                                        page: 'get_actions'
                                    })
                                    .done(function(json) {
                                        console.log(json);
                                        editor = new JSONEditor(document.getElementById("editor_holder"), {
                                            schema: {
                                                "type": "array",
                                                "format": "tabs",
                                                "items": {
                                                    "type": "object",
                                                    "headerTemplate": "{{ self.action_name }}",
                                                    "format": "grid",
                                                    "properties": {
                                                        "action_name": {
                                                            "type": "string",
                                                            "propertyOrder": 1
                                                        },
                                                        "action_type": {
                                                            "type": "string",
                                                            "enum": mk_array(lib.action_types),
                                                            "propertyOrder": 2
                                                        },
                                                        "action_magic_type": {
                                                            "type": "string",
                                                            "enum": mk_array(lib.magic_types, "magic_type_name"),
                                                            "propertyOrder": 3
                                                        },
                                                        "hit_chance_formula": {
                                                            "type": "string",
                                                            "description": "hit chance of the action",
                                                            "format": "textarea",
                                                            "propertyOrder": 4
                                                        },
                                                        "attributes": {
                                                            "type": "array",
                                                            "format": "table",
                                                            "items": {
                                                                "type": "string",
                                                                "options": {
                                                                    "input_width": "150px"
                                                                },
                                                                "enum": mk_array(lib.attributes)
                                                            },
                                                            "propertyOrder": 5
                                                        },
                                                        "description": {
                                                            "type": "string",
                                                            "format": "textarea",
                                                            "propertyOrder": 6
                                                        },
                                                        "uservars": {
                                                            "type": "array",
                                                            "format": "table",
                                                            "items": {
                                                                "type": "object",
                                                                "format": "grid",
                                                                "properties": {
                                                                    "var_name": {
                                                                        "type": "string"
                                                                    },
                                                                    "description": {
                                                                        "type": "string",
                                                                        "format": "textarea"
                                                                    },
                                                                    "var_default_value": {
                                                                        "type": "number"
                                                                    }

                                                                }
                                                            },
                                                            "propertyOrder": 7
                                                        },
                                                        "tier_lvl": {
                                                            "type": "array",
                                                            "format": "tabs",
                                                            "items": {
                                                                "type": "object",
                                                                "headerTemplate": "{{ self.tier_lvl_name }}",
                                                                "format": "grid",
                                                                "properties": {
                                                                    "tier_lvl_name": {
                                                                        "type": "string",
                                                                        "enum": ["Normal", "Expert", "Master", "Grandmaster"],
                                                                        "propertyOrder": 1
                                                                    },
                                                                    "target_area": {
                                                                        "type": "string",
                                                                        "description": "target fields",
                                                                        "enum": ["Single target", "Multi target"],
                                                                        "propertyOrder": 2
                                                                    },
                                                                    "only_char_filter": {
                                                                        "type": "array",
                                                                        "format": "table",
                                                                        "options": {
                                                                            "collapsed": true,
                                                                        },
                                                                        "description": "action restriction to chosen characters",
                                                                        "items": {
                                                                            "type": "object",
                                                                            "format": "grid",
                                                                            "properties": {
                                                                                "char": {
                                                                                    "type": "string",
                                                                                    "enum": mk_array(chars, "name"),
                                                                                },
                                                                            },
                                                                        },
                                                                        "propertyOrder": 3
                                                                    },
                                                                    "min_level_filter": {
                                                                        "type": "string",
                                                                        "format": "table",
                                                                        "description": "minimum level the character must have to use this action",
                                                                        "enum": ["Normal", "Expert", "Master", "Grandmaster"],
                                                                        "propertyOrder": 4
                                                                    },
                                                                    "weapon_filters": {
                                                                        "type": "array",
                                                                        "format": "table",
                                                                        "options": {
                                                                            "collapsed": true
                                                                        },
                                                                        "description": "the action is only available if the right weapon is equiped, skilled and leveled",
                                                                        "items": {
                                                                            "type": "object",
                                                                            "format": "grid",
                                                                            "properties": {
                                                                                "weapon_filter": {
                                                                                    "type": "string",
                                                                                    "enum": mk_array(lib.weapon_types),
                                                                                    "description": "weapon type",
                                                                                },
                                                                                "weapon_tier_level_filter": {
                                                                                    "type": "string",
                                                                                    "enum": ["tier 0", "tier 1", "tier 2", "tier 3", "tier 4"],
                                                                                    "description": "minimum tier",
                                                                                },
                                                                                "weapon_skill_filter": {
                                                                                    "type": "string",
                                                                                    "enum": mk_array(lib.weapon_types),
                                                                                    "description": "minimum skill",
                                                                                },
                                                                                "weapon_skill_level_filter": {
                                                                                    "type": "string",
                                                                                    "enum": ["Normal", "Expert", "Master", "Grandmaster"],
                                                                                    "description": "minimum skill level",
                                                                                },
                                                                            },
                                                                        },
                                                                        "propertyOrder": 5
                                                                    },
                                                                    "magic_filters": {
                                                                        "type": "array",
                                                                        "format": "table",
                                                                        "options": {
                                                                            "collapsed": true
                                                                        },
                                                                        "description": "the action is only available if this magic is skilled and leveled",
                                                                        "items": {
                                                                            "type": "object",
                                                                            "format": "grid",
                                                                            "properties": {
                                                                                "magic_skill_filter": {
                                                                                    "type": "string",
                                                                                    "enum": mk_array(lib.magic_types, "magic_type_name"),
                                                                                    "description": "minimum skill",
                                                                                },
                                                                                "magic_skill_level_filter": {
                                                                                    "type": "string",
                                                                                    "enum": ["Normal", "Expert", "Master", "Grandmaster"],
                                                                                    "description": "minimum level",
                                                                                },
                                                                            },
                                                                        },
                                                                        "propertyOrder": 6
                                                                    },
                                                                    "other_skill_filters": {
                                                                        "type": "array",
                                                                        "format": "table",
                                                                        "options": {
                                                                            "collapsed": true
                                                                        },
                                                                        "description": "the action is only available if this skill is skilled and leveled",
                                                                        "items": {
                                                                            "type": "object",
                                                                            "format": "grid",
                                                                            "properties": {
                                                                                "other_skill_filter": {
                                                                                    "type": "string",
                                                                                    "enum": mk_array(lib.skill_types, "skill_type_name"),
                                                                                    "description": "minimum skill",
                                                                                },
                                                                                "other_skill_level_filter": {
                                                                                    "type": "string",
                                                                                    "enum": ["Normal", "Expert", "Master", "Grandmaster"],
                                                                                    "description": "minimum level",
                                                                                },
                                                                            },
                                                                        },
                                                                        "propertyOrder": 7
                                                                    },

                                                                    "item_filters": {
                                                                        "type": "array",
                                                                        "format": "table",
                                                                        "options": {
                                                                            "collapsed": true
                                                                        },
                                                                        "description": "action only available when a certain item is equiped",
                                                                        "items": {
                                                                            "type": "object",
                                                                            "format": "grid",
                                                                            "properties": {
                                                                                "item_filter": {
                                                                                    "type": "string",
                                                                                    "enum": mk_array(equipment, "equipment_name"),
                                                                                    "description": "item/equipment name",
                                                                                },
                                                                                "item_tier_level_filter": {
                                                                                    "type": "string",
                                                                                    "enum": ["tier 0", "tier 1", "tier 2", "tier 3", "tier 4"],
                                                                                    "description": "minimum tier",
                                                                                },
                                                                            },
                                                                        },
                                                                        "propertyOrder": 8
                                                                    },
                                                                    "damage": {
                                                                        "type": "array",
                                                                        "format": "table",
                                                                        "options": {
                                                                            "collapsed": true
                                                                        },
                                                                        "description": "Damage this action deals",
                                                                        "items": {
                                                                            "type": "object",
                                                                            "format": "grid",
                                                                            "properties": {
                                                                                "damage_type": {
                                                                                    "type": "string",
                                                                                    "enum": mk_array(lib.damage_types_all, "name")
                                                                                },
                                                                                "formula": {
                                                                                    "type": "string",
                                                                                    "format": "textarea"
                                                                                },
                                                                                "damage_heal": {
                                                                                    "type": "string",
                                                                                    "enum": ["damage", "heal"]
                                                                                },
                                                                                "affected_damage_pool": {
                                                                                    "type": "string",
                                                                                    "enum": mk_array(lib.pools)
                                                                                },
                                                                                "skip_resistance": {
                                                                                    "type": "string",
                                                                                    "enum": ["false", "true"]
                                                                                },
                                                                                "add_token": {
                                                                                    "type": "string",
                                                                                    "enum": [0, 1, 2, 3, 4, 5]
                                                                                }
                                                                            }
                                                                        },
                                                                        "propertyOrder": 9
                                                                    },
                                                                    "effect_add_damage": {
                                                                        "type": "array",
                                                                        "format": "table",
                                                                        "options": {
                                                                            "collapsed": true
                                                                        },
                                                                        "description": "effect additional damage",
                                                                        "items": {
                                                                            "type": "object",
                                                                            "format": "grid",
                                                                            "properties": {
                                                                                "chance": {
                                                                                    "type": "string",
                                                                                    "format": "textarea"
                                                                                },
                                                                                "damage_type": {
                                                                                    "type": "string",
                                                                                    "enum": mk_array(lib.damage_types_all, "name")
                                                                                },
                                                                                "damage_formula": {
                                                                                    "type": "string",
                                                                                    "format": "textarea"
                                                                                },
                                                                                "damage_heal": {
                                                                                    "type": "string",
                                                                                    "enum": ["damage", "heal"]
                                                                                },
                                                                                "affected_damage_pool": {
                                                                                    "type": "string",
                                                                                    "enum": mk_array(lib.pools)
                                                                                },
                                                                                "skip_resistance": {
                                                                                    "type": "string",
                                                                                    "enum": ["false", "true"]
                                                                                },
                                                                                "target": {
                                                                                    "type": "string",
                                                                                    "enum": ['action_executor', 'action_target', 'executor_group', 'target_group', 'executor_group_others', 'target_group_others']
                                                                                }
                                                                            }
                                                                        },
                                                                        "propertyOrder": 10
                                                                    },
                                                                    "effect_add_special_token": {
                                                                        "type": "array",
                                                                        "format": "table",
                                                                        "options": {
                                                                            "collapsed": true
                                                                        },
                                                                        "description": "effect add special token",
                                                                        "items": {
                                                                            "type": "object",
                                                                            "format": "grid",
                                                                            "properties": {
                                                                                "chance": {
                                                                                    "type": "string",
                                                                                    "format": "textarea"
                                                                                },
                                                                                "token": {
                                                                                    "type": "string",
                                                                                    "enum": mk_array(lib.special_token, "name")
                                                                                },
                                                                                "target": {
                                                                                    "type": "string",
                                                                                    "enum": ['action_executor', 'action_target', 'executor_group', 'target_group', 'executor_group_others', 'target_group_others']
                                                                                }
                                                                            }
                                                                        },
                                                                        "propertyOrder": 11
                                                                    },
                                                                    "effect_add_state": {
                                                                        "type": "array",
                                                                        "format": "table",
                                                                        "options": {
                                                                            "collapsed": true
                                                                        },
                                                                        "description": "effect add state",
                                                                        "items": {
                                                                            "type": "object",
                                                                            "format": "grid",
                                                                            "properties": {
                                                                                "chance": {
                                                                                    "type": "string",
                                                                                    "format": "textarea"
                                                                                },
                                                                                "state": {
                                                                                    "type": "string",
                                                                                    "enum": mk_array(lib.states, 'state_name')
                                                                                },
                                                                                "target": {
                                                                                    "type": "string",
                                                                                    "enum": ['action_executor', 'action_target', 'executor_group', 'target_group', 'executor_group_others', 'target_group_others']
                                                                                },
                                                                                "rounds": {
                                                                                    "type": "string",
                                                                                    "format": "textarea"
                                                                                },
                                                                                "add_remove": {
                                                                                    "type": "string",
                                                                                    "enum": ['add', 'remove']
                                                                                }
                                                                            }
                                                                        },
                                                                        "propertyOrder": 12
                                                                    },
                                                                    "effect_summon_char": {
                                                                        "type": "array",
                                                                        "format": "table",
                                                                        "options": {
                                                                            "collapsed": true
                                                                        },
                                                                        "description": "effect summon character",
                                                                        "items": {
                                                                            "type": "object",
                                                                            "format": "grid",
                                                                            "properties": {
                                                                                "chance": {
                                                                                    "type": "string",
                                                                                    "format": "textarea"
                                                                                },
                                                                                "char_name": {
                                                                                    "type": "string",
                                                                                    "enum": mk_array(chars, 'name')
                                                                                }
                                                                            }
                                                                        },
                                                                        "propertyOrder": 13
                                                                    },
                                                                    "effect_add_field": {
                                                                        "type": "array",
                                                                        "format": "table",
                                                                        "options": {
                                                                            "collapsed": true
                                                                        },
                                                                        "description": "effect add field (buff/debuff)",
                                                                        "items": {
                                                                            "type": "object",
                                                                            "format": "grid",
                                                                            "properties": {
                                                                                "chance": {
                                                                                    "type": "string",
                                                                                    "format": "textarea"
                                                                                },
                                                                                "field": {
                                                                                    "type": "string",
                                                                                    "enum": mk_array(fields, 'field_name')
                                                                                },
                                                                                "field_owner": {
                                                                                    "type": "string",
                                                                                    "enum": ['action_executor', 'action_target']
                                                                                },
                                                                                "field_targets": {
                                                                                    "type": "string",
                                                                                    "enum": ['action_executor', 'action_target', 'executor_group', 'target_group', 'executor_group_others', 'target_group_others']
                                                                                }
                                                                            }
                                                                        },
                                                                        "propertyOrder": 14
                                                                    },
                                                                    "cost": {
                                                                        "type": "array",
                                                                        "format": "table",
                                                                        "options": {
                                                                            "collapsed": true
                                                                        },
                                                                        "description": "action cost",
                                                                        "items": {
                                                                            "type": "object",
                                                                            "format": "grid",
                                                                            "properties": {
                                                                                "cost_type": {
                                                                                    "type": "string",
                                                                                    "enum": mk_array(lib.pools)
                                                                                },
                                                                                "cost_value": {
                                                                                    "type": "string",
                                                                                    "format": "textarea"
                                                                                }
                                                                            }
                                                                        },
                                                                        "propertyOrder": 15
                                                                    },
                                                                    "token_cost": {
                                                                        "type": "array",
                                                                        "format": "table",
                                                                        "options": {
                                                                            "collapsed": true
                                                                        },
                                                                        "description": "action token cost",
                                                                        "items": {
                                                                            "type": "object",
                                                                            "format": "grid",
                                                                            "properties": {
                                                                                "token": {
                                                                                    "type": "string",
                                                                                    "enum": mk_array(lib.damage_types_all, "name")
                                                                                },
                                                                                "token_val": {
                                                                                    "type": "string",
                                                                                    "enum": [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 19]
                                                                                },
                                                                                "special_token": {
                                                                                    "type": "string",
                                                                                    "enum": mk_array(lib.special_token, "name")
                                                                                },
                                                                                "special_token_val": {
                                                                                    "type": "string",
                                                                                    "enum": [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 19]
                                                                                },
                                                                            }
                                                                        },
                                                                        "propertyOrder": 16
                                                                    },
                                                                    "message_after_attack": {
                                                                        "type": "string",
                                                                        "format": "textarea",
                                                                        "description": "Message in the chat after the action",
                                                                        "propertyOrder": 17
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            },
                                            theme: 'bootstrap3',
                                            ajax: true,
                                            startval: json,
                                            options: {
                                                disable_array_reorder: true,
                                                iconlib: 'bootstrap3'
                                            }
                                        }).on('change', function() {
                                            $.post('setdata.php?page=set_sorceries', {
                                                data: JSON.stringify(editor.getValue(), null, 2)
                                            });

                                        });

                                        //tb_init('a.thickbox');

                                    })
                                    .fail(function(jqxhr, textStatus, error) {
                                        var err = textStatus + ", " + error;
                                        console.log("Request Failed: " + err);
                                    });

                            })
                            .fail(function(jqxhr, textStatus, error) {
                                var err = textStatus + ", " + error;
                                console.log("Request Failed: " + err);
                            });

                    })
                    .fail(function(jqxhr, textStatus, error) {
                        var err = textStatus + ", " + error;
                        console.log("Request Failed: " + err);
                    });
            })
            .fail(function(jqxhr, textStatus, error) {
                var err = textStatus + ", " + error;
                console.log("Request Failed: " + err);
            });

    })
    .fail(function(jqxhr, textStatus, error) {
        var err = textStatus + ", " + error;
        console.log("Request Failed: " + err);
    });

$('#copy_tier').click(function() {

    var editordata = editor.getValue();

    var index1 = $('div[data-schemaid="root"][data-schematype="array"]>div>div[class*="tabs"]>a[class*="active"]').index();
    var index2 = $('div[data-schemaid!="root"][data-schematype="array"][class^="col"][data-schemapath^="root.' + index1 + '"]>div>div[class*="tabs"]>a[class*="active"]').index();

    for (x = 0; x < 5; x++) {

        var node = editordata[index1].tier_lvl[index2];

        if (!editordata[index1].tier_lvl[x]) {

            editordata[index1].tier_lvl[x] = node;

        }

    }

    editor.setValue(editordata);

});

$('#copy_whole').click(function() {

    var editordata = editor.getValue();

    var index1 = $('div[data-schemaid="root"][data-schematype="array"]>div>div[class*="tabs"]>a[class*="active"]').index();
    var node = $.extend(true, {}, editordata[index1]);

    node.action_name += " 2";
    editordata[editordata.length] = node;

    editor.setValue(editordata);
});

</script>
<?
include("footer_nochat.php");