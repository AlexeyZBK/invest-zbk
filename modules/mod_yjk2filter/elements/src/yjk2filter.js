/*======================================================================*\
|| #################################################################### ||
|| # Package - YJ K2 Filter                								||
|| # Copyright (C) since 2007  Youjoomla.com. All Rights Reserved.      ||
|| # license - PHP files are licensed under  GNU/GPL V2                 ||
|| # license - CSS  - JS - IMAGE files  are Copyrighted material        ||
|| # bound by Proprietary License of Youjoomla.com                      ||
|| # for more information visit http://www.youjoomla.com/license.html   ||
|| # Redistribution and  modification of this software                  ||
|| # is bounded by its licenses                                         ||
|| # websites - http://www.youjoomla.com | http://www.yjsimplegrid.com  ||
|| #################################################################### ||
\*======================================================================*/

window.addEvent("domready", function () {

  // add class to li's from labels
  $$('.pane-slider li').each(function (el) {
    var get_all_labels = el.getElements('label');

    // get all ids and replace them... use .get for 1.2+ or .getProperty for 1.11
    var get_label_ids = get_all_labels.map(function (label) {
      return label.getProperty("for").replace("jform_params_", "");
    });

    el.addClass(get_label_ids.join(" ") + '_yjme');

  });

	//  sliders for "Create extra fields groups filter" Yes or No
	var gotgroups_range = $('yjk2filter_groups_range_holder');
	var gotfields_range = $('yjk2filter_extrafields_range_holder');
	// collect all items for toggler
	var get_yjk2filter_groups_range = $$('.yjk2filter_groups_range_yjme');
	var get_yjk2filter_fields_range = $$('.yjk2filter_extrafields_range_yjme');
	// add items in toggler
	gotgroups_range.adopt(get_yjk2filter_groups_range);
	gotfields_range.adopt(get_yjk2filter_fields_range);

	//slider with "Range values for desired field" for Groups filter
	var selected_group_range = $('jform_params_yjk2filter_create_groups_range').get("value");
	if (selected_group_range == 0) {
		var yjk2filtergroup_range = new Fx.Slide('yjk2filter_groups_range_holder', {
			duration: 1000,
			transition: Fx.Transitions.Pow.easeOut
		}).hide();
	} else if (selected_group_range == 1) {
		var yjk2filtergroup_range = new Fx.Slide('yjk2filter_groups_range_holder', {
			duration: 1000,
			transition: Fx.Transitions.Pow.easeOut
		}).show();
	}
	
	$('yjk2filter_groups_range_holder').getParent().addClass('togh_yjk2filter_groups');	
	
	$('jform_params_yjk2filter_create_groups_range').addEvent('change', function (event) {
		event.stop();
	
		var selected_group_range = this.get("value");
		if (selected_group_range == 0) { ///groups selected
			yjk2filtergroup_range.toggle();
		}
		if (selected_group_range == 1) { ///fields selected
			yjk2filtergroup_range.toggle();
		}
	
	});
	
	//slider with "Range values for desired field" for extra fields filter	
	var selected_extrafield_range = $('jform_params_yjk2filter_create_extrafields_range').get("value");
	if (selected_extrafield_range == 0) {
		var yjk2filterextrafield_range = new Fx.Slide('yjk2filter_extrafields_range_holder', {
			duration: 1000,
			transition: Fx.Transitions.Pow.easeOut
		}).hide();
	} else if (selected_extrafield_range == 1) {
		var yjk2filterextrafield_range = new Fx.Slide('yjk2filter_extrafields_range_holder', {
			duration: 1000,
			transition: Fx.Transitions.Pow.easeOut
		}).show();
	}
	
	$('yjk2filter_extrafields_range_holder').getParent().addClass('togh_yjk2filter_fields');	
	
	$('jform_params_yjk2filter_create_extrafields_range').addEvent('change', function (event) {
		event.stop();
	
		var selected_extrafield_range = this.get("value");
		if (selected_extrafield_range == 0) { ///groups selected
			yjk2filterextrafield_range.toggle();
		}
		if (selected_extrafield_range == 1) { ///fields selected
			yjk2filterextrafield_range.toggle();
		}
	
	});

	//  sliders for "Create extra fields groups filter" Yes or No
	var gotgroups = $$('#yjk2filter_groups_holder');
	var gotfields = $$('#yjk2filter_fields_holder');
	// collect all items for toggler
	var get_yjk2filter_groups = $$('.yjk2filter_rename_groups,.yjk2filter_rename_group_yjme,.yjk2filter_groups_id_yjme,.jform_params_yjk2filter_groups_id,.yjk2filter_groups,yjk2filter_extrafields,.yjk2filter_groups_connection_yjme,.yjk2filter_available_group_fields,.yjk2filter_available_group_fields_selected_yjme,.yjk2filter_create_groups_range_yjme,.yjk2filter_groups_range_holder_yjme,.yjk2filter_groups_field_order_yjme');
	var get_yjk2filter_fields = $$('.yjk2filter_extrafields,.yjk2filter_extrafields_id_yjme,.yjk2filter_fields_group_id,.yjk2filter_fields_group_id_yjme,.yjk2filter_extrafields_connection_yjme,.yjk2filter_extrafields_range_holder_yjme,.yjk2filter_create_extrafields_range_yjme,.yjk2filter_extrafields_range_holder,.yjk2filter_extrafields_order_yjme');
	// add items in toggler
	gotgroups.adopt(get_yjk2filter_groups);
	gotfields.adopt(get_yjk2filter_fields);

	//toggle
	var selected = $('jform_params_yjk2filter_groups_filter').get("value");
	if (selected == 0) {
		var yjk2filterSlide1 = new Fx.Slide('yjk2filter_groups_holder', {
			duration: 1000,
			transition: Fx.Transitions.Pow.easeOut
		}).hide();
		var yjk2filterSlide2 = new Fx.Slide('yjk2filter_fields_holder', {
			duration: 1000,
			transition: Fx.Transitions.Pow.easeOut
		}).show();
	} else if (selected == 1) {
		var yjk2filterSlide1 = new Fx.Slide('yjk2filter_groups_holder', {
			duration: 1000,
			transition: Fx.Transitions.Pow.easeOut
		}).show();
		var yjk2filterSlide2 = new Fx.Slide('yjk2filter_fields_holder', {
			duration: 1000,
			transition: Fx.Transitions.Pow.easeOut
		}).hide();
	}
	
	$('yjk2filter_groups_holder').getParent().addClass('togh_yjk2filter_groups');
	$('yjk2filter_fields_holder').getParent().addClass('togh_yjk2filter_fields');
	
	$('jform_params_yjk2filter_groups_filter').addEvent('change', function (event) {
		event.stop();
	
		var selectedsource = this.get("value");
		if (selectedsource == 0) { ///groups selected
			yjk2filterSlide1.toggle();
			yjk2filterSlide2.toggle();
			//$('k2not').setStyle('display', 'none');
		}
	
		if (selectedsource == 1) { ///fields selected
			yjk2filterSlide1.toggle();
			yjk2filterSlide2.toggle();
			//$('k2not').setStyle('display', 'block');
		}
	
	});
	
  // move order
  var cssholder = $('css_file');
  var cssselect = $('jform_params_module_css');
  cssselect.inject(cssholder, 'top');

  var tmplholder = $('copy_template');
  var tmplselect = $('jform_params_module_template');
  tmplselect.inject(tmplholder, 'top');	
	
	// filter select
	$('jform_params_yjk2filter_extrafields0').addEvent('click', function () {
		$('jformparamsyjk2filter_extrafields_id').setProperty('disabled', 'disabled');
		$$('#jformparamsyjk2filter_extrafields_id option').each(function (el) {
			el.setProperty('selected', 'selected');
		});
	})
	
	$('jform_params_yjk2filter_extrafields1').addEvent('click', function () {
		$('jformparamsyjk2filter_extrafields_id').removeProperty('disabled');
		$$('#jformparamsyjk2filter_extrafields_id option').each(function (el) {
			el.removeProperty('selected');
		});
	})
	

	
	// groups select
	$('jform_params_yjk2filter_groups0').addEvent('click', function () {
		$('jformparamsyjk2filter_groups_id').setProperty('disabled', 'disabled');
		$$('#jformparamsyjk2filter_groups_id option').each(function (el) {
			el.setProperty('selected', 'selected');
		});
		get_group_available_extra_fields();		
	})
	
	$('jform_params_yjk2filter_groups1').addEvent('click', function () {
		$('jformparamsyjk2filter_groups_id').removeProperty('disabled');
		$$('#jformparamsyjk2filter_groups_id option').each(function (el) {
			el.removeProperty('selected');
		});
	})
	

		
	//group available fields
	$('jform_params_yjk2filter_available_group_fields0').addEvent('click', function () {
		$('jformparamsyjk2filter_available_group_fields_selected').setProperty('disabled', 'disabled');
		$$('#jformparamsyjk2filter_available_group_fields_selected option').each(function (el) {
			el.setProperty('selected', 'selected');
		});
	})
	
	$('jform_params_yjk2filter_available_group_fields1').addEvent('click', function () {
		$('jformparamsyjk2filter_available_group_fields_selected').removeProperty('disabled');
		$$('#jformparamsyjk2filter_available_group_fields_selected option').each(function (el) {
			el.removeProperty('selected');
		});
	})
	

	
	//get the available fields on select groups to search by
	$('jformparamsyjk2filter_groups_id').addEvent('change', function () {
		
		//function to get all the available fields for the selected group(s) for YES group filter
		get_group_available_extra_fields();
	});	
	
	//group extra fields search, first check if we have group selected
	if ($('jformparamsyjk2filter_fields_group_id').get("value") == "") {
		$('jform_params_yjk2filter_extrafields0').setProperty('disabled', 'disabled');
		$('jform_params_yjk2filter_extrafields1').setProperty('disabled', 'disabled');
		$('jformparamsyjk2filter_extrafields_id').setProperty('disabled', 'disabled');
	}else{
		$('jform_params_yjk2filter_extrafields0').removeProperty('disabled');
		$('jform_params_yjk2filter_extrafields1').removeProperty('disabled');
		$('jformparamsyjk2filter_extrafields_id').removeProperty('disabled');

		//make the ajax call
		var req = new Request({
		  	method: 'post',
		  	url: '../modules/mod_yjk2filter/helpers/actions/get_param_fields_group.php?group_id='+$('jformparamsyjk2filter_fields_group_id').get("value"),
			onComplete: function(response) { $$('li.yjk2filter_extrafields_id_yjme').set({'html':response}); }
		}).send();		
	}
	
});

