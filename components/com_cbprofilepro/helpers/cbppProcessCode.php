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

require_once( JPATH_ROOT . '/components/com_cbprofilepro/helpers/processCode.php' );


class cbppProcessCode extends ProcessCode {

	function __construct ($data, $params) {
	
		$reason = $params['reason'];

		if($reason == 'profile') {
		
			$this->profile_owner_id = Cbprofilepro::getProfileOwnerId($reason);
			$cbUser_id = $this->profile_owner_id;
			
			$this->cookie_name_prefix = 'up'.$this->profile_owner_id;
			$this->cbAPIparams = array('output' => 'html', 'reason' => 'profile');
			
		} elseif ($reason == 'profile_edit') {
		
			$this->profile_owner_id = Cbprofilepro::getProfileOwnerId($reason);
			$cbUser_id = $this->profile_owner_id;
			
			$this->cookie_name_prefix = 'ud'.$this->profile_owner_id; // .'-userdetails'
			$this->cbAPIparams = array('output' => 'htmledit', 'reason' => 'edit');
			
		} elseif($reason == 'registration') {
		
			$user = JFactory::getUser();
			$cbUser_id = $user->id;
			
			$this->cookie_name_prefix = 'registers';
			$this->cbAPIparams = array('output' => 'htmledit', 'reason' => 'register');
		}

		$this->cbVersion = Cbprofilepro::getCBVersion();

		$this->cbUser = &CBuser::getInstance($cbUser_id);
		
		$this->reason = $reason;
		
		$this->js_path = JURI::root() . 'components/com_cbprofilepro/includes/js/';
		$this->css_path = JURI::root() . 'components/com_cbprofilepro/includes/css/';	
 
		parent::__construct($data, $params);
	}


	public function process () {
	
		parent::process();
		$this->loadFields();
		$this->loadCBTabs();
		$this->loadCBTabPositions();	
		
		$this->addHeadTags();	
	}

	protected function loadModalSupport ($selector_class = 'cbpp-modal') {
	
		parent::loadModalSupport($selector_class);
 		return;
	}

	protected function getUserProfileType () {
	
		$params	= JComponentHelper::getParams( 'com_cbprofilepro' );
		$profiletype_field = $params->get('profiletype_field', 'cb_profiletype');
	
		$db = JFactory::getDBO(); 
		$user = JFactory::getUser();

		$db->setQuery("SELECT ".$profiletype_field." FROM #__comprofiler WHERE id =".$user->id);
		$user_profile_type = $db->loadResult();

		return $user_profile_type;
	}
	
	private function loadCBTabs () {
	
		$tab_matches = $this->getMatches('#{(tab|object)\s(.*)}#sU');
		if(!$tab_matches) return;

		$db = JFactory::getDBO(); 
		$db->setQuery("SELECT * FROM #__comprofiler_tabs"); // "SELECT tabid FROM #__comprofiler_tabs WHERE title=".$db->Quote($matches[1]) | $tabid = $db->loadResult();
		$tabs = $db->loadObjectList('title');
		
		$tabs_loaded = array();
		
		foreach ($tab_matches as $match) {
		
			$tab_title = $match[2];
			
			if(isset($tabs_loaded[$tab_title])) {
			
				$output = $tabs_loaded[$tab_title];
				
			} else {
			
				if ($this->reason == 'registration' && in_array($tab_title, array('terms and conditions', 'reCaptcha'))) {
				
					$output = $this->getObject($tab_title);
			
				} elseif (isset($tabs[$tab_title])) {
				
					$tabObject = $tabs[$tab_title];
					$output = $this->getCBTab($tabObject);

					//if($output) $this->tabpositions_loaded[$tabObject->position] = true;
				
				} else {
				
					$output = $this->processSubstitution(JText::_("COM_CBPROFILEPRO_CBTAB_NOT_FOUND_ERROR"), $tab_title);
				}
			
				$tabs_loaded[$tab_title] = $output;
			}

			$this->replaceOutput($output, $match[0]);
		}
				
		return;
	}
	
