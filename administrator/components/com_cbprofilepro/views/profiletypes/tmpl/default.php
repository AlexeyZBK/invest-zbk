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
defined('_JEXEC') or die;

if(version_compare(JVERSION,'3.0.0','ge')) { 
	JHtml::_('behavior.tabstate');
	JHtml::_('bootstrap.tooltip');
	JHtml::_('behavior.multiselect');
	JHtml::_('formbehavior.chosen', 'select');
} else {
	JHtml::_('behavior.tooltip');
	JHtml::_('script','system/multiselect.js',false,true);
}

$user		= JFactory::getUser();
$userId		= $user->get('id');
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$saveOrder	= $listOrder == 'a.ordering';

$j25_centered = (version_compare(JVERSION,'3.0.0','ge')) ? '' : 'center';
?>

<?php if(version_compare(JVERSION,'3.0.0','ge')) : ?>

	<style>
		.fltlft {
			float: left;
		}
		.fltrt {
			float: right;
		}
		fieldset label, fieldset span.faux-label {
			float: left;
			clear: left;
			display: block;
			margin: 10px 5px 10px 0;
		}
		fieldset input, fieldset textarea, fieldset select, fieldset img, fieldset button {
			/*float: left;
			width: auto;
			margin: 5px 5px 5px 0;*/
		}
		label.filter-search-lbl {
			margin: 5px 5px 0 0;
		}
		input#filter_search {
			margin: 0;
		}
		.clr {
			clear: both;
			overflow: hidden;
			height: 0;
		}
		table.adminlist td.order span {
			float: left;
			width: 25px;
			text-align: center;
			height: 25px;
		}
		input.text-area-order {
			text-align: center;
			margin: 0 5px;
			width: 20px;
		}
		table.adminlist {
			margin-top:10px;
		}
	</style>
	
<?php endif; ?>

