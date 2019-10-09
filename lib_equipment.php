<?php
include('security.php');
include('language.php');
include('header.php');
?>
<style>

#lib_header{
	position: fixed;
	z-index: 1000;
	background-color: #666;
}

#editor_holder{
	width: 1140px;
	margin-top: 50px;
}

</style>

<div class="container" id="lib_header">
	<div class="row">
		<div class="col md-2">

			<button type="button" id="copy_tier" class="btn btn-info">copy tier data</button> 
			<button type="button" id="copy_whole" class="btn btn-primary">copy item/equipment</button>

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


<script>

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

        //console.log(lib.attributes);

        $.getJSON("getdata.php", {
                page: 'get_equipment'
            })
            .done(function(json) {

                editor = new JSONEditor(document.getElementById("editor_holder"), {
                    schema: {
                        "type": "array",
                        "format": "tabs",
                        "items": {
                            "type": "object",
                            "headerTemplate": "{{ self.equipment_name }}",
                            "format": "grid",
                            "properties": {
                                "equipment_name": {
                                    "type": "string"
                                },
                                "eq_type": {
                                    "type": "string",
                                    "enum": mk_array(lib.defensive)
                                },
                                "description": {
                                    "type": "string",
                                    "format": "textarea",
                                },
                                "armor_formula": {
                                    "type": "string",
                                    "format": "textarea",
                                    "description": "armor formula",
                                },
                                "magic_armor_formula": {
                                    "type": "string",
                                    "format": "textarea",
                                    "description": "magic armor formula",
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
                                                "enum": ["tier 1", "tier 2", "tier 3", "tier 4", "tier 5"]
                                            },
                                            "volume": {
                                                "type": "string",
                                                "format": "number",
                                                "description": "space the item needs",
                                            },
                                            "value_in_gold": {
                                                "type": "string",
                                                "format": "number",
                                                "description": "value in gold"
                                            },
                                            "durability": {
                                                "type": "string",
                                                "format": "number",
                                                "description": "durability of the item",
                                            },
                                            "affects_cost": {
                                                "type": "array",
                                                "format": "table",
                                                "description": "affect on the action cost",
                                                "items": {
                                                    "type": "object",
                                                    "format": "grid",
                                                    "properties": {
                                                        "cost_type": {
                                                            "type": "string",
                                                            "enum": mk_array(lib.pools)
                                                        },
                                                        "cost_formula": {
                                                            "type": "string",
                                                            "format": "textarea"
                                                        }
                                                    }
                                                }
                                            },
                                            "modifiers": {
                                                "type": "array",
                                                "format": "table",
                                                "items": {
                                                    "type": "object",
                                                    "format": "grid",
                                                    "properties": {
                                                        "path": {
                                                            "type": "string",
                                                            "format": "textarea"
                                                        },
                                                        "modifier": {
                                                            "type": "string"
                                                        }
                                                    }
                                                }
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
                    $.post('setdata.php?page=set_equipment', {
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

    node.equipment_name += " 2";
    editordata[editordata.length] = node;

    editor.setValue(editordata);
});

</script>
<?
include("footer_nochat.php");