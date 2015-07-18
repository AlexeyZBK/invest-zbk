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
defined('_JEXEC') or die('Restricted access');

include_once(JPATH_ADMINISTRATOR.'/components/com_comprofiler/plugin.foundation.php');

require_once(JPATH_ROOT.'/components/com_cbprofilepro/helpers/cbprofilepro.php');
$cbVersion = Cbprofilepro::getCBVersion();

$db = JFactory::getDBO();

// Community Builder fields
$db->setQuery("SELECT * FROM #__comprofiler_fields ORDER BY tabid DESC, ordering");
$fields = $db->loadObjectList();

$db->setQuery("SELECT id FROM #__comprofiler_plugin WHERE element = 'cbconditional'");
$result = $db->loadResult();
$cbconditional = ($result) ? true : false;

$db->setQuery("SELECT * FROM #__comprofiler_tabs ORDER BY position");
$tabs = $db->loadObjectList("tabid");

$tabs_by_positions = array(); 
foreach($tabs as $tab) { 
	if(!isset($tabs_by_positions[$tab->position])) $tabs_by_positions[$tab->position] = array();
	$tabs_by_positions[$tab->position][] = $tab;
} 

$tab_positions = array();	

if(version_compare($cbVersion, '1.9.1', '=<')) {

	$language = JFactory::getLanguage();
	$languageTag = $language->getTag();

	if(file_exists(JPATH_SITE.'/components/com_comprofiler/plugin/language/'.$languageTag.'/language.php')) {
		include_once(JPATH_SITE.'/components/com_comprofiler/plugin/language/'.$languageTag.'/language.php');
	} else {
		include_once(JPATH_SITE.'/components/com_comprofiler/plugin/language/default_language/default_language.php');		
	}
	
	cbimport('language.admin');
	$tab_positions['cb_head'] = getLangDefinition("_UE_POS_CB_HEAD");
	$tab_positions['cb_left'] = getLangDefinition("_UE_POS_CB_LEFT");
	$tab_positions['cb_middle'] = getLangDefinition("_UE_POS_CB_MIDDLE");
	$tab_positions['cb_right'] = getLangDefinition("_UE_POS_CB_RIGHT");
	$tab_positions['cb_tabmain'] = getLangDefinition("_UE_POS_CB_MAIN");
	$tab_positions['cb_underall'] = getLangDefinition("_UE_POS_CB_BOTTOM");

} else {

	$tab_positions['canvas_menu'] = 'Canvas Menu';
	$tab_positions['canvas_background'] = 'Canvas Background';
	$tab_positions['canvas_stats_top'] = 'Canvas Stats Top';
	$tab_positions['canvas_stats_middle'] = 'Canvas Stats Middle';
	$tab_positions['canvas_stats_bottom'] = 'Canvas Stats Bottom';
	$tab_positions['canvas_photo'] = 'Canvas Photo';
	$tab_positions['canvas_title_top'] = 'Canvas Title Top';
	$tab_positions['canvas_title_middle'] = 'Canvas Title Middle';
	$tab_positions['canvas_title_bottom'] = 'Canvas Title Bottom';
	$tab_positions['canvas_main_left'] = 'Canvas Main Left';
	$tab_positions['canvas_main_left_static'] = 'Canvas Main Left Static';
	$tab_positions['canvas_main_middle'] = 'Canvas Main Middle';
	$tab_positions['canvas_main_right'] = 'Canvas Main Right';
	$tab_positions['canvas_main_right_static'] = 'Canvas Main Right Static';
	
	$tab_positions['cb_head'] = 'Header (above left/middle/right)';
	$tab_positions['cb_left'] = 'Left side (of middle area)';
	$tab_positions['cb_middle'] = 'Middle area';
	$tab_positions['cb_right'] ='Right side (of middle area)';
	$tab_positions['cb_tabmain'] = 'Main area (below left/middle/right)';
	$tab_positions['cb_underall'] = 'Bottom area (below main area)';
}

for ( $i = 1 ; $i <= 9; $i++ ) {
	for ( $j = 1 ; $j <= 9; $j++ ) {
		$tab_positions['L'.$i.'C'.$j] = CBTxt::T('Line') . ' ' . $i . ' ' . CBTxt::T('Column') . ' ' . $j;
	}
}

for ( $i = 1 ; $i <= 9; $i++ ) {
	$tab_positions['not_on_profile_'.$i] = CBTxt::T('Not displayed on profile') . ' ' . $i;
}

//$db->setQuery("SELECT * FROM #__comprofiler_tabs WHERE pluginclass NOT IN ('getContactTab', 'getAuthorTab', 'getForumTab', 'getBlogTab', 'getConnectionTab', 'getNewslettersTab', 'getMenuTab', 'getConnectionPathsTab', 'getPageTitleTab', 'getPortraitTab', 'getStatusTab', 'getmypmsproTab', 'cbProfileProTab')");
//$objects = $db->loadObjectList(); 

// module positions 
$db->setQuery("SELECT * FROM #__modules WHERE client_id = 0"); 
$modules = $db->loadObjectList(); 

$modules_by_positions = array(); 
foreach($modules as $module) { 
	if(!isset($modules_by_positions[$module->position])) $modules_by_positions[$module->position] = array();
	$modules_by_positions[$module->position][] = $module;
} 

// jQuery UI tabs
$path_pattern = JPATH_ROOT.'/components/com_cbprofilepro/includes/css/jquery-ui-themes/*';
$folder_paths = glob($path_pattern, GLOB_ONLYDIR);
$themes = array();
foreach($folder_paths as $folder_path) {
	preg_match('#.*/(.*)#', $folder_path, $matches);
	$themes[] =	$matches[1];
}
if($themes[0] == 'pages') $themes[] = array_shift($themes);

// show/hide 
$db->setQuery("SELECT title FROM #__viewlevels ORDER by id ASC");
$access_levels = $db->loadColumn();

$db->setQuery("SELECT title FROM #__usergroups ORDER by id ASC");
$user_groups = $db->loadColumn();

$db->setQuery("SELECT title, alias FROM #__cb_profiletypes ORDER by id ASC");
$profile_types = $db->loadObjectList();

$document = JFactory::getDocument();
$document->addStyleSheet(JURI::root().'administrator/components/com_cbprofilepro/includes/css/jquery-ui.min.css');

$tooltip_class = (version_compare(JVERSION,'3.0.0','ge')) ? 'hasTooltip' : 'hasTip';

$enabledHTML = '<img src="'.JURI::root().'administrator/components/com_cbprofilepro/includes/images/tick.png" border="0" />';
$disabledHTML = '<img src="'.JURI::root().'administrator/components/com_cbprofilepro/includes/images/publish_x.png" border="0" />'; 

$params = JComponentHelper::getParams('com_cbprofilepro');

