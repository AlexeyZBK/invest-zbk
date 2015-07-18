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

// No direct access.
defined('_JEXEC') or die;

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');

// Load the tooltip behavior.
if(version_compare(JVERSION,'3.0.0','<')) {
	JHtml::_('behavior.tooltip');
}
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
if(version_compare(JVERSION,'3.0.0','ge')) {
	JHtml::_('behavior.tabstate');
	JHtml::_('formbehavior.chosen', 'select');
}

$width_100_class = (version_compare(JVERSION,'3.0.0','ge')) ? ''  : 'width-100';
$form_horizontal_class = (version_compare(JVERSION,'3.0.0','ge')) ? 'form-horizontal'  : '';
$options_container_class = (version_compare(JVERSION,'3.0.0','ge')) ? 'row-fluid form-horizontal-desktop' : '';
$span6_class = (version_compare(JVERSION,'3.0.0','ge')) ? 'span6' : 'fltlft width-50';
$row_fluid = (version_compare(JVERSION,'3.0.0','ge')) ? 'row-fluid' : '';
$clearfix_class = (version_compare(JVERSION,'3.0.0','ge')) ? 'clearfix' : 'clr';
$float_left_class = (version_compare(JVERSION,'3.0.0','ge')) ? 'pull-left' : 'fltlft';

$user = JFactory::getUser();
$user_editor = $user->getParam('editor');
if($user_editor) {
	$active_editor = $user_editor;
} else {
	$config = JFactory::getConfig();
	$active_editor = (version_compare(JVERSION,'3.0.0','ge')) ? $config->get('editor') : $config->getValue( 'editor' );
}

$document = JFactory::getDocument();
ob_start(); ?>
	<style>
		<?php if(version_compare(JVERSION,'3.0.0','<')) : ?>
		
			label {
				display: block;
				margin-bottom: 5px;
			} 

			#jform_profile-lbl, #jform_registration-lbl, #jform_profile_edit-lbl {
				font-size:16px;
			}
			#jform_registration-lbl, #jform_profile_edit-lbl {
				margin-top:25px;
			}
		<?php endif; ?>
	
		#show-more-button, #hide-more-button {
			display: inline-block;
			color: #08c;
			border-bottom: 1px dashed;
			cursor: pointer;
			margin: 0px 0px 5px;
		}
		
		#show-more-button:hover, #hide-more-button:hover {
			color: #005580;
		}
		
		.width-238 {
			width: 238px;
		}
		
		#cbppWorkspacesTabs {
			margin-top:15px;
		}
		
		#cbppWorkspacesTabs.nav-tabs > li > a  {
			font-size: 15px;
			padding: 9px 15px;
		}
	</style>
<?php $head_css = ob_get_clean();
$document->addCustomTag($head_css);

if(version_compare(JVERSION,'3.0.0','<')) {
	$head_js = '<script type="text/javascript" src="'.JURI::root().'components/com_cbprofilepro/includes/js/jquery.min.js"></script>';
	$document->addCustomTag($head_js);
}

ob_start(); ?>
	<script type="text/javascript">
		jQuery.noConflict();   
		jQuery(document).ready(function($){ 
		
			$("#show-more-button").click(function () { 
				$(this).hide();
				$("#more-container,#hide-more-button").show();
			});
			$("#hide-more-button").click(function () {
				$(this).hide();
				$("#more-container").hide();
				$("#show-more-button").show();
			});
		});
	</script>
<?php $head_js = ob_get_clean(); 
$document->addCustomTag($head_js); 

$workspaces = array('profile' => array('title' => 'COM_CBPROFILEPRO_FIELD_PROFILE_LABEL'), 'profile_edit' => array('title' => 'COM_CBPROFILEPRO_FIELD_PROFILE_EDIT_LABEL'), 'registration' => array('title' => 'COM_CBPROFILEPRO_FIELD_REGISTRATION_LABEL'));
$cb_installed = (file_exists(JPATH_ADMINISTRATOR.'/components/com_comprofiler/plugin.foundation.php')) ? 1 : 0;
?>

<script type="text/javascript">

	Joomla.submitbutton = function(task) {
	
		if (task == 'profiletype.cancel' || document.formvalidator.isValid(document.id('item-form'))) {
			
			<?php echo $this->form->getField('profile')->save(); ?>
			<?php echo $this->form->getField('profile_edit')->save(); ?>
			<?php echo $this->form->getField('registration')->save(); ?>
			
			Joomla.submitform(task, document.getElementById('item-form'));
		} else {
			alert('<?php echo $this->escape(JText::_("JGLOBAL_VALIDATION_FORM_FAILED")); ?>');
		}
	}

</script>		

