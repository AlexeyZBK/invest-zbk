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

class CbprofileproViewProfiletypes extends JFakeView
{ 
	protected $items;
	protected $pagination;
	protected $state;

	public function display($tpl = null)
	{
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');
		$this->authors		= $this->get('Authors');
		
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$this->addToolbar();

		parent::display($tpl);
	}

	protected function addToolbar()
	{
		$canDo	= CbprofileproHelper::getActions();
		$user		= JFactory::getUser();
		JToolBarHelper::title(JText::_('COM_CBPROFILEPRO_PROFILETYPES_TITLE'), (version_compare(JVERSION,'3.0.0','ge')) ? 'stack article' : 'cbprofilepro-header');
		
		if(version_compare(JVERSION,'3.0.0','<')) {
			$document = JFactory::getDocument();
			$document->addStyleDeclaration('.icon-48-cbprofilepro-header { background-image: url(../administrator/components/com_cbprofilepro/includes/images/header-icon.png); }');
		}
		
		if ($canDo->get('core.create')) {
			JToolBarHelper::addNew('profiletype.add','JTOOLBAR_NEW');
		}

		if (($canDo->get('core.edit')) || ($canDo->get('core.edit.own'))) {
			JToolBarHelper::editList('profiletype.edit','JTOOLBAR_EDIT');
		}

		if ($canDo->get('core.edit.state')) {
			JToolBarHelper::divider();
			JToolBarHelper::custom('profiletypes.publish', 'publish.png', 'publish_f2.png','JTOOLBAR_PUBLISH', true);
			JToolBarHelper::custom('profiletypes.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
			JToolBarHelper::makeDefault('profiletypes.setDefault', 'COM_CBPROFILEPRO_TOOLBAR_SET_DEFAULT');
			JToolBarHelper::divider();
			JToolBarHelper::custom('profiletypes.checkin', 'checkin.png', 'checkin_f2.png', 'JTOOLBAR_CHECKIN', true);
		}

		if ($canDo->get('core.delete')) {
			JToolBarHelper::deleteList('', 'profiletypes.delete', 'JTOOLBAR_DELETE');
			JToolBarHelper::divider();
		}

		if ($canDo->get('core.admin')) {
			JToolBarHelper::preferences('com_cbprofilepro');
		}

	}
}