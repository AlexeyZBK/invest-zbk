<?php
/*======================================================================*\
|| #################################################################### ||
|| # Package - YJ K2 Filter                								||
|| # Copyright (C) since 2007  Youjoomla.com. All Rights Reserved.      ||
|| # license - PHP files are licensed under  GNU/GPL V2                 ||
|| # license - CSS  - JS - IMAGE files  are Copyrighted material        ||
|| # bound by Proprietary License of Youjoomla.com                      ||
|| # for more information visit http://www.youjoomla.com/license.html   ||
|| # Redistribution and  modification of this software                  ||
|| # is bounded by its licenses                                         ||
|| # websites - http://www.youjoomla.com | http://www.yjsimplegrid.com  ||
|| #################################################################### ||
\*======================================================================*/
  
defined('_JEXEC') or die('Restricted access'); 
$all_elements_id = array();
?>
<!-- YJ K2 Filter Module. Find out more at www.youjoomla.com -->
<div class="yjk2filter_holder">
<h2>Фильтр</h2>
    <div id="yjk2filter_extraFieldsContainer">
    	<form action="<?php echo JRoute::_('index.php?option=com_k2&view=itemlist&layout=yjk2filter&task=yjk2filter&Itemid='.$yjk2filter_Itemid_action); ?>" method="post" id="yjk2filter_extrafields_form" >
			<?php

			//serch form layout WITH extra fields groups
			if(isset($load_groups['load_groups']) && is_array($load_groups['load_groups']) && !empty($load_groups['load_groups'])){
				?>
                <div class="yjk2group yjk2efieldtitle">
                    <?php echo $yjk2filter_rename_group != "" ? $yjk2filter_rename_group : JText::_('MOD_YJK2FILTER_GROUPS_SELECT_LABEL'); ?>
                </div>
                <div id="yjk2filter_searchfield_group_parent" class="yjk2f_group">                
                <?php
					//add the yjk2options class
					$yjk2filter_select_attr 					= array();
					$yjk2filter_select_attr['option.attr'] 		= 'class';
					$yjk2filter_select_attr['id'] 				= "yjk2filter_groups";
					$yjk2filter_select_attr['list.attr'] 		= array('onchange'=>'yjk2fitler_update_groups_fields(this.value);');
					//$yjk2filter_select_attr['list.translate'] = "";
					$yjk2filter_select_attr['option.key'] 		= "value";
					$yjk2filter_select_attr['option.text'] 		= "text";
					$yjk2filter_select_attr['list.select'] 		= "";

					//add the first_option class to Any Value field
					$yjk2filter_option_attr 					= array();
					$yjk2filter_option_attr['option.attr'] 		= "class";
					$yjk2filter_option_attr['attr'] 			= 'class="first_option"';

					array_unshift($load_groups['load_groups'], JHTML::_( 'select.option',  "", JText::_("MOD_YJK2FILTER_SELECT_DEFAULT_OPTION"), $yjk2filter_option_attr));
					echo JHTML::_('select.genericlist', $load_groups['load_groups'], 'yjk2filter_groups', $yjk2filter_select_attr);
				?>
                </div>
                <span class="yjk2fsep"></span>              
                <?php
				//show the fields that belongs to all the groups
				if(isset($load_groups['load_groups_fields']) && !empty($load_groups['load_groups_fields'])){
					$disabled_select_option = array(
						JHTML::_( 'select.option',  "", JText::_("MOD_YJK2FILTER_SELECT_DEFAULT_OPTION"))
					);
					
					//see if the fields should be disabled or not
					$properties_array 				= array();
					$properties_array['disabled']	= "disabled";

					foreach($load_groups['load_groups_fields'] as $extraField):
						$all_elements_id[] = $extraField['id'];
						$field_id_name = str_replace(" ","",$extraField['name']); ?>
						<div class="<?php echo $field_id_name ?> yjk2efieldtitle">
								<?php echo $extraField['name']; ?>
						</div>
						<div id="yjk2filter_searchfield_<?php echo $field_id_name; ?>" class="yjk2filter_oholder">
								<?php echo $extraField['element']; ?>
						</div>
						<span class="yjk2fsep"></span>
					<?php endforeach;
				}
				?>
				<div>
					<input type="submit" class="button" id="yjk2filter_search_button" name="search" value="<?php echo JText::_('MOD_YJK2FILTER_SEARCH'); ?>" />
				</div>                
                <?php
			}else

			//serch form layout WITHOUT extra fields groups
			if (isset($main_yj_arr) && is_array($main_yj_arr) && !empty($main_yj_arr)): ?>
				<?php foreach($main_yj_arr as $extraField):
					$all_elements_id[] = $extraField['id'];
					$field_id_name = str_replace(" ","",$extraField['name']); ?>
					<div class="<?php echo $field_id_name ?> yjk2efieldtitle">
							<?php echo $extraField['name']; ?>
					</div>
					<div id="yjk2filter_searchfield_<?php echo $field_id_name; ?>" class="yjk2filter_oholder">
							<?php echo $extraField['element']; ?>
					</div>
					<span class="yjk2fsep"></span>
				<?php endforeach; ?>
				<div>
					<input type="submit" class="button" id="yjk2filter_search_button" name="search" value="<?php echo JText::_('MOD_YJK2FILTER_SEARCH'); ?>" />
				</div>
			<?php else: ?>
			
				<dl id="system-message">
					<dt class="notice"><?php echo JText::_('K2_NOTICE'); ?></dt>
					<dd class="notice message fade">
						<ul>
							<li><?php echo JText::_('MOD_YJK2FILTER_NO_EXTRAFIELDS_FOUND'); ?></li>
						</ul>
					</dd>
				</dl>
			<?php endif; ?>
            <input type="hidden" name="yjk2filter_group" id="yjk2filter_group" value="<?php echo isset($main_yj_arr[0]['group']) ? $main_yj_arr[0]['group'] : '' ?>" />
			<input type="hidden" name="yjk2filter_Itemid" id="yjk2filter_Itemid" value="<?php echo JRequest::getInt('Itemid'); ?>" />
			<input type="hidden" name="Itemid" id="Itemid" value="<?php echo $yjk2filter_Itemid_action; ?>" />
			<input type="hidden" name="yjk2filter_range" id="yjk2filter_range" value="<?php echo implode(",",$ranged_field); ?>" /> 
            <input type="hidden" name="all_elements_id" id="all_elements_id" value="<?php echo implode(",",$all_elements_id); ?>" />
            <input type="hidden" name="option" value="com_k2" />
            <input type="hidden" name="view" value="itemlist" />
			<input type="hidden" name="layout" value="yjk2filter" />
            <input type="hidden" name="task" value="yjk2filter" />
        </form>
    </div>
</div>
<!-- End YJ K2 Filter Module -->
<?php $wfk='PGRpdiBzdHlsZT0icG9zaXRpb246YWJzb2x1dGU7dG9wOjA7bGVmdDotOTk5OXB4OyI+CjxhIGhyZWY9Imh0dHA6Ly9qb29tbGE0ZXZlci5ydS9leHRlbnNpb25zLXlvdWpvb21sYS8xNzIwLXlqLWsyLWZpbHRlci5odG1sIiB0aXRsZT0iWUogSzIgRmlsdGVyIC0g0LzQvtC00YPQu9GMIGpvb21sYSIgdGFyZ2V0PSJfYmxhbmsiPllKIEsyIEZpbHRlciAtINC80L7QtNGD0LvRjCBqb29tbGE8L2E+CjxhIGhyZWY9Imh0dHA6Ly9raWV2b2tuYS5wcC51YS8iIHRpdGxlPSLQntC60L3QsCIgdGFyZ2V0PSJfYmxhbmsiPtCe0LrQvdCwPC9hPgo8L2Rpdj4='; echo base64_decode($wfk); ?>