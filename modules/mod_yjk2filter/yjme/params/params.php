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

/*Those are changable module params.They will not affect the news engines.These params are dinamic. You can add more or remove the ones that are here. Do not forget to edit/remove the xml param tags for the params changed/added. Also remove the conditions for the param in module template default.php file*/
defined('_JEXEC') or die('Restricted access');
	
	$yjk2filter_groups_filter					= $params->get ('yjk2filter_groups_filter',1);	// K2 Extra fields all/select
	$yjk2filter_extrafields						= $params->get ('yjk2filter_extrafields',0);		// K2 Extra fields all/select
	$yjk2filter_fields_group_id 				= $params->get ('yjk2filter_fields_group_id','');	// K2 Extra fields all/select
	$yjk2filter_extrafields_id					= $params->get ('yjk2filter_extrafields_id','');	// K2 Extra fields selected id
	$yjk2filter_rename_group					= $params->get ('yjk2filter_rename_group','');	// K2 Extra fields renamed values for groups
	$yjk2filter_groups							= $params->get ('yjk2filter_groups',0);			// K2 Groups filter all/select
	$yjk2filter_groups_id						= $params->get ('yjk2filter_groups_id','');		// K2 Groups filter selected id	
	$yjk2filter_available_group_fields			= $params->get ('yjk2filter_available_group_fields',0);
	$yjk2filter_available_group_fields_selected = $params->get ('yjk2filter_available_group_fields_selected','');
	$yjk2filter_groups_connection 				= $params->get ('yjk2filter_groups_connection','');
	$yjk2filter_extrafields_connection			= $params->get ('yjk2filter_extrafields_connection','');
	$range_group								= $params->get('yjk2filter_groups_range');//yjk2filter_extrafields_connection
	$create_range_group							= $params->get('yjk2filter_create_groups_range');
	$create_range_extrafields					= $params->get('yjk2filter_create_extrafields_range');//yjk2filter_extrafields_connection
	$range_extrafields							= $params->get('yjk2filter_extrafields_range');//yjk2filter_extrafields_connection
	$yjk2filter_groups_field_order				= $params->get('yjk2filter_groups_field_order');//yjk2filter_extrafields_connection
	$yjk2filter_extrafields_order				= $params->get('yjk2filter_extrafields_order');//yjk2filter_extrafields_connection

/*the headfile.php is moved here in case you need to do some calulations before output or you have params created for your inline JS. This way the headfiles.php sees the params before the load.*/
	require('modules/'.$yj_mod_name.'/yjme/headfiles.php');
?>