ob_start(); ?>
	<style type="text/css">
		table.items { 
			max-width:100%;
			border-collapse:collapse;
		}
		table.items th, table.items td, ul.items li  {
			text-align:left; 
			padding:7px 0;
		}
		table.items th:first-child, table.items td:first-child, ul.items li {
			padding-left:7px;
		}
		table.items td, ul.items li {
			border-top:1px solid #ddd;
		}
		ul.items li:first-child {
			border-top:0px;
		}
		ul.items {
			margin:0px;
			padding: 0px;
			list-style:none;
		}
		.field-insert:hover, .output-insert:hover   {
			background-color:#f5f5f5;
			cursor:pointer;
		}
		.list-delimeter {
			background-color:#f9f9f9;
		}
		.ui-accordion .ui-accordion-content {
			padding:0px;
		}	
		.ui-accordion .ui-accordion-header {
			line-height:29px;
		}
		.ui-accordion .ui-accordion-header, .jbutton {
			outline: none;
		}
		.option {
			margin:6px 0 6px 12px;
			clear:both;
		}
		.option > label {
			float:left;
			text-align:left;
			width:30%;
			margin-top:3px;
		} 
		.option > input, .option > div {
			text-align:left;
		} 
		.profile_edit-visible, .registration-visible  {
			display:none;
		}
		.toolbar-links a {
			display: inline-block;
			margin: 0 2px;
			text-decoration: none;
			color: #08c;
		}
		.toolbar-links a:hover {
			color: #eb8f00;
		}
		.tooltip-highlight {
			margin-bottom: 5px;
			<?php if(version_compare(JVERSION,'3.0.0','ge')) : ?> 
				text-align:center;
			<?php endif; ?>
		}
	</style>
	<style type="text/css" id="tooltip-classes"> 
		.profile_edit-tooltip, .registration-tooltip { 
			display: none; 
		} 
	</style>
<?php $head_css = ob_get_clean(); 
$document->addCustomTag($head_css);

$head_js = '<script type="text/javascript" src="'.JURI::root().'administrator/components/com_cbprofilepro/includes/js/jquery-ui.min.js"></script>';
$head_js .= "\n".'<script type="text/javascript" src="'.JURI::root().'components/com_cbprofilepro/includes/js/jquery-cookie.min.js"></script>';

