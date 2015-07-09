<?php
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

// no direct access 
defined('_JEXEC') or die('Restricted access');
if(!class_exists('YjK2FilterHelp') && !function_exists('YjK2FilterItems'))// lets reuse them!
{	

	class YjK2FilterHelp 
	{
		 
		static function YjK2FilterItems(&$params){ 
			/* prepare database */ 
			$db					= JFactory::getDBO(); 
 
			/* prepare default module params */
			$yj_mod_name		= basename(dirname(__FILE__));// 10-8-2011

			//generate the k2 extra field id
			$field_name	= JRequest::getVar('field_name', '');
			$field_id	= explode("_",$field_name);
				 
			//get the extra fields id to reload
			$conn_field_ids 			= array();
			$conn_field_name 			= array();	 
			$skip_child_connection 		= array();
			$add_reload_parent_action  	= array();

			$yjk2filter_fields_group_id = $params->get('yjk2filter_fields_group_id','');	// K2 Extra fields all/select
			$connection 				= $params->get('yjk2filter_extrafields_connection');//yjk2filter_extrafields_connection
			$create_range				= $params->get('yjk2filter_create_extrafields_range');//yjk2filter_extrafields_connection
			$range 						= $params->get('yjk2filter_extrafields_range');//yjk2filter_extrafields_connection
			$connection_all				= explode(PHP_EOL,$connection);
			$range_all					= explode(PHP_EOL,$range);

			$json 			= new Services_JSON;
			$ranged_field	= array();

			//generate connection 
			if(isset($connection_all[0]) && !empty($connection_all[0])){
				foreach($connection_all as $connection_val){
					$connection_val_first = explode("=>",trim($connection_val));
					if($connection_val_first[0] != "" && $connection_val_first[1] != ""){
						
						//generate the child connection and parent connection for another connections
						if(1){
							$add_reload_parent_action[] = $connection_val_first[0];
							$skip_child_connection[] 	= "'".trim($connection_val_first[1])."'";
						}
						
						//get the extra fields id for the connected fields
						$query = "SELECT ex.id  FROM #__k2_extra_fields as ex
								  WHERE ex.name = '".trim($connection_val_first[0])."' AND ex.group = ".$yjk2filter_fields_group_id;
						$db->setQuery($query);
						$conn_first_id = $db->loadResult();
				
						$query = "SELECT ex.id  FROM #__k2_extra_fields as ex
								  WHERE ex.name = '".trim($connection_val_first[1])."' AND ex.group = ".$yjk2filter_fields_group_id;
						$db->setQuery($query);
						$conn_second_id = $db->loadResult();		
				
						// Make sure there aren't any errors
						if ($db->getErrorNum()) {
							echo $db->getErrorMsg();
							exit;
						}

						//add the reloaded extra feilds to the array		
						if( settype($conn_first_id,'integer') == settype($field_id[1],'integer') ){
							if(isset($conn_field_ids[$conn_first_id])){
								$conn_field_ids[$conn_first_id][] = $conn_second_id;
							}else{
								$conn_field_ids[$conn_first_id] = array($conn_second_id);
							}
							$conn_field_name[] = $connection_val_first[1];
						}
					}
				}
			}else{
				$conn_field_ids[$field_id[1]][] = 2;
				$conn_field_name[] = "Car model";
			}

			//generate range 
			$skip_range 	= array();
			$formated_range = array();
			if(!empty($range_all) && $create_range == 1){
				foreach($range_all as $range_val){
					$range_val_first = explode("=>",trim($range_val));
					if($range_val_first[0] != "" && $range_val_first[1] != ""){
						
						//get the extra fields id for the ranged field
						$query = "SELECT ex.id  FROM #__k2_extra_fields as ex
								  WHERE ex.name = '".trim($range_val_first[0])."' AND ex.group = ".$yjk2filter_fields_group_id." AND ex.type = 'textfield'";
						$db->setQuery($query);
						$range_first_id = $db->loadResult();				
				
						// Make sure there aren't any errors
						if ($db->getErrorNum()) {
							echo $db->getErrorMsg();
							exit;
						}
						
						if($range_first_id > 0){
							//add the ranged field id to the return value
							$ranged_field[] = $range_first_id;

							//generate the range values
							$range_drop_down_row 	= array();							
							$range_values 			= explode("|",$range_val_first[1]);
							if(!empty($range_values)){
								foreach($range_values as $range_value){
									//create new object, we need to keep the same values as k2 database fields
									$range_object 			= new JObject();
									$range_object->name 	= $range_value;
									$range_object->value 	= $range_value;
									$range_object->targer 	= NULL;
									$range_drop_down_row[] 	= $range_object;
								}
								//add the drop-down values to the final formated range var
								$formated_range[$range_first_id] = $range_drop_down_row;
							}
						}
					}
				}
			}

			require('modules/'.$yj_mod_name.'/yjme/get_k2extrafields.php');

			//  this is the main array for k2/joomla news items. both use same vars for ouptut
			$main_yj_arr = array();
			if(is_array($load_items) && !empty($load_items)){
				foreach ( $load_items as $row ) {
				
					//check to see if we have the field in range array
					//if yes replace the field type and values with the ranged ones
					if (array_key_exists($row->id, $formated_range) && !empty($formated_range[$row->id]) && $row->type == 'textfield'){
						//force extra field to a select one
						$row->type = "select";
						//add the new values
						$row->value = $json->encode($formated_range[$row->id]);
					}

					//see if we have to add reload action to this field name
					if(in_array($row->name,$add_reload_parent_action)){
						$reload = 1;
					}else{
						$reload = 0;
					}				
				
					$main_yj_arr[] = array(
						'group' 			=> $row->group,//add the field id to use it in jQuery for replacing the exact form element					
						'id' 				=> $row->id,//add the field id to use it in jQuery for replacing the exact form element
						'name' 				=> $row->name,
						'element' 			=> YjK2FilterHelp::renderExtraField($row,NULL,$reload,1,0)
					);
				}
			}

			return array('main_yj_arr'=>$main_yj_arr,'ranged_field'=>$ranged_field);

		}

		public static function renderExtraField($extraField,$itemID=NULL,$connection=0,$add_default=1,$group=0){

			$mainframe = JFactory::getApplication();
			require_once(JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR."components".DIRECTORY_SEPARATOR."com_k2".DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'JSON.php');
			$json=new Services_JSON;
	
			if (!is_null($itemID)){
				$item = JTable::getInstance('K2Item', 'Table');
				$item->load($itemID);
			}

			$defaultValues = $json->decode($extraField->value);
			
			$active='';
			foreach ($defaultValues as $row => $value){
				if ($extraField->type=='textfield' || $extraField->type=='csv' || $extraField->type=='labels' || $extraField->type=='date')
					$active=$value->value;
				else if ($extraField->type=='textarea'){
					$active[0]=$value->value;
					$active[1]=$value->editor;
				}
				else if($extraField->type=='link'){
					$active[0]=$value->name;
					$active[1]=$value->value;
					$active[2]=$value->target;
				}
				else
					$active='';
				
				//yjk2filter we change the value to name for our plugin search functionality
				//$defaultValues[$row]->value = $defaultValues[$row]->name;
				
				//add the class option.attr to be able to add yjk2filter class to option fields
				$defaultValues[$row]->class = "class='yjk2options'";
			}

			if($add_default == 1 && $extraField->type == 'select' ){
				$added_default_value 			= new stdClass();
				$added_default_value->name 		= JText::_("MOD_YJK2FILTER_SELECT_DEFAULT_OPTION");
				$added_default_value->value 	= "";
				$added_default_value->target 	= "";
				$added_default_value->class 	= "class='first_option'";
				array_unshift($defaultValues, $added_default_value);
			}

			if (isset($item)){
				$currentValues=$json->decode($item->extra_fields);
				if (count($currentValues)){
					foreach ($currentValues as $value){
						if ($value->id==$extraField->id){
							if($extraField->type=='textarea'){
								$active[0]=$value->value;
							}
							else if($extraField->type=='date') {
								$active = (is_array($value->value))? $value->value[0]:$value->value;
							}
							else{
								$active = $value->value;
							}
						}
					}
				}
	
			}

			switch ($extraField->type){
	
				case 'textfield':
				$output='<input type="text" id="YJK2FilterExtraField_'.$extraField->id.'" name="YJK2Filter[ExtraField_'.$extraField->id.']" value="'.$active.'" '.(isset($extraField->attribute) && $extraField->attribute == 'disabled' ? 'disabled="disabled"' : "" ).'/>';
				break;
	
				case 'labels':
				$output='<input type="text" name="YJK2Filter[ExtraField_'.$extraField->id.']" value="'.$active.'"/> '.JText::_('K2_COMMA_SEPARATED_VALUES');
				break;			
				
				case 'textarea':
				if($active[1]){
					$output='<textarea name="YJK2Filter[ExtraField_'.$extraField->id.']" id="YJK2FilterExtraField_'.$extraField->id.'" rows="10" cols="40" class="k2ExtraFieldEditor">'.$active[0].'</textarea>';
				}
				else{
					$output='<textarea name="YJK2Filter[ExtraField_'.$extraField->id.']" rows="10" cols="40">'.$active[0].'</textarea>';
				}
	
				break;

				case 'select':
					if($group == 1){
						//action for search based on groups
						$onchange_action = $connection == 1 ? array('onchange'=>'yjk2fitler_update_search_form(this.id,this.value,0);') : '';
						//$onchange_action = array('onchange'=>'yjk2fitler_update_search_form(this.id,this.value,0);');
					}else{
						//action for extra field search
						$onchange_action 			= array();
						if($connection == 1){
							$onchange_action['onchange'] = 'yjk2fitler_update_field_form(this.id,this.value,0);';
						}
					}
					
					if(isset($extraField->attribute) && $extraField->attribute == 'disabled'){
						$onchange_action['disabled'] = 'disabled';
					}					

					//add the yjk2options class
					$yjk2filter_select_attr 					= array();
					$yjk2filter_select_attr['option.attr'] 		= 'class';
					$yjk2filter_select_attr['id'] 				= "YJK2FilterExtraField_".$extraField->id;
					$yjk2filter_select_attr['list.attr'] 		= $onchange_action;
					$yjk2filter_select_attr['option.key'] 		= "value";
					$yjk2filter_select_attr['option.text'] 		= "name";
					$yjk2filter_select_attr['list.select'] 		= $active;
					
					$output=JHTML::_('select.genericlist', $defaultValues, 'YJK2Filter[ExtraField_'.$extraField->id.']', $yjk2filter_select_attr);
				break;
	
				case 'multipleSelect':
					if($group == 1){
						//action for search based on groups
						$onchange_action = $connection == 1 ? array('onchange'=>'yjk2fitler_update_search_form(this.id,this.value,1);','multiple'=>'multiple') : array('multiple'=>'multiple');
					}else{
						//action for extra field search
						$onchange_action 				= array();
						$onchange_action['multiple'] 	= 'multiple';
						if($connection == 1){
							$onchange_action['onchange'] = 'yjk2fitler_update_field_form(this.id,this.value,1);';
						}
					}
					
					if($extraField->attribute == 'disabled'){
						$onchange_action['disabled'] = 'disabled';
					}					

					//add the yjk2options class
					$yjk2filter_select_attr 					= array();
					$yjk2filter_select_attr['option.attr'] 		= 'class';
					$yjk2filter_select_attr['id'] 				= "YJK2FilterExtraField_".$extraField->id;
					$yjk2filter_select_attr['list.attr'] 		= $onchange_action;
					//$yjk2filter_select_attr['list.translate'] = "";
					$yjk2filter_select_attr['option.key'] 		= "value";
					$yjk2filter_select_attr['option.text'] 		= "name";
					$yjk2filter_select_attr['list.select'] 		= $active;

					$output=JHTML::_('select.genericlist', $defaultValues, 'YJK2Filter[ExtraField_'.$extraField->id.'][]', $yjk2filter_select_attr);
				break;
	
				case 'radio':
					$onchange_action = array();
					if($extraField->attribute == 'disabled'){
						$onchange_action['disabled'] = 'disabled';
					}				
				$output=JHTML::_('select.radiolist', $defaultValues, 'YJK2Filter[ExtraField_'.$extraField->id.']', $onchange_action, 'value', 'name',$active);
				break;
	
				case 'link':
				$output='<label>'.JText::_('K2_TEXT').'</label>';
				$output.='<input type="text" name="YJK2Filter[ExtraField_'.$extraField->id.'[]]" value="'.$active[0].'"/>';
				$output.='<label>'.JText::_('K2_URL').'</label>';
				$output.='<input type="text" name="YJK2Filter[ExtraField_'.$extraField->id.'[]]" value="'.$active[1].'"/>';
				$output.='<label for="YJK2Filter[ExtraField_'.$extraField->id.']">'.JText::_('K2_OPEN_IN').'</label>';
				$targetOptions[]=JHTML::_('select.option', 'same', JText::_('K2_SAME_WINDOW'));
				$targetOptions[]=JHTML::_('select.option', 'new', JText::_('K2_NEW_WINDOW'));
				$targetOptions[]=JHTML::_('select.option', 'popup', JText::_('K2_CLASSIC_JAVASCRIPT_POPUP'));
				$targetOptions[]=JHTML::_('select.option', 'lightbox', JText::_('K2_LIGHTBOX_POPUP'));
				$output.=JHTML::_('select.genericlist', $targetOptions, 'YJK2Filter[ExtraField_'.$extraField->id.'[]]', '', 'value', 'text', $active[2]);
				break;
	
				case 'csv':
					$output = '<input type="file" name="YJK2Filter[ExtraField_'.$extraField->id.'[]]"/>';
	
					if(is_array($active) && count($active)){
						$output.= '<input type="hidden" name="YJK2Filter[CSV_'.$extraField->id.']" value="'.htmlspecialchars($json->encode($active)).'"/>';
						$output.='<table class="csvTable">';
						foreach($active as $key=>$row){
							$output.='<tr>';
							foreach($row as $cell){
								$output.=($key>0)?'<td>'.$cell.'</td>':'<th>'.$cell.'</th>';
							}
							$output.='</tr>';
						}
						$output.='</table>';
						$output.='<label>'.JText::_('K2_DELETE_CSV_DATA').'</label>';
						$output.='<input type="checkbox" name="YJK2Filter[ResetCSV_'.$extraField->id.']"/>';
					}
				break;
				
				case 'date':
				$output = JHTML::_('calendar', $active, 'YJK2Filter[ExtraField_'.$extraField->id.']', 'YJK2FilterExtraField_'.$extraField->id);
				break;
	
			}

			return $output;
	
		}
		
		static function YjK2FilterItemsGroups(&$params){
			/* prepare database */
			$db					= JFactory::getDBO();

			/* prepare default module params */
			$yj_mod_name		= basename(dirname(__FILE__));// 10-8-2011
			$load_groups		= "";
			
			$range 						= $params->get('yjk2filter_groups_range');//yjk2filter_extrafields_connection
			$create_range				= $params->get('yjk2filter_create_groups_range');
			$range_all					= explode(PHP_EOL,$range);
			$json 						= new Services_JSON;
			$ranged_field				= array();
			$add_reload_parent_action 	= array();	
			
			//generate range 
			$skip_range 	= array();
			$formated_range = array();
			if(!empty($range_all) && $create_range == 1){
				foreach($range_all as $range_val){
					$range_val_first = explode("=>",trim($range_val));
					if($range_val_first[0] != "" && $range_val_first[1] != ""){
						
						//generate the range values
						$range_drop_down_row 	= array();							
						$range_values 			= explode("|",$range_val_first[1]);
						if(!empty($range_values)){
							foreach($range_values as $range_value){
								//create new object, we need to keep the same values as k2 database fields
								$range_object 			= new JObject();
								$range_object->name 	= $range_value;
								$range_object->value 	= $range_value;
								$range_object->targer 	= NULL;
								$range_drop_down_row[] 	= $range_object;
							}
							//add the drop-down values to the final formated range var
							$formated_range[$range_val_first[0]] = $range_drop_down_row;
						}
					}
				}
			}			
			
			require('modules/'.$yj_mod_name.'/yjme/get_k2extrafields.php');

			//  this is the main array for k2/joomla news items. both use same vars for ouptut
			$main_yj_arr = array();
			if(is_array($load_groups_fields) && !empty($load_groups_fields)){
				foreach ( $load_groups_fields as $row ) {
				
					//check to see if we have the field in range array
					//if yes replace the field type and values with the ranged ones
					if (array_key_exists($row->name, $formated_range) && !empty($formated_range[$row->name]) && $row->type == 'textfield'){
						//force extra field to a select one
						$row->type = "select";
						//add the new values
						$row->value = $json->encode($formated_range[$range_val_first[0]]);
					}				
				
					//see if we have to add reload action to this field name
					if(in_array($row->name,$add_reload_parent_action)){
						$reload = 1;
					}else{
						$reload = 0;
					}				
				
					$main_yj_arr[] = array(
						'group' 			=> $row->group,//add the field id to use it in jQuery for replacing the exact form element					
						'id' 				=> $row->id,//add the field id to use it in jQuery for replacing the exact form element
						'name' 				=> $row->name,
						'element' 			=> YjK2FilterHelp::renderExtraField($row,NULL,$reload,1,1)
					);
				}
			}

			return array('ranged_field'=>$ranged_field,'groups'=>array('load_groups'=>$load_groups,'load_groups_fields'=>$main_yj_arr));

		}
		
		function yjk2filter_order_compare($a, $b){
			//print_r($a);	
			//print_r($b); exit;
			if($a->name == $b->name) return 0;
			else return -1;
		}				
		
	}
	
}