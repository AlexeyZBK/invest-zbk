<?php
/*
# ------------------------------------------------------------------------
# Vina Slicebox Image Slider for Joomla 3
# ------------------------------------------------------------------------
# Copyright(C) 2014 www.VinaGecko.com. All Rights Reserved.
# @license http://www.gnu.org/licenseses/gpl-3.0.html GNU/GPL
# Author: VinaGecko.com
# Websites: http://vinagecko.com
# Forum:    http://vinagecko.com/forum/
# ------------------------------------------------------------------------
*/

// no direct access
defined('_JEXEC') or die('Restricted access');
require_once dirname(__FILE__) . '/helper.php';

// load json code
$slider = json_decode($params->get('slides', ''));

// check if don't have any slide
if(!$slider) {
	echo "You don't have any slide!";
	return;
}

// load data
$slides = modVinaSliceboxImageSliderHelper::getSildes($slider);

// module config.
$maxWidth			= $params->get('maxWidth', '100%');
$moduleMargin		= $params->get('moduleMargin', '0px auto 0px auto');
$modulePadding		= $params->get('modulePadding', '0px 0px 0px 0px');
$bgImage			= $params->get('bgImage', NULL);
if($bgImage != '') {
	if(strpos($bgImage, 'http://') === FALSE) {
		$bgImage = JURI::base() . $bgImage;
	}
}
$isBgColor			= $params->get('isBgColor', 0);
$bgColor			= $params->get('bgColor', '#FFFFFF');
$linkOnImage		= $params->get('linkOnImage', 0);
$displayCaptions	= $params->get('displayCaptions', 1);
$resizeImage		= $params->get('resizeImage', 1);
$imageWidth			= $params->get('imageWidth', 600);
$imageHeight		= $params->get('imageHeight', 300);
$shadow				= $params->get('shadow', 1);
$arrows				= $params->get('arrows', 1);
$dots				= $params->get('dots', 1);
// effect config.
$orientation		= $params->get('orientation', 'r');
$perspective		= $params->get('perspective', 1200);
$cuboidsCount		= $params->get('cuboidsCount', 5);
$cuboidsRandom		= $params->get('cuboidsRandom', 1);
$maxCuboidsCount	= $params->get('maxCuboidsCount', 5);
$disperseFactor		= $params->get('disperseFactor', 0);
$colorHiddenSides	= $params->get('colorHiddenSides', '#222222');
$sequentialFactor	= $params->get('sequentialFactor', 150);
$speed				= $params->get('speed', 600);
$autoplay			= $params->get('autoplay', 1);
$interval			= $params->get('interval', 3000);

// display layout
require JModuleHelper::getLayoutPath($module->module, $params->get('layout', 'default'));
modVinaSliceboxImageSliderHelper::getCopyrightText($module);