ob_start(); ?>
	<script type="text/javascript">
		var active_workspace = 'profile';
		var workspace_selector = active_workspace;
		var workspaces = <?php echo json_encode($workspaces); ?>;
		
		function insertOutput(output) {
			<?php /*window.parent.jInsertEditorText(output, "'.$this->eName.'");
			window.parent.SqueezeBox.close();*/ ?>
			window.jInsertEditorText(output, 'jform_'+active_workspace);
			return false;
		}

		function autoResize(id) {

			var iframe = document.getElementById(id);
			var doc = iframe.contentDocument ? iframe.contentDocument : iframe.contentWindow.document;

			iframe.style.visibility = 'hidden';
			iframe.height = iframe.style.height = "0px";

			doc = doc || document;
			body = doc.body;
			html = doc.documentElement;
			var newheight = Math.max(body.scrollHeight, body.offsetHeight, html.clientHeight, html.scrollHeight, html.offsetHeight);

			iframe.height = iframe.style.height= (newheight) + "px";
			iframe.style.visibility = 'visible';
		}

		jQuery.noConflict();   
		jQuery(document).ready(function($){ 
		
			var toolbar = new function() {

				this.element = $("#cbpp-toolbar");
				this.contents_element = $("#cbpp-accordion");
			//	this.placeholder_element = $("#cbpp-toolbar-placeholder");
				this.fieldset_element = $("fieldset.adminform");
	
				this.adjust = function () {
				
					this.placeholder_element = $("#"+workspace_selector+"-toolbar-placeholder");
	
					var fieldset_width = this.fieldset_element.width();  
					var editor_width = $("#"+workspace_selector+"-editor-container").width();

					var sidebar_width = fieldset_width - editor_width;
		
					var sidebar = (sidebar_width < 238) ? 0 : 1; // 250
		
					var width = (sidebar) ? sidebar_width-10 : fieldset_width;
					this.adjustWidth(width);
		
					var contents_height =  this.contents_element.height();  <?php // on accordion create, activate, window resize, editor resize ?>
					<?php if(version_compare(JVERSION,'3.0.0','<=')) : ?>
						contents_height += 25;
					<?php endif; ?> 
					
					var placeholder_height = (sidebar) ? 'auto' : contents_height+10;
					this.adjustPlaceholderHeight(placeholder_height);
			
					var placeholder_offset = this.placeholder_element.offset();
					var bottom_offset = this.fieldset_element.offset().top + this.fieldset_element.height();
			
					var window_height = $(window).height();
					var window_top = $(window).scrollTop();
		
					if(sidebar) {
						<?php /* if(typeof sidebar_margin_left === 'undefined') sidebar_margin_left = <?php echo $sidebar_margin_left; ?>; */ ?>
						var left_offset = placeholder_offset.left + workspaces[workspace_selector]['sidebar_margin_left']; // 10

						if(contents_height >= (bottom_offset - placeholder_offset.top)) { // var position = 'sidebar_static';

							var height = bottom_offset - placeholder_offset.top;
							var top_offset = placeholder_offset.top - window_top; // - 2
							
						} else {
			
							<?php // var deduction = (contents_height > height) ? contents_height : height; ?>
							var deduction = contents_height;
							var sidebar_bottom_start_offset = bottom_offset - deduction;

							if(window_top > sidebar_bottom_start_offset) { // var position = 'sidebar_bottom';
								
								var height = contents_height;
								var top_offset = - (window_top - sidebar_bottom_start_offset);

							} else if (window_top > (placeholder_offset.top - <?php echo (version_compare(JVERSION,'3.0.0','ge')) ? 80 : 2; ?>)) { // var position = 'sidebar_fixed'; 
				
								var height = window_height - <?php echo (version_compare(JVERSION,'3.0.0','ge')) ? 116 : 4; ?>;
								var top_offset = <?php echo (version_compare(JVERSION,'3.0.0','ge')) ? 80 : 2; ?>;			
					
							} else { // var position = 'sidebar_top';

								var height = (contents_height > window_height) ? contents_height + 0 : window_height;
								var top_offset = placeholder_offset.top - window_top;
							}
						}
					} else { // var position = 'bottom_static';
		
						var height = contents_height;
						var top_offset = placeholder_offset.top - window_top + 10;
						var left_offset = placeholder_offset.left;			
					}

					this.adjustTopOffset(top_offset);
					this.adjustLeftOffset(left_offset);
					this.adjustHeight(height);	
			
					return this;
				}
		
				this.adjustPlaceholderHeight = function (new_placeholder_height) {

					if(typeof this.placeholder_height !== 'undefined' && new_placeholder_height === this.placeholder_height) return;

					this.placeholder_height = new_placeholder_height;
					this.placeholder_element.height(this.placeholder_height);
				}
		
				this.adjustWidth = function (new_width) { <?php // on window load, resize; editor resize ?>

					if(typeof this.width !== 'undefined' && new_width === this.width) return;

					this.width = new_width;
					this.element.width(this.width);
				}
		
				this.adjustHeight = function (new_height) { <?php // on window load, resize; accordion /*create,*/ activate; editor resize ?>

					if(typeof this.height !== 'undefined' && new_height === this.height) return;

					this.height = new_height;
					this.element.height(this.height);
				}

				this.adjustTopOffset = function (new_top_offset) {

					if(typeof this.top_offset !== 'undefined' && new_top_offset === this.top_offset) return;

					this.top_offset = new_top_offset;

					<?php //this.element.css({ top: this.top_offset });
					//this.element.offset({ top: this.placeholder_element.offset().top }); ?>
					this.element[0].style['top'] = this.top_offset+'px';
				}
	
				this.adjustLeftOffset = function (new_left_offset) {
	
					if(typeof this.left_offset !== 'undefined' && new_left_offset === this.left_offset) return;

					this.left_offset = new_left_offset;
		
					<?php // this.element.css({ /*position: 'fixed', top: this.top_offset, */left: this.left_offset });
					// this.element.offset({ /*top: this.placeholder_element.offset().top, */left: this.left_offset }); ?>
					this.element[0].style['left'] = this.left_offset+'px'; <?php // fastest method ?>

				}
			}
			function adjustToolbar() {
			
				toolbar.adjust();
			}
			
			var window_loaded = 0;
				
			$(window).on({ 
				load: function() { 
				
					<?php foreach($workspaces as $view => $workspace) : ?>
						var <?php echo $view; ?>_iframe  = document.getElementById("jform_<?php echo $view; ?>_ifr");
						if(<?php echo $view; ?>_iframe !== null) $(<?php echo $view; ?>_iframe.contentWindow).on("resize", adjustToolbar);
						<?php if(version_compare(JVERSION,'3.0.0','<=')) : ?>
							if(<?php echo $view; ?>_iframe !== null) {
								$(<?php echo $view; ?>_iframe.contentWindow).on('focus', function () { active_workspace = '<?php echo $view; ?>'; switchWorkspace(); });
							} else {
								$("textarea#jform_<?php echo $view; ?>").on('focus', function () { active_workspace = '<?php echo $view; ?>'; switchWorkspace(); });
							}
						<?php endif; ?>
					<?php endforeach; ?>

					<?php if(version_compare(JVERSION,'3.0.0','ge')) : ?>
						<?php if($active_editor == 'tinymce') : ?>
							tinyMCE.get('jform_'+active_workspace).focus();
						<?php endif; ?>
					<?php else : ?>
						if(profile_iframe !== null) {
							$(profile_iframe.contentWindow).focus();
						} else {
							//$("textarea#jform_profile").focus();
						}
					<?php endif; ?>
					
					adjustToolbar();

					window_loaded = 1;
				}, 
				resize: adjustToolbar,
				scroll: function() { 
		
					if(!window_loaded) return;
			
					adjustToolbar();
				}
			});
		
			$("#cbpp-accordion").on("accordionactivate", adjustToolbar);	 
	
			$("#show-more-button,#hide-more-button").on("click", adjustToolbar);
			
			<?php if(version_compare(JVERSION,'3.0.0','ge')) : ?>
				$("#cbppWorkspacesTabs").on('shown.bs.tab', function (e) {
					active_workspace = $(e.target).attr('href').replace('#', '');
					active_workspace = active_workspace.replace('profil_edit', 'profile_edit');
					workspace_selector = active_workspace;
			
					<?php if($active_editor == 'tinymce') : ?>
						if(window_loaded) tinyMCE.get('jform_'+active_workspace).focus();
					<?php endif; ?>
			
					switchWorkspace();
					adjustToolbar();
				});
			<?php endif; ?>
			<?php if(version_compare(JVERSION,'3.0.0','<=')) : ?>
				$("#anchor-links a").on('click', function () { 
					active_workspace = $(this).attr('href').match(/#jform_(.+)-lbl/)[1];
				
					<?php //if($active_editor == 'tinymce') : ?> 
						var editor_iframe  = document.getElementById("jform_"+active_workspace+"_ifr");
						if(editor_iframe !== null) {
							$(editor_iframe.contentWindow).focus();
						} else {
							$("textarea#jform_"+active_workspace).focus();
						}
					<?php //endif; ?>
					
					switchWorkspace();
				}); 
			<?php endif; ?>
			
			function switchWorkspace() {
				switch(active_workspace) {
					case 'registration':
						$(".profile-visible,.profile_edit-visible").hide();
						$(".registration-visible").show();
						$("style#tooltip-classes").html(".profile-tooltip, .profile_edit-tooltip { display: none } .registration-tooltip { display: block }");
					break;
					case 'profile_edit':
						$(".profile-visible,.registration-visible").hide();
						$(".profile_edit-visible").show();
						$("style#tooltip-classes").html(".profile-tooltip, .registration-tooltip { display: none } .profile_edit-tooltip { display: block }");
					break;
					case 'profile':
						$(".registration-visible,.profile_edit-visible").hide();
						$(".profile-visible").show();
						$("style#tooltip-classes").html(".profile_edit-tooltip, .registration-tooltip { display: none } .profile-tooltip { display: block }");
					break; 
				} 
			}
			
			<?php if($active_editor == 'tinymce') : ?>
				
				$("fieldset").on("mousedown", ".mce-resizehandle,.mceResize", function() {

					<?php if(version_compare(JVERSION,'3.0.0','<=')) : ?>
						 active_workspace = $(this).attr('id').match(/jform_(.+)_resize/)[1];
					<?php endif; ?>	
						
					if(typeof workspaces[workspace_selector]['tinymce_width_unset'] === 'undefined') return;	
					if(typeof workspaces[active_workspace]['editor_width_check'] !== 'undefined' && workspaces[active_workspace]['editor_width_check'] !== 'timedout') return;

					var iframe_element = $("#jform_"+active_workspace+"_ifr");	
					var editor_width = iframe_element.width();
				
					var startTime = new Date().getTime();
				
					workspaces[active_workspace]['editor_width_check'] = setInterval(function() { 

						var new_editor_width = iframe_element.width();
						
						if(editor_width !== new_editor_width) {

							workspaces[workspace_selector]['sidebar_margin_left'] = 10;
							<?php // $("#editor-container").width("auto"); ?>
							$("#"+workspace_selector+"-editor-container").attr('class', '<?php echo $float_left_class; ?>');
							
							<?php if(version_compare(JVERSION,'3.0.0','ge')) : ?>
								$("#"+workspace_selector+"-editor-container").parent(".row-fluid").removeClass('row-fluid');
								$("#"+workspace_selector+"-toolbar-placeholder").attr('class', 'pull-left width-238');
							<?php endif; ?>	
								
							clearInterval(workspaces[active_workspace]['editor_width_check']);
							workspaces[active_workspace]['editor_width_check'] = 'finished';
							<?php if(version_compare(JVERSION,'3.0.0','<=')) : ?>
								workspaces[workspace_selector]['tinymce_width_unset'] = undefined;
							<?php endif; ?>	
						}
						if(new Date().getTime() - startTime > 10000){
							clearInterval(workspaces[active_workspace]['editor_width_check']);
							workspaces[active_workspace]['editor_width_check'] = 'timedout';
						}
					}, 10)
				});
			<?php endif; ?>
			
			$(".jbutton").button();
		
			$(".jbutton-set").buttonset();

			$("#cbpp-accordion").accordion({
				heightStyle: "content"<?php /*"fill"*/ ?>, collapsible: true, 
				active: (!isNaN($.cookie("cbpp-accordion-active"))) ? parseInt($.cookie("cbpp-accordion-active")) : false,
				beforeActivate: function( event, ui ) { 
					if($(event.toElement).attr('data-toggle') == "form-reset" || $(event.toElement).parents("button").attr('data-toggle') == "form-reset") return false; 
				}, 
				activate: function( event, ui ) { 
					var active = $(this).accordion("option","active");
					$.cookie("cbpp-accordion-active", active, { expires: 30 });
					if(active == 2) autoResize('code-list-iframe');
				}
			});
			
			$("[data-toggle=form-reset]").click(function() { 
				$($(this).attr("data-target"))[0].reset();
			});

			$("#ftl-show").click(function() {
				$("#ftl-class-container,#delimeter-container").show();
			});
		
			$("#ftl-hide,#field-options-reset").click(function() {
				$("#ftl-class-container,#delimeter-container").hide();
			});

			$(".field-insert").click(function() {		
		
				var fieldname = $(this).attr("data-fieldname");
				var output = "{fld "+fieldname+"}";

				var fld_class = $("#fld-class").val();
				if (fld_class != '') output = '<span class="'+fld_class+'">'+output+'</span>';
		
				var ftl = $('input:radio[name=ftl-controller]:checked').val();
		
				if (ftl == 1) {	
					var delimeter = $("#delimeter").val();
					var ftl_output = "{ftl "+fieldname+"}"+delimeter;
			
					var ftl_class = $("#ftl-class").val();
					if (ftl_class != '') ftl_output = '<span class="'+ftl_class+'">'+ftl_output+'</span>';
					output = ftl_output+" "+output;
				} 
		
				<?php if($cbconditional) : ?>
					if(active_workspace === 'registration' || active_workspace === 'profile_edit') {
						var fld_id = $(this).attr("data-fieldid");
						output = '<div id="cbfr_'+fld_id+'">'+output+'</div>';
					}
				<?php endif; ?>
		
				return insertOutput(output);
			});
				
			$(".output-insert").click(function(e) {	
				var output = $(this).attr("data-output");
				return insertOutput(output);
			});
			
			$("#pagebreak-insert").click(function() {	
				
				var attributes = '';
				
				var title = $("#page-title").val();
				if(title) attributes += 'title="'+title+'" ';
		
				var alt = $("#page-alt").val();
				alt = alt.replace(/\s/g, '');
				if(alt) attributes += 'alt="'+alt+'" ';
		
				var output = '<hr class="cbpp-pagebreak"'+attributes+' />';
				return insertOutput(output);
			});
				
			$("#append-tab").click(function() {
			
				if(typeof tabs_counter === 'undefined') tabs_counter = 1;
				tabs_counter++; 

				var tabHtml =	 '<div class="option">'
									+'<label for="tab'+tabs_counter+'-title">#'+tabs_counter+' <?php echo JText::_('TABS_TAB_TITLE_LABEL'); ?></label>'
									+'<input type="text" id="tab'+tabs_counter+'-title" name="tab-title" size="40" class="input-xlarge" />'
								+'</div>'
								+'<div class="option">'
									+'<label for="tab'+tabs_counter+'-content" style="margin-top:0px;">#'+tabs_counter+' <?php echo JText::_('TABS_TAB_CONTENT_LABEL'); ?></label>'
									+'<textarea id="tab'+tabs_counter+'-content" name="tab-content" cols="35" rows="8" class="input-xlarge" /></textarea>'
								+'</div>';

				$("#appended-tabs").append(tabHtml);
				return false;
			});
	
			$("#jqueryui-tabs-reset").click(function() {
				$("#appended-tabs").html("");
				tabs_counter = 1;
			});
	
			$("#jqueryui-tabs-insert").click(function() {
	
				$("#jqueryui-tabs-error").text("").hide();

				var options = '';
			
				if($("#mouseover").is(":checked")) options += " mouseover"; 
				if($("#collapsible").is(":checked")) options += " collapsible"; 
				if($("#cookie").is(":checked")) options += " cookie"; 
				if($("#sortable").is(":checked")) options += " sortable"; 
		
				var navigation = $("[name=navigation]:checked").val();
				if(navigation) options += " "+navigation; 

				var theme = $("#theme").val();
				if(theme) options += " theme="+theme;
		
				var tabs_pattern = '';

				var tabs_titles = $('[name=tab-title]');
				if(!tabs_titles.length) { 
					$("#jqueryui-tabs-error").text("<?php echo JText::_('TABS_NO_TABS_ERROR'); ?>").show();
					return;
				}
				var tabs_contents = $('[name=tab-content]');
		
				for (var i = 0; i < tabs_titles.length; i++) {	
					var num = i+1;
					if(tabs_titles[i].value) {
						tabs_pattern += "{TAB"+num+"="+tabs_titles[i].value+"}"+tabs_contents[i].value+"{/TAB"+num+"}";
					} else {
						$("#jqueryui-tabs-error").text("<?php echo JText::_('TABS_NO_TAB_TITLE_ERROR'); ?>"+num).show();
						return;
					}
				}

				var output = "{TABS"+options+"}"+tabs_pattern+"{/TABS}";
				return insertOutput(output);
			});
	
			$("#showhide-form-reset").click(function() {
				$("#if-container").hide( );
				$("#for-container").hide( );
			});
			
			$("#if-button").click(function() {
				$("#for-container").hide( );
				$("#if-container").show( );
			});
	
			$("#for-button").click(function() {
				$("#if-container").hide( );
				$("#for-container").show( );
			});
		
			$("#showhide-insert").click(function() {			

				$("#showhide-error").text("").hide();

				var show_hide = $('input:radio[name=showhide-controller]:checked').val();
	
				var content = $('#showhide-content').val();

				var if_for = $('input:radio[name=iffor-controller]:checked').val();
		
				if(typeof if_for === 'undefined') {
					$("#showhide-error").text("<?php echo JText::_('SHOWHIDE_NO_CONDITION_ERROR'); ?>").show();
					return;
				}

				if (if_for == 'IF') {
		
					var owners_visitors = $('input:radio[name=ownersvisitors-controller]:checked').val();
			
					var field = $('#if-field').val(); 
					if(!field) {
						$("#showhide-error").text("<?php echo JText::_('SHOWHIDE_NO_FIELD_ERROR'); ?>").show();
						return;
					}
			
					var contains = $('#contains').val(); 	
					if(!contains) { 
						$("#showhide-error").text("<?php echo JText::_('SHOWHIDE_NO_FIELD_VALUE_ERROR'); ?>").show();
						return;
					}
			
					var conditions = owners_visitors+field+"="+contains;

				} else if (if_for == 'FOR')  {
		
					var conditions = new Array();
			
					var access_level = $('#for-access_level').val(); 			
					if(access_level) conditions.push("access_level="+access_level); 

					var user_groups = $('#for-user_group').val(); 
					if(user_groups) conditions.push("user_group="+user_groups.join(","));
			
					var profile_types = $('#for-profile_type').val();

					<?php if(count($profile_types) >= 3) : ?>
						if(profile_types) profile_types = profile_types.join(",");
					<?php endif; ?>

					if(profile_types) conditions.push("profile_type="+profile_types); 
			
					var owner_visitor = $('input:radio[name=ownervisitor-controller]:checked').val();
					if(owner_visitor) conditions.push(owner_visitor);
			
					var conditions = conditions.join(" || ");
			
					if(!conditions) { 
						$("#showhide-error").text("<?php echo JText::_('SHOWHIDE_NO_USER_PROPERTIES_ERROR'); ?>").show();
						return;
					}

				} 
		
				var output = "{"+show_hide+"_"+if_for+" "+conditions+"}"+content+"{/"+show_hide+"_"+if_for+"}";
				return insertOutput(output);
			});	
		
			$("#modal-insert").click(function() {	
			
				var url = $("#link_url").val();
		
				var text = $("#link_text").val();
		
				var attributes = '';
		
				var title = $("#link_title").val();
				if (title != '') attributes += ' title="'+title+'"';

				var width = $("#modal_width").val();
				var height = $("#modal_height").val();
				if(width != '' && height != '') attributes += ' rel="{handler: \'iframe\', size: {x: '+width+', y: '+height+'}}"';
	
				var output = '<a href="'+url+'" class="cbpp-modal"'+attributes+'>'+text+'</a>';
		
				return insertOutput(output);
			});

			$("#tooltip-insert").click(function() {			
		
				var options = new Array();
		
				var tooltip_content = $("#tooltip_content").val();
				if (tooltip_content) options.push("content="+tooltip_content);
		
				var tooltip_title = $("#tooltip_title").val();
				if (tooltip_title) options.push("title="+tooltip_title);
		
				var image_for_tooltip = $("#image_for_tooltip").val();
				if (image_for_tooltip) options.push("image="+image_for_tooltip);
		
				var text_for_tooltip = $("#text_for_tooltip").val();
				if (text_for_tooltip) options.push("text="+text_for_tooltip);
		
				var url_for_tooltip = $("#url_for_tooltip").val();
				if (url_for_tooltip) options.push("url="+url_for_tooltip);
		
				var options = options.join(" || ");
				var output = "{tooltip "+options+"}";
	
				return insertOutput(output);
			});
		});
	</script>	
<?php $head_js .= ob_get_clean(); 
$document->addCustomTag($head_js); ?>

<?php if(version_compare(JVERSION,'3.0.0','<=')) : ?>
	<div class="toolbar-links" style="font-size:11px; margin: 0px; padding:5px 0 8px;"><a href="javascript:void(0);" onclick="Joomla.submitbutton('profiletype.apply')" style="float:right"><?php echo JText::_('JTOOLBAR_APPLY'); ?></a> <?php echo JText::_('NAVIGATE_TO'); ?>: <span id="anchor-links"><a href="#jform_profile-lbl"><?php echo JText::_('COM_CBPROFILEPRO_FIELD_PROFILE_LABEL'); ?></a> <a href="#jform_profile_edit-lbl"><?php echo JText::_('COM_CBPROFILEPRO_FIELD_PROFILE_EDIT_LABEL'); ?></a> <a href="#jform_registration-lbl"><?php echo JText::_('COM_CBPROFILEPRO_FIELD_REGISTRATION_LABEL'); ?></a></span></div>
<?php endif; ?>
<div id="cbpp-accordion">
	<h3 style="margin-top: 0px;"><button type="button" class="jbutton" style="float:right" data-toggle="form-reset" data-target="#field-options" id="field-options-reset"><?php echo JText::_('RESET_OPTIONS_BUTTON'); ?></button><?php echo JText::_('FIELD_HEADING'); ?></h3>
	<div>
		<form id="field-options" style="margin:15px 0;">
			<div class="option">
				<label for="fld-class"><?php echo JText::_('FIELD_CLASS_LABEL'); ?></label>
				<input type="text" id="fld-class" class="inputbox" size="40" />
			</div>			
			<div class="option">
				<label><?php echo JText::_('FIELD_TITLE_LABEL'); ?></label>
				<div class="jbutton-set"> 
					<input type="radio" name="ftl-controller" id="ftl-show" value="1" /><label for="ftl-show"><?php echo JText::_('FIELD_TITLE_OPTION_SHOW'); ?></label>
					<input type="radio" name="ftl-controller" id="ftl-hide" value="0" checked="checked" /><label for="ftl-hide"><?php echo JText::_('FIELD_TITLE_OPTION_HIDE'); ?></label>
				</div>
			</div>
			<div class="option" id="ftl-class-container" style="display:none;">
				<label for="ftl-class"><?php echo JText::_('FIELD_TITLE_CLASS_LABEL'); ?></label>						
				<input type="text" id="ftl-class" size="40" />
			</div>
			<div class="option" id="delimeter-container" style="display:none;">
				<label for="delimeter"><?php echo JText::_('FIELD_DELIMETER_LABEL'); ?></label>						
				<input type="text" id="delimeter" size="40" />
			</div> 		  
		</form>
		<div style="max-height:400px; overflow:auto;">
			<table cellpadding="0" cellspacing="0" width="100%" border="0" class="items">
				<thead>
					<tr>
						<th><?php echo JText::_('FIELD_TITLE_HEADING'); ?></th>
						<th><?php echo JText::_('FIELD_TYPE_HEADING'); ?></th>
						<th><?php echo JText::_('FIELD_TAB_HEADING'); ?></th>
						<th class="profile-visible" style="text-align:center;"><?php echo JText::_('FIELD_SHOW_ON_PROFILE_HEADING'); ?></th>
						<th class="profile_edit-visible registration-visible" style="text-align:center;"><?php echo JText::_('FIELD_REQUIRED_HEADING'); ?></th>
						<th class="registration-visible" style="text-align:center;"><?php echo JText::_('FIELD_SHOW_ON_REGISTRATION_HEADING'); ?></th>
						<th style="text-align:center;"><?php echo JText::_('FIELD_PUBLISHED_HEADING'); ?></th>
					</tr>
				</thead> 
				<tbody>
					<tr style="display: table-row;" class="field-insert <?php echo $tooltip_class; ?> profile-visible profile_edit-visible" data-fieldname="user_id" data-fieldid="user_id" title="<?php echo JText::_('INSERT_TOOLTIP'); ?>">
						<td><?php echo JText::_('USER_ID'); ?></td>
						<td><?php echo JText::_('SYSTEM_HIDDEN'); ?></td>
						<td></td>
						<td class="profile-visible" style="text-align:center;"><?php echo $enabledHTML; ?></td>
						<td class="profile_edit-visible registration-visible" style="text-align:center;"><?php echo $disabledHTML ?></td>
						<td class="registration-visible" style="text-align:center;"><?php echo $disabledHTML ?></td>
						<td style="text-align:center;"><?php echo $enabledHTML ?></td>
					</tr>
					<?php foreach($fields AS $field) : ?>
						<tr class="field-insert <?php echo $tooltip_class; ?>" data-fieldname="<?php echo $field->name; ?>" data-fieldid="<?php echo $field->fieldid; ?>" title="<div class='profile-tooltip'><?php echo JText::_('CBFIELD_PROFILE_INSERT_TOOLTIP'); ?></div><div class='profile_edit-tooltip'><?php echo JText::_('CBFIELD_PROFILE_EDIT_INSERT_TOOLTIP'); ?></div><div class='registration-tooltip'><?php echo JText::_('CBFIELD_REGISTRATION_INSERT_TOOLTIP'); ?></div>">
							<td><?php echo JText::_(getLangDefinition($field->title)); ?></td>
							<td><?php echo JText::_(getLangDefinition($field->type)); ?></td>
							<td<?php if (!$tabs[$field->tabid]->enabled) echo ' style="color:#FF0000"'; ?>><?php echo getLangDefinition($tabs[$field->tabid]->title); ?></td>
							<td class="profile-visible" style="text-align:center;"><?php echo ($field->profile) ? $enabledHTML : $disabledHTML; ?></td>   
							<td class="profile_edit-visible registration-visible" style="text-align:center;"><?php echo ($field->required) ? $enabledHTML : $disabledHTML; ?></td>
							<td class="registration-visible" style="text-align:center;"><?php echo ($field->registration) ? $enabledHTML : $disabledHTML; ?></td>
							<td style="text-align:center;"><?php echo ($field->published) ? $enabledHTML : $disabledHTML; ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
	<h3><?php echo JText::_('CBTAB_HEADING'); ?></h3>
	<div>
		<div style="max-height:500px; overflow:auto;">
			<table width="100%" cellpadding="0" cellspacing="0" border="0" class="items">
				<thead>
					<tr>
						<th>
							<div style="float:right; width:80px; text-align:center;"><?php echo JText::_('CBTAB_PUBLISHED_HEADING'); ?></div>
							<?php echo JText::_('CBTAB_TITLE_HEADING'); ?>
						</td>
						<th style="padding-left:7px; width:40%"><?php echo JText::_('CBTAB_POSITION_HEADING'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php if(version_compare($cbVersion, '1.9.1', '<=')) : global $ueConfig; ?>
						<!-- terms and conditions -->
						<tr class="registration-visible">
							<td class="output-insert <?php echo $tooltip_class; ?>" data-output="{object terms and conditions}" title="<?php echo JText::_('TOC_NOTICE'); ?>">
								<div style="float:right; width:80px; text-align:center;"><?php echo ($ueConfig['reg_enable_toc']) ? $enabledHTML : $enabledHTML; ?></div>
								<div style="margin-right:80px;"><?php echo JText::_(getLangDefinition('_UE_AVATAR_TOC_LINK')); ?></div>
							</td>
							<td style="border-left:1px solid #ddd; padding-left:7px;"></td>
						</tr>
					<?php endif; ?>
					<!-- reCAPTCHA -->
					<tr class="registration-visible">
						<td class="output-insert <?php echo $tooltip_class; ?>" data-output="{object reCaptcha}" title="<?php echo JText::_('RECAPTCHA_NOTICE'); ?>">
							<div style="float:right; text-align:right; margin-right:5px; font-size:11px;">
								<?php echo ($params->get('publickey')) ? '<span style="color:#009900">'.JText::_('PUBLIC_KEY_SPECIFIED').'</span>' : '<span style="color:#FF0000">'.JText::_('PUBLIC_KEY_NOT_SPECIFIED').'</span>'; ?><br />
								<?php echo ($params->get('privatekey')) ? '<span style="color:#009900">'.JText::_('PRIVATE_KEY_SPECIFIED').'</span>' : '<span style="color:#FF0000">'.JText::_('PRIVATE_KEY_NOT_SPECIFIED').'</span>'; ?>
							</div>
							<div>reCaptcha</div>
						</td>
						<td style="border-left:1px solid #ddd; padding-left:7px;">
						</td>
					</tr>
					<?php foreach($tabs_by_positions as $position => $tabs) : ?>
						<?php foreach($tabs as $key=>$tab) : ?>
							<tr>
								<td class="output-insert <?php echo $tooltip_class; ?>" data-output="{tab <?php echo $tab->title; ?>}" title="<?php echo JText::_('INSERT_TOOLTIP'); ?>">
									<div style="float:right; width:80px; text-align:center;"><?php echo ($tab->enabled) ? $enabledHTML : $disabledHTML; ?></div>
									<div style="margin-right:80px;"><?php echo JText::_(getLangDefinition($tab->title)); ?></div>
								</td>
								<?php if($key == 0) : ?>
									<td style="border-left:1px solid #ddd; padding-left:7px;" rowspan="<?php echo count($tabs); ?>" <?php if($position) echo 'class="output-insert '.$tooltip_class.'" data-output="{tabposition '.$position.'}" title="'.JText::_('INSERT_TOOLTIP').'"'; ?>>
										<?php if($position && isset($tab_positions[$position])) echo $tab_positions[$position]; ?>
									</td>
								<?php endif; ?>
							</tr>
						<?php endforeach; ?>
					<?php endforeach; ?>
				</tbody>
			</table>	
		</div>
	</div>
	<h3><a style="float:right" class="jbutton modal" rel="{handler: 'iframe'}" href="index.php?option=com_cbprofilepro&view=code&layout=item&tmpl=component"><?php echo JText::_('CODE_NEW_BUTTON'); ?></a><?php echo JText::_('CODE_HEADING'); ?></h3>
	<div>
		<iframe id="code-list-iframe" onload="autoResize('code-list-iframe');" src="index.php?option=com_cbprofilepro&view=code&layout=list&tmpl=component" width="100%" height="200px" frameborder="0" scrolling="no" seamless="seamless"></iframe>
	</div>
	<h3><button type="button" data-toggle="form-reset" data-target="#pagebreak-form" id="pagebreak-form-reset" class="jbutton" style="float:right"><?php echo JText::_('RESET_BUTTON'); ?></button><?php echo JText::_('PAGE_HEADING'); ?></h3>
	<div>
		<form id="pagebreak-form" style="margin:15px 0;">
			<div class="option">
				<label for="page-title"><?php echo JText::_('PAGE_TITLE_LABEL'); ?></label>
				<input type="text" id="page-title" size="40" />
			</div>
			<div class="option <?php echo $tooltip_class; ?>" title="<?php echo JText::_("PAGE_URL_ALIAS_TOOLTIP"); ?>">
				<label for="page-alt"><?php echo JText::_('PAGE_URL_ALIAS_LABEL'); ?></label>
				<input type="text" id="page-alt" size="40" />
			</div>
			<div style="text-align:center; margin-top:10px;"">
				<button type="button" class="jbutton" id="pagebreak-insert"><?php echo JText::_('INSERT_BUTTON'); ?></button>
			</div>
		</form>
	</div>
	<h3><button type="button" data-toggle="form-reset" data-target="#jqueryui-tabs" id="jqueryui-tabs-reset" class="jbutton" style="float:right"><?php echo JText::_('RESET_BUTTON'); ?></button><?php echo JText::_('TABS_HEADING'); ?></h3>
	<div>
		<form id="jqueryui-tabs" style="margin:15px 0;">	
			<div class="option">
				<label><?php echo JText::_('TABS_OPTIONS_LABEL'); ?></label>
				<div style="margin-left: 30%;">
					<input type="checkbox" name="mouseover" value="1" id="mouseover" class="jbutton" /><label for="mouseover"><?php echo JText::_('TABS_OPTION_OPEN_ON_MOUSEOVER'); ?></label>
					<input type="checkbox" name="cookie" value="1" id="cookie" class="jbutton" /> <label for="cookie"><?php echo JText::_('TABS_OPTION_REMEMBER_LAST_VISITED'); ?></label>
					<input type="checkbox" name="collapsible" value="1" id="collapsible" class="jbutton" /> <label for="collapsible"><?php echo JText::_('TABS_OPTION_CONTENT_COLLAPSIBLE'); ?></label>
					<input type="checkbox" name="sortable" value="1" id="sortable" class="jbutton" /> <label for="sortable"><?php echo JText::_('TABS_OPTION_SORTABLE'); ?></label>
				</div>
			</div>
			<div class="option">
				<label><?php echo JText::_('TABS_NAVIGATION_LABEL'); ?></label>
				<div class="jbutton-set"> 
					<input type="radio" name="navigation" value="" id="nav_top" checked="checked" /><label for="nav_top"><?php echo JText::_('TABS_NAVIGATION_OPTION_TOP'); ?></label>
					<input type="radio" name="navigation" value="nav-bottom" id="nav_bottom" /><label for="nav_bottom"><?php echo JText::_('TABS_NAVIGATION_OPTION_BOTTOM'); ?></label>
					<input type="radio" name="navigation" value="nav-left" id="nav_left" /><label for="nav_left"><?php echo JText::_('TABS_NAVIGATION_OPTION_LEFT'); ?></label>
					<input type="radio" name="navigation" value="nav-right" id="nav_right" /><label for="nav_right"><?php echo JText::_('TABS_NAVIGATION_OPTION_RIGHT'); ?></label>
				</div>
			</div>
			<div class="option <?php echo $tooltip_class; ?>" title="<?php echo JText::_("TABS_THEME_TOOLTIP"); ?>">
				<label><?php echo JText::_('TABS_THEME_LABEL'); ?></label>
				<select name="theme" id="theme" size="1">
					<?php foreach ($themes as $theme) : ?>
						<option value="<?php echo $theme; ?>"><?php echo $theme; ?></option>  
					<?php endforeach; ?>
				</select>
			</div>
			<div class="option">
				<label for="tab1-title">#1 <?php echo JText::_('TABS_TAB_TITLE_LABEL'); ?></label>
				<input type="text" id="tab1-title" name="tab-title" class="input-xlarge" size="40" />
			</div>			
			<div class="option">
				<label for="tab1-content" style="margin-top:0px;">#1 <?php echo JText::_('TABS_TAB_CONTENT_LABEL'); ?></label>
				<textarea id="tab1-content" name="tab-content" cols="35" rows="8" class="input-xlarge" /></textarea>
			</div>
			<div id="appended-tabs"></div>
			<div style="text-align:center;">
				<button type="button" id="append-tab" class="jbutton"><?php echo JText::_('TABS_ADD_BUTTON'); ?></button>
			</div>
			<div id="jqueryui-tabs-error" style="display:none; color: #FF0000; text-align:center; margin-top:10px;"></div>
			<div style="text-align:center; margin-top:10px;">
				<button type="button" id="jqueryui-tabs-insert" class="jbutton"><?php  echo JText::_('INSERT_BUTTON'); ?></button>
			</div>
		</form>	
	</div>	
	<h3><button type="button" data-toggle="form-reset" data-target="#showhide-form" id="showhide-form-reset" class="jbutton" style="float:right"><?php echo JText::_('RESET_BUTTON'); ?></button> <?php echo JText::_('SHOWHIDE_HEADING'); ?></h3>
	<div style="overflow: visible;">
		<form id="showhide-form" style="margin:15px 0;">
			<div style="text-align:center; margin-top:10px;">
				<div class="jbutton-set">
					<input type="radio" name="showhide-controller" value="SHOW" id="show" checked="checked" /><label for="show"><?php echo JText::_('SHOW'); ?></label>				
					<input type="radio" name="showhide-controller" value="HIDE" id="hide" /><label for="hide"><?php echo JText::_('HIDE'); ?></label>
				</div>
				<textarea class="input-xlarge" name="showhide-content" id="showhide-content" cols="35" rows="10" style="margin:5px 0 10px;" placeholder="<?php echo JText::_('ANY_HTML_CODE'); ?>" /><?php echo JText::_('THIS_CONTENT'); ?></textarea>
				<div class="jbutton-set">  
					<input type="radio" name="iffor-controller" value="IF" id="if-button" /><label for="if-button"><?php echo JText::_('IF'); ?></label>				
					<input type="radio" name="iffor-controller" value="FOR" id="for-button" /><label for="for-button"><?php echo JText::_('FOR'); ?></label>
				</div>
			</div>
			<div id="if-container" style="display:none;">
				<div class="option">
					<label><?php echo JText::_('PROFILE'); ?></label>
					<div class="jbutton-set"> 
						<input type="radio" name="ownersvisitors-controller" value="" id="if-owners" checked="checked"/><label for="if-owners"><?php echo JText::_("OWNERS"); ?></label>
						<input type="radio" name="ownersvisitors-controller" value=" visitor's " id="if-visitors" /><label for="if-visitors"><?php echo JText::_("VISITORS"); ?></label>
					</div>
				</div>
				<div class="option">
					<label for="if-field"><?php echo JText::_('FIELD'); ?></label>
					<select id="if-field" size="1">
						<option value="">- <?php echo JText::_('SELECT'); ?> -</option>   
						<?php foreach($fields AS $field) : ?>
							<option value="<?php echo $field->name; ?>"><?php echo JText::_(getLangDefinition($field->title)); ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="option <?php echo $tooltip_class; ?>" title="<?php echo JText::_('CONTAINS_INPUT_TOOLTIP'); ?>">
					<label for="contains"><?php echo JText::_('CONTAINS'); ?></label>
					<input type="text" id="contains" name="contains" size="40" /> 
				</div>       
			</div>
			<div id="for-container" style="display:none">
				<div class="option">
					<label for="for-access_level"><?php echo JText::_('ACCESS_LEVEL'); ?></label>
					<select name="access_level" id="for-access_level" size="1">
						<option value="">- <?php echo JText::_('SELECT'); ?> -</option>   
						<?php foreach($access_levels AS $access_level) : ?>
							<option value="<?php echo $access_level; ?>"><?php echo JText::_( $access_level ); ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="option">
					<label for="for-user_group"><?php echo JText::_('USER_GROUP'); ?></label>
					<select name="user_group" id="for-user_group" size="3" multiple="multiple">
						<?php foreach($user_groups AS $user_group) : ?>
							<option value="<?php echo $user_group; ?>"><?php echo JText::_($user_group ); ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="option">
					<label for="for-profile_type"><?php echo JText::_('PROFILE_TYPE'); ?></label>
					<select name="profile_type" id="for-profile_type" <?php echo (count($profile_types) >= 3) ? 'size="3" multiple="multiple"' : 'size="1"'; ?>>
						<?php if(count($profile_types) < 3) : ?>
							<option value="">- <?php echo JText::_('SELECT'); ?> -</option>  
						<?php endif; ?>
						<?php foreach($profile_types AS $profile_type) : ?>
							<option value="<?php echo $profile_type->alias; ?>"><?php echo JText::_($profile_type->title); ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="option">
					<label><?php echo JText::_('PROFILE'); ?></label>
					<div class="jbutton-set">  
						<input type="radio" name="ownervisitor-controller" value="profile_owner" id="for-owner" /><label for="for-owner"><?php echo JText::_('OWNERSHIP'); ?></label>				
						<input type="radio" name="ownervisitor-controller" value="profile_visitor" id="for-visitor" /><label for="for-visitor"><?php echo JText::_('NOT_OWNERSHIP'); ?></label>
					</div>
				</div>
			</div>
			<div id="showhide-error" style="display:none; color: #FF0000; text-align:center;"></div>
			<div style="text-align:center; margin-top:10px;">
				<button type="button" class="jbutton" id="showhide-insert"><?php  echo JText::_('INSERT_BUTTON'); ?></button>
			</div>
		</form>
	</div>
	<h3><?php echo JText::_('MODULE_HEADING'); ?></h3>
	<div>
		<div style="max-height:500px; overflow:auto;">
			<table width="100%" cellpadding="0" cellspacing="0" border="0" class="items">
				<thead>
					<tr>
						<th><?php echo JText::_('MODULE_TITLE_HEADING'); ?></td>
						<th style="padding-left:7px;"><?php echo JText::_('MODULE_POSITION_HEADING'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($modules_by_positions as $position => $modules) : ?>
						<?php foreach($modules as $key=>$module) : ?>
							<tr>
								<td class="output-insert <?php echo $tooltip_class; ?>" data-output="{loadmodule <?php echo $module->module; ?>}" title="<?php echo JText::_('INSERT_TOOLTIP'); ?>">
									<?php echo JText::_($module->title); ?>
								</td>
								<?php if($key == 0) : ?>
									<td style="border-left:1px solid #ddd; padding-left:7px;" rowspan="<?php echo count($modules); ?>" <?php if($position) echo 'class="output-insert '.$tooltip_class.'" data-output="{loadposition '.$position.'}" title="'.JText::_('INSERT_TOOLTIP').'"'; ?>>
										<?php echo $position; ?>
									</td>
								<?php endif; ?>
							</tr>
						<?php endforeach; ?>
					<?php endforeach; ?>
				</tbody>
			</table>	
		</div>
	</div>
	<h3><button type="button" data-toggle="form-reset" data-target="#modal-form" id="modal-form-reset" class="jbutton" style="float:right"><?php echo JText::_('RESET_BUTTON'); ?></button><?php echo JText::_('MODAL_HEADING'); ?></h3>
	<div>		
		<form id="modal-form" style="margin:15px 0;">
			<div class="option <?php echo $tooltip_class; ?>" title="<?php echo JText::_("LINK_URL_INPUT_TOOLTIP"); ?>">
				<label for="link_url"><?php echo JText::_('URL'); ?> *</label>
				<input type="text" id="link_url" name="link_url" size="40" />
			</div>
			<div class="option <?php echo $tooltip_class; ?>" title="<?php echo JText::_('LINK_TEXT_INPUT_TOOLTIP'); ?>">
				<label for="link_text"><?php echo JText::_('TEXT'); ?> * </label>
				<input type="text" id="link_text" name="link_text" size="40" />
			</div>
			<div class="option <?php echo $tooltip_class; ?>" title="<?php echo JText::_("LINK_TITLE_INPUT_TOOLTIP"); ?>">
				<label for="link_title"><?php echo JText::_('TITLE'); ?></label>
				<input type="text" id="link_title" name="link_title" size="40" />
			</div>
			<div class="option <?php echo $tooltip_class; ?>" title="<?php echo JText::_('MODAL_WIDTH_HEIGHT_INPUTS_TOOLTIP'); ?>">
				<label for="modal_width"><?php echo JText::_('WINDOW'); ?></label>
				<div style="display:inline-block;">
					<label for="modal_width" style="display:inline; vertical-align:5px"><?php echo JText::_('WIDTH'); ?>:</label>
					<input type="text" id="modal_width" name="modal_width" size="5" class="input-mini" />
					<span style="vertical-align:5px" ><?php echo JText::_('PX'); ?> </span>
					<label for="modal_height" style="display:inline; vertical-align:5px; margin-left:10px;"><?php echo JText::_('HEIGHT'); ?>:</label>
					<input type="text" id="modal_height" name="modal_height" size="5" class="input-mini" />
					<span style="vertical-align:5px" ><?php echo JText::_('PX'); ?> </span>
				</div>
			</div>
			<div style="text-align:center;">
				<button type="button" id="modal-insert" class="jbutton"><?php echo JText::_('INSERT_BUTTON'); ?></button>
			</div>
		</form>
	</div>
	<h3><button type="button" data-toggle="form-reset" data-target="#tooltip-form" id="tooltip-form-reset" class="jbutton" style="float:right"><?php echo JText::_('RESET_BUTTON'); ?></button><?php echo JText::_('TOOLTIP_HEADING'); ?></h3>
	<div>
		<form id="tooltip-form" style="margin:15px 0;">
			<div class="option <?php echo $tooltip_class; ?>" title="<?php echo JText::_('TOOLTIP_CONTENT_INPUT_TOOLTIP'); ?>">
				<label for="tooltip_content"><?php echo JText::_('TOOLTIP_CONTENT'); ?> *</label>
				<input type="text" id="tooltip_content" name="tooltip_content" size="40" />
			</div>
			<div class="option <?php echo $tooltip_class; ?>" title="<?php echo JText::_('TOOLTIP_TITLE_INPUT_TOOLTIP'); ?>">
				<label for="tooltip_title"><?php echo JText::_('TOOLTIP_TITLE'); ?></label>
				<input type="text" id="tooltip_title" name="tooltip_title" size="40" />
			</div>
			<div class="option <?php echo $tooltip_class; ?>" title="<?php echo JText::_("IMAGE_FOR_TOOLTIP_INPUT_TOOLTIP"); ?>">
				<label for="image_for_tooltip"><?php echo JText::_('IMAGE'); ?></label>
				<input type="text" id="image_for_tooltip" name="image_for_tooltip" size="40" />
			</div>
			<div class="option <?php echo $tooltip_class; ?>" title="<?php echo JText::_('TEXT_FOR_TOOLTIP_INPUT_TOOLTIP'); ?>">
				<label for="text_for_tooltip"><?php echo JText::_('TEXT'); ?></label>
				<input type="text" id="text_for_tooltip" name="text_for_tooltip" size="40" />
			</div>
			<div class="option <?php echo $tooltip_class; ?>" title="<?php echo JText::_('URL_FOR_TOOLTIP_INPUT_TOOLTIP'); ?>">
				<label for="text_for_tooltip"><?php echo JText::_('URL'); ?></label>
				<input type="text" id="url_for_tooltip" name="url_for_tooltip" size="40" />
			</div>
			<div style="text-align:center;">
				<button id="tooltip-insert" class="jbutton"><?php echo JText::_('INSERT_BUTTON'); ?></button>
			</div>
		</form>
	</div>
</div>