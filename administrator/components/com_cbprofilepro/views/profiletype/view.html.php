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

class CbprofileproViewProfiletype extends JFakeView
{
	protected $form;
	protected $item;
	protected $state;

	public function display($tpl = null)
	{
		// Initialiase variables.
		$this->form		= $this->get('Form');
		$this->item		= $this->get('Item');
		$this->state	= $this->get('State');
		$this->canDo	= CbprofileproHelper::getActions();

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
		if(version_compare(JVERSION,'3.0.0','ge')) {
			JFactory::getApplication()->input->set('hidemainmenu', true);
		} else {
			JRequest::setVar('hidemainmenu', true);
		}
		$user		= JFactory::getUser();
		$userId		= $user->get('id');
		$isNew		= ($this->item->id == 0);
		$checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $userId);
		$canDo		= CbprofileproHelper::getActions();
		JToolBarHelper::title(JText::_('COM_CBPROFILEPRO_PAGE_'.($checkedOut ? 'VIEW_PROFILETYPE' : ($isNew ? 'ADD_PROFILETYPE' : 'EDIT_PROFILETYPE'))), (version_compare(JVERSION,'3.0.0','ge')) ? 'pencil-2 article-add' : 'article-add.png');

		// Built the actions for new and existing records.

		// For new records, check the create permission.
		if ($isNew) {
			JToolBarHelper::apply('profiletype.apply', 'JTOOLBAR_APPLY');
			JToolBarHelper::save('profiletype.save', 'JTOOLBAR_SAVE');
			JToolBarHelper::custom('profiletype.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
			JToolBarHelper::cancel('profiletype.cancel', 'JTOOLBAR_CANCEL');
		}
		else {
			// Can't save the record if it's checked out.
			if (!$checkedOut) {
				// Since it's an existing record, check the edit permission, or fall back to edit own if the owner.
				if ($canDo->get('core.edit') || ($canDo->get('core.edit.own') && $this->item->created_by == $userId)) {
					JToolBarHelper::apply('profiletype.apply', 'JTOOLBAR_APPLY');
					JToolBarHelper::save('profiletype.save', 'JTOOLBAR_SAVE');

					// We can save this record, but check the create permission to see if we can return to make a new one.
					if ($canDo->get('core.create')) {
						JToolBarHelper::custom('profiletype.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
					}
				}
			}

			// If checked out, we can still save
			if ($canDo->get('core.create')) {
				JToolBarHelper::custom('profiletype.save2copy', 'save-copy.png', 'save-copy_f2.png', 'JTOOLBAR_SAVE_AS_COPY', false);
			}

			JToolBarHelper::cancel('profiletype.cancel', 'JTOOLBAR_CLOSE');
		}

	}
}