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
defined( '_JEXEC' ) or die( 'Restricted access' ); ?>

<?php if ($this->params->get('show_page_heading', 1)) : ?>
	<h1><?php echo $this->escape($this->params->get('page_title')); ?></h1>
<?php endif; ?>		
<?php if ($this->display_type == 'link') : ?>
	<ul class="cbpp-profiletype-link">
		<?php foreach ($this->profiletypes as $profiletype) : ?>
			<li>
				<h2>
					<a href="<?php echo JRoute::_('index.php?option=com_comprofiler&task=registers&profiletype='.$profiletype->alias.'&Itemid='.JRequest::getVar('Itemid')); ?>"><?php echo JText::_($profiletype->title); ?></a>
				</h2>
				<p>
					<?php echo JText::_($profiletype->description); ?>
				</p>
			</li>
		<?php endforeach; ?>
	</ul>
<?php else : ?>	
	<form id="cbpp-profiletypes-select-form" class="cbpp-profiletype-<?php echo $this->display_type; ?>" method="GET" action="index.php">
		<input type="hidden" name="option" value="com_comprofiler">
		<input type="hidden" name="task" value="registers">
		<input type="hidden" name="Itemid" value="<?php echo JRequest::getVar('Itemid'); ?>">
		<?php if ($this->display_type == 'select') : ?>
			<label for="cbpp-profiletype-select"><?php echo JText::_("PROFILE_TYPE"); ?></label>
			<select name="profiletype" id="cbpp-profiletype-select">
				<option value="">- <?php echo JText::_("SELECT"); ?> -</option>
				<?php foreach($this->profiletypes as $profiletype) : ?>
					<option value="<?php echo $profiletype->alias; ?>"><?php echo JText::_($profiletype->title); ?></option>
				<?php endforeach; ?>
			</select>
		<?php elseif ($this->display_type == 'radio') : ?>
			<?php foreach ($this->profiletypes as $profiletype) : ?>
				<div class="radio">
					<label>
						<input type="radio" name="profiletype" value="<?php echo $profiletype->alias; ?>">
						<?php echo JText::_($profiletype->title); ?>
					</label>
					<p>
						<?php echo JText::_($profiletype->description); ?>
					</p>
				</div>
			<?php endforeach; ?>
		<?php endif; ?>
		<button type="submit" id="submit" class="button btn btn-primary"><?php echo JText::_('NEXT'); ?></button>
	</form>	
<?php endif; ?>
<?php if ($this->params->get('show_link', 1)) : ?>
	<div style="margin:30px 0 10px; clear:both; text-align:center; font-size:75%"><a href="http://www.joomduck.com/extensions/community-builder-profile-pro" target="_blank" title="Community Builder Profile Pro component for Joomla!">Powered by CB Profile Pro</a></div>
<?php endif; ?>