	private function loadCBTabPositions () {
	
		$tabposition_matches = $this->getMatches('#{tabposition\s(.*)}#sU');
		if(!$tabposition_matches) return;
		
		$cbUser = $this->cbUser;
		
		$db = JFactory::getDBO(); 
		$db->setQuery("SELECT * FROM #__comprofiler_tabs WHERE enabled = 1 ORDER by tabid DESC");
		$tabs = $db->loadObjectList();
		
		$tabs_by_positions = array(); 
		foreach($tabs as $tab) { 
			if(!isset($tabs_by_positions[$tab->position])) $tabs_by_positions[$tab->position] = array();
			$tabs_by_positions[$tab->position][] = $tab;
		} 

		foreach ($tabposition_matches as $match) {
		
			$tabposition = $match[1];

			for ($i = 0; (!isset($this->tabpositions_loaded[$tabposition]) && $i < count($tabs_by_positions[$tabposition])) ; $i++) {
				$tabObject = $tabs_by_positions[$tabposition][$i];
				$this->getCBTab($tabObject);
				//$this->tabpositions_loaded[$tabposition] = true;
			}
			
			$output = $cbUser->getPosition($tabposition);
			$this->replaceOutput($output, $match[0]);
		}
				
		return;
	}
	
	private function getCBTab ($tabObject) {

		if(!isset($this->tabpositions_loaded)) $this->tabpositions_loaded = array();
		
		if ($this->reason == 'registration') {

			global $_PLUGINS;
			// $tabid = $row->tabid;
			$pluginid = $tabObject->pluginid;
			$pluginclass =  $tabObject->pluginclass;
		
			$method = 'getDisplayRegistration';
		
			$thisui = 1;
			$args = array( &$tab , &$user, $thisui, &$postdata );
	
			$object =  $_PLUGINS->call( $pluginid, $method, $pluginclass, $args, ( is_object( $tab ) ? $tab->params : null ) );

			if(is_array($object)) {
				$output = '';
				foreach($object as $object_instance) {
					if(isset($object_instance->_value)) {
						$output .= '<div class="cbpp_object">'.$object_instance->_value.'</div>';
					}
				}
			} elseif(isset($object->_value)) { 
				$output = $object->_value;
			} else {
				$output = $object;
			}		

			if($output) return $output;
		}
		
		$cbUser = $this->cbUser;
		$tabid = $tabObject->tabid;
		//$output = $cbUser->getTab($tabid);	
		$output = $cbUser->getTab($tabid, null, $this->cbAPIparams['output'], null, $this->cbAPIparams['reason']);
		$output = '<div class="cb_tab_content">'.$output.'</div>'; //cb_tab_html 
		if($tabObject->enabled) $this->tabpositions_loaded[$tabObject->position] = true;
		return $output;
		
	//	return $user->getTab( $input[2], $default, ( $output == 'none' ? null : $output ), $formatting, $reason );
	//	public function getTab( $tab, $defaultValue = null, $output = 'html', $formatting = null, $reason = 'profile' ) {
	}
		
