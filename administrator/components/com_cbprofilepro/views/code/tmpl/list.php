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

$document = JFactory::getDocument();
$document->addStyleSheet(JURI::root().'administrator/components/com_cbprofilepro/includes/css/jquery-ui.min.css');

if(version_compare(JVERSION,'3.0.0','ge')) {
	JHtml::_('bootstrap.tooltip');
} else {
	JHtml::_('behavior.tooltip');
}

ob_start(); ?>
	<style type="text/css">
		body {
			margin:0px;
			padding:0px;
		}
		a:link, a:visited {
			color: #1c94c4;
		}
		<?php if(version_compare(JVERSION,'3.0.0','ge')) : ?>
			#system-message-container {
				margin:0px 10px;
			}
			.alert {
				margin:10px 0;
			}
		<?php endif; ?>
		ul.items {
			margin:0px;
			padding:0px;
			list-style:none;
		}
		ul.items li  {
			text-align:left; 
			clear:both;
			border-top:1px solid #ddd;
		}
		ul.items li:first-child {
			border-top:0px;
		}
		.output-insert:hover   {
			background-color:#f5f5f5;
			cursor:pointer;
		}
	</style>
	<?php if(version_compare(JVERSION,'3.0.0','<'))	: ?>
		<script type="text/javascript" src="<?php echo JURI::root(); ?>components/com_cbprofilepro/includes/js/jquery.min.js"></script>
	<?php endif; ?>
	<script type="text/javascript" src="<?php echo JURI::root(); ?>administrator/components/com_cbprofilepro/includes/js/jquery-ui.min.js"></script>
	<script type="text/javascript">
		jQuery.noConflict();   
		jQuery(document).ready(function($){ 
		
			$(".output-insert").click(function(e) {	
				if($(e.toElement).parents("div.jbutton-set").hasClass('jbutton-set')) return;
				var output = $(this).attr("data-output"); 
				return window.parent.insertOutput(output);
			});
		
			$(".jbutton-set").buttonset();
	
		});
	</script>	
<?php $head_tags = ob_get_clean(); 
$document->addCustomTag($head_tags); 

$db = JFactory::getDBO();
// codes
$db->setQuery("SELECT * FROM #__cbppmagicwindow_code ORDER BY id DESC");
$codes = $db->loadObjectList(); 
?>
<?php if(count($codes)) : ?>
	<div style="max-height:400px; overflow:auto;">
		<ul class="items">
			<?php foreach($codes as $code) : ?>
				<li class="output-insert" data-output="{code <?php echo $code->title; ?>}">
					<div class="jbutton-set" style="float:right; margin:7px;"> 
						<a href="javascript:void(0);" onclick="window.parent.SqueezeBox.open('index.php?option=com_cbprofilepro&view=code&layout=item&cid=<?php echo $code->id; ?>&tmpl=component', { handler: 'iframe' });" ><?php echo JText::_('CODE_EDIT_BUTTON'); ?></a>
						<a href="index.php?option=com_cbprofilepro&view=code&layout=item&task=delete&cid=<?php echo $code->id; ?>&tmpl=component"><?php echo JText::_('CODE_DELETE_BUTTON'); ?></a>
					</div>
					<div style="padding:12px 7px;" class="<?php echo (version_compare(JVERSION,'3.0.0','ge')) ? 'hasTooltip' : 'hasTip'; ?>" title="<?php echo JText::_('INSERT_TOOLTIP'); ?>"><?php echo $code->title; ?></div>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
<?php else : ?>
	<?php JFactory::getApplication()->enqueueMessage(JText::_('CODE_NO_ITEMS_NOTICE'), 'notice'); ?>
<?php endif; ?> 