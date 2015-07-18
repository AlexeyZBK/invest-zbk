<?php
/* CB Profile Pro plugin for Community Builder for Joomla! 2.5, 3.x - Version 1.5.1
------------------------------------------------------------------------------------
Copyright (C) 2009-2014 Joomduck. All rights reserved.
Website: www.joomduck.com
E-mail: support@joomduck.com
Developer: Joomduck
Created: December 2014
License: GNU GPLv2 http://www.gnu.org/licenses/gpl-2.0.html
*/ 

if ( ! ( defined( '_VALID_CB' ) || defined( '_JEXEC' ) || defined( '_VALID_MOS' ) ) ) { die( 'Direct Access to this location is not allowed.' ); }
	
$task = (version_compare(JVERSION,'3.0.0','ge')) ? JFactory::getApplication()->input->get('task') : JRequest::getVar('task');
$task = strtolower($task);

global $_PLUGINS; 

$_PLUGINS->registerUserFieldTypes( array( 'profiletype_select'	=> 'cbFieldProfileTypeSelect' ));
$_PLUGINS->registerUserFieldParams();

$_PLUGINS->registerFunction( 'onBeforeRegisterFormDisplay', 'addAdditionalInputs', 'CBProfileProRegistration' );
$_PLUGINS->registerFunction( 'onBeforeUserRegistration', 'reCaptchaCheck', 'CBProfileProRegistration' );

if($task == 'saveuseredit' || $task == 'saveregisters') {
	$cbpp = (version_compare(JVERSION,'3.0.0','ge')) ? JFactory::getApplication()->input->get('cbpp') : JRequest::getVar('cbpp');
	if($cbpp) $_PLUGINS->registerFunction( 'onAfterFieldsFetch', 'skipRequiredFields', 'CBPPHelper' );
}

$_PLUGINS->registerFunction( 'onAfterUserProfileDisplay', 'removeCurvyCornersScript', 'CBPPHelper' );
$_PLUGINS->registerFunction( 'onAfterUserProfileEditDisplay', 'removeCurvyCornersScript', 'CBPPHelper' );

if($task == 'registers') $_PLUGINS->registerFunction( 'onAfterTabsFetch', 'removeCurvyCornersScript', 'CBPPHelper' );

class getProfileProTab extends cbTabHandler {

	function getDisplayTab($tab, $user, $ui) {
	
		require_once(JPATH_ROOT . '/components/com_cbprofilepro/helpers/cbprofilepro.php');
		
		$tab_content = Cbprofilepro::getPageContent('profile');
			
		if ($tab_content == 'default_cb_page') {
			return;
		} else {
			return $tab_content;
		}
	}

}	

class cbFieldProfileTypeSelect extends cbFieldHandler {

