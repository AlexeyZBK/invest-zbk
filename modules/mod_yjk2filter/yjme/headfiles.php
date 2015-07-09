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
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.framework', true);
$document 	= JFactory::getDocument();
$module_css	= $params->get('module_css','stylesheet.css');
$document->addStyleSheet(JURI::base() . 'modules/'.$yj_mod_name.'/css/'.$module_css.'');

if(version_compare(JVERSION,'3.0.0','>=')){
	$document->addScript(JURI::base() . 'modules/'.$yj_mod_name.'/src/yjk2filter30.js');
}else{
	$document->addScript(JURI::base() . 'modules/'.$yj_mod_name.'/src/yjk2filter.js');
}
			
//Document type examples
//$document->addStyleSheet(JURI::base() . 'modules/'.$yj_mod_name.'/css/'.$module_css.'');
//$document->addScript('');
//$document->addScriptDeclaration("jQuery.noConflict();");
//$document->addCustomTag('<style type="text/css"></style>');
//$document->addScriptDeclaration("");

$document->addScriptDeclaration("var YjK2Filter_url = '".JURI::base()."'; var YjK2Filter_no_values_form = '".JText::_('MOD_YJK2FILTER_FORM_NO_VALUES')."'");

$who = strtolower($_SERVER['HTTP_USER_AGENT']);
if(preg_match("/msie 7/",$who) ){
	  $document->addCustomTag('
	  <style type="text/css">
	  #yjk2filter_extraFieldsContainer .yjk2filter_oholder select{
	   height:20px;
	   position: absolute;
	   top: -2px; 
	   left:-2px;
	  }
	  #yjk2filter_extraFieldsContainer .yjk2filter_oholder{
		  height:17px;
	  }
	  </style>');
}
?>