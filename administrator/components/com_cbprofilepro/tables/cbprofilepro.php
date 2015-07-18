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
defined('JPATH_BASE') or die;

class JTableCbprofilepro extends JTable
{
	function __construct(&$db)
	{
		parent::__construct('#__cb_profiletypes', 'id', $db);
	}

	public function bind($array, $ignore = '')
	{
	
		// Verify that the default profile type is not unset
		if ($this->default=='1' && ($array['default']=='0')) {
			$this->setError(JText::_('COM_CBPROFILEPRO_ERROR_UNSET_ONLY_DEFAULT'));
			return false;
		}

		// Verify that the default profile type is not unpublished
		if ($this->default=='1' /*&& $this->language=='*'*/ && $array['state'] !='1') {
			$this->setError(JText::_('COM_CBPROFILEPRO_ERROR_UNPUBLISH_DEFAULT'));
			return false;
		}

		return parent::bind($array, $ignore);
	}

	public function check()
	{
		if (trim($this->title) == '') {
			$this->setError(JText::_('COM_CBPROFILEPRO_WARNING_PROVIDE_VALID_NAME'));
			return false;
		}

		if (trim($this->alias) == '') {
			$this->alias = $this->title;
		}

		$this->alias = JApplication::stringURLSafe($this->alias);

		if (trim(str_replace('-','',$this->alias)) == '') {
			$this->alias = JFactory::getDate()->format('Y-m-d-H-i-s');
		}
		
		// Cast the default property to an int for checking.
		$this->default = (int) $this->default;

		return true;
	}

	public function store($updateNulls = false)
	{
		$date	= JFactory::getDate();
		$user	= JFactory::getUser();

		if ($this->id) {
			// Existing item
			if(version_compare(JVERSION,'3.0.0','ge')) {
				$this->modified		= $date->toSql();
			} else {
				$this->modified		= $date->toMySQL();
			}
			$this->modified_by	= $user->get('id');
		} else {
			// New profile type. A profile type created and created_by field can be set by the user,
			// so we don't touch either of these if they are set.
			if (!intval($this->created)) {
				if(version_compare(JVERSION,'3.0.0','ge')) {
					$this->created = $date->toSql();
				} else {
					$this->created = $date->toMySQL();
				}
			}

			if (empty($this->created_by)) {
				$this->created_by = $user->get('id');
			}
		}
		
		// Verify that the alias is unique
		$table = JTable::getInstance('Cbprofilepro','JTable');
		if ($table->load(array('alias'=>$this->alias, 'language'=>$this->language)) && ($table->id != $this->id || $this->id==0)) {
			$this->setError(JText::_('COM_CBPROFILEPRO_ERROR_UNIQUE_ALIAS'));
			return false;
		}
		
		// Verify that the default profile type is unique
		if ($this->default=='1') {
			$table = JTable::getInstance('Cbprofilepro','JTable');
			if ($table->load(array('default'=>'1'))) {
				if ($table->checked_out && $table->checked_out!=$this->checked_out) {
					$this->setError(JText::_('COM_CBPROFILEPRO_ERROR_DEFAULT_CHECKIN_USER_MISMATCH'));
					return false;
				}
				$table->default=0;
				$table->checked_out=0;
				$table->checked_out_time='0000-00-00 00:00:00';
				$table->store();
			}
		}
	
		return  parent::store($updateNulls);
	}

	public function publish($pks = null, $state = 1, $userId = 0)
	{
		// Initialise variables.
		$k = $this->_tbl_key;

		// Sanitize input.
		JArrayHelper::toInteger($pks);
		$userId = (int) $userId;
		$state  = (int) $state;

		// If there are no primary keys set check to see if the instance key is set.
		if (empty($pks)) {
			if ($this->$k) {
				$pks = array($this->$k);
			}
			// Nothing to set publishing state on, return false.
			else {
				$this->setError(JText::_('JLIB_DATABASE_ERROR_NO_ROWS_SELECTED'));
				return false;
			}
		}

		// Build the WHERE clause for the primary keys.
		$where = $k.'='.implode(' OR '.$k.'=', $pks);

		// Determine if there is checkin support for the table.
		if (!property_exists($this, 'checked_out') || !property_exists($this, 'checked_out_time')) {
			$checkin = '';
		} else {
			$checkin = ' AND (checked_out = 0 OR checked_out = '.(int) $userId.')';
		}

		// Update the publishing state for rows with the given primary keys.
		$this->_db->setQuery(
			'UPDATE `'.$this->_tbl.'`' .
			' SET `state` = '.(int) $state .
			' WHERE ('.$where.')' .
			$checkin
		);
		if(version_compare(JVERSION,'3.0.0','ge')) {
			$this->_db->execute();
		} else {
			$this->_db->query();
		}

		// Check for a database error.
		if ($this->_db->getErrorNum()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// If checkin is supported and all rows were adjusted, check them in.
		if ($checkin && (count($pks) == $this->_db->getAffectedRows())) {
			// Checkin the rows.
			foreach($pks as $pk) {
				$this->checkin($pk);
			}
		}

		// If the JTable instance value is in the list of primary keys that were set, set the instance.
		if (in_array($this->$k, $pks)) {
			$this->state = $state;
		}

		$this->setError('');

		return true;
	}

}