<form action="<?php echo JRoute::_('index.php?option=com_cbprofilepro&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="item-form" class="form-validate" style="margin:10px 0 10px;">
	<div class="<?php echo $width_100_class; ?>">
		<fieldset class="adminform">
			<?php if(version_compare(JVERSION,'3.0.0','<')) : ?>
				<legend><?php echo empty($this->item->id) ? JText::_('COM_CBPROFILEPRO_NEW_PROFILETYPE') : JText::sprintf('COM_CBPROFILEPRO_EDIT_PROFILETYPE', $this->item->id); ?><?php // echo JText::_('Basic Options'); ?></legend>
			<?php endif; ?>
			<div class="<?php echo $form_horizontal_class; ?>">
				<div class="<?php echo $options_container_class; ?>">
					<div class="<?php echo $span6_class; ?>">    
						<div class="control-group">
							<div class="control-label">
								<?php echo $this->form->getLabel('title'); ?>
							</div>
							<div class="controls">
								<?php echo $this->form->getInput('title'); ?>
							</div>
						</div>
						<div class="control-group">
							<div class="control-label">
								<?php echo $this->form->getLabel('alias'); ?>
							</div>
							<div class="controls">
								<?php echo $this->form->getInput('alias'); ?>
							</div>
						</div>
						<div class="control-group">
							<div class="control-label">
								<?php echo $this->form->getLabel('description'); ?>
							</div>
							<div class="controls">
								<?php echo $this->form->getInput('description'); ?>
							</div>
						</div>
					</div>
					<div class="<?php echo $span6_class; ?>">   
						<div class="control-group">
							<div class="control-label">
								<?php echo $this->form->getLabel('state'); ?>
							</div>
							<div class="controls">
								<?php echo $this->form->getInput('state'); ?>
							</div>
						</div>
						<div class="control-group">
							<div class="control-label">
								<?php echo $this->form->getLabel('default'); ?>
							</div>
							<div class="controls">
								<?php echo $this->form->getInput('default'); ?>
							</div>
						</div>
						<div class="control-group">
							<div class="control-label">
								<?php echo $this->form->getLabel('cb_template'); ?>
							</div>
							<div class="controls">
								<?php echo $this->form->getInput('cb_template'); ?>
							</div>
						</div>
					</div>
				</div>
				<?php if(version_compare(JVERSION,'3.0.0','<')) : ?>
					<div class="clr"></div>
				<?php endif; ?>
				<span id="show-more-button">Show more</span><span id="hide-more-button" style="display:none;">Hide</span>
				<div class="<?php echo $options_container_class; ?>" style="display:none;" id="more-container">
					<div class="<?php echo $span6_class; ?>">    
						<div class="control-group">
							<div class="control-label">
								<?php echo $this->form->getLabel('created_by'); ?>
							</div>
							<div class="controls">
								<?php echo $this->form->getInput('created_by'); ?>
							</div>
						</div>
						<div class="control-group">
							<div class="control-label">
								<?php echo $this->form->getLabel('created'); ?>
							</div>
							<div class="controls">
								<?php echo $this->form->getInput('created'); ?>
							</div>
						</div>
						<?php if ($this->item->modified_by) : ?>
							<div class="control-group">
								<div class="control-label">
									<?php echo $this->form->getLabel('modified_by'); ?>
								</div>
								<div class="controls">
									<?php echo $this->form->getInput('modified_by'); ?>
								</div>
							</div>
							<div class="control-group">
								<div class="control-label">
									<?php echo $this->form->getLabel('modified'); ?>
								</div>
								<div class="controls">
									<?php echo $this->form->getInput('modified'); ?>
								</div>
							</div>
						<?php endif; ?>
					</div>
					<div class="<?php echo $span6_class; ?>">   
						<div class="control-group">
							<div class="control-label">
								<?php echo $this->form->getLabel('language'); ?>
							</div>
							<div class="controls">
								<?php echo $this->form->getInput('language'); ?>
							</div>
						</div>
						<?php if ($this->item->version) : ?>
							<div class="control-group">
								<div class="control-label">
									<?php echo $this->form->getLabel('version'); ?>
								</div>
								<div class="controls">
									<?php echo $this->form->getInput('version'); ?>
								</div>
							</div>
						<?php endif; ?>
						<div class="control-group">
							<div class="control-label">
								<?php echo $this->form->getLabel('id'); ?>
							</div>
							<div class="controls">
								<?php echo $this->form->getInput('id'); ?>
							</div>
						</div>
					</div>
				</div>	
				<?php if(version_compare(JVERSION,'3.0.0','<')) : ?>
					<div class="clr"></div>
				<?php endif; ?>
			</div>	
			<?php if(version_compare(JVERSION,'3.0.0','ge')) : ?>
			
				<?php echo JHtml::_('bootstrap.startTabSet', 'cbppWorkspaces', array('active' => 'profile')); ?>
			
				<?php foreach($workspaces as $view => $workspace) : ?>
					<?php
					$workspaces[$view]['sidebar_margin_left'] = (version_compare(JVERSION,'3.0.0','ge')) ? 0 : 10;
					$editor_container_class = (version_compare(JVERSION,'3.0.0','ge')) ? 'span8' : 'fltlft width-70';
					$toolbar_placeholder_class = (version_compare(JVERSION,'3.0.0','ge')) ? 'span4' : 'fltlft width-238';
	
					if($active_editor == 'tinymce') {
						if(isset($_COOKIE['TinyMCE_jform_'.$view.'_size'])) {
							$workspaces[$view]['sidebar_margin_left'] = 10;
							$editor_container_class = (version_compare(JVERSION,'3.0.0','ge')) ? 'pull-left' : 'fltlft';
							$toolbar_placeholder_class = (version_compare(JVERSION,'3.0.0','ge')) ? 'pull-left width-238' : 'fltlft width-238';
						} else {
							$workspaces[$view]['tinymce_width_unset'] = 1;
						}
					}
					?>
					<?php echo JHtml::_('bootstrap.addTab', 'cbppWorkspaces', str_replace('profile_edit', 'profil_edit', $view), JText::_($workspace['title'], true)); ?>
						<div class="<?php echo $row_fluid; ?>">
							<div id="<?php echo $view; ?>-editor-container" class="<?php echo $editor_container_class; ?>">
								<?php echo $this->form->getInput($view); ?>
								<div class="<?php echo $clearfix_class; ?>"></div>
							</div>
							<div id="<?php echo $view; ?>-toolbar-placeholder" class="<?php echo $toolbar_placeholder_class; ?>">
								<?php if(!$cb_installed) : ?>
									<p style="text-align:center; font-size:1.1em; color:red;"><?php echo JText::_('COMMUNITY_BUILDER_NOT_INSTALLED_ERROR'); ?></p>
								<?php endif; ?>
							</div>
						</div> 
					<?php echo JHtml::_('bootstrap.endTab'); ?>	
				<?php endforeach; ?>

				<?php echo JHtml::_('bootstrap.endTabSet'); ?>
				
			<?php else :?> 
				<?php $view = 'profile'; 
				$workspaces[$view]['sidebar_margin_left'] = (version_compare(JVERSION,'3.0.0','ge')) ? 0 : 10;
				$editor_container_class = (version_compare(JVERSION,'3.0.0','ge')) ? 'span8' : 'fltlft width-70';
				$toolbar_placeholder_class = (version_compare(JVERSION,'3.0.0','ge')) ? 'span4' : 'fltlft width-238';

				if($active_editor == 'tinymce') {
					if(isset($_COOKIE['TinyMCE_jform_profile_size']) || isset($_COOKIE['TinyMCE_jform_profile_edit_size']) || isset($_COOKIE['TinyMCE_jform_registration_size'])) {
						$workspaces[$view]['sidebar_margin_left'] = 10;
						$editor_container_class = (version_compare(JVERSION,'3.0.0','ge')) ? 'pull-left' : 'fltlft';
						$toolbar_placeholder_class = (version_compare(JVERSION,'3.0.0','ge')) ? 'pull-left width-238' : 'fltlft width-238';
					} else {
						$workspaces[$view]['tinymce_width_unset'] = 1;
					}
				}
				?>
				<div class="<?php echo $row_fluid; ?>" style="margin-top:10px;">
					<div id="<?php echo $view; ?>-editor-container" class="<?php echo $editor_container_class; ?>">
						<?php foreach($workspaces as $wview => $workspace) : ?>
						
							<?php echo $this->form->getLabel($wview); ?>
							<div class="clr"></div>
							<?php echo $this->form->getInput($wview); ?>
							<div class="clr"></div>
	
						<?php endforeach; ?>
					</div>
					<div id="<?php echo $view; ?>-toolbar-placeholder" class="<?php echo $toolbar_placeholder_class; ?>">
						<?php if(!$cb_installed) : ?>
							<p style="text-align:center; font-size:1.1em; color:red;"><?php echo JText::_('COMMUNITY_BUILDER_NOT_INSTALLED_ERROR'); ?></p>
						<?php endif; ?>
					</div>
				</div> 
				
			<?php endif; ?>
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="return" value="<?php echo (version_compare(JVERSION,'3.0.0','ge')) ? JFactory::getApplication()->input->get('return') : JRequest::getCmd('return'); ?>" />
			<?php echo JHtml::_('form.token'); ?>
		</fieldset>
	</div>
</form>
<?php if($cb_installed) : ?>
	<div id="cbpp-toolbar" style="overflow:auto; position:fixed; top:0px; left:-10000px;">
		<?php require_once(JPATH_ADMINISTRATOR.'/components/com_cbprofilepro/views/profiletype/tmpl/includes/accordion.php'); ?>
	</div>
<?php endif; ?>