	private function getObject ($object_title) {
		
		switch($object_title) {
			case 'terms and conditions':
				$output = '';
				if(version_compare($this->cbVersion, '1.9.1', '<=')) {
					global $ueConfig, $_CB_framework;
					if($ueConfig['reg_enable_toc']) {
						$output						=	"<div class=\"cbSnglCtrlLbl\"><input type='checkbox' name='acceptedterms' id='acceptedterms' class='required' value='1' mosReq='0' mosLabel='"
														.	htmlspecialchars( _UE_TOC )
														.	"' /> <label for='acceptedterms'>"
														.	sprintf(_UE_TOC_LINK,"<a href='".cbSef(htmlspecialchars($ueConfig['reg_toc_url']))."' target='_BLANK'> ","</a>") . '</label>'
														.	getFieldIcons( $_CB_framework->getUi(), 1, null, null, null )
														.	"</div>"	;
						$output = '<div class="cb_field">'.$output.'</div>';
				
						$js = '$(function() {	$("#cbfr_termsc").remove(); });';
						$_CB_framework->outputCbJQuery($js);
					}
				}
				break;
			case 'reCaptcha':
				require_once(JPATH_SITE . '/components/com_cbprofilepro/includes/reCaptcha/recaptchalib.php');
				$params	= JComponentHelper::getParams('com_cbprofilepro');
				$publickey = $params->get('publickey');
				$output = recaptcha_get_html($publickey);	
				break;
		} 

		//if(!$output) $output = sprintf(JText::_("ERROR_OBJECT_EMPTY"), $object_title);

		return $output;
	}
	
	private function loadFields () {
	
			$field_matches = $this->getMatches('#{(fld|ftl)\s(.*)}#sU');
			if(!$field_matches) return;
	
			$db = JFactory::getDBO();
			$db->setQuery("SELECT * FROM #__comprofiler_fields");
			$fieldObjects = $db->loadObjectList('name');
				
			$fields = array();
			if ($this->reason == 'profile' || $this->reason == 'profile_edit') $fields['user_id'] = array('ftl' => JText::_("USER_ID"), 'fld' => $this->profile_owner_id);
					
			foreach($field_matches as $match) {
		
				$type = $match[1];
				$fieldname = $match[2]; 
			
				if(isset($fields[$fieldname])) {	 
						
					$field = $fields[$fieldname];
					//if(isset($fields[$fieldcode][$type])) $output = $fields[$fieldcode][$type];
			
				} else {
			
					$field = array();
				
					$field['fld'] = $this->getCBField($fieldname);
				
					if(isset($fieldObjects[$fieldname])) {
				
						if(isset($field['fld'])) {
		
							$fieldObject = $fieldObjects[$fieldname];
							$fieldTitle = $fieldObject->title;
							$fieldTitle = getLangDefinition($fieldTitle);
							$fieldTitle = JText::_($fieldTitle); 
							$field['ftl'] = $fieldTitle;
						
						} else {
					
							$field['ftl'] = $field['fld'] = '';
						}
					}
				
					$fields[$fieldname] = $field;
				}

				$output = (isset($field[$type])) ? $field[$type] : $this->processSubstitution(JText::_("COM_CBPROFILEPRO_CBFIELD_NOT_FOUND_ERROR"), $fieldname);

				$this->replaceOutput($output, $match[0]);
			}
		
		return;
	}
	
