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
 
class com_cbprofileproInstallerScript
{

	function preflight( $type, $parent ) {

		if ( $type == 'update' ) {
			
			$oldRelease = $this->getParam('version');
		
			if ($oldRelease == '1.0.0') {
				
				$db = JFactory::getDbo();
				$db->setQuery('SELECT extension_id FROM #__extensions WHERE element = "com_cbprofilepro"');
				$extension_id = $db->loadResult();
				
				if($extension_id) {
					
					$this->shemasUpdate( $extension_id, $oldRelease );
					
					$this->update_sitesUpdate( $extension_id );
					
				}
			}
		}
		
	}
	
	function shemasUpdate ( $extension_id, $oldRelease ) {
	
		$db = JFactory::getDbo();
		$db->setQuery('SELECT version_id FROM #__schemas WHERE extension_id = '.$extension_id);
		$version_id = $db->loadResult();

		if(!$version_id) {
		
			$db->setQuery("INSERT INTO #__schemas (version_id, extension_id) VALUES ('".$oldRelease."', '".$extension_id."')" );
			
			if($db->query()) {
				echo "<br />";
				echo "#__schemas updated";
			}
		}	
	
	}
	
	function update_sitesUpdate( $extension_id ) {
		
		$db = JFactory::getDbo();
		$db->setQuery('SELECT update_site_id FROM #__update_sites_extensions WHERE extension_id = '.$extension_id);
		$update_site_id = $db->loadResult();
		
		if($update_site_id) {
		
			$db->setQuery('DELETE FROM #__update_sites WHERE update_site_id = '.$update_site_id);
		
			if($db->query()) {
				echo "<br />";
				echo "#__update_sites updated";
			}
		}
		
	}

	function getParam( $name ) {
		
		$db = JFactory::getDbo();
		$db->setQuery('SELECT manifest_cache FROM #__extensions WHERE element = "com_cbprofilepro"');
		$manifest = json_decode( $db->loadResult(), true );
		return $manifest[ $name ];
	}

}