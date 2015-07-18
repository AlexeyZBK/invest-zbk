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

class ProcessCode {

	function __construct ($data, $params) {
	
		$this->data = $data;
		$this->initHeadTags();
	}

	public function process () {
	
		$this->prepareContent();
		$this->loadCodes();
		$this->data = preg_replace_callback("#{(SHOW_FOR|HIDE_FOR)\s(.*)}(.*){/(SHOW_FOR|HIDE_FOR)}#sU", array($this, 'loadShowFor'), $this->data);
		$this->data = preg_replace_callback("#{(SHOW_IF|HIDE_IF)\s(visitor's\s)??(.*)=(.*)}(.*){/(SHOW_IF|HIDE_IF)}#sU", array($this, 'loadShowIf'), $this->data);
		$this->loadPages();
		$this->loadjQueryUITabs();
		$this->loadModalSupport();
		$this->loadTooltips();
	}	

	public function setData($data) {
	
		$this->data = $data;
	}

	public function getData() {

		return $this->data;
	}
	
	protected function processSubstitution ($string, $substitution) {
		
		return '<em>'.sprintf($string, '<b>\''.htmlspecialchars($substitution, ENT_NOQUOTES).'\'</b>').'</em>';
	}
	
	protected function getMatches($regex) {
	
		return (preg_match_all($regex, $this->data, $matches, PREG_SET_ORDER)) ? $matches : null;
	}
	
	protected function replaceOutput ($output, $match) {
	
		$pos = strpos($this->data, $match);
		$this->data = substr_replace($this->data, $output, $pos, strlen($match));
		return;
	}
	
	private function initHeadTags() {
	
		$head_tags = array();
		$head_tags['raw'] = $head_tags['js'] = $head_tags['js_files'] = $head_tags['css'] = $head_tags['css_files'] = array();
		$this->head_tags = $head_tags;
	}
	
	protected function addHeadTags () {

		//if(!$this->head_tags) return;
		
		if($this->head_tags) {
		
			$document = JFactory::getDocument();
			$this->head_tags = implode("\n", $this->head_tags);
			$document->addCustomTag($this->head_tags);
		}
		
		$this->initHeadTags();
		 
		return;
	}	
	
	protected function prepareContent () {
	
		$this->data = JHTML::_('content.prepare', $this->data);
		return;
	}
	
	protected function loadModalSupport ($selector_class) {
	
 		if(stripos($this->data, 'class="'.$selector_class.'"') !== false) JHTML::_('behavior.modal', 'a.'.$selector_class);	
 		return;
	}

	protected function loadCodes ($db_table) {
		
		$matches = $this->getMatches('#{code\s(.*)}#sU');
		if(!$matches) return;
		
		$db = JFactory::getDBO();
		$db->setQuery("SELECT * FROM ".$db_table); //." WHERE title = ".$db->Quote($matches[1])
		$codes = $db->loadObjectList('title');

		foreach ($matches as $match) {
			$code_title = $match[1];
			if(isset($codes[$code_title])) {
				$code = $codes[$code_title];
				ob_start();
					eval('?>'.$code->content);
				$output = ob_get_clean();
			} else {
				$output = JText::_('ERROR_CODE_NOT_FOUND');
			} 

			$this->replaceOutput($output, $match[0]);
		}
	
		return;
	}
	
	// This function loads content shown/hidden if different field value
	protected function loadShowIf ($matches) {
	
		$condition = $matches[1];
		$whosfield = $matches[2];
		$conditionField = $matches[3];
		$conditionValue = $matches[4];
		$conditionContent = $matches[5]; 
	
		if ($condition == "SHOW_IF") {
			$return = "";  	
		} elseif ($condition == "HIDE_IF") {
			$return = $conditionContent;  	
		}

		$USERfieldValue = $this->getUserFieldValue($conditionField, $whosfield);
		if($USERfieldValue == 'return_now') return $return;

		$conditionValues = explode("||", $conditionValue);
		$conditionValues = array_map("trim", $conditionValues);
	
		foreach ($conditionValues as $value) 	{	
			if( ($value == 'empty' && !$USERfieldValue) || ($value != 'empty' && preg_match('/'.$value.'/i', $USERfieldValue)) ){ 
				if ($condition == "SHOW_IF") {
					$return = $conditionContent;
				} elseif ($condition == "HIDE_IF") {
					$return = "";  	
				}
			}			
		}
		
		return $return;
	}
	