	function getField( &$field, &$user, $output, $reason, $list_compare_types ) {
		
		$value = $user->get( $field->name );
		
		if($field->params->get( 'display_type' )) {
			$displayType = $field->params->get( 'display_type' );
			$field->type = $field->params->get( 'display_type' );
		} else {
			$displayType = "select";
			$field->type = "select";
		}
		switch ( $output ) {

			case 'htmledit':
				
				global $_CB_database;
				$viewer = JFactory::getUser();

				// no state filter for admin
				if ( $viewer->authorise('core.admin', 'com_cbprofilepro') ) {
					$state = "";
				} else {
					$state = " WHERE state = 1";
				}
				
				$_CB_database->setQuery( "SELECT alias AS `value`, title AS `text`, concat('cbpp',id) AS id FROM #__cb_profiletypes".$state." ORDER BY ordering" );
				$allValues	=	$_CB_database->loadObjectList();

				if ( $reason == 'search' ) { 
//					$html			=	$this->_fieldEditToHtml( $field, $user, $reason, 'input', 'multicheckbox', $value, '', $allValues );
					$displayType	=	$field->type;
					switch ( $field->type ) {
						case 'radio':
							$jqueryclass		=	'cb__js_' . $field->type;
							break;
					
						case 'select':
							$jqueryclass		=	'cb__js_' . $field->type;
							break;
					
						default:
							$jqueryclass		=	'';
							break;
					}
					if ( in_array( $list_compare_types, array( 0, 2 ) ) && ( $displayType != 'multicheckbox' ) ) {
						array_unshift( $allValues, moscomprofilerHTML::makeOption( '', _UE_NO_PREFERENCE ) );
					}
					$html			=	$this->_fieldEditToHtml( $field, $user, $reason, 'input', $displayType, $value, '', $allValues );
					$html			=	$this->_fieldSearchModeHtml( $field, $user, $html, ( strpos( $displayType, 'multi' ) === 0 ? 'multiplechoice' : 'singlechoice' ), $list_compare_types, $jqueryclass );
				} elseif($reason == 'register') {
				
					$profiletype = (version_compare(JVERSION,'3.0.0','ge')) ? JFactory::getApplication()->input->get('profiletype') : JRequest::getVar('profiletype');

					if ($profiletype) $value = $profiletype;
					
					$html			=	$this->_fieldEditToHtml( $field, $user, $reason, 'input', $field->type, $value, '', $allValues );
					$html .= "\n<script type=\"text/javascript\">
				
							var elements = document.getElementsByName('".$field->name."');
							for ( var i = 0; i < elements.length; i++ ){	";	

					if ($displayType == 'select') {
						$html .= "elements[i].setAttribute('onchange', 'switchRegisterPage(this.options[this.selectedIndex].value)');";
					} elseif ($displayType == 'radio') {
						$html .= "elements[i].setAttribute('onclick', 'switchRegisterPage(this.value)');";
					}

					$itemid = (version_compare(JVERSION,'3.0.0','ge')) ? JFactory::getApplication()->input->get('Itemid') : JRequest::getVar('Itemid');

					$html .= '}

					function switchRegisterPage (alias)  {

						if (alias != "") {
						
							var form = document.getElementById("cbcheckedadminForm");
							
							form.action = form.action.replace("saveregisters", "registers");
							
							var taskinput = document.getElementsByName("task");
							for ( var i = 0; i < taskinput.length; i++  ){	
								if(taskinput[i].value == "saveregisters") {
									taskinput[i].setAttribute("value", "registers");
								}
							}
																															
							var elements = document.getElementsByName("profiletype");
							
							if (!elements.length) {
							
								var profiletypeinput = document.createElement("input");
								profiletypeinput.setAttribute("type", "hidden");
								profiletypeinput.setAttribute("name", "profiletype");
								profiletypeinput.setAttribute("value", alias);
								form.appendChild(profiletypeinput);
								
							} else {
								
								var profiletypeinput = document.getElementsByName("profiletype");
								for (var i = 0; i < profiletypeinput.length; i++ ) {
									profiletypeinput[i].setAttribute("value", alias);
								}
							}
							
							var itemidinput = document.createElement("input");
							itemidinput.setAttribute("type", "hidden");
							itemidinput.setAttribute("name", "Itemid");
							itemidinput.setAttribute("value", "'.$itemid.'");
							form.appendChild(itemidinput);
					
							form.submit();
						
						}
					}
					</script>';
				} else {
					$html			=	$this->_fieldEditToHtml( $field, $user, $reason, 'input', $field->type, $value, '', $allValues );
				}
				return $html;
				break;
			case 'html':
				global $_CB_database;
				$_CB_database->setQuery( "SELECT alias, title FROM #__cb_profiletypes ORDER BY ordering" );
				$profiletypes	=	$_CB_database->loadObjectList('alias');

				$field_output =  parent::getField( $field, $user, $output, $reason, $list_compare_types );
				if(isset($profiletypes[$field_output])) $field_output = $profiletypes[$field_output]->title;
				return $field_output;
				break;
			case 'rss':
			case 'xml':
			case 'json':
			case 'php':
			case 'csv':
			case 'csvheader':
			case 'fieldslist':
			default:	
				return parent::getField( $field, $user, $output, $reason, $list_compare_types );
				break;
		}
		return '*' . CBTxt::T('Unknown Output Format') . '*';
	}
	
}

class CBProfileProRegistration extends cbPluginHandler {