/* Load Event fires when the whole page is loaded, included all images */
window.addEvent('load', function() {
	$('jformparamsyjk2filter_fields_group_id').addEvent('change', function (event) {
		
		//enable the rest of the elements
		$('jform_params_yjk2filter_extrafields0').removeProperty('disabled');
		$('jform_params_yjk2filter_extrafields1').removeProperty('disabled');
		$('jformparamsyjk2filter_extrafields_id').removeProperty('disabled');
		
		$$('li.yjk2filter_extrafields_id_yjme').set({'html':'Please wait'});
		
		//prevent the page from changing
		new Event(event).stop();
		
		//make the ajax call
		var req = new Request({
		  	method: 'post',
		  	url: '../modules/mod_yjk2filter/helpers/actions/get_param_fields_group.php?group_id='+$('jformparamsyjk2filter_fields_group_id').get("value"),
			onComplete: function(response) { 
				$$('li.yjk2filter_extrafields_id_yjme').set({'html':response}); 

				//check All button and also select all extra fields - options
				$('jform_params_yjk2filter_extrafields0').set('checked',true);
			
				$('jformparamsyjk2filter_extrafields_id').setProperty('disabled', 'disabled');
				$$('#jformparamsyjk2filter_extrafields_id option').each(function (el) {
					el.setProperty('selected', 'selected');
				});				
			}
		}).send();	
	});	
	
	//function to check if extra fields checkbox is set to All/Select for NO group filter
	check_all_select_fields_no_groupfilter();	

	//function to check if available fields checkbox is set to All/Select for Yes group filter
	check_all_select_available_fields_yes_group_filter();

	//function to check if group checkbox is set to All/Select for YES group filter
	check_all_select_groups_yes_groupfilter();	
	
});

