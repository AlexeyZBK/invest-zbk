<?php
/* Community Builder Profile Pro component for Joomla! 2.5, 3.x - Version 4.5.1
-------------------------------------------------------------------------------
Copyright (C) 2009-2014 Joomduck. All rights reserved.
Website: www.joomduck.com
E-mail: support@joomduck.com
Developer: Joomduck
Created: December 2014
License: GNU GPLv2 http://www.gnu.org/licenses/gpl-2.0.html
*/

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

if(!class_exists('JFakeContoller')) {
	if(version_compare(JVERSION,'3.0.0','ge')) {
		class JFakeContoller extends JControllerLegacy {}               
	} else {
		class JFakeContoller extends JController {}
	}
}

class CbprofileproController extends JFakeContoller
{
	function __construct($config = array())
	{

		parent::__construct($config);
	}

	public function display($cachable = false, $urlparams = false)
	{

		$vName	= JRequest::getCmd('view', 'profiletypes');
		JRequest::setVar('view', $vName);
		
		$lName	= JRequest::getCmd('layout', 'select');
		JRequest::setVar('layout', $lName);
		
		$user = JFactory::getUser();

		if($vName = 'profiletypes' && $lName = 'select' && $user->id != 0) {
			echo JText::_( 'WARNING_ALREADY_LOGGED_IN' );
			return;
		}

		parent::display($cachable, $urlparams);

		return $this;
	}
}