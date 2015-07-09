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

jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

$yj_mod_name 				= basename(dirname(__FILE__));
$main_yj_arr 				= $yj_mod_name.'s';
$yj_get_items 				= $yj_mod_name;
$module_template 			= $params->get('module_template','Default');
$module_template_check		= JFolder::exists(JPATH_ROOT.DIRECTORY_SEPARATOR."modules".DIRECTORY_SEPARATOR.$yj_mod_name.DIRECTORY_SEPARATOR."tmpl".DIRECTORY_SEPARATOR.$module_template);
$k2_check					= JFolder::exists(JPATH_ROOT.DIRECTORY_SEPARATOR."components".DIRECTORY_SEPARATOR."com_k2".DIRECTORY_SEPARATOR);
$mainframe 					= JFactory::getApplication();

//check if the yjk2filter plugin is installed and published
if(!JPluginHelper::isEnabled('system', 'yjk2filter')){
	JError::raiseNotice('', JText::_('MOD_YJK2FILTER_PLUGIN_UNPUBLISHED'));		
}

if($module_template_check && $k2_check){
	JLoader::register('Services_JSON', JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_k2'.DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'JSON.php');
	
	require_once (dirname(__FILE__).DIRECTORY_SEPARATOR.'helper.php');
	require('modules/'.$yj_mod_name.'/yjme/params/params.php');

	//get the extra fields groups drop-down
	if($yjk2filter_groups_filter == 1){
		$yjk2filter_vars	= YjK2FilterHelp::YjK2FilterItemsGroups($params);
		$load_groups		= $yjk2filter_vars['groups'];
	}else{
		$yjk2filter_vars	= YjK2FilterHelp::YjK2FilterItems($params);
		$main_yj_arr 		= $yjk2filter_vars['main_yj_arr'];
	}
	
	//array with all ranged fields id
	$ranged_field		= $yjk2filter_vars['ranged_field'];
		
	//get the Itemid for the search result
	$yjk2filter_Itemid_action 	= "";
	$menu						= $mainframe->getMenu();
	$newItemid 					= $menu->getItems('link', 'index.php?option=com_k2&view=itemlist&layout=yjk2filter&task=yjk2filter', 1);
	if(isset($newItemid->id) && $newItemid->id > 0){
		$yjk2filter_Itemid_action = $newItemid->id;
	}else{

		// get menu item
		$db  = JFactory::getDbo();		
		$sql = "SELECT id 
				FROM #__menu
				WHERE link LIKE 'index.php?option=com_k2&view=itemlist&layout=yjk2filter%' AND published = 1 
				LIMIT 0,1";
		$db->setQuery($sql);
		$k2CategoryItemlist = $db->loadResult();
		// Make sure there aren't any errors
		if ($db->getErrorNum()) {
			echo $db->getErrorMsg();
			exit;
		}		
		
		if(isset($k2CategoryItemlist) && $k2CategoryItemlist > 0){
			$yjk2filter_Itemid_action = $k2CategoryItemlist;
		}else{
			//get the current Itemid
			$currentItemid				= $menu->getActive();
			$yjk2filter_Itemid_action 	= isset($currentItemid) && !empty($currentItemid) ?  $currentItemid->id : 0;
		}
	}

	require(JModuleHelper::getLayoutPath(''.$yj_mod_name.'',''.$module_template.'/default'));
	
}else{
	if(!$k2_check){
		echo JText::_( 'K2_ERROR' );
	}else{
		echo JText::_( 'TEMPLATE_ERROR' );
	}
}
?>