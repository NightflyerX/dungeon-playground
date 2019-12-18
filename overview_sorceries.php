<?php
include('security.php');
include('language.php');
include('header.php');
?>
<style type="text/css">

#overview_container{
	background-color: rgba(0, 0, 0, 0.7);
}

.container{
	margin: 10px;
}

.container .title{
	background-color: #2A9FD6;
}

.container .tier_title{
	background-color: #42b3f5;
}

</style>

<div id="overview_container" class="container-fluid">
	<ul class="nav nav-tabs" id="myTab" role="tablist">
	</ul>
	<div class="tab-content" id="myTabContent">
	</div>
</div>

<script>

$(document).ready( function(){

	function s( string ){

		return string.toLowerCase().replace(' ', '_');

	}

	$.getJSON("getdata.php", {page: 'get_types'}).done( function(lib){

		lib.action_types.forEach( function( action ){

			$('#myTab').append( `
				<li class="nav-item">
    					<a class="nav-link" id="${s(action.name)}-tab" data-toggle="tab" href="#${s(action.name)}" role="tab" aria-controls="${s(action.name)}" aria-selected="true">${action.name}</a>
  				</li>

			`);

			$('#myTabContent').append( 
				`<div class="tab-pane" id="${s(action.name)}" role="tabpanel" aria-labelledby="${s(action.name)}-tab">
				</div>
			`);

		});

		$.getJSON("getdata.php", { page: 'get_sorceries' }).done(function(actions) {

			console.log( actions );

			actions.forEach( function( action ){

				var attributes = !action.attributes || action.attributes.length == 0 ? '' : '<p>attributes: '+action.attributes.join(', ')+'</p>';
				var magic_type = action.action_type == 'Magieangriff' ? '<p>action_magic_type: '+action.action_magic_type+'</p>' : '';
				
				if( !action.uservars || action.uservars.length == 0 ){

					var uservars = '';

				}else{

					var uservars = '<p>uservars: ';
					action.uservars.forEach( function( uvar ){
						uservars += '{ var_name: '+uvar.var_name+', description : '+uvar.description+', var_default_value :'+uvar.var_default_value+' } ';
					});
					uservars += '</p>';

				}

				var tier_lvl = '';

				action.tier_lvl.forEach( function( tier ){

					tier_lvl += `
								<div class="col-2">
									<div class="row tier_title">
										</h5>${tier.tier_lvl_name}</h5>
									</div>
						`;

							if( tier.weapon_filters && tier.weapon_filters.length > 0 ){
								
								tier_lvl += '<div class="row" style="background-color:\'#f57e42\'">weapon_filters:</div>';

								tier.weapon_filters.forEach( function( filter ){

									tier_lvl += `<p>
									weapon_filter: <span style="color:#f57542;">${filter.weapon_filter}</span>, 
									weapon_tier_level_filter : <span style="color:#f5a142;">${filter.weapon_tier_level_filter}</span>, 
									weapon_skill_filter : <span style="color:#f5c242;">${filter.weapon_skill_filter}</span>, 
									weapon_skill_level_filter : <span style="color:#f5e942;">${filter.weapon_skill_level_filter}</span>
									</p>`;

								});

							}

							if( tier.magic_filters && tier.magic_filters.length > 0 ){

								tier.magic_filters.forEach( function( filter ){

									tier_lvl += `<p>
									magic_skill_filter: <span style="color:#f57542;">${filter.magic_skill_filter}</span>, 
									magic_skill_level_filter : <span style="color:#f5e942;">${filter.magic_skill_level_filter}</span>
									</p>`;

								});

							}

							if( tier.item_filters && tier.item_filters.length > 0 ){

								tier.item_filters.forEach( function( filter ){
									tier_lvl += `<p>
									item_filter: <span style="color:#f57542;">${filter.item_filter}</span>, 
									item_tier_level_filter : <span style="color:#f5e942;">${filter.item_tier_level_filter}</span>
									</p>`;

								});

							}

							tier_lvl += 'target_area : '+tier.target_area;

							if( tier.cost && tier.cost.length > 0 ){

								tier.cost.forEach( function( cost ){
									tier_lvl += `<p>
									cost_type: <span style="background-color:#f57542;">${cost.cost_type}</span>, 
									cost_value : <span style="background-color:#42bcf5;">${cost.cost_value}</span>
									</p>`;

								});

							}

							if( tier.damage && tier.damage.length > 0 ){

								tier.damage.forEach( function( damage ){
									tier_lvl += `<p>
									damage_type: <span style="background-color:#f57542;">${damage.damage_type}</span>, 
									formula : <span style="background-color:#42bcf5;">${damage.formula}</span>
									damage_heal : ${damage.damage_heal}
									affected_damage_pool : ${damage.affected_damage_pool}
									skip_resistance : ${damage.skip_resistance}
									</p>`;

								});

							}

							if( tier.effect_add_damage && tier.effect_add_damage.length > 0 ){

								tier.effect_add_damage.forEach( function( damage ){
									tier_lvl += `<div class="jumbotron" style="background-color:grey"> <p class="lead">effect_add_damage</p>
									chance: <span style="background-color:#f57542;">${damage.chance}</span>, 
									damage_type : <span style="background-color:#42bcf5;">${damage.damage_type}</span>
									damage_formula : <span style="background-color:#42bcf5;">${damage.damage_formula}</span>
									damage_heal : ${damage.damage_heal}
									affected_damage_pool : ${damage.affected_damage_pool}
									skip_resistance : ${damage.skip_resistance}
									target : <span style="background-color:#f57542;">${damage.target}</span>
									</div>`;

								});

							}

							if( tier.effect_add_field && tier.effect_add_field.length > 0 ){

								tier.effect_add_field.forEach( function( field ){
									tier_lvl += `<div class="jumbotron" style="background-color:grey"> <p class="lead">effect_add_field</p>
									chance: <span style="background-color:#f57542;">${field.chance}</span>, 
									field : <span style="background-color:#42bcf5;">${field.field}</span>
									field_owner : <span style="background-color:#42bcf5;">${field.field_owner}</span>
									field_targets : <span style="background-color:#f57542;">${field.field_targets}</span>
									</div>`;

								});

							}

							if( tier.effect_add_state && tier.effect_add_state.length > 0 ){

								tier.effect_add_state.forEach( function( state ){
									tier_lvl += `<div class="jumbotron" style="background-color:grey"> <p class="lead">effect_add_state</p>
									chance: <span style="background-color:#f57542;">${state.chance}</span>, 
									state : <span style="background-color:#42bcf5;">${state.state}</span>
									rounds : <span style="background-color:#42bcf5;">${state.rounds}</span>
									target : <span style="background-color:#f57542;">${state.target}</span>
									</div>`;

								});

							}

							if( tier.effect_add_special_token && tier.effect_add_special_token.length > 0 ){

								tier.effect_add_special_token.forEach( function( token ){
									tier_lvl += `<div class="jumbotron" style="background-color:grey"> <p class="lead">effect_add_special_token</p>
									chance: <span style="background-color:#f57542;">${token.chance}</span>, 
									token : <span style="background-color:#42bcf5;">${token.token}</span>
									target : <span style="background-color:#f57542;">${token.target}</span>
									</div>`;

								});

							}

							if( tier.effect_summon_char && tier.effect_summon_char.length > 0 ){

								tier.effect_summon_char.forEach( function( summon ){
									tier_lvl += `<div class="jumbotron" style="background-color:grey"> <p class="lead">effect_summon_char</p>
									chance: <span style="background-color:#f57542;">${summon.chance}</span>, 
									char_name : <span style="background-color:#42bcf5;">${summon.char_name}</span>
									</div>`;

								});

							}

							if( tier.only_char_filter && tier.only_char_filter.length > 0 ){

								tier.only_char_filter.forEach( function( only_char ){
									tier_lvl += `<div class="jumbotron" style="background-color:orange"> <p class="lead">Action only usable by</p>
									char: <span style="background-color:#f57542;">${only_char.char}</span>, 
									min_level_filter : <span style="background-color:#42bcf5;">${tier.min_level_filter}</span>
									</div>`;

								});

							}
					

					tier_lvl += `

							</div>
						`;
				});

				$('#'+s(action.action_type)).append( `
					<div class="container-fluid">
						<div class="row">
							<div class="col title">
								<h4>${action.action_name}</h4>
							</div>
						</div>
						<div class="row">
							<div class="col-3">
								${magic_type}
								<p>hit_chance_formula: <span style="color:#6ff542">${action.hit_chance_formula}</span></p>
								${attributes}
								${uservars}
							</div>
							${tier_lvl}
						</div>
					</div>
				`);

			});
			

		});

	});

});

	


</script>



<?