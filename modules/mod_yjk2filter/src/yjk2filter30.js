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

var $YJK2F = jQuery.noConflict();

//search based on group, update child fields when parent connection
function yjk2fitler_update_search_form(field_name,value,multiple){
	if(multiple == 1){
		var value = $YJK2F("#"+field_name).val();
	}
	
	var group = $YJK2F("#yjk2filter_groups").val();
	var Itemid = $YJK2F("#yjk2filter_Itemid").val();
	$YJK2F.ajax({
		url: YjK2Filter_url+"modules/mod_yjk2filter/helpers/actions/get_fields_form.php?field_name="+field_name+"&value="+value+"&group="+group+"&multiple="+multiple+"&Itemid="+Itemid,
		dataType: 'json',
		type: 'post',
		success: function(response){
			if(response.no){
				//proceed - no group selected
				$YJK2F.each(response.no, function(index, element) {
					if(!$YJK2F("#yjk2filter_searchfield_"+element.id+" select").attr( "multiple")){
						//select the first drop-dwon option and disable the select field
						$YJK2F("#yjk2filter_searchfield_"+element.id+" select")
							.find('option')
							.remove()
							.end()
							.append('<option value="" class="first_option">'+ element.default_message +'</option>')
							.val('')
						;
					}
					
					$YJK2F("#yjk2filter_searchfield_"+element.id+" select").attr( "disabled", "disabled" );
		   		});			
			}else if(response.error){
				$YJK2F.each(response.error, function(index, element) {
					if(!$YJK2F("#yjk2filter_searchfield_"+element.id+" select").attr( "multiple")){
						$YJK2F("#yjk2filter_searchfield_"+element.id+" select")
							.find('option')
							.remove()
							.end()
							.append('<option value="" class="first_option">'+ element.default_message +'</option>')
							.val('')
						;
					}
					
					$YJK2F("#yjk2filter_searchfield_"+element.id+" select").attr( "disabled", "disabled" );
			   });
			}else if(response.message){
				//proceed - no error message				
				$YJK2F.each(response.message, function(index, element) {
					//change the yjk2 extra fields search form
					$YJK2F("#yjk2filter_searchfield_"+element.id).html(element.element);
					$YJK2F("#yjk2filter_searchfield_"+element.id+" select").removeAttr("disabled");					
		   		});
			}
		}
	});	
	//activate the search button
	$YJK2F("#yjk2filter_search_button").removeAttr("disabled");	
}

//search based on group, update fields when selecting group first
function yjk2fitler_update_groups_fields(field_id){
	var Itemid = $YJK2F("#yjk2filter_Itemid").val();
	$YJK2F.ajax({
		url: YjK2Filter_url+'modules/mod_yjk2filter/helpers/actions/get_fields_group.php?field_id='+field_id+'&Itemid='+Itemid,
		dataType: 'json',
		type: 'post',
		success: function(response){
			if(response.no){
				//proceed - no group selected
				$YJK2F.each(response.no, function(index, element) {
					if(!$YJK2F("#yjk2filter_searchfield_"+element.js_id+" select").attr( "multiple")){
						//select the first drop-dwon option and disable the select field
						$YJK2F("#yjk2filter_searchfield_"+element.js_id+" select")
							.find('option')
							.remove()
							.end()
							.append('<option value="">'+ element.default_message +'</option>')
							.val('')
						;
					}
					
					$YJK2F("#yjk2filter_searchfield_"+element.js_id+" select").attr( "disabled", "disabled" );
					//$("#wrapper input[type=button]")
		   		});
			}else if(response.message){
				//proceed - no error message				
				$YJK2F.each(response.message, function(index, element) {
					//change the yjk2 extra fields search form
					$YJK2F("#yjk2filter_searchfield_"+element.js_id).html(element.element);
					$YJK2F("#yjk2filter_searchfield_"+element.js_id).removeAttr("disabled");
					$YJK2F("#yjk2filter_range").val(element.ranged_field);
		   		});
			}
		}
	});	
	//activate the search button
	$YJK2F("#yjk2filter_search_button").removeAttr("disabled");		
}

