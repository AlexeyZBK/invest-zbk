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

class JFormFieldk2groupsconnection extends JFormField{

	var	$type = 'k2groupsconnection'; 
 
	function getInput(){

		$k2_check = JFolder::exists(JPATH_ROOT.DIRECTORY_SEPARATOR."components".DIRECTORY_SEPARATOR."com_k2".DIRECTORY_SEPARATOR);
		
		if($k2_check):
			$html = '
			<div id="group_connections">
				<div class="clearbuttons"></div>
				<div class="button2-left">
					<div class="blank">
						<a title="'.JText::_('Add new connection').'"  href="javascript:yjk2filter_create_group_connections();">'.JText::_('Add new connection').'</a>
					</div>
				</div>
			</div>
			';
			echo $html;
		else:
			$output= 'K2 is not installed!<br />';
		endif;
			return $output;
	}
}