	private function getCBField ($fieldName) {
	
		$cbUser = $this->cbUser; 

		switch ($this->reason) {
			case 'profile':
			
				$fieldHTML = $cbUser->getField($fieldName /*, $defaultValue Value if field is not in reach of viewer user or innexistant*/);
				//function getField( $fieldName, $defaultValue = null, $output = 'html', $formatting = 'none', $reason = 'profile', $list_compare_types = 0 )
				break;
				
			case 'profile_edit':

				$postdata = &$_POST;

				if ($postdata != null && isset($postdata[$fieldName])) {
				
					global $_PLUGINS;
			
					$tabs =	$cbUser->_getCbTabs(false); 

					$field	=	$tabs->_getTabFieldsDb( null, $cbUser, 'edit', $fieldName ); 
					if (!isset($field[0])) return;
					$field		=	$field[0];
		
					$_PLUGINS->callField( $field->type, 'prepareFieldDataSave', array( &$field, &$cbUser->_cbuser,  &$postdata, 'edit' ), $field );
					$fieldHTML = $_PLUGINS->callField( $field->type, 'getFieldRow', array( &$field, &$cbUser->_cbuser, 'htmledit', 'none', 'edit', 0 ), $field );

				} else {
				
					$fieldHTML = $cbUser->getField( $fieldName, null, 'htmledit', 'none', 'edit' );
				}
				
				if($fieldHTML) $fieldHTML = '<span class="cb_field" style="display:inline">' . $fieldHTML . '</span>';
				
				break;

			case 'registration':

				global $_PLUGINS;

			/*	$output = 'htmledit';
				$formatting = 'none';
				$reason = 'register';
				$list_compare_types = 0;*/

				$tabs =	$cbUser->_getCbTabs( false );

				$field	=	$tabs->_getTabFieldsDb( null, $cbUser, 'register', $fieldName ); 
				if (!isset($field[0])) return;
				$field	=	$field[0];
	
				$postdata = &$_POST;
				if ( $postdata != null && isset($postdata[$fieldName])) {
					$_PLUGINS->callField( $field->type, 'prepareFieldDataSave', array( &$field, &$cbUser->_cbuser,  &$postdata, 'register' ), $field );
				} else {
					if(version_compare($this->cbVersion, '1.9.1', '<=')) {
						$tabs->_initFieldToDefault( $field, $cbUser->_cbuser, 'register' );
					} else {
						$_PLUGINS->callField( $field->type, 'initFieldToDefault', array( &$field, &/*$user*/$cbUser->_cbuser, /*$reason*/'register' ), $field );
					}
				}
	
				$fieldHTML = $_PLUGINS->callField( $field->type, 'getFieldRow', array( &$field, &$cbUser->_cbuser, 'htmledit', 'none', 'register', 0 ), $field );
			
				if($fieldHTML) $fieldHTML = '<span class="cb_field" style="display:inline">' . $fieldHTML . '</span>';

			/*	static $cbconditional = "undefined";
				if($cbconditional === "undefined") {  
					$db = &JFactory::getDBO();
					$db->setQuery("SELECT id FROM #__comprofiler_plugin WHERE element = 'cbconditional'");
					$result = $db->loadResult();
					$cbconditional = ($result) ? true : false;
				} 
				if($cbconditional) { 
					$fld_id = $field->fieldid;
					$fieldHTML = '<span id="cbfr_'.$fld_id.'">'.$fieldHTML.'</span>';
				} */
		
				break;
		}
		
		return $fieldHTML;
	}
	
	protected function loadCodes ($db_table = '#__cbppmagicwindow_code') {
	
		parent::loadCodes($db_table);
	}
		
	protected function getUserFieldValue ($conditionField, $whosfield) {
	
		$user = JFactory::getUser();
		$task = (version_compare(JVERSION,'3.0.0','ge')) ? JFactory::getApplication()->input->get('task') /* The filter defaults to cmd. */ : JRequest::getCmd('task');
		$task = strtolower($task);
			

		if ($task == "registers" /* ?saveregisters */ || ($whosfield == "visitor's " && $user->id == 0)) return 'return_now';
			
		if ($whosfield == "visitor's " || $task == 'userdetails' || $task == 'saveuseredit' /* saveUserEdit */) {  
			$fieldowner_id = $user->id;
		} else {
			global $_CB_framework;
			$fieldowner_id = $_CB_framework->displayedUser();
		}
		
		$db = JFactory::getDBO();
		$db->setQuery("SELECT ".$conditionField." FROM #__users AS u JOIN #__comprofiler as c WHERE u.id=".$fieldowner_id." AND c.id=".$fieldowner_id);
		$USERfieldValue = $db->loadResult();
		
		return $USERfieldValue;
	}