//search based on extra fields only, update child fields when parent connection
function yjk2fitler_update_field_form(field_name,value,multiple){
	if(multiple == 1){
		var value = $YJK2F("#"+field_name).val();
	}
	
	var Itemid = $YJK2F("#yjk2filter_Itemid").val();	
	var group = $YJK2F("#yjk2filter_group").val();
	$YJK2F.ajax({
		url: YjK2Filter_url+'modules/mod_yjk2filter/helpers/actions/get_extra_fields_form.php?field_name='+field_name+'&value='+value+'&group='+group+'&multiple='+multiple+'&Itemid='+Itemid,
		dataType: 'json',
		type: 'post',
		success: function(response){
			if(response.no){
				//proceed - no group selected
				$YJK2F.each(response.no, function(index, element) {
					if(!$YJK2F("#yjk2filter_searchfield_"+element.id+" select").attr( "multiple")){
						//select the first drop-dwon option and disable the select field
						$YJK2F("#yjk2filter_searchfield_"+element.id+" select")
							.find('option')
							.remove()
							.end()
							.append('<option value="">'+ element.default_message +'</option>')
							.val('')
						;
					}
					
					$YJK2F("#yjk2filter_searchfield_"+element.id+" select").attr( "disabled", "disabled" );
					//$("#wrapper input[type=button]")
		   		});				
			}else if(response.error){
				$YJK2F.each(response.error, function(index, element){
					if(!$YJK2F("#yjk2filter_searchfield_"+element.id+" select").attr( "multiple")){
						$YJK2F("#yjk2filter_searchfield_"+element.id+" select")
							.find('option')
							.remove()
							.end()
							.append('<option value="">'+ element.default_message +'</option>')
							.val('')
						;
					}
					
					$YJK2F("#yjk2filter_searchfield_"+element.id+" select").attr( "disabled", "disabled" );
			   });
			}else if(response.message){
				//proceed - no error message				
				$YJK2F.each(response.message, function(index, element) {
					//change the yjk2 extra fields search form
					$YJK2F("#yjk2filter_searchfield_"+element.id).html(element.element);
					$YJK2F("#yjk2filter_searchfield_"+element.id+" select").removeAttr("disabled");
		   		});
			}
		}
	});	
	//activate the search button
	$YJK2F("#yjk2filter_search_button").removeAttr("disabled");		
}

        
function sort_options () { 
	  var yjselect = $YJK2F('#yjk2filter_extrafields_form select');
	  $YJK2F(yjselect).each(function (elm) {
		var listitems = $YJK2F(this).children('option.yjk2options').get();
		 listitems.sort(function(a, b) {
			var compA = $YJK2F(a).text().toUpperCase();
			var compB = $YJK2F(b).text().toUpperCase();
		    compA = (parseInt(compA,10) || compA);
		    compB = (parseInt(compB,10) || compB);
			return (compA < compB) ? -1 : (compA > compB) ? 1 : 0;
		 })
		   $YJK2F.each(listitems, function(idx, itm) { 
				$YJK2F(this).parent().append(itm); 
		 });
	  }); 
}

$YJK2F(document).ready(function() {		  
   sort_options ();
  $YJK2F(document).ajaxSuccess(function(){
	sort_options ();
  });
});


jQuery.noConflict();
(function ($) {
    $(function () {

        $(document).ready(function () {

            $('#yjk2filter_extrafields_form').on('submit', function (e) {

                e.preventDefault();

                var got_value = 0;
                var form_elements = $('#all_elements_id').val().split(",");
                var sendform = this;

                //select the parent group drop-down, on search by group, first
                if ($('#yjk2filter_groups') && $('#yjk2filter_groups').val()) {
                    sendform.submit();
                    got_value = 1;
                    e.stopPropagation();
                    return false;
                }

                //select the extra fields
                $.each(form_elements, function (index, element) {
  
				    if ($('#YJK2FilterExtraField_' + element).val()) {
                        sendform.submit();
                        got_value = 1;
                        e.stopPropagation();
                        return false;
                    }
                });

                //create error div for empty values
                if (got_value == 0) {
                    if ($("#yjk2filter_form_error").length == 0) {
                        $("#yjk2filter_search_button")
                            .after('<div class="log" id="yjk2filter_form_error">' + YjK2Filter_no_values_form + '</div>')
                    }
                }

            });

            $('#yjk2filter_extrafields_form select').on('change', function (event) {
                if ($("#yjk2filter_form_error").length > 0) {
                    $("#yjk2filter_form_error").remove();
                }
            });

        });

    });
})(jQuery);