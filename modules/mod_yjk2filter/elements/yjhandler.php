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

//<field name="handler" type="yjhandler"/>   add once in xml to load custom codes
// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die();
/**
 * Renders a spacer element
 *
 * @package 	Joomla.Framework
 * @subpackage		Parameter
 * @since		1.5
 */

class JFormFieldYjHandler extends JFormField
{
	/**
	* Element name
	*
	* @access	protected
	* @var		string
	*/
	
	var	$type = 'YjHandler';
	
	public function getInput(){
		$e_folder = basename(dirname(dirname(__FILE__)));
		$document = JFactory::getDocument();
		$document->addScriptDeclaration('var yjk2filter_module_id = ' . JRequest::getInt('id') . ';');
				
		if(version_compare(JVERSION,'3.0.0','>=')) {
			$document->addStyleSheet(JURI::root() . 'modules/'.$e_folder.'/elements/css/stylesheet30.css');
			$document->addScript(JURI::root() . 'modules/'.$e_folder.'/elements/src/yjk2filter30.js');
		}else{
			$document->addStyleSheet(JURI::root() . 'modules/'.$e_folder.'/elements/css/stylesheet.css');
			$document->addScript(JURI::root() . 'modules/'.$e_folder.'/elements/src/yjk2filter.js');		
		}
	
		echo '<div id="selectedresult"></div>';
		return ;
	}
		public function getLabel() {
		return false;
	}
}
