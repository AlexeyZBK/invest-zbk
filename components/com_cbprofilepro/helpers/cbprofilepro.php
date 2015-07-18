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

require_once( JPATH_ROOT . '/components/com_cbprofilepro/helpers/cbppProcessCode.php' );

class Cbprofilepro { 

	public static function getPageContent($reason, $formatting = "tabletrs") { 

		static $num = 0; $num++;
		if($num > 1) return 'default_cb_page';

 	 	$reason = str_replace('profile-edit', 'profile_edit', $reason);

		// load language support
		$language = JFactory::getLanguage();
		$language_tag = $language->getTag();
		$language->load('com_cbprofilepro', JPATH_SITE, $language_tag);
		
	 	// get component configuration  
		$params	= JComponentHelper::getParams('com_cbprofilepro'); 
		
		$profiletype = Cbprofilepro::getProfiletype($reason);

		if (!isset($profiletype) || $profiletype->$reason === '') {
		
			$undefined_empty = $params->get('undefined_empty', 'cb_default'); 

			switch ($undefined_empty) {
				case 'error_message':
					if(!isset($profiletype)) {
						$error_message = JText::_('ERROR_PROFILETYPE_NOT_SPECIFIED'); 
					} elseif ($profiletype->$reason === '') {
						$error_message = JText::_('ERROR_PROFILETYPE_DATA_NOT_SPECIFIED');	
					}
					$output = '<div>' . $error_message . '</div>';
					break;
				case 'cb_default':  
					$output = 'default_cb_page'; 
					break;
			}
			return $output;
	    }
	    
	    $output = '';
	    
	    if($reason == 'profile' || $reason == 'registration') {
			$show_title = $params->get('show_title', 1); 
			if ($show_title)  $output .= '<h3 class="cbpp-profiletype-title">' . JText::_($profiletype->title) . '</h3>'; 
	    }
	
		$pc = new cbppProcessCode($profiletype->$reason, array('reason' => $reason));
		//$pc->setData($data);
		$pc->process();
		//$pc->addHeadTags();
		$output .= $pc->getData();
	    
		$show_link = $params->get('show_link', 1); 
		if ($show_link) { 
			$link_html = '<a href="http://www.joomduck.com/extensions/community-builder-profile-pro?clk=pwrd_by" target="_blank" title="Community Builder Profile Pro component for Joomla!">Powered by CB Profile Pro</a>';
			$link_html = '<div style="margin:15px 0; clear:both; text-align:center; font-size:75%">'.$link_html.'</div>';
			
			if ($reason == 'profile') {
				$output .= $link_html;
			} elseif ($reason == 'profile_edit') {
			//	$output .= '<div style="position:relative;"><div style="position:absolute; bottom:-80px; width:100%; text-align:center; font-size:75%">'.$link_html.'</div></div>';
				$pc->head_tags['js'][] = '$( ".cbEditProfile" ).append( \''.$link_html.'\' );';
				$pc->addHeadTags();
			} elseif ($reason == 'registration') {
			//	$output .= '<div style="position:relative;"><div style="position:absolute; bottom:-120px; width:100%; text-align:center; font-size:75%">'.$link_html.'</div></div>';
				$pc->head_tags['js'][] = '$( ".cbRegistration" ).append( \''.$link_html.'\' );';
				$pc->addHeadTags();
			}
		}
		
		if ($reason == 'registration' && $formatting == 'tabletrs') {
			$output = '<tr><td colspan="2" class="cbpp-registration">'.$output.'</td></tr>';
		} else {
			$output = '<div class="cbpp-'.$reason.'">'.$output.'</div>';
		}

		// add relevant template stylesheet
		Cbprofilepro::loadTemplateCSS( $profiletype, $reason );
		
		// get inputs for /*missing required fields,*/ profile type field and "profiletype" url variable
		$additional_inputs = Cbprofilepro::getAdditionalInputs($profiletype, $reason);
		if($additional_inputs) $output .= $additional_inputs;
		
		return $output; 
	}
	
	public static function getProfiletype ($reason) {
		
		$db = JFactory::getDBO();
		$user = JFactory::getUser();

		// get component configuration 
		$params	= JComponentHelper::getParams('com_cbprofilepro');
		
		// no state filter for admin
		$state = ($user->authorise('core.admin', 'com_cbprofilepro')) ? '' : " AND `state` = 1";
		//if ( $user->authorise('core.admin', 'com_cbprofilepro') && $user->id == $_CB_framework->displayedUser() ) $state = ''; // profile
		//if (JRequest::getInt('uid')) $uid = JRequest::getInt('uid');  if ( $user->authorise('core.admin', 'com_cbprofilepro') && !isset($uid) ) $state = ''; // profile_edit

		$multiple = $params->get('multiple', 1);

		if ($multiple) {

			if ($reason == 'registration') {
					
				$profiletype_input = (version_compare(JVERSION,'3.0.0','ge')) ? JFactory::getApplication()->input->get('profiletype') : JRequest::getVar('profiletype'); 	
				if ($profiletype_input) $selected = $profiletype_input;
	
			} else {
			
				$profiletype_field = $params->get('profiletype_field', 'cb_profiletype');

				$profile_owner_id = Cbprofilepro::getProfileOwnerId($reason);
			
				$db->setQuery("SELECT ".$profiletype_field." FROM #__comprofiler WHERE id = " . $profile_owner_id);
				$field_value = $db->loadResult();
				
				if ($field_value) $selected = $field_value;
			}
			
		}

		if (isset($selected)) { // get selected profile type data if selected profile type defined

			$db->setQuery("SELECT * FROM #__cb_profiletypes WHERE `alias` = " . $db->Quote($selected) . $state);
			$profiletype = $db->loadObject();

			if(isset($profiletype) && $profiletype->registration == '' && $params->get('registration_empty', 'default_profiletype') == "default_profiletype") {
				$db->setQuery("SELECT registration FROM #__cb_profiletypes WHERE `default` = 1" . $state);
				$profiletype->registration = $db->loadResult();
			}
		
		} else { // get default profile type data if selected profile type undefined (also runs automatically if multiple profile types disabled)
		
			$db->setQuery("SELECT * FROM #__cb_profiletypes WHERE `default` = 1" . $state);
			$profiletype = $db->loadObject();
		}
		
		return (isset($profiletype)) ? $profiletype : null;
	}	
	
