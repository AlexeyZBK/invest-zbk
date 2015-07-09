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

// Set flag that this is a parent file
if(!defined('_JEXEC')) define( '_JEXEC', 1 );
$get_file_info  = pathinfo(__FILE__);
$jpath = preg_replace('/(\btemplates\b|\bmodules\b|\bcomponents\b|\bplugins\b)(.*)/','',$get_file_info['dirname']);
define('JPATH_BASE',rtrim($jpath,DIRECTORY_SEPARATOR));
require_once ( JPATH_BASE .DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'defines.php' );
require_once ( JPATH_BASE .DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'framework.php' );
require_once ( JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR."components".DIRECTORY_SEPARATOR."com_k2".DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'JSON.php');

$mainframe 	= JFactory::getApplication('site');
$db			= JFactory::getDBO();

if(version_compare(JVERSION,'3.0.0','<')) {
	JRequest::clean(); 
}

//load the language files also 
$lang 	= JFactory::getLanguage();
$lang->load("mod_yjk2filter", JPATH_SITE, null, false, false);

$field_id	= JRequest::getInt('field_id');

$json 		= new Services_JSON;
$response 	= new JObject();

if($field_id > 0){
	$query_where = " AND f.group = ".$field_id." ";
}else{
	$query_where = "";
}

//get the module params for extra fields connection
jimport('joomla.application.module.helper');	
$mod  		= JModuleHelper::getModule('mod_yjk2filter');

// Instantiate the params.
$params 	= new JRegistry;
$params->loadString($mod->params);

$yjk2filter_groups_id = $params->get('yjk2filter_groups_id','');

//generate array from group connection fields
$connection 	= $params->get('yjk2filter_groups_connection');
$connection_all	= explode(PHP_EOL,$connection);

$conn_field_ids 			= array();
$skip_child_connection 		= array();
$add_reload_parent_action  	= array();

//generate connection
if(!empty($connection_all)){
	foreach($connection_all as $connection_val){
		$connection_val_first = explode("=>",trim($connection_val));
		if($connection_val_first[0] != "" && $connection_val_first[1] != ""){
			$add_reload_parent_action[] = $connection_val_first[0];
			$skip_child_connection[] 	= "'".trim($connection_val_first[1])."'";
		}
	}
}

//generate range
$range 			= $params->get('yjk2filter_groups_range');//yjk2filter_extrafields_connection
$create_range	= $params->get('yjk2filter_create_groups_range');
$range_all		= explode(PHP_EOL,$range);
$json 			= new Services_JSON;
$ranged_field	= array();	

//generate range 
$skip_range 	= array();
$formated_range = array();
if(!empty($range_all) && $create_range == 1){
	foreach($range_all as $range_val){
		$range_val_first = explode("=>",$range_val);
		if($range_val_first[0] != "" && $range_val_first[1] != ""){

			//get the extra fields id for the ranged field
			$query = "SELECT ex.id  FROM #__k2_extra_fields as ex
					  WHERE ex.name = '".trim($range_val_first[0])."' AND ex.group = ".$field_id." AND ex.type = 'textfield'";
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

//generate query to not reload the child connected field
if(!empty($skip_child_connection) && $field_id > 0){
	$skip_child_connection_query  = " AND f.name NOT IN (".implode(",",$skip_child_connection).") ";	
}else{
	$skip_child_connection_query = "";
}

//get the extra fields that belongs to all the groups	
$query = "	
		SELECT 
			f.*,
			NULL as attribute
		FROM #__k2_extra_fields AS f 
		WHERE (f.type = 'select' OR f.type = 'multipleSelect' OR f.type = 'textfield' OR f.type = 'radio') ".$skip_child_connection_query
		.$query_where.
		" GROUP BY name ";
//$query .= count($yjk2filter_groups_id) > 1 ? " HAVING (SELECT COUNT( ff.group ) FROM #__k2_extra_fields AS ff WHERE ff.name = f.name) = (SELECT COUNT( id ) FROM #__k2_extra_fields_groups ) " : "";

$db->setQuery($query);
$load_groups_fields = $db->loadObjectList();

// Make sure there aren't any errors
if ($db->getErrorNum()) {
	echo $db->getErrorMsg();
	exit;
}

if(!empty($load_groups_fields)){

	if($field_id > 0){
		//load the remaining extra fields values from db
		require_once(JPATH_ROOT.'/modules/mod_yjk2filter/helper.php');
	
		//generate the html layout
		foreach ( $load_groups_fields as $row ) {
			
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
				'js_id' 			=> str_replace(" ","",$row->name),//add the field id to use it in jQuery for replacing the exact form element
				'name' 				=> $row->name,
				'element' 			=> YjK2FilterHelp::renderExtraField($row,NULL,$reload,1,1),
				'ranged_field' 		=> implode(",",$ranged_field),
				'default_message' 	=> $lang->_("MOD_YJK2FILTER_SELECT_DEFAULT_OPTION")//add the field id to use it in jQuery for replacing the exact form element
			);
		}
		$response->message = $main_yj_arr;
		
	}else{
	
		//generate the html layout
		foreach ( $load_groups_fields as $row ) {
			
			$main_yj_arr[] = array(
				'js_id' 			=> str_replace(" ","",$row->name),//add the field id to use it in jQuery for replacing the exact form element
				'default_message' 	=> $lang->_("MOD_YJK2FILTER_SELECT_DEFAULT_OPTION")//add the field id to use it in jQuery for replacing the exact form element
			);
		}
		$response->no = $main_yj_arr;	
	}
}
	
echo $json->encode($response);
$mainframe->close();
exit;	
		