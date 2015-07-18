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

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.modellist');

class CbprofileproModelProfiletypes extends JModelList
{

	public function getProfiletypes() 
	{
		if (!isset($this->profiletypes)) 
		{
			$this->_db->setQuery("SELECT id, title, alias, description FROM #__cb_profiletypes WHERE state = 1 ORDER BY ordering");
			if (!$this->profiletypes = $this->_db->loadObjectList()) 
			{
				$this->setError($this->_db->getError());
			}
		}
		return $this->profiletypes;
	}
}