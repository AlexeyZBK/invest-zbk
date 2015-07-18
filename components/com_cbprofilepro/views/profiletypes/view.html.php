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
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view'); 

if(!class_exists('JFakeView')) {
	if(version_compare(JVERSION,'3.0.0','ge')) {
		class JFakeView extends JViewLegacy {}               
	} else {
		class JFakeView extends JView {}
	}
}
 
class CbprofileproViewProfiletypes extends JFakeView 
{ 

	function display($tpl = null) 
    {  	

		$profiletypes = $this->get('profiletypes');
		
		$app = JFactory::getApplication();
		$params = $app->getParams();
		
		$display_type = $params->get('display_type', 'radio');
			
		$this->assignRef('profiletypes', $profiletypes); 
		$this->assignRef('params', $params);	
		$this->assignRef('display_type', $display_type);	
		
		$document = JFactory::getDocument();
		$document->addStyleSheet(JURI::root().'components/com_cbprofilepro/includes/css/profiletypes-select.css');
	
		// Check for errors.
		if (count($errors = $this->get('Errors'))) 
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
	
		parent::display($tpl); 
			
	}
	
} 