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
	protected $default_view = 'profiletypes';

	public function display($cachable = false, $urlparams = false)
	{
		require_once JPATH_COMPONENT.'/helpers/cbprofilepro.php';

		if(version_compare(JVERSION,'3.0.0','ge')) {
			$view   = $this->input->get('view', 'articles');
			$layout = $this->input->get('layout', 'articles');
			$id     = $this->input->getInt('id');
		} else {
			$view		= JRequest::getCmd('view', 'profiletypes');
			$layout 	= JRequest::getCmd('layout', 'default');
			$id			= JRequest::getInt('id');
		}

		// Check for edit form. 
		if ($view == 'profiletype' && $layout == 'edit' && !$this->checkEditId('com_cbprofilepro.edit.profiletype', $id)) {
			// Somehow the person just went to the form - we don't allow that.
			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
			$this->setMessage($this->getError(), 'error');
			$this->setRedirect(JRoute::_('index.php?option=com_cbprofilepro&view=profiletypes', false));

			return false;
		}

		parent::display();

		return $this;
	}
}