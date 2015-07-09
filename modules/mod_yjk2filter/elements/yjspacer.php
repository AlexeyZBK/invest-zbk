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

// Check to ensure this file is within the rest of the framework
defined('_JEXEC') or die('Restricted access');

jimport('joomla.form.formfield');

/**
 * Renders a spacer element
 *
 * @package 	Joomla.Framework
 * @subpackage		Parameter
 * @since		1.5
 */

class JFormFieldYjSpacer extends JFormField
{
	/**
	* Element name
	*
	* @access	protected
	* @var		string
	*/
	
	public $type = 'YjSpacer';

	public function getInput()
	//function fetchElement($name, $value, &$node, $control_name)
	{
		$value = $this->value;
		$options = array ();
		$getname = str_replace(array('jform[params]', '[', ']'),'',$this->name);
		return '
		<div id="'.$getname.'" class="yjspacer_holder">
			<div class="yjspacer">'.JText::_($value).'</div>
		</div>
		';
	
	}
/*	public function getLabel() {
		return false;
	}*/

}