//function to generate drop-downs used for creating fields connection for YES group filter
function yjk2filter_create_group_connections(){
	var selected = new Array();
	var selected_options = $('jformparamsyjk2filter_available_group_fields_selected').getSelected();
	
	//proceed only if we have more than one field selected
	if(selected_options.length > 1){
		//create 2 new drop-dwons for the selected options
		var fields_connections_div 		= new Element('div', {'id':'group_connected_fields_div','style':'margin: 4px; height:55px; padding: 10px; text-align:left;'}).inject($('group_connections'));
		var fields_connections_parent 	= new Element('select', {'id':'group_connected_fields_parent','name':'group_connected_fields_parent[]'}).inject(fields_connections_div);
		var fields_connections_child 	= new Element('select', {'id':'group_connected_fields_child','name':'group_connected_fields_child[]','multiple':'multiple'}).inject(fields_connections_div);
		//insert the default element
		new Element('option', {'value': '', 'text':'Select field'}).inject(fields_connections_parent);
		new Element('option', {'value': '', 'text':'Select field'}).inject(fields_connections_child);
		
		$('jformparamsyjk2filter_available_group_fields_selected').getSelected().each(function(option) {
			new Element('option', {'value': option.get('value'), 'text':option.get('value')}).inject(fields_connections_parent);
			new Element('option', {'value': option.get('value'), 'text':option.get('value')}).inject(fields_connections_child);
			//alert(option.get('text'));
		});
	}else{
		alert('Only one field selected!');
	}
}

