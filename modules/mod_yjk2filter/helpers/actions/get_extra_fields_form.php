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
header("Content-Type: text/html");
$get_file_info  = pathinfo(__FILE__);
$jpath = preg_replace('/(\btemplates\b|\bmodules\b|\bcomponents\b|\bplugins\b)(.*)/','',$get_file_info['dirname']);
define('JPATH_BASE',rtrim($jpath,DIRECTORY_SEPARATOR));
require_once ( JPATH_BASE .DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'defines.php' );
require_once ( JPATH_BASE .DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'framework.php' );
require_once ( JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR."components".DIRECTORY_SEPARATOR."com_k2".DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'JSON.php');

$mainframe 	= JFactory::getApplication('site');
$mainframe->initialise();
$db			= JFactory::getDBO();

//load the language files also
$lang 	= JFactory::getLanguage();
$lang->load("mod_yjk2filter", JPATH_SITE, null, false, true);

$field_name	= JRequest::getVar('field_name', '');
$value		= JRequest::getVar('value', '');
$multiple	= JRequest::getInt('multiple');
$group		= JRequest::getInt('group');

$json 		= new Services_JSON;
$response 	= new JObject();

if($field_name != '' && $group > 0){

	//generate the k2 extra field id
	$field_id	= explode("_",$field_name);
	settype($field_id[1],'integer');
	
	//get the module params for extra fields connection
	jimport('joomla.application.module.helper');	
	$mod  		= JModuleHelper::getModule('mod_yjk2filter');
	
	// Instantiate the params.
	$params 	= new JRegistry;
	$params->loadString($mod->params);
	
	//get the extra fields id to reload
	$conn_field_ids 			= array();
	$conn_field_name 			= array();	
	$skip_child_connection 		= array();
	$add_reload_parent_action  	= array();	

	$connection 	= $params->get('yjk2filter_extrafields_connection');//yjk2filter_extrafields_connection
	$connection_all	= explode(PHP_EOL,$connection);
	
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
						  WHERE ex.name = '".trim($connection_val_first[0])."' AND ex.group = ".$group;
				$db->setQuery($query);
				$conn_first_id = $db->loadResult();
		
				$query = "SELECT ex.id  FROM #__k2_extra_fields as ex
						  WHERE ex.name = '".trim($connection_val_first[1])."' AND ex.group = ".$group;
				$db->setQuery($query);
				$conn_second_id = $db->loadResult();		
		
				// Make sure there aren't any errors
				if ($db->getErrorNum()) {
					echo $db->getErrorMsg();
					exit;
				}
	
				//add the reloaded extra feilds to the array		
				settype($conn_first_id,'integer');
				settype($field_id[1],'integer');
				if( $conn_first_id == $field_id[1] ){
					if(isset($conn_field_ids[$conn_first_id])){
						$conn_field_ids[$conn_first_id][] = $conn_second_id;
					}else{
						$conn_field_ids[$conn_first_id] = array($conn_second_id);
					}
					$conn_field_name[] = $connection_val_first[1];
				}
			}
		}
	}
	
	//selected Any value, so disable the child drop-downs
	if($value == ""){
		//no records found, disable the child fields
		foreach($conn_field_name as $connected_name){
			$main_yj_arr[] = array(
				'id' 				=> str_replace(" ","",$connected_name),//add the field id to use it in jQuery for replacing the exact form element
				'default_message' 	=> $lang->_("MOD_YJK2FILTER_SELECT_DEFAULT_OPTION")//add the field id to use it in jQuery for replacing the exact form element		
			);
			$response->no = $main_yj_arr;				
		}
		echo $json->encode($response);
		$mainframe->close();
		exit;		
	}
	
	if(isset($field_id[1]) && $field_id[1] != ''){
		
		//'(.*\"id\":\"".($field_id[1])."\",\"value\":\"\[.*\"".$value."\".*\]\")'";	
		//generate query for connected fields
		$regexp_extra = "";		
		if(isset($conn_field_ids[$field_id[1]]) && !empty($conn_field_ids[$field_id[1]])){
			foreach($conn_field_ids[$field_id[1]] as $connected_id){
				$regexp_extra .= ".*{\"id\":\"".($connected_id)."\",\"value\":\"[^\\\"].*[^\\\"]*\"}.*";
			}
		}
		
		//see if we have multiselect
		if($multiple == 1){
			$value_array 		= explode(",",$value);
			$query_array_value 	= array();		
			foreach($value_array as $value_val){
				settype($value_val,'integer');
				//["1","2","3","4"] - \"[^\\\"]*".$yjk2filter_search_value."[^\\\"]*\"
				$query_array_value[] = "\"[^\\\"]*".$value_val."[^\\\"]*\"";
			}
			$query = "SELECT 
						* 
					  FROM #__k2_items 
					  WHERE extra_fields REGEXP BINARY '(.*{\"id\":\"".($field_id[1])."\",\"value\":\[".implode(",",$query_array_value)."\]})'";
			$db->setQuery($query);
			$k2_items = $db->loadObjectList();			
		}else{
			$query = "SELECT 
						* 
					  FROM #__k2_items 
					  WHERE extra_fields REGEXP BINARY '(.*{\"id\":\"".($field_id[1])."\",\"value\":\"".$value."\"})'";
			$db->setQuery($query);
			$k2_items = $db->loadObjectList();
		}

		// Make sure there aren't any errors
		if ($db->getErrorNum()) {
			echo $db->getErrorMsg();
			exit;
		}

		if(!empty($k2_items)){
			
			//create new array with the desired field values
			$new_field_values = array();

			$yjk2filter_extrafields		= 1;
			$yjk2filter_extrafields_id 	= array();

			foreach($k2_items as $k2_item){
			
				$dbValues = $json->decode($k2_item->extra_fields);

				if(!empty($dbValues)){
					//remove the current field id
					//for($i=0; $i < count($dbValues); $i++){
					foreach($dbValues as $dbVal){

						if($dbVal->id == $field_id[1]){
							//unset($dbValues[$i]);
							continue;
						}else{
							//do not select from database the changed element
							if(!in_array($dbVal->id, $yjk2filter_extrafields_id)) $yjk2filter_extrafields_id[] = $dbVal->id;

							if(isset($conn_field_ids) && !empty($conn_field_ids)){
								//create the new_field_values array field
								if(in_array($dbVal->id,$conn_field_ids[$field_id[1]])){
									if(!isset($new_field_values[$dbVal->id])){
										$new_field_values[$dbVal->id]->id 		= $dbVal->id;
										$new_field_values[$dbVal->id]->value 	= array();
									}
									
									//add the values to the new_field_values_array
									if(!in_array($dbVal->value,$new_field_values[$dbVal->id]->value)){
										//check for multiselect, values is array or not
										if(is_array($dbVal->value)){
											$new_field_values[$dbVal->id]->value = $dbVal->value;									
										}else{
											$new_field_values[$dbVal->id]->value[] = $dbVal->value;
										}
									}
									
								}
							}
						}
					}

				}else{
					//error message, disable the child drop-dwons
					foreach($conn_field_name as $connected_name){
						$main_yj_arr[] = array(
							'id' 				=> str_replace(" ","",$connected_name),//add the field id to use it in jQuery for replacing the exact form element
							'default_message' 	=> $lang->_("MOD_YJK2FILTER_SELECT_DEFAULT_OPTION")//add the field id to use it in jQuery for replacing the exact form element		
						);
						$response->error = $main_yj_arr;				
					}
					echo $json->encode($response);
					$mainframe->close();
					exit;
				}
			}

			//load the remaining extra fields values from db
			require		(JPATH_ROOT.'/modules/mod_yjk2filter/yjme/get_k2extrafields.php');
			require_once(JPATH_ROOT.'/modules/mod_yjk2filter/helper.php');

			//generate the html layout
			foreach ( $load_items as $row ) {//$load_items
				
				if(!is_object($row) || !isset($new_field_values[$row->id])){
					continue;
				}
				
				//see if we have to add reload action to this field name
				if(in_array($row->name,$add_reload_parent_action)){
					$reload = 1;
				}else{
					$reload = 0;
				}				

				//keep only the new fields values and remove the values that do not match the selected fields so far
				$all_db_values = $json->decode($row->value);
				if(is_array($all_db_values) && !empty($all_db_values)){
					foreach($all_db_values as $db_row => $db_value){
						//remove the records we don't need
						if( !in_array($db_value->value,$new_field_values[$row->id]->value) ){
							unset($all_db_values[$db_row]);
						}
					}
				}
				$all_db_values = array_values($all_db_values);
				$row->value 	= $json->encode($all_db_values);
				
				$main_yj_arr[] = array(
					'id' 				=> str_replace(" ","",$row->name),//add the field id to use it in jQuery for replacing the exact form element
					'name' 				=> $row->name,
					'element' 			=> YjK2FilterHelp::renderExtraField($row,NULL,$reload,1,0),
					'default_message' 	=> $lang->_("MOD_YJK2FILTER_SELECT_DEFAULT_OPTION")//add the field id to use it in jQuery for replacing the exact form element		
				);
				$response->message = $main_yj_arr;
			}					

			if(!isset($response->message)){
				//no records found, disable the child fields
				foreach($conn_field_name as $connected_name){
					$main_yj_arr[] = array(
						'id' 				=> str_replace(" ","",$connected_name),//add the field id to use it in jQuery for replacing the exact form element
						'default_message' 	=> $lang->_("MOD_YJK2FILTER_SELECT_DEFAULT_OPTION")//add the field id to use it in jQuery for replacing the exact form element		
					);
					$response->no = $main_yj_arr;				
				}
			}			
			
		}else{
			//no records found, disable the child fields
			foreach($conn_field_name as $connected_name){
				$main_yj_arr[] = array(
					'id' 				=> str_replace(" ","",$connected_name),//add the field id to use it in jQuery for replacing the exact form element
					'default_message' 	=> $lang->_("MOD_YJK2FILTER_SELECT_DEFAULT_OPTION")//add the field id to use it in jQuery for replacing the exact form element		
				);
				$response->no = $main_yj_arr;				
			}
		}
		
	}else{
		//error message, don't do anything
		$response->error 	= "";
		$response->message 	= "";
		$response->no		= "";
		
		echo $json->encode($response);
		$mainframe->close();
		exit;
	}

	echo $json->encode($response);
	$mainframe->close();
	exit;	
}	

$response->error 	= "";
$response->message 	= "";
$response->no		= "";

echo $json->encode($response);
$mainframe->close();
exit;	