	// This function loads content shown/hidden for different usertypes
	protected function loadShowFor ($matches) {
	
		$condition = $matches[1];
		$conditionString = $matches[2];
		$conditionContent = $matches[3]; 
		
		if($condition == "SHOW_FOR") {
			$return = "";  
		} elseif($condition == "HIDE_FOR") {
			$return = $conditionContent; 
		}
		
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		
		$conditionString = $conditionString." ||";
		$matched = false;
	
		if(preg_match( "#access_level=(.*)\s\|\|#sU", $conditionString, $matches )) {
			$condition_access_level = $matches[1];
			$db->setQuery("SELECT id FROM #__viewlevels WHERE title = '".$condition_access_level."'");
			$condition_access_level = $db->loadResult();
			
			$user_access_levels = $user->getAuthorisedViewLevels();
			if (in_array($condition_access_level, $user_access_levels)) {
				$matched = true;
			}
		}
		
		if(preg_match( "#user_group=(.*)\s\|\|#sU", $conditionString, $matches )) {
			$condition_groups = $matches[1];
			$condition_groups = explode(",",  $condition_groups);
			$condition_groups = array_map("trim", $condition_groups);
			
			$db->setQuery('SELECT `title`, `id` FROM `#__usergroups`');
			$groupsArray = $db->loadAssocList('id');

			$user_groups = $user->groups;  			 

			foreach($user_groups as $user_group_id) {
				if(array_key_exists($user_group_id, $groupsArray) && in_array($groupsArray[$user_group_id]['title'], $condition_groups )) {
					$matched = true;
				}
			}
		}
		
		if(preg_match( "#profile_type=(.*)\s\|\|#sU", $conditionString, $matches )) {
			$condition_profile_types = $matches[1];
			$condition_profile_types = explode(",",  $condition_profile_types);
			$condition_profile_types = array_map("trim", $condition_profile_types);
			
			$user_profile_type = $this->getUserProfileType ();
	
			if($user_profile_type) {
				if(in_array($user_profile_type, $condition_profile_types )) {
					$matched = true;
				}
			}
		}
		
		if(preg_match( "#(profile_owner|profile_visitor)#sU", $conditionString, $matches )) {
			$condition_owner_visitor = $matches[1];
		
			if(isset($this->profile_owner_id) && $this->profile_owner_id == $user->id) {
				$user_owner_visitor = "profile_owner";
			} else {
				$user_owner_visitor = "profile_visitor";
			}
			
			if($user_owner_visitor == $condition_owner_visitor) {
				$matched = true;
			}
		}
		
		if($matched == true) {
			if($condition == "SHOW_FOR") {
				$return = $conditionContent; 
			} elseif($condition == "HIDE_FOR") {
				$return = "";  
			}
		}
	
		return $return;
	
	}
	
	protected function loadTooltips () {
	
		$matches = $this->getMatches('#{tooltip\s(.*)}#sU'); 
		if(!$matches) return;
		
		if(version_compare(JVERSION,'3.0.0','ge')) {
			JHtml::_('bootstrap.tooltip');
		} else { 
			JHTML::_('behavior.tooltip');
		}
		
		foreach($matches as $match) {
		
			$paramsString = $match[1];
			$paramsString = $paramsString.' ||';
			
			$params = array('content' => '', 'title' => '', 'image' => 'tooltip.png', 'text' => '', 'url' => '');
		
			foreach ($params as $name => $value) {
				$params[$name] = (preg_match("#".$name."=(.*)\s\|\|#sU", $paramsString, $matches)) ? $matches[1] : $value;
			}
	
			$output = JHTML::tooltip($params['content'], $params['title'], $params['image'], $params['text'], $params['url']);
			$this->replaceOutput($output, $match[0]);
		}
			   
		return;
	}
	
