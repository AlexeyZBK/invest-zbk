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




class JFormFieldk2extrafieldsmultiple extends JFormField
{

	var	$type = 'k2extrafieldsmultiple'; 

	function getInput(){

	$k2_check = JFolder::exists(JPATH_ROOT.DIRECTORY_SEPARATOR."components".DIRECTORY_SEPARATOR."com_k2".DIRECTORY_SEPARATOR);
	if($k2_check):
		
		$params = &JComponentHelper::getParams('com_k2');

		$document = &JFactory::getDocument();
		 
		JHtml::_('behavior.framework', true);
		
/*		//get the group id
		$group_id = JRequest::getInt('group_id');

		//display extra fields for the selected group id
		if($group_id > 0):
			$db = &JFactory::getDBO();
			echo $query = 'SELECT m.* FROM #__k2_extra_fields as m WHERE m.published = 1 AND m.group = '.$group_id.' AND (m.type = "select" OR m.type = "multipleSelect") GROUP BY m.name ORDER BY m.group, m.ordering ASC';
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
		
				$fieldName = $this->name.'[]';

				$output= JHTML::_('select.genericlist',  $mitems, $fieldName, ' size="10" multiple="multiple" style="width:90%;" ', 'value', 'text', $this->value );
			else:
				$output= '
					<select id="jformparamsyjk2filter_extrafields_id" class="inputbox" size="10" multiple="multiple" style="width:90%;" name="params[yjk2filter_extrafields_id][]" disabled="disabled">
					<option value="" disabled="disabled">'.JText::_('MOD_YJK2FILTER_NO_EXTRAFIELDS_FOUND').'</option>
					</select><br />
				';		
			endif;
		else:*/
			$fieldName = $this->name.'[]';		
			//empty group id, do not display any extra fields
			$output= JHTML::_('select.genericlist',  array() , $fieldName, 'multiple="multiple"', 'value', 'text', '' );
		//endif;			
	else:
		$output= '
			<select id="jformparamsyjk2filter_extrafields_id" class="inputbox" size="10" multiple="multiple" style="width:90%;" name="params[yjk2filter_extrafields_id][]" disabled="disabled">
			<option value="" disabled="disabled">K2 is not installed!</option>
			</select><br />
		';
	endif;
		return $output;
	}
}