	protected function loadPages ($pagebreak_id_class = 'cbpp-pagebreak') {
	
		$pagebreaks = $this->getMatches('#<hr.*class="' . $pagebreak_id_class . '".*\/>#iU'); 
		if(!$pagebreaks) return;

		global $_CB_framework; 
		
	//	$stylesheetUrl = JURI::root().'components/com_cbprofilepro/includes/css/jquery-ui-themes/pages/jquery-ui.css';
	//	$_CB_framework->document->addHeadStyleSheet($stylesheetUrl);
		$this->head_tags['css_files']['jquery-ui-pages'] = 'jquery-ui-themes/pages/jquery-ui.css';
		
	//	$debug = $_CB_framework->getCfg('debug');
	//	$min = ($debug) ? '.min' : '';
	
	//	$_CB_framework->addJQueryPlugin('jquery-ui', '/components/com_cbprofilepro/includes/js/jquery-ui'.$min.'.js');
	//	$_CB_framework->addJQueryPlugin('jquery-cookie', '/components/com_cbprofilepro/includes/js/jquery-cookie'.$min.'.js');
		$this->head_tags['js_files']['jquery-ui'] = 'jquery-ui.min.js';
		$this->head_tags['js_files']['jquery-cookie'] = 'jquery-cookie.min.js';
		
		$this->jQueryUIBaseTagIssueFix();

		$js_output = 'var pages = $("#cbpp-pages");';
		
		if ($this->reason == "registration") {
		
			$js_output .= "\n".'pages.tabs({ event: "" });

				$(".cbpp-next").click(function() { 
				
					var active_page = pages.tabs("option","active");
					var next_num = active_page + 1;
					var valid = true;
		
					$("input, textarea, select", "#page" + next_num).each(function() {
						if($(this).attr("id")) {
							var result = $("#cbcheckedadminForm").validate().element($(this));
							if (result === false && valid) {	
								valid = false; 
								$("#cbcheckedadminForm").validate().focusInvalid();
							}
						}
					});

					if(valid) pages.tabs({ active: next_num }); 

					return false;
				});
		
				$(".cbpp-prev").click(function() {
				
					var active_page = pages.tabs("option","active");
					pages.tabs({ active: active_page - 1 })
					return false;
				});'; 
				// $(".cb_button_wrapper").hide();

			$this->head_tags['css'][] = '
				.cb_button_wrapper, .cb_template .cbRegistrationSubmit {
					display:none;
				}
				.cbpp-prev, .cbpp-next, .cbpp-register { 
					margin:5px 0; 
				}
				.cbpp-next, .cbpp-register { 
					float:right; 
				}
				div#cbpp-pages:after {
					content: " ";
					display: block;
					height: 0;
					clear: both;
					overflow: hidden;
					visibility: hidden;
				}
				
				#cbpp-pages > .ui-widget-header .ui-state-hover, 
				#cbpp-pages > .ui-widget-header .ui-state-focus {
					background: #e6e6e6 url('.$this->css_path.'jquery-ui-themes/pages/images/ui-bg_highlight-soft_75_e6e6e6_1x100.png) 50% 50% repeat-x;
					outline:none;
				}
				#cbpp-pages > .ui-widget-header .ui-state-active {
					background: none;
				}
				#cbpp-pages > ul.ui-widget-header li a, #cbpp-pages > ul.ui-widget-header li {
					outline:none;
					color: #555555;
					pointer-events: none; 
					cursor: default;
				}';

		} else {
		
			$cookie_name = $this->cookie_name_prefix.'-pages';
			
			$js_output .= "\n".'pages.tabs({
			activate: function(event, ui) { $.cookie("'.$cookie_name.'", $(this).tabs("option","active"), { expires: 1 }); }, 
			active: $.cookie("'.$cookie_name.'") 
			});';
		}

		//$js_output = '$(function() { ' . $js_output . ' });';
		//$_CB_framework->outputCbJQuery($js_output);
		$this->head_tags['js'][] = $js_output;

		$num = 1;
		$pages_total = count($pagebreaks);
		$nav_buttons = '';
		$ending = '';
		$prev_button = '<input type="button" class="button btn btn-default cbpp-prev" value="« '.addslashes(JText::_(getLangDefinition(CBTxt::T("Previous")))).'" />';
		$next_button = '<input type="button" class="button btn btn-primary cbpp-next" value="'.addslashes(JText::_(getLangDefinition(CBTxt::T("Next")))).' »" />';

		foreach($pagebreaks as $pagebreak) {
	
			$pos = strpos($this->data, $pagebreak[0]);
			
			if($num == 1 && $pos !== 0) {
				$num++;
				$pages_total++;
				$nav_buttons .= '<li><a style="" href="#page1">'.JText::_('FIRST_PAGE_TITLE').'</a></li>';
				$this->data  = '<div id="page1">'.$this->data;
				$ending = '';
				if($this->reason  == "registration") $ending .= $next_button;
				$ending .= '</div>';
				$pos = strpos($this->data, $pagebreak[0]);
			} 
			
			$title = (preg_match('#title="(.*)"#iU', $pagebreak[0], $matches)) ? $matches[1] : JText::_('PAGE_TITLE')." ".$num;
			
			$alias = (preg_match('#alt="(.*)"#iU', $pagebreak[0], $matches) && $this->reason  != "registration") ? $matches[1] : "page".$num;
			$nav_buttons .= '<li><a href="#'.$alias.'">'.JText::_($title).'</a></li>';

			$this->data = substr_replace( $this->data, $ending.'<div id="'.$alias.'">', $pos, strlen($pagebreak[0]) );
	
			$ending = '';
			if($this->reason  == "registration") {
				$ending .= $next_button;
				if($num > 1) $ending .= $prev_button;
			}
			$ending .= '</div>';
			$num++;
		}
		
		$addition = '';
		if($this->reason  == "registration") {
			if($pages_total > 1) $addition .= $prev_button;
			$addition .= '<input type="submit" class="button btn btn-primary cbpp-register" value="'.addslashes(JText::_(getLangDefinition((version_compare($this->cbVersion, '1.9.1', '<=')) ? "_UE_REGISTER" : "UE_REGISTRATION"))).'">';
		}

		
		$this->data = '<div class="pages"><div id="cbpp-pages"><ul>'.$nav_buttons.'</ul>'.$this->data.$addition.'</div></div></div>';
		
		return;
	}
	
	public function addHeadTags () {

		global $_CB_framework;

		if($this->head_tags['js_files']) {
		
			$debug = $_CB_framework->getCfg('debug');
			$min = ($debug) ? '.min' : '';

		
			foreach ($this->head_tags['js_files'] as $id => $filename) {
				$_CB_framework->addJQueryPlugin($id, '/components/com_cbprofilepro/includes/js/'.$id . $min.'.js');
				//	if($num == 1) $_CB_framework->addJQueryPlugin( 'jquery_ui', '/components/com_cbprofilepro/includes/js/jquery-ui'.$min.'.js' );
				// $_CB_framework->addJQueryPlugin( 'jquery_cookie', '/components/com_cbprofilepro/includes/js/jquery-cookie'.$min.'.js' );
			} 
		}

		if ($this->head_tags['js']) {
		
			$js_output = implode("\n", $this->head_tags['js']);
			$js_output = '$(function() { ' . $js_output . ' });';
			$_CB_framework->outputCbJQuery($js_output);
		}

		if ($this->head_tags['css_files']) {
		
			foreach ($this->head_tags['css_files'] as $id => $path) {
				$stylesheetUrl = JURI::root().'components/com_cbprofilepro/includes/css/'.$path;
				$_CB_framework->document->addHeadStyleSheet($stylesheetUrl);
			}
		}
		
		if ($this->head_tags['css']) {
		
			$css_output = implode("\n", $this->head_tags['css']);
			$css_output = '<style> ' . $css_output . ' </style>';
			$this->head_tags['raw'][] = $css_output;
		}		

		$this->head_tags = $this->head_tags['raw'];	
		
		parent::addHeadTags();
		
		return;
	}	
	
}