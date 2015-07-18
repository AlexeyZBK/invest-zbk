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

jimport('joomla.application.component.view');

if(!class_exists('JFakeView')) {
	if(version_compare(JVERSION,'3.0.0','ge')) {
		class JFakeView extends JViewLegacy {}               
	} else {
		class JFakeView extends JView {}
	}
}

class CbprofileproViewCode extends JFakeView
{
	public function display($tpl = null)
	{
		$document	= JFactory::getDocument();
		$document->setTitle('Code');
		
		$eName = (version_compare(JVERSION,'3.0.0','ge')) ? JFactory::getApplication()->input->get('e_name') : JRequest::getVar('e_name');
		$eName = preg_replace( '#[^A-Z0-9\-\_\[\]]#i', '', $eName );
		// $this->assignRef('eName', $eName); // JViewLegacy::assignRef is deprecated in J3
		$this->eName = $eName;
		
		$this->canDo	= CbprofileproHelper::getActions();

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		parent::display($tpl);
	}
}