	protected function loadjQueryUITabs () {
	
		$matches = $this->getMatches('#{TABS(.*)}(.*){/TABS}#sU'); 
		if(!$matches) return;
		
		$this->head_tags['js_files']['jquery-ui'] = 'jquery-ui.min.js';
		$this->jQueryUIBaseTagIssueFix();

		$num = 1;
		
		foreach ($matches as $match) {
		
			$optionsString = $match[1];
			$tabsString = $match[2];
	
			$options = array();
			$actions = '';
			$nav_function = '';
			$class = '';
			$theme = 'smoothness';
			$sort_axis = 'x';
	
			if($optionsString) {
	
				if (strpos($optionsString, 'mouseover') !== false) {
					$options['event'] = "event: 'mouseover'";
				}
	
				if (strpos($optionsString, 'collapsible') !== false) {
					$options['collapsible'] = 'collapsible: true';
					$options['active'] = 'active: false';
				}
	
				if (strpos($optionsString, 'cookie') !== false) {
				
					$this->head_tags['js_files']['jquery-cookie'] = 'jquery-cookie.min.js';
					
					$cookie_name = $this->cookie_name_prefix.'-tabs-'.$num;
									
					$options['active'] = 'active: $.cookie("'.$cookie_name.'")';
					$options['activate'] = 'activate: function(event, ui) { $.cookie("'.$cookie_name.'", $(this).tabs("option", "active"), { expires: 1 }); }';
				}
			
				$options = ($options) ? '{ '.implode(', ', $options).' }' : '';	
			
				if (strpos($optionsString, 'nav-bottom') !== false) {
				
					$nav_function = "\n".'$( ".tabs-bottom .ui-tabs-nav, .tabs-bottom .ui-tabs-nav > *" )
					  .removeClass( "ui-corner-all ui-corner-top" )
					  .addClass( "ui-corner-bottom" );
 
					$( "#tabs'.$num.'.tabs-bottom .ui-tabs-nav" ).appendTo( "#tabs'.$num.'.tabs-bottom" );';

					$class = ' class="tabs-bottom"';

					$this->head_tags['css'][] = '#tabs'.$num.' .tabs-spacer { float: left; height: 200px; }
					  .tabs-bottom .ui-tabs-nav { clear: left; padding: 0 .2em .2em .2em; }
					  .tabs-bottom .ui-tabs-nav li { top: auto; bottom: 0; margin: 0 .2em 1px 0; border-bottom: auto; border-top: 0; }
					  .tabs-bottom .ui-tabs-nav li.ui-tabs-active { margin-top: -1px; padding-top: 1px; }';			
				
				} elseif (strpos($optionsString, 'nav-left') !== false) {
				
					$actions .= '.addClass( "ui-tabs-vertical ui-helper-clearfix" )';
					$nav_function = "\n".'$( "#tabs'.$num.' li" ).removeClass( "ui-corner-top" ).addClass( "ui-corner-left" );';

					$sort_axis = 'y';

					$this->head_tags['css'][] = '#tabs'.$num.'.ui-tabs-vertical { width: 55em; }
					#tabs'.$num.'.ui-tabs-vertical .ui-tabs-nav { padding: .2em .1em .2em .2em; float: left; width: 12em; }
					#tabs'.$num.'.ui-tabs-vertical .ui-tabs-nav li { clear: left; width: 100%; border-bottom-width: 1px !important; border-right-width: 0 !important; margin: 0 -1px .2em 0; }
					#tabs'.$num.'.ui-tabs-vertical .ui-tabs-nav li a { display:block; }
					#tabs'.$num.'.ui-tabs-vertical .ui-tabs-nav li.ui-tabs-active { padding-bottom: 0; padding-right: .1em; border-right-width: 1px; }
					#tabs'.$num.'.ui-tabs-vertical .ui-tabs-panel { padding: 1em; float: right; width: 40em; }';
				
				} elseif (strpos($optionsString, 'nav-right') !== false) {
				
					$actions .= '.addClass( "ui-tabs-vertical ui-helper-clearfix" )';
					$nav_function = "\n".'$( "#tabs'.$num.' li" ).removeClass( "ui-corner-top" ).addClass( "ui-corner-right" );';
					
					$sort_axis = 'y';

					$this->head_tags['css'][] = '#tabs'.$num.'.ui-tabs-vertical { width: 55em; }
					#tabs'.$num.'.ui-tabs-vertical .ui-tabs-nav { padding: .2em .2em .2em .1em; float: right; width: 12em; }
					#tabs'.$num.'.ui-tabs-vertical .ui-tabs-nav li { clear: right; width: 100%; border-bottom-width: 1px !important; border-left-width: 0 !important; margin: 0 0 .2em -1px; }
					#tabs'.$num.'.ui-tabs-vertical .ui-tabs-nav li a { display:block; }
					#tabs'.$num.'.ui-tabs-vertical .ui-tabs-nav li.ui-tabs-active { padding-bottom: 0; padding-left: .1em; border-left-width: 0px; }
					#tabs'.$num.'.ui-tabs-vertical .ui-tabs-panel { padding: 1em; float: left; width: 40em; }';
				}
			
				if (strpos($optionsString, 'sortable') !== false) {
					$actions .= '.find( ".ui-tabs-nav" ).sortable({ axis: "'.$sort_axis .'", stop: function() { $("#tabs'.$num.'").tabs( "refresh" ); } })';
				}
			
				if (preg_match( '/theme=(.*)/', $optionsString, $matches)) {
					$theme = $matches[1];
				}
			}
	
			$this->head_tags['css_files']['jquery-ui-'.$theme] = 'jquery-ui-themes/'.$theme.'/jquery-ui.css';
	
			$this->head_tags['js'][] = '$("#tabs'.$num.'").tabs('.$options.')'.$actions.'; '.$nav_function;
		
			$regex =  "#{TAB(.*)=(.*)}(.*){/TAB(.*)}#sUi";
		
			$output = '<div class="'.$theme.'"><div id="tabs'.$num.'"'.$class.' ><ul>';
			$output .= preg_replace_callback( $regex, create_function ('&$matches', 'return "<li><a href=\"#tab'.$num.'-".$matches[1]."\">".JText::_($matches[2])."</a></li>";'), $tabsString ); 
			$output .= "</ul>";
			$output .= preg_replace_callback( $regex, create_function ('&$matches', 'return "<div id=\"tab'.$num.'-".$matches[1]."\">".$matches[3]."</div>";'), $tabsString ); 
			$output .= '</div></div>';
		
			$this->replaceOutput($output, $match[0]);
			
			$num++;
		}
		
		return;
	}	
		
	protected function jQueryUIBaseTagIssueFix () {

		$document = JFactory::getDocument();
		$base = $document->getBase();
		if(!$base) return;
		
		$url = JURI::current();
		$query = JURI::getInstance()->getQuery();
		if($query) $url .= '?'.$query;
		
		if($base != $url) $document->setBase($url);

		return;
		/* return "\n".'var baseElements = document.getElementsByTagName("base"); alert(document.location.href);
			if( baseElements.length>0 ) {
				baseElements[0].href = document.location.href;
   			}'."\n"; */
	}
}