<form action="<?php echo JRoute::_('index.php?option=com_cbprofilepro&view=profiletypes');?>" method="post" name="adminForm" id="adminForm">
	<fieldset id="filter-bar">
		<div class="filter-search fltlft">
			<label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_CBPROFILEPRO_FILTER_SEARCH_DESC'); ?>" />
			<button type="submit" class="btn"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
			<button type="button" class="btn" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
		</div>
		<div class="filter-select fltrt">
		 <?php $config = array();
				$config['archived'] = 0;
				$config['trash'] = 0;	?>
			<select name="filter_published" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED');?></option>
				<?php 	echo JHtml::_('select.options', JHtml::_('jgrid.publishedOptions', $config), 'value', 'text', $this->state->get('filter.published'), true);?>
			</select>

			<select name="filter_author_id" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('JOPTION_SELECT_AUTHOR');?></option>
				<?php echo JHtml::_('select.options', $this->authors, 'value', 'text', $this->state->get('filter.author_id'));?>
			</select>

			<select name="filter_language" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('JOPTION_SELECT_LANGUAGE');?></option>
				<?php echo JHtml::_('select.options', JHtml::_('contentlanguage.existing', true, true), 'value', 'text', $this->state->get('filter.language'));?>
			</select>
		</div>
	</fieldset>
	<div class="clr"> </div>

	<table class="adminlist table table-striped">
		<thead>
			<tr>
				<th width="1%">
					<?php if(version_compare(JVERSION,'3.0.0','ge')) : ?>
						<?php echo JHtml::_('grid.checkall'); ?>
					<?php else : ?>
						<input type="checkbox" name="checkall-toggle" value="" onclick="checkAll(this)" />
					<?php endif; ?>
				</th>
				<th>
					<?php echo JHtml::_('grid.sort', 'JGLOBAL_TITLE', 'a.title', $listDirn, $listOrder); ?>
				</th>
				<th width="5%">
					<?php echo JHtml::_('grid.sort', 'COM_CBPROFILEPRO_HEADING_DEFAULT', 'a.default', $listDirn, $listOrder); ?>
				</th>
				<th width="5%">
					<?php echo JHtml::_('grid.sort', 'JSTATUS', 'a.state', $listDirn, $listOrder); ?>
				</th>
				<th width="10%">
					<?php echo JHtml::_('grid.sort',  'JGRID_HEADING_ORDERING', 'a.ordering', $listDirn, $listOrder); ?>
					<?php if ($saveOrder) :?>
						<?php echo JHtml::_('grid.order',  $this->items, 'filesave.png', 'profiletypes.saveorder'); ?>
					<?php endif; ?>
				</th>
				<th width="10%" class="nowrap hidden-phone">
					<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_CREATED_BY', 'a.created_by', $listDirn, $listOrder); ?>
				</th>
				<th width="10%" class="nowrap hidden-phone">
					<?php echo JHtml::_('grid.sort', 'JDATE', 'a.created', $listDirn, $listOrder); ?>
				</th>
				<th width="5%" class="nowrap hidden-phone">
					<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_LANGUAGE', 'language', $listDirn, $listOrder); ?>
				</th>
				<th width="1%" class="nowrap hidden-phone">
					<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="15">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<?php foreach ($this->items as $i => $item) :
			$item->max_ordering = 0; //??
			$ordering	= ($listOrder == 'a.ordering');
			$canCreate	= $user->authorise('core.create',		'com_cbprofilepro');
			$canEdit	= $user->authorise('core.edit',			'com_cbprofilepro');
			$canCheckin	= $user->authorise('core.manage',		'com_checkin') || $item->checked_out == $userId || $item->checked_out == 0;
			$canEditOwn	= $user->authorise('core.edit.own',		'com_cbprofilepro') && $item->created_by == $userId;
			$canChange	= $user->authorise('core.edit.state',	'com_cbprofilepro') && $canCheckin;
			?>
			<tr class="row<?php echo $i % 2; ?>">
				<td class="center">
					<?php echo JHtml::_('grid.id', $i, $item->id); ?>
				</td>
				<td>
					<?php if ($item->checked_out) : ?>
						<?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'profiletypes.', $canCheckin); ?>
					<?php endif; ?>
					<?php if ($canEdit || $canEditOwn) : ?>
						<a href="<?php echo JRoute::_('index.php?option=com_cbprofilepro&task=profiletype.edit&id='.$item->id);?>">
							<?php echo $this->escape($item->title); ?></a>
					<?php else : ?>
						<?php echo $this->escape($item->title); ?>
					<?php endif; ?>
					<p class="smallsub small"><?php echo JText::sprintf('COM_CBPROFILEPRO_LIST_ALIAS', $this->escape($item->alias)); ?></p>
				</td>
				<td class="<?php echo $j25_centered; ?>">
					<?php echo JHtml::_('jgrid.isdefault', $item->default, $i, 'profiletypes.', (!$item->default) && $canChange);?>
				</td>
				<td class="<?php echo $j25_centered; ?>">
					<?php echo JHtml::_('jgrid.published', $item->state, $i, 'profiletypes.', $canChange, 'cb'); ?>
				</td>
				<td class="order">
					<?php if ($canChange) : ?>
						<?php if ($saveOrder) :?>
							<?php if ($listDirn == 'asc') : ?>
								<span><?php echo $this->pagination->orderUpIcon($i, 1, 'profiletypes.orderup', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>
								<span><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, 1, 'profiletypes.orderdown', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
							<?php elseif ($listDirn == 'desc') : ?>
								<span><?php echo $this->pagination->orderUpIcon($i, 1, 'profiletypes.orderdown', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>
								<span><?php echo $this->pagination->orderDownIcon($i, 1, 'profiletypes.orderup', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
							<?php endif; ?>
						<?php endif; ?>
						<?php $disabled = $saveOrder ?  '' : 'disabled="disabled"'; ?>
						<input type="text" name="order[]" size="5" value="<?php echo $item->ordering;?>" <?php echo $disabled ?> class="text-area-order" />
					<?php else : ?>
						<?php echo $item->ordering; ?>
					<?php endif; ?>
				</td>
				<td class="<?php echo $j25_centered; ?> hidden-phone">
					<a href="<?php echo JRoute::_('index.php?option=com_users&task=user.edit&id='.(int) $item->created_by); ?>" title="<?php echo JText::_('JAUTHOR'); ?>">
						<?php echo $this->escape($item->author_name); ?>
					</a>
				</td>
				<td class="<?php echo $j25_centered; ?> nowrap hidden-phone">
					<?php echo JHtml::_('date',$item->created, JText::_('DATE_FORMAT_LC4')); ?>
				</td>
				<td class="<?php echo $j25_centered; ?> hidden-phone">
					<?php if ($item->language=='*'):?>
						<?php echo JText::alt('JALL','language'); ?>
					<?php else:?>
						<?php echo $item->language_title ? $this->escape($item->language_title) : JText::_('JUNDEFINED'); ?>
					<?php endif;?>
				</td>
				<td class="center hidden-phone">
					<?php echo (int) $item->id; ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
	<?php echo JHtml::_('form.token'); ?>
</form>