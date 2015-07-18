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

// no direct access
defined('_JEXEC') or die;

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_cbprofilepro')) {
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

// Include dependencies
jimport('joomla.application.component.controller');

if(version_compare(JVERSION,'3.0.0','ge')) {
	$controller = JControllerLegacy::getInstance('Cbprofilepro');
	$controller->execute(JFactory::getApplication()->input->get('task'));
} else {
	$controller = JController::getInstance('Cbprofilepro');
	$controller->execute(JRequest::getCmd('task'));
}
$controller->redirect();