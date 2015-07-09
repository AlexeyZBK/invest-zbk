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

defined('_JEXEC') or die ;

class plgsystemyjk2filterInstallerScript{
 
    public function postflight($type, $parent){
		if(!JPluginHelper::getPlugin('system', 'yjk2filter') && JFile::exists(JPATH_SITE.DS.'plugins'.DS.'system'.DS.'yjk2filter'.DS.'yjk2filter.php')){
			$db = & JFactory::getDBO();
			$query = "UPDATE #__extensions SET enabled='1' WHERE element='yjk2filter'";
			$db->setQuery($query);
			$db->query();
		}

		 if (JFolder::exists(JPATH_ROOT.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_k2')){
			 
			JFile::copy(JPATH_ROOT.'/plugins/system/yjk2filter/k2views/yjk2filter.php',JPATH_ROOT.'/components/com_k2/views/itemlist/tmpl/yjk2filter.php');
			JFile::copy(JPATH_ROOT.'/plugins/system/yjk2filter/k2views/yjk2filter.xml',JPATH_ROOT.'/components/com_k2/views/itemlist/tmpl/yjk2filter.xml');
			
			echo '<h2 style="color:green;">YJK2 Filter is successfully installed!</h2>';
			
		 }else{
			 
			 
			 echo '<h2 style="color:red;">K2 is not installed. This extensions requires K2 Joomla Extension</h2>';
		 }
		
    }
}