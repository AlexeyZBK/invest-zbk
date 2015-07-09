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

window.addEvent('domready', function() {
	$('yjk2filter_extrafields_form').addEvent('submit', function(e) {
		/**
		 * Prevent the submit event
		 */
		new Event(e).stop();
		var got_value = 0;
		
		var form_elements = $('all_elements_id').value.split(",");
		
		//select the parent group drop-down, on search by group, first
		if($('yjk2filter_groups') && $('yjk2filter_groups').value){
			$('yjk2filter_extrafields_form').submit();
			got_value = 1;
			new Event(e).stopPropagation();  // stop event from bubbling up
			return false;  // never run handler when clicking on element				
		}
		
		//select the extra fields
		form_elements.each(function(item, index){
			if($('YJK2FilterExtraField_'+item) && $('YJK2FilterExtraField_'+item).value){
				$('yjk2filter_extrafields_form').submit();
				got_value = 1;
				new Event(e).stopPropagation();  // stop event from bubbling up
				return false;  // never run handler when clicking on element				
			}
		});
		
		//create error div for empty values
		if(got_value == 0){
			if(!$("yjk2filter_form_error")){
				var yjk2fitler_error = new Element('div').set({'class':'log','id':'yjk2filter_form_error'}).injectAfter($('yjk2filter_search_button'))
			}
			$("yjk2filter_form_error").innerHTML = YjK2Filter_no_values_form;
		}
	});
});