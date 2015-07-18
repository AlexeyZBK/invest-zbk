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

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

class CbprofileproControllerProfiletypes extends JControllerAdmin
{
	public function __construct($config = array())
	{
		parent::__construct($config);
		$this->registerTask('unsetDefault',	'setDefault');
	}

	function setDefault()
	{
		if(version_compare(JVERSION,'3.0.0','ge')) {
			// Check for request forgeries
			JSession::checkToken('request') or jexit(JText::_('JINVALID_TOKEN'));
			$cid    = $this->input->get('cid', array(), 'array');
		} else {
			// Check for request forgeries
			JRequest::checkToken('default') or die(JText::_('JINVALID_TOKEN'));
			// Get items to publish from the request.
			$cid	= JRequest::getVar('cid', array(), '', 'array');
		}

		$data	= array('setDefault' => 1, 'unsetDefault' => 0);
		$task 	= $this->getTask();
		$value	= JArrayHelper::getValue($data, $task, 0, 'int');

		if (empty($cid)) {
			JError::raiseWarning(500, JText::_($this->text_prefix.'_NO_ITEM_SELECTED'));
		} else {
			// Get the model.
			$model = $this->getModel();

			// Make sure the item ids are integers
			JArrayHelper::toInteger($cid);

			// Publish the items.
			if (!$model->setDefault($cid, $value)) {
				JError::raiseWarning(500, $model->getError());
			} else {
				if ($value == 1) {
					$ntext = 'COM_CBPROFILEPRO_DEFAULT_SET';
				}
				else {
					$ntext = 'COM_CBPROFILEPRO_DEFAULT_UNSET';
				}
				$this->setMessage(JText::plural($ntext, count($cid)));
			}
		}

		$this->setRedirect(JRoute::_('index.php?option=com_cbprofilepro&view=profiletypes', false));
	}
	 
	public function getModel($name = 'Profiletype', $prefix = 'CbprofileproModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}
}