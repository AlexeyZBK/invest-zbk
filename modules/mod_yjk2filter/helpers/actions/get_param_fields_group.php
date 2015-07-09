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

jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

if(version_compare(JVERSION,'3.0.0','<')) {
	JRequest::clean(); 
}

//load the language files also 
$lang 	= JFactory::getLanguage(); 
$lang->load("mod_yjk2filter", JPATH_SITE, null, false, true);

$k2_check = JFolder::exists(JPATH_ROOT.DIRECTORY_SEPARATOR."components".DIRECTORY_SEPARATOR."com_k2".DIRECTORY_SEPARATOR);
if($k2_check):
	
	$params = JComponentHelper::getParams('com_k2');
	
	$document = JFactory::getDocument();
	 
	JHtml::_('behavior.framework', true);
	
	//get the group id
	$group_id = JRequest::getInt('group_id');

	//display extra fields for the selected group id
	if($group_id > 0):

		$db = JFactory::getDBO();
			
		//get the module params for extra fields connection
		$sql = "SELECT params
				FROM #__modules 
				WHERE module = 'mod_yjk2filter'";
		$db->setQuery($sql);
		$mod_params = $db->loadResult();
		
		// Instantiate the params.
		$params 	= new JRegistry;
		$params->loadString($mod_params);

		$query = 'SELECT m.* FROM #__k2_extra_fields as m WHERE m.published = 1 AND m.group = '.$group_id.' AND (m.type = "select" OR m.type = "multipleSelect" OR m.type = "textfield" OR m.type = "radio") GROUP BY m.name ORDER BY m.group, m.ordering ASC';
		$db->setQuery( $query );
		$list = $db->loadObjectList();
		// Make sure there aren't any errors
		if ($db->getErrorNum()) {
			echo $db->getErrorMsg();
			exit;
		}		
		
		if(is_array($list) && !empty($list)):
			foreach ( $list as $item ) {
				$item->name = JString::str_ireplace('&#160;', '- ', $item->name);
				$mitems[] = JHTML::_('select.option',  $item->id, '   '.$item->name );
			}
	
			$fieldName = 'jform[params][yjk2filter_extrafields_id][]';
	
			$output= JHTML::_('select.genericlist',  $mitems, $fieldName, ' multiple="multiple"', 'value', 'text', $params->get('yjk2filter_extrafields_id') );
		else:
			$output= '
				<select id="jformparamsyjk2filter_extrafields_id" class="inputbox" size="10" multiple="multiple" style="width:90%;" name="params[yjk2filter_extrafields_id][]" disabled="disabled">
				<option value="" disabled="disabled">'.JText::_('MOD_YJK2FILTER_NO_EXTRAFIELDS_FOUND').'</option>
				</select><br />
			';
		endif;
	else:
		$fieldName = 'jform[params][yjk2filter_extrafields_id][]';
		//empty group id, do not display any extra fields
		$output= JHTML::_('select.genericlist',  array() , $fieldName, 'multiple="multiple"', 'value', 'text', '' );
	endif;			
else:
	$output= '
		<select id="jformparamsyjk2filter_extrafields_id" class="inputbox" size="10" multiple="multiple" style="width:90%;" name="params[yjk2filter_extrafields_id][]" disabled="disabled">
		<option value="" disabled="disabled">K2 is not installed!</option>
		</select><br />
	';
endif;
echo $output;
$mainframe->close();
exit;