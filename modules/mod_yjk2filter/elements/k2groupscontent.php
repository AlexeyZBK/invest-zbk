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

class JFormFieldk2groupscontent extends JFormField
{

	var	$type = 'k2groupscontent';

	function getInput(){

	$k2_check = JFolder::exists(JPATH_ROOT.DIRECTORY_SEPARATOR."components".DIRECTORY_SEPARATOR."com_k2".DIRECTORY_SEPARATOR);
	if($k2_check):
		
		$params = &JComponentHelper::getParams('com_k2');
		
		$document = &JFactory::getDocument();
		 
		JHtml::_('behavior.framework', true);
		
		$db = &JFactory::getDBO();
		$query = 'SELECT * FROM #__k2_extra_fields_groups';
		$db->setQuery( $query );
		$list = $db->loadObjectList();
		// Make sure there aren't any errors
		if ($db->getErrorNum()) {
			echo $db->getErrorMsg();
			exit;
		}		
		
		if(is_array($list) && !empty($list)):
			foreach ( $list as $item ) {
				$output .= '
					<li class="yjk2filter_rename_groups">
						<label id="jform_params_yjk2filter_rename_groups-lbl" for="jform_params_yjk2filter_rename_group_'.$item->id.'">'.$item->name.'</label>
						<input name="jform[params][yjk2filter_rename_group]['.$item->id.']" id="jform_params_yjk2filter_rename_group_'.$item->id.'" value="'.$this->value[$item->id].'" type="text"><br/>
					</li>';
			}

		else:
			$output = JText::_('MOD_YJK2FILTER_NO_GROUPS_FOUND');		
		endif;
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
