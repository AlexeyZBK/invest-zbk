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

class JFormFieldk2fieldsgroup extends JFormField
{

	var	$type = 'k2fieldsgroup';

	function getInput(){

	$k2_check = JFolder::exists(JPATH_ROOT.DIRECTORY_SEPARATOR."components".DIRECTORY_SEPARATOR."com_k2".DIRECTORY_SEPARATOR);
	
	if($k2_check):
		
		$params = JComponentHelper::getParams('com_k2');
		
		$document = JFactory::getDocument();
		 
		JHtml::_('behavior.framework', true);
		
		$db = JFactory::getDBO();
		$query = 'SELECT m.* FROM #__k2_extra_fields_groups as m';
		$db->setQuery( $query );
		$list = $db->loadObjectList();
		// Make sure there aren't any errors
		if ($db->getErrorNum()) {
			echo $db->getErrorMsg();
			exit;
		}		
		
		if(is_array($list) && !empty($list)):
			$mitems		= array();
			$mitems[] 	= JHTML::_('select.option',  '', JText::_('MOD_YJK2FILTER_SELECT_GROUP') );
			foreach ( $list as $item ) {
				$item->name = JString::str_ireplace('&#160;', '- ', $item->name);
				$mitems[] = JHTML::_('select.option',  $item->id, '   '.$item->name );
			}
	
			$fieldName = $this->name;
	
			$output= JHTML::_('select.genericlist',  $mitems, $fieldName, '', 'value', 'text', $this->value );
		else:
			$output= '
				<select id="jformparamsyjk2filter_fields_group_id" class="inputbox" style="width:90%;" name="params[yjk2filter_fields_group_id]" disabled="disabled">
				<option value="" disabled="disabled">'.JText::_('MOD_YJK2FILTER_NO_GROUPS_FOUND').'</option>
				</select><br />
			';		
		endif;
	else:
		$output= '
			<select id="jformparamsyjk2filter_fields_group_id" class="inputbox" style="width:90%;" name="params[yjk2filter_fields_group_id]" disabled="disabled">
			<option value="" disabled="disabled">K2 is not installed!</option>
			</select><br />
		';
	endif;
		return $output;
	}
}