//function to get all the available fields for the selected group(s) for YES group filter
function get_group_available_extra_fields(){

	//groups search - generate the available extra fields
	var groups_selected_id = new Array();
	$('jformparamsyjk2filter_groups_id').getSelected().each(function(option) {
		groups_selected_id.push(option.get('value'));
	});																	  

	//make the ajax call
	var req = new Request({
		method: 'post',
		url: '../modules/mod_yjk2filter/helpers/actions/get_available_groups_fields.php?groups='+groups_selected_id.join(',')+'&module_id='+yjk2filter_module_id,
		onComplete: function(response) { 
			$('yjk2filter_available_group_fields_content').set({'html':response}); 

			//check if available fields checkbox is set to All/Select for Yes group filter
			check_all_select_available_fields_yes_group_filter();			
		}
	}).send();																	  
}

//function to check if extra fields checkbox is set to All/Select for NO group filter
function check_all_select_fields_no_groupfilter(){ 
	if ($('jform_params_yjk2filter_extrafields0').checked) {
		
		$('jformparamsyjk2filter_extrafields_id').setProperty('disabled', 'disabled');
		$$('#jformparamsyjk2filter_extrafields_id option').each(function (el) {
			el.setProperty('selected', 'selected');
		});
	}
	
	if ($('jform_params_yjk2filter_extrafields1').checked) {
		$('jformparamsyjk2filter_extrafields_id').removeProperty('disabled');
	}
}

//function to check if group checkbox is set to All/Select for YES group filter
function check_all_select_groups_yes_groupfilter(){
	if ($('jform_params_yjk2filter_groups0').checked) {
		
		//set all the groups to selected
		$('jformparamsyjk2filter_groups_id').setProperty('disabled', 'disabled');
		$$('#jformparamsyjk2filter_groups_id option').each(function (el) {
			el.setProperty('selected', 'selected');
		});
		
		//retrieve the available extra fields (fields that belong to all the selected groups)
		get_group_available_extra_fields();	
	}
	
	if ($('jform_params_yjk2filter_groups1').checked) {
		$('jformparamsyjk2filter_groups_id').removeProperty('disabled');
		
		//retrieve the available extra fields (fields that belong to all the selected groups)
		get_group_available_extra_fields();			
	}
}

//function to check if available fields checkbox is set to All/Select for Yes group filter
function check_all_select_available_fields_yes_group_filter(){
	if ($('jform_params_yjk2filter_available_group_fields0').checked) {
		$('jformparamsyjk2filter_available_group_fields_selected').setProperty('disabled', 'disabled');
		$$('#jformparamsyjk2filter_available_group_fields_selected option').each(function (el) {
			el.setProperty('selected', 'selected');
		});
	}
	
	if ($('jform_params_yjk2filter_available_group_fields1').checked) {
		$('jformparamsyjk2filter_available_group_fields_selected').removeProperty('disabled');
	}
}
