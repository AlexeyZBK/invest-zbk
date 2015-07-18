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

jimport('joomla.application.component.modeladmin');

require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/cbprofilepro.php';

class CbprofileproModelProfiletypeBase extends JModelAdmin
{
	protected $text_prefix = 'COM_CBPROFILEPRO';

	protected function canDelete($record)
	{ 
		if (!empty($record->id)) {
			if ($record->default == 1) {
				return ;
			}
			$user = JFactory::getUser();
			return $user->authorise('core.delete', 'com_cbprofilepro');
		}
	}

	protected function canEditState($record)
	{
		$user = JFactory::getUser();

		if (!empty($record->id)) {
			return $user->authorise('core.edit.state', 'com_cbprofilepro');
		}
		else {
			return parent::canEditState($record);
		}
	}



	public function getTable($type = 'Cbprofilepro', $prefix = 'JTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	public function getItem($pk = null)
	{
		$item = parent::getItem($pk);

		return $item;
	}

	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_cbprofilepro.profiletype', 'profiletype', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}

		// Modify the form based on Edit State access controls.
		if (!$this->canEditState((object) $data)) {
			// Disable fields for display.
			$form->setFieldAttribute('ordering', 'disabled', 'true');
			$form->setFieldAttribute('state', 'disabled', 'true');

			// Disable fields while saving.
			// The controller has already verified this is an profile type you can edit.
			$form->setFieldAttribute('ordering', 'filter', 'unset');
			$form->setFieldAttribute('state', 'filter', 'unset');
		}

		return $form;
	}

	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_cbprofilepro.edit.profiletype.data', array());

		if (empty($data)) {
			$data = $this->getItem();

		}

		return $data;
	}

	public function save($data)
	{
		if (parent::save($data)) {
			return true;
		}

		return false;
	}
	
	protected function canSave($data = array(), $key = 'id')
	{
		return JFactory::getUser()->authorise('core.edit', 'com_cbprofilepro');
	}
	
	function publish(&$pks, $value = 1)
	{

		// Initialise variables.
		$table		= $this->getTable();
		$pks		= (array) $pks;

		// Default profile type existence checks.
		if ($value != 1) {
			foreach ($pks as $i => $pk)
			{
				if ($table->load($pk) && $table->default) {
					// Prune items that you can't change.
					JError::raiseWarning(403, JText::_('COM_CBPROFILEPRO_ERROR_UNPUBLISH_DEFAULT'));
					unset($pks[$i]);
					break;
				}
			}
		}

		// Clean the cache
		$this->cleanCache();
				
		return parent::publish($pks,$value);
	}

	 
	function setDefault(&$pks, $value = 1)
	{
		// Initialise variables.
		$table		= $this->getTable();
		$pks		= (array) $pks;
		$user		= JFactory::getUser();

		$setonce = false;
		$onedefault	= false;

		foreach ($pks as $i => $pk)
		{
			if ($table->load($pk)) {
				if (!$setonce) {
					$setonce = true;
					
					if ($table->default == $value) {
						unset($pks[$i]);
						JError::raiseNotice(403, JText::_('COM_CBPROFILEPRO_ERROR_ALREADY_DEFAULT'));
					}
					else {
						$table->default = $value;
						$table->state = 1;

						if (!$this->canSave($table)) {
							// Prune items that you can't change.
							unset($pks[$i]);
							JError::raiseWarning(403, JText::_('JLIB_APPLICATION_ERROR_SAVE_NOT_PERMITTED'));
						}
						else if (!$table->check()) {
							// Prune the items that failed pre-save checks.
							unset($pks[$i]);
							JError::raiseWarning(403, $table->getError());
						}
						else if (!$table->store()) {
							// Prune the items that could not be stored.
							unset($pks[$i]);
							JError::raiseWarning(403, $table->getError());
						}
					}
					
				}	
				else {
					unset($pks[$i]);
					if (!$onedefault) {
						$onedefault = true;
						JError::raiseNotice(403, JText::sprintf('COM_CBPROFILEPRO_ERROR_ONE_DEFAULT'));
					}
				}
			}
		}

		// Clean the cache
		$this->cleanCache();
				
		return true;
	}

	protected function getReorderConditions($table)
	{
		$condition = array();
		return $condition;
	}

	protected function cleanCache($group = NULL, $client_id = 0)
	{
		parent::cleanCache('com_cbprofilepro');
	}	
}

if(version_compare(JVERSION,'3.0.0','ge')) {

	class CbprofileproModelProfiletype extends CbprofileproModelProfiletypeBase {

		protected function prepareTable($table)
		{
			// Increment the version number.
			$table->version++;

			// Reorder the profile types so that new is first
			if (empty($table->id)) {
				$table->reorder('state >= 0');
			}
		}
	}

} else {

	class CbprofileproModelProfiletype extends CbprofileproModelProfiletypeBase {

		protected function prepareTable(&$table)
		{
			// Increment the version number.
			$table->version++;

			// Reorder the profile types so that new is first
			if (empty($table->id)) {
				$table->reorder('state >= 0');
			}
		}
	}
	
}
