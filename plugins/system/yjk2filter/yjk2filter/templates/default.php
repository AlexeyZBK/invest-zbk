<?php
/**
 * @version		$Id: generic.php 1249 2011-10-19 17:37:34Z joomlaworks $
 * @package		K2
 * @author		JoomlaWorks http://www.joomlaworks.gr
 * @copyright	Copyright (c) 2006 - 2011 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */
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

// no direct access  
defined('_JEXEC') or die ('Restricted access');
?>

<!-- Start K2 Generic (search/date/yjk2filter) Layout -->
<div id="k2Container" class="genericView yjk2filter<?php if($this->params->get('pageclass_sfx')) echo ' '.$this->params->get('pageclass_sfx'); ?>">

	<?php if($this->params->get('show_page_title')): ?>
	<!-- Page title -->
	<div class="componentheading<?php echo $this->params->get('pageclass_sfx')?>">
		<?php echo $this->escape($this->params->get('page_title')); ?>
	</div>
	<?php endif; ?>

	<?php if(count($this->items)): ?>
        <div class="genericItemList">
            <?php foreach($this->items as $item): ?>
    
            <!-- Start K2 Item Layout -->
            <div class="genericItemView">
    
                <div class="genericItemHeader">
                    <?php if($item->params->get('genericItemDateCreated')): ?>
                    <!-- Date created -->
                    <span class="genericItemDateCreated">
                        <?php echo JHTML::_('date', $item->created , JText::_('K2_DATE_FORMAT_LC2')); ?>
                    </span>
                    <?php endif; ?>
                
                  <?php if($item->params->get('genericItemTitle')): ?>
                  <!-- Item title -->
                  <h2 class="genericItemTitle">
                    <?php if ($item->params->get('genericItemTitleLinked')): ?>
                        <a href="<?php echo $item->link; ?>">
                        <?php echo $item->title; ?>
                    </a>
                    <?php else: ?>
                    <?php echo $item->title; ?>
                    <?php endif; ?>
                  </h2>
                  <?php endif; ?>
              </div>
    
              <div class="genericItemBody">
                  <?php if($item->params->get('genericItemImage') && !empty($item->imageGeneric)): ?>
                  <!-- Item Image -->
                  <div class="genericItemImageBlock">
                      <span class="genericItemImage">
                        <a href="<?php echo $item->link; ?>" title="<?php if(!empty($item->image_caption)) echo K2HelperUtilities::cleanHtml($item->image_caption); else echo K2HelperUtilities::cleanHtml($item->title); ?>">
                            <img src="<?php echo $item->imageGeneric; ?>" alt="<?php if(!empty($item->image_caption)) echo K2HelperUtilities::cleanHtml($item->image_caption); else echo K2HelperUtilities::cleanHtml($item->title); ?>" style="width:<?php echo $item->params->get('itemImageGeneric'); ?>px; height:auto;" />
                        </a>
                      </span>
                      <div class="clr"></div>
                  </div>
                  <?php endif; ?>
                  
                  <?php if($item->params->get('genericItemIntroText')): ?>
                  <!-- Item introtext -->
                  <div class="genericItemIntroText">
                    <?php echo $item->introtext; ?>
                  </div>
                  <?php endif; ?>
    
                  <div class="clr"></div>
              </div>
              
              <div class="clr"></div>
              
              <?php if($item->params->get('genericItemExtraFields') && count($item->extra_fields)): ?>
              <!-- Item extra fields -->  
              <div class="genericItemExtraFields">
                <h4><?php echo JText::_('K2_ADDITIONAL_INFO'); ?></h4>
                <ul>
                    <?php foreach ($item->extra_fields as $key=>$extraField): ?>
                    <?php if($extraField->value): ?>
                    <li class="<?php echo ($key%2) ? "odd" : "even"; ?> type<?php echo ucfirst($extraField->type); ?> group<?php echo $extraField->group; ?>">
                        <span class="genericItemExtraFieldsLabel"><?php echo $extraField->name; ?></span>
                        <span class="genericItemExtraFieldsValue"><?php echo $extraField->value; ?></span>		
                    </li>
                    <?php endif; ?>
                    <?php endforeach; ?>
                    </ul>
                <div class="clr"></div>
              </div>
              <?php endif; ?>
              
                <?php if($item->params->get('genericItemCategory')): ?>
                <!-- Item category name -->
                <div class="genericItemCategory">
                    <span><?php echo JText::_('K2_PUBLISHED_IN'); ?></span>
                    <a href="<?php echo $item->category->link; ?>"><?php echo $item->category->name; ?></a>
                </div>
                <?php endif; ?>
                
                <?php if ($item->params->get('genericItemReadMore')): ?>
                <!-- Item "read more..." link -->
                <div class="genericItemReadMore">
                    <a class="k2ReadMore" href="<?php echo $item->link; ?>">
                        <?php echo JText::_('K2_READ_MORE'); ?>
                    </a>
                </div>
                <?php endif; ?>
    
                <div class="clr"></div>
            </div>
            <!-- End K2 Item Layout -->
            
            <?php endforeach; ?>
        </div>
    
        <!-- Pagination -->
        <?php if($this->pagination->getPagesLinks()): ?>
        <div class="k2Pagination">
            <?php echo $this->pagination->getPagesLinks(); ?>
            <div class="clr"></div>
            <?php echo $this->pagination->getPagesCounter(); ?>
        </div>
        <?php endif; ?>

	<?php else: ?>
        <dl id="system-message">
            <dt class="notice"><?php echo JText::_('К сожалению по вашему запросу ничего не обнаружено...'); ?></dt>
            <dd class="notice message fade fuck">
                <ul>
                    <li><?php echo JText::_('- Попробуйте изменить условия фильтрации контента -'); ?></li>
                </ul>
            </dd>
        </dl>    
	<?php endif; ?>
	
</div>
<!-- End K2 Generic (search/date/yjk2filter) Layout -->