	// This function adds inputs for correct profile type selection on default CB registration page 
	function addAdditionalInputs() {

		$params	= JComponentHelper::getParams( 'com_cbprofilepro' );
		$multiple = $params->get('multiple', 1);

		$profiletype = (version_compare(JVERSION,'3.0.0','ge')) ? JFactory::getApplication()->input->get('profiletype') : JRequest::getVar('profiletype');

		if(!$multiple || !$profiletype) return;

		$profiletype_field = $params->get('profiletype_field', 'cb_profiletype');

		$javascript = '
		<script type="text/javascript">
		
			window.onload = function addAdditionalInputs () {
				var elements = document.getElementsByName("profiletype");
				if (!elements.length) {
					var input = document.createElement("input");
					input.setAttribute("type", "hidden");
					input.setAttribute("name", "profiletype");
					input.setAttribute("value", "'.$profiletype.'");
					var form = document.getElementById("cbcheckedadminForm");
					form.appendChild(input);
				}
				var elements = document.getElementsByName("'.$profiletype_field.'");
				if(!elements.length) {
					var input = document.createElement("input");
					input.setAttribute("type", "hidden");
					input.setAttribute("name", "'.$profiletype_field.'");
					input.setAttribute("value", "'.$profiletype.'");
					var form = document.getElementById("cbcheckedadminForm");
					form.appendChild(input);
				}
			};
		</script>';
		
		return $javascript;

	}

	function reCaptchaCheck() {

		if (isset($_POST["recaptcha_challenge_field"]) )  {
		 
			require_once( JPATH_SITE . '/components/com_cbprofilepro/includes/reCaptcha/recaptchalib.php');

			$params	= JComponentHelper::getParams( 'com_cbprofilepro' );
			$privatekey = $params->get('privatekey');

			$resp = recaptcha_check_answer ($privatekey,
										$_SERVER["REMOTE_ADDR"],
										$_POST["recaptcha_challenge_field"],
										$_POST["recaptcha_response_field"]);
		
			if (!$resp->is_valid) {
				$lang = JFactory::getLanguage();
				$lang->load('com_cbprofilepro');
				
				$errortext = JText::_("INVALID_RECAPTCHA_CODE"); 
			
				global $_PLUGINS; 
				$_PLUGINS->raiseError(0);
				$_PLUGINS->_setErrorMSG( $errortext );
			}
		}
		
		return;
   	}

}

class CBPPHelper extends cbPluginHandler {

	function skipRequiredFields(  &$fields, &$user, $reason, $tabid, $fieldIdOrName, $fullAccess  ) {

		$postdata = &$_POST;
		$filesdata = $_FILES;
		$mandatory_fields = array('email', 'username', 'password');

	//	if(isset($postdata['task']) && ($postdata['task'] ==  'saveregisters' || $postdata['task'] ==  'saveUserEdit')) {
			if($fieldIdOrName == null) {
				foreach($fields as $field) {
					if(!isset($postdata[$field->name]) && !isset($filesdata[$field->name.'__file']) && !in_array ($field->name, $mandatory_fields) && $field->required == 1){
						$field->required = 0;
					}
				}
			}
	//	}
		return;
	}

	function removeCurvyCornersScript () {
		
		global $_CB_framework;
		$scripts = $_CB_framework->document->_head['scriptsUrl'];

		foreach ($scripts as $url=>$type) 
		{
			if (strpos($url, '/components/com_comprofiler/js/curvycorners.min.js') !== false || strpos($url, '/components/com_comprofiler/js/curvycorners.js') !== false) 
			{
				unset($scripts[$url]);
			}
		}
		
		$_CB_framework->document->_head['scriptsUrl'] = $scripts;
		
		return;
	}
	
}