	public static function getProfileOwnerId ($reason) {
		
		if ($reason == 'registration') return;
		
		switch ($reason) {
			case 'profile':
				global $_CB_framework; 
				$user_id = $_CB_framework->displayedUser();
				break;
				
			case 'profile_edit':
				$user = JFactory::getUser();
				$uid_input = (version_compare(JVERSION,'3.0.0','ge')) ? JFactory::getApplication()->input->get('uid', null, 'int') : JRequest::getInt('uid'); 	
				$user_id = ($uid_input) ? $uid_input : $user->id; 
				break;
		}
		
		return $user_id;
	}
	
	public static function getAdditionalInputs ( $profiletype, $reason ) {
		
		$params	= JComponentHelper::getParams( 'com_cbprofilepro' );
		$profiletype_field = $params->get('profiletype_field', 'cb_profiletype');
		$multiple = $params->get('multiple', 1);

		$additional_inputs = '';
		$profiletype_url_data = '';
		$profiletype_field_data = '';
	
		$profiletype_input = (version_compare(JVERSION,'3.0.0','ge')) ? JFactory::getApplication()->input->get('profiletype') : JRequest::getVar('profiletype');
		
		if($reason == 'registration' && $profiletype_input && $multiple) {
			$profiletype_url_data =  '<input type="hidden" name="profiletype" id="profiletype" value="'.$profiletype_input.'">';
			if(strpos( $profiletype->registration, $profiletype_field ) === false) {
				$profiletype_field_data = '<input type="hidden" name="'.$profiletype_field.'" id="'.$profiletype_field.'" value="'.$profiletype->alias.'">';
			}
			
			$additional_inputs = '<div id="hidden_inputs" style="display:none;">'.$profiletype_url_data.$profiletype_field_data.'</div>';
		}
		
		$additional_inputs .= '<input type="hidden" name="cbpp" value="1">';

		return $additional_inputs;
	}
	
	public static function loadTemplateCSS ( $profiletype, $reason ) {
		
		global $_CB_framework, $ueConfig;
		
		// get component configuration 
		$params	= JComponentHelper::getParams( 'com_cbprofilepro' );
		$template_rule = $params->get('template_rule', 'forall'); 
		
		if ($template_rule == 'forone') {
		
			$template =	$profiletype->cb_template;
			
		} elseif ($template_rule == 'forall') {
			
			$db = JFactory::getDBO();
			$user = JFactory::getUser();

			$multiple = $params->get('multiple', 1);
			$profiletype_field = $params->get('profiletype_field', 'cb_profiletype');
			
			if ($user->id == 0) {
				return;
			}
			
			// no state filter for admin
			if ( $user->authorise('core.admin', 'com_cbprofilepro') && ($user->id == $_CB_framework->displayedUser() ||  $reason == 'profile_edit') ) {
				$state = "";
			} else {
				$state = " AND `state` = 1";
			}
			
			if ($multiple) {
				
				$db->setQuery("SELECT ".$profiletype_field." FROM #__comprofiler WHERE id=".$user->id);
				$field_value = $db->loadResult();
		
				if ($field_value) {
					$db->setQuery("SELECT cb_template FROM #__cb_profiletypes WHERE `alias` = '".$field_value."'".$state);
					$template = $db->loadResult();
				}			
			}				
			
			if (!isset($template)) {			
				$db->setQuery("SELECT cb_template FROM #__cb_profiletypes WHERE `default` = 1".$state);
			} 

		}

		if(isset($template) && $template && $template != $ueConfig['templatedir']) { 
		
			$remove_default_css = '$("link[href*=\''.selectTemplate().'template.css\']").remove();';
			$_CB_framework->outputCbJQuery( $remove_default_css );

			/*$output = 'live_site';
			$template_file = 'template.css';
			$media = null;*/
			
			$_CB_framework->document->addHeadStyleSheet( selectTemplate('live_site', $template).'template.css', false, null );
		}  
	}	
	
	public static function getCBVersion () {
	

		$xml = JPATH_ROOT.'/administrator/components/com_comprofiler/comprofiler.xml';
		$parser	= new SimpleXMLElement($xml , NULL , true);

		$version = (string)$parser->version;
		
		return $version;
	}
}