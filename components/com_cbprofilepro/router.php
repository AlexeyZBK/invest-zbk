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

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' ); 

function CbprofileproBuildRoute(&$query) 
{ 
	$segments = array(); 
	
	if (isset($query['view'])) 
	{ 
		$segments[] = $query['view']; 
		unset($query['view']); 
	} 
	if (isset($query['layout'])) 
	{ 
		$segments[] = $query['layout']; 
		unset($query['layout']); 
	} 
	
	return $segments;  
} 
function CbprofileproParseRoute($segments) 
{ 
	$vars = array(); 
	
	if (isset($segments[0])) { 
		$vars['view'] = $segments[0]; 
	}
	
	if (isset($segments[1])) { 
		$vars['layout'] = $segments[1]; 
	}
	
	return $vars; 
} 