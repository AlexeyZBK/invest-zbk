<?php 
/**
 * @package		YJ Module Engine
 * @author		Youjoomla.com
 * @website     Youjoomla.com 
 * @copyright	Copyright (c) 2007 - 2011 Youjoomla.com.
 * @license   PHP files are GNU/GPL V2. CSS / JS / IMAGES are Copyrighted Commercial 
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
//require_once (JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_content'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'route.php');
 
if(!isset($yjk2filter_groups_filter)) 					$yjk2filter_groups_filter					= $params->get ('yjk2filter_groups_filter',1);
if(!isset($yjk2filter_extrafields)) 					$yjk2filter_extrafields						= $params->get ('yjk2filter_extrafields',0);
if(!isset($yjk2filter_extrafields_id))					$yjk2filter_extrafields_id 					= $params->get ('yjk2filter_extrafields_id','');

if(!isset($yjk2filter_groups))							$yjk2filter_groups							= $params->get ('yjk2filter_groups',0);
if(!isset($yjk2filter_groups_id))						$yjk2filter_groups_id						= $params->get ('yjk2filter_groups_id','');
if(!isset($yjk2filter_fields_group_id))					$yjk2filter_fields_group_id					= $params->get ('yjk2filter_fields_group_id','');

if(!isset($yjk2filter_available_group_fields)) 			$yjk2filter_available_group_fields			= $params->get ('yjk2filter_available_group_fields',0);
if(!isset($yjk2filter_available_group_fields_selected))	$yjk2filter_available_group_fields_selected = $params->get ('yjk2filter_available_group_fields_selected','');

if(!isset($yjk2filter_groups_connection))				$yjk2filter_groups_connection 				= $params->get ('yjk2filter_groups_connection','');
if(!isset($yjk2filter_extrafields_connection))			$yjk2filter_extrafields_connection 			= $params->get ('yjk2filter_extrafields_connection','');

if(!isset($yjk2filter_groups_field_order))				$yjk2filter_groups_field_order 				= $params->get ('yjk2filter_groups_field_order','');
if(!isset($yjk2filter_extrafields_order))				$yjk2filter_extrafields_order 				= $params->get ('yjk2filter_extrafields_order','');


//Create extra fields groups filter
if($yjk2filter_groups_filter == 1){

	if ($yjk2filter_groups == 1 && !empty($yjk2filter_groups_id) && is_array($yjk2filter_groups_id) ) {
		$selected_groups_query_group  = " AND g.id    IN (".implode(",",$yjk2filter_groups_id).")";
		$selected_groups_query_fields = " AND f.group IN (".implode(",",$yjk2filter_groups_id).")";		
	}else{
		$selected_groups_query_group  = "";
		$selected_groups_query_fields = "";
	}

	//select extra fields group and each group fields
	$query = "SELECT 
				g.name as text, 
				g.id as value,
				CONCAT( 'class=','\"yjk2options\"' ) as class,
				(SELECT group_concat(DISTINCT f.name separator ',') FROM #__k2_extra_fields as f WHERE g.id = f.group AND published = 1 ORDER BY ordering) as fields, 
				(SELECT group_concat(DISTINCT f.name separator ',') FROM #__k2_extra_fields as f WHERE  published = 1 ORDER BY ordering) as all_fields 				
			  FROM #__k2_extra_fields_groups as g 
			  WHERE 1 ".$selected_groups_query_group;

	$db->setQuery($query);
	$load_groups = $db->loadObjectList('value');

	// Make sure there aren't any errors
	if ($db->getErrorNum()) {
		echo $db->getErrorMsg();
		exit;
	}
	
	//get the extra fields that belongs to all the groups
	if ($yjk2filter_available_group_fields == 1 && !empty($yjk2filter_available_group_fields_selected) && is_array($yjk2filter_available_group_fields_selected) ) {
		foreach($yjk2filter_available_group_fields_selected as $row => $value){
			$yjk2filter_available_group_fields_selected[$row] = "'".$value."'";
		}
		$query_available_group_fields  = " AND f.name IN (".implode(",",$yjk2filter_available_group_fields_selected).")";
	}else{
		$query_available_group_fields  = "";
	}

	//query for connected field only
	$connected_only_query = "";
	$connected_only_array = array();
	if(isset($new_field_values) && !empty($new_field_values)){
		foreach($new_field_values as $new_field_id){
			$connected_only_array[] = $new_field_id->id;
		}
		if(!empty($connected_only_array)){
			$connected_only_query = " AND f.id IN (".implode(",",$connected_only_array).")";
		}
	}
	
	$sql = "SELECT 
				f.*,
				f.name as label,
				'disabled' as attribute,
				group_concat(DISTINCT `group` separator ',') as groups 
			FROM #__k2_extra_fields AS f 
			WHERE (f.type = 'select' OR f.type = 'multipleSelect' OR f.type = 'textfield' OR f.type = 'radio') ".$selected_groups_query_fields." ".$query_available_group_fields." ".$connected_only_query." AND published = 1 
			GROUP BY name ";
		//$sql .= count($yjk2filter_groups_id) > 1 ? " HAVING (SELECT COUNT( ff.group ) FROM #__k2_extra_fields AS ff WHERE ff.name = f.name) = (SELECT COUNT( id ) FROM #__k2_extra_fields_groups ) " : "";
		$sql .= " ORDER BY f.ordering";
	$db->setQuery($sql);
	$load_groups_fields = $db->loadObjectList();

	// Make sure there aren't any errors
	if ($db->getErrorNum()) {
		echo $db->getErrorMsg();
		exit;
	}


	//reorder the field by module param
	if($yjk2filter_groups_field_order != ""){
		$yjk2filter_order_array = explode(PHP_EOL,$yjk2filter_groups_field_order);
		$yjk2filter_order_temp  = array();

		foreach($yjk2filter_order_array as $order_row => $order_name){
			foreach($load_groups_fields as $item_row => $item_name){
				//get the new order key
				if(trim($order_name) == trim($item_name->name)){
					//move the found object to the new order array
					$yjk2filter_order_array[$order_row] = $item_name;
					//remove the moved array
					unset($load_groups_fields[$item_row]);
					break;
				}
			}
		}

		foreach($load_groups_fields as $item_row => $item_name){
			array_push($yjk2filter_order_array,$item_name);
		}
		$load_groups_fields = $yjk2filter_order_array;
	}

}else{

	//query for connected field only
	if(isset($skip_child_connection) && !empty($skip_child_connection)){
		$query_disable_child = " CASE WHEN exf.name IN (".implode(",",$skip_child_connection).") THEN 'disabled' ELSE NULL END as attribute, ";
	}else{
		$query_disable_child = "";	
	}

	//select the extra fields
	$query = "SELECT 
				exf.*, ".$query_disable_child."
				exfg.name as groupname 
			FROM 
				#__k2_extra_fields AS exf 
				LEFT JOIN #__k2_extra_fields_groups exfg ON exf.group=exfg.id 
			WHERE 
				exf.id > 0 AND 
				published = 1 AND 
				(exf.type = 'select' OR exf.type = 'multipleSelect' OR exf.type = 'textfield' OR exf.type = 'radio') AND 
				exf.group = ".$yjk2filter_fields_group_id;

	//get the selected group id
	//if (!empty($yjk2filter_fields_group_id[0]) && is_array($yjk2filter_fields_group_id) ) {
	//	$query .= " AND exf.group IN (".implode(",",$yjk2filter_fields_group_id).")";
	//}	
	
	//get the selected extra fields
	if ($yjk2filter_extrafields == 1 && !empty($yjk2filter_extrafields_id) && is_array($yjk2filter_extrafields_id) ) {
		$query .= " AND exf.id IN (".implode(",",$yjk2filter_extrafields_id).")";
	}
	
	//add the extrafields order
	$query .= " ORDER BY exf.group, exf.ordering";
	
	$db->setQuery($query);
	$load_items = $db->loadObjectList();

	// Make sure there aren't any errors
	if ($db->getErrorNum()) {
		echo $db->getErrorMsg();
		exit;
	}

	//reorder the field by maodule param
	if($yjk2filter_extrafields_order != ""){
		$yjk2filter_order_array = explode(PHP_EOL,$yjk2filter_extrafields_order);
		$yjk2filter_order_temp  = array();

		foreach($yjk2filter_order_array as $order_row => $order_name){
			foreach($load_items as $item_row => $item_name){
				//get the new order key
				if(trim($order_name) == trim($item_name->name)){
					//move the found object to the new order array
					$yjk2filter_order_array[$order_row] = $item_name;
					//remove the moved array
					unset($load_items[$item_row]);
					break;
				}
			}
		}

		foreach($load_items as $item_row => $item_name){
			array_push($yjk2filter_order_array,$item_name);
		}
		$load_items = $yjk2filter_order_array;		
	}
}