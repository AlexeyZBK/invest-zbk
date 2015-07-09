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

jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

if(version_compare(JVERSION,'3.0.0','<')) {
	JRequest::clean(); 
}

//load the language files also 
$lang 	= JFactory::getLanguage(); 
$lang->load("mod_yjk2filter", JPATH_SITE, null, false, false);

$k2_check = JFolder::exists(JPATH_ROOT.DIRECTORY_SEPARATOR."components".DIRECTORY_SEPARATOR."com_k2".DIRECTORY_SEPARATOR);
if($k2_check):

	$params = JComponentHelper::getParams('com_k2');
	$document = JFactory::getDocument();
	JHtml::_('behavior.framework', true);
	
	//get the group id
	$groups 	= JRequest::getVar('groups');
	$module_id 	= JRequest::getInt('module_id');

	//display extra fields for the selected group id
	if($groups != '' && $module_id > 0):
	
		//get the module params for extra fields connection
		$sql = "SELECT params
				FROM #__modules 
				WHERE module = 'mod_yjk2filter' AND id = " . $module_id;
		$db->setQuery($sql);
		$mod_params = $db->loadResult();

		// Instantiate the params.
		$params = new JRegistry;
		$params->loadString($mod_params);
		$db 	= JFactory::getDBO();

		//see if we have multiple groups selected
		if(strstr($groups, ',')){
			$groups_each = explode(",",$groups);
			$group_union_query = array();
			foreach($groups_each as $row => $group){
				$group_innerjoin_query[]	= " Inner Join #__k2_extra_fields AS f" . $row . " ON f.name = f" . $row . ".name";
				$group_where_query[] 		= " ( (f" . $row . ".type = 'select' OR f" . $row . ".type = 'multipleSelect' OR f" . $row . ".type = 'textfield' OR f" . $row . ".type = 'radio') AND f" . $row . ".group = " . $group . " AND f" . $row . ".published = 1) ";
			}
			$multiple_groups_query 	= "	SELECT f.name as label, '" . $groups . "' as groups
										FROM #__k2_extra_fields AS f
										" . implode(" ",$group_innerjoin_query) . " 
										WHERE
										" . implode(" AND ",$group_where_query) . "										
										GROUP BY f.name ORDER BY f.ordering ";//, group_concat(DISTINCT `group` separator ',') as groups 
			$db->setQuery($multiple_groups_query);
			$list = $db->loadObjectList();
		}else{
			//get the extra fields that belongs to all the groups
			$sql = "SELECT 
						f.name as label,
						group_concat(DISTINCT `group` separator ',') as groups 
					FROM #__k2_extra_fields AS f 
					WHERE (f.type = 'select' OR f.type = 'multipleSelect' OR f.type = 'textfield' OR f.type = 'radio') AND f.group IN (".$groups.") AND published = 1 
					GROUP BY name ORDER BY f.ordering ";
			$db->setQuery($sql);
			$list = $db->loadObjectList();
		}

		// Make sure there aren't any errors
		if ($db->getErrorNum()) {
			echo $db->getErrorMsg();
			exit;
		}	
		
		if(is_array($list) && !empty($list)):
			foreach ( $list as $item ) {
				$item->name = JString::str_ireplace('&#160;', '- ', $item->label);
				$mitems[] = JHTML::_('select.option',  $item->label, '   '.$item->label );
			}
	
			$fieldName = 'jform[params][yjk2filter_available_group_fields_selected][]';
	
			$output= JHTML::_('select.genericlist',  $mitems, $fieldName, ' multiple="multiple" size="10" style="width:90%;"', 'value', 'text', $params->get('yjk2filter_available_group_fields_selected') );
		else:
			$output= '
				<select id="jformparamsyjk2filter_available_group_fields_selected" class="inputbox" size="10" multiple="multiple" style="width:90%;" name="params[yjk2filter_available_group_fields_selected][]" disabled="disabled">
					<option value="" disabled="disabled">'.JText::_('MOD_YJK2FILTER_NO_EXTRAFIELDS_FOUND').'</option>
				</select><br />
			';
		endif;
	else:
		$fieldName = 'jform[params][yjk2filter_available_group_fields_selected][]';
		//empty group id, do not display any extra fields
		$output= JHTML::_('select.genericlist',  array(JText::_('MOD_YJK2FILTER_NO_EXTRAFIELDS_FOUND')) , $fieldName, 'multiple="multiple"', 'value', 'text', '' );
	endif;			
else:
	$output= '
		<select id="jformparamsyjk2filter_available_group_fields" class="inputbox" size="10" multiple="multiple" style="width:90%;" name="params[yjk2filter_available_group_fields][]" disabled="disabled">
			<option value="" disabled="disabled">K2 is not installed!</option>
		</select><br />
	';
endif;
echo $output;