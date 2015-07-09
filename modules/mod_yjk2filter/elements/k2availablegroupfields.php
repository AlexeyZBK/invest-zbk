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


class JFormFieldk2availablegroupfields extends JFormField
{

	var	$type = 'k2availablegroupfields';

	function getInput(){

		$k2_check = JFolder::exists(JPATH_ROOT.DIRECTORY_SEPARATOR."components".DIRECTORY_SEPARATOR."com_k2".DIRECTORY_SEPARATOR);
		if($k2_check):
	
			$output= '
				<div id="yjk2filter_available_group_fields_content">
					<select id="jformparamsyjk2filter_available_group_fields_selected" class="inputbox" size="10" multiple="multiple" style="width:90%;" name="params[yjk2filter_available_group_fields_selected][]" disabled="disabled">
					</select>
				</div>					
			';		
		else:
			$output= '
				<div id="yjk2filter_available_group_fields_content">			
					<select id="jformparamsyjk2filter_available_group_fields_selected" class="inputbox" size="10" multiple="multiple" style="width:90%;" name="params[yjk2filter_available_group_fields_selected][]" disabled="disabled">
						<option value="" disabled="disabled">K2 is not installed!</option>
					</select>
				</div>
			';
		endif;
			return $output;
	}
}
