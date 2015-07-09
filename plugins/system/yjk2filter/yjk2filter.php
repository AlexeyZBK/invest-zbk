<?php
/*======================================================================*\
|| #################################################################### || 
|| # Package - YJ K2 Filter                								||
|| # Copyright (C) since 2007  Youjoomla.com. All Rights Reserved.      ||
|| # license - PHP files are licensed under  GNU/GPL V2                 ||
|| # license - CSS  - JS - IMAGE files  are Copyrighted material        ||
|| # bound by Proprietary License of Youjoomla.com                      ||
|| # for more information visit http://www.youjoomla.com/license.html   ||  
|| # Redistribution and  modification of this software                  ||
|| # is bounded by its licenses                                         ||
|| # websites - http://www.youjoomla.com | http://www.yjsimplegrid.com  ||
|| #################################################################### ||
\*======================================================================*/
 
// no direct access  
defined('_JEXEC') or die ('Restricted access');
 
ini_set('error_reporting','E_ALL ^ E_NOTICE'); 

require_once (JPATH_ADMINISTRATOR.'/components/com_k2/lib/JSON.php');
 
jimport('joomla.plugin.plugin');
jimport('joomla.html.parameter');
jimport('joomla.version'); 

//yjk2filter code to display the k2 extra fields search module
class plgSystemYJK2Filter extends JPlugin {

	function plgSystemYJK2Filter(&$subject, $config) {
		parent::__construct($subject, $config);
	}
	 
	function onAfterRoute() {

		// Determine Joomla! version
		if(!defined('K2_JVERSION')){
			if(version_compare( JVERSION, '1.6.0', 'ge' )) {
				define('K2_JVERSION','16'); 
			} else {
				define('K2_JVERSION','15');
			}	
		}
		
		if (!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
		$YjK2FilterPath = JPATH_BASE.DS.'plugins'.DS.'system'.DS.'yjk2filter'.DS.'yjk2filter';

		//get the request variables 
		$option 	= JRequest::getVar("option");   
		$view 		= JRequest::getVar("view"); 
		$task 		= JRequest::getVar("task");  
		$layout		= JRequest::getVar("layout");  
		$format 	= JRequest::getVar("format","html");
		$tmpl		= JRequest::getVar("tmpl",""); 
		// Instantiate the application.
		$app 		= JFactory::getApplication('site');

		// Load the language file for the template
		$lang = JFactory::getLanguage();
		$lang->load("plg_system_yjk2filter", JPATH_ADMINISTRATOR, null, false, false);
		
		//action for yjk2filter search code
		if( ( $option == "com_k2" && $view == "itemlist" && $layout == "yjk2filter" ) || ( $option == "com_k2" && $view == "itemlist" && $task == "yjk2filter" )) { 

			if(empty($task)){
				
				JRequest::setVar('task', 'yjk2filter');
				
			}

			if(empty($layout)){
				
				JRequest::setVar('layout', 'yjk2filter');
				
			}
			
			//include com_k2 files
			require_once (JPATH_SITE.DS.'components'.DS.'com_k2'.DS.'controllers'.DS.'itemlist.php');
 
 			//define Joomla constants
			if (!defined('JPATH_ROOT')) define('JPATH_ROOT', JPath::clean(JPATH_SITE)); 
			if (!defined('JPATH_COMPONENT')) define( 'JPATH_COMPONENT',	JPATH_BASE.DS.'components'.DS.'com_k2');
			if (!defined('JPATH_COMPONENT_SITE')) define( 'JPATH_COMPONENT_SITE', JPATH_SITE.DS.'components'.DS.'com_k2');
			if (!defined('JPATH_COMPONENT_ADMINISTRATOR')) define( 'JPATH_COMPONENT_ADMINISTRATOR',	JPATH_ADMINISTRATOR.DS.'components'.DS.'com_k2');
			
 			//overwrite com_k2 default variables to use YjK2Filter files
			$yjk2filter_conf					=  array();
			$yjk2filter_conf['name'] 			=  "itemlist";  
			$yjk2filter_conf['default_task'] 	=  "display";
			$yjk2filter_conf['base_path'] 		=  $YjK2FilterPath;
			$yjk2filter_conf['model_path'] 		=  $YjK2FilterPath.DS."models"; 
			$yjk2filter_conf['view_path'] 		=  $YjK2FilterPath.DS."views";
			
			//create com_k2 controller
			$k2controller = new K2ControllerItemlist();
			$k2controller->__construct($yjk2filter_conf);

			//add YjK2Filter Template file to com_k2 View
			$view = $k2controller->getView($view, $format);

			require_once 'yjk2filter/models/yjk2filteritemlist.php';
			require_once 'yjk2filter/models/yjk2filteritem.php';

			
			$yjk2filteritemlist_model = new K2Modelyjk2filteritemlist();
			$view->setModel($yjk2filteritemlist_model,true);
			
			//create YjK2Filter Item Model and it to YjK2Filter View
			$yjk2filteritem_model 	= new K2Modelyjk2filterItem();
			$view->setModel($yjk2filteritem_model,true);						
			
			//check if current template have yjk2filter layout
			jimport('joomla.filesystem.path');			
			$template_layout = JPATH_SITE.DS.'templates'.DS.$app->getTemplate().DS.'html'.DS.'com_k2'.DS.'yjk2filter';
			$templateFound = JPath::find($template_layout, 'default.php');
			
			if($templateFound){
				$view->addTemplatePath($template_layout);			
			}else{
				$view->addTemplatePath($YjK2FilterPath.DS.'templates');			
			}
		}
		
	}
}