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

$doc = JFactory::getDocument();
$doc->addScript('modules/mod_vina_slicebox_image_slider/assets/js/modernizr.custom.46884.js', 'text/javascript');
$doc->addScript('modules/mod_vina_slicebox_image_slider/assets/js/jquery.slicebox.js', 'text/javascript');
$doc->addStyleSheet('modules/mod_vina_slicebox_image_slider/assets/css/slicebox.css');
$timthumb = JURI::base() . 'modules/mod_vina_slicebox_image_slider/libs/timthumb.php?a=c&amp;q=99&amp;z=0';
?>
<style type="text/css" scoped>
#vina-slicebox-slider<?php echo $module->id; ?> {
	max-width: <?php echo $maxWidth; ?>;
	padding: <?php echo $modulePadding; ?>;
	margin: <?php echo $moduleMargin; ?>;
	<?php echo ($bgImage != '') ? 'background: url('.$bgImage.') top center no-repeat;' : ''; ?>
	<?php echo ($isBgColor) ? 'background-color: ' . $bgColor : ''; ?>
}
#vina-slicebox-slider<?php echo $module->id; ?> .nav-arrows {
	<?php echo ($arrows == 1) ? 'display: none !important;' : ''; ?>
}
#vina-slicebox-slider<?php echo $module->id; ?>:hover .nav-arrows {
	<?php echo ($arrows == 1) ? 'display: block !important;' : ''; ?>
}
#vina-slicebox-slider<?php echo $module->id; ?> .nav-dots {
	<?php echo ($dots == 1) ? 'display: none !important;' : ''; ?>
}
#vina-slicebox-slider<?php echo $module->id; ?>:hover .nav-dots {
	<?php echo ($dots) ? 'display: block !important;' : ''; ?>
}
#vina-copyright<?php echo $module->id; ?> {
	font-size: 12px;
	<?php if(!$params->get('copyRightText', 0)) : ?>
	height: 1px;
	overflow: hidden;
	<?php endif; ?>
	clear: both;
}
</style>
<div id="vina-slicebox-slider<?php echo $module->id; ?>" class="vina-slicebox-slider">
	<ul class="sb-slider">
		<?php foreach($slides as $slide) : ?>
		<?php
			$image 		= $slide->img;
			$image 		= (strpos($image, 'http://') === false) ? JURI::base() . $image : $image;
			$bigImage   = $timthumb . '&amp;w=' . $imageWidth . '&amp;h=' . $imageHeight . '&amp;src=' . $image;
			$bigImage   = ($resizeImage) ? $bigImage : $image;
		?>
		<li>
			<img src="<?php echo $bigImage; ?>" alt="<?php echo empty($slide->name) ? $slide->img : $slide->name; ?>"/>
			<?php if($displayCaptions && $slider->src != 'dir'): ?>
			<?php echo $slide->text; ?>
			<?php endif; ?>
		</li>
		<?php endforeach; ?>
	</ul>
	
	<?php if($shadow) : ?>
	<div class="shadow"></div>
	<?php endif; ?>
	
	<?php if($arrows) : ?>
	<div class="nav-arrows">
		<a href="#">Next</a>
		<a href="#">Previous</a>
	</div>
	<?php endif; ?>
	
	<?php if($dots) : ?>
	<div class="nav-dots">
		<?php foreach($slides as $key => $slide) : ?>
		<span<?php echo (!$key) ? ' class="nav-dot-current"' : ''; ?>></span>
		<?php endforeach; ?>
	</div>
	<?php endif; ?>
</div>

<script type="text/javascript">
jQuery(function($) {

	var Page<?php echo $module->id; ?> = (function() {
		<?php if($arrows) : ?>
		var $navArrows = $('#vina-slicebox-slider<?php echo $module->id; ?> .nav-arrows').hide();
		<?php endif; ?>
		
		<?php if($dots) : ?>
		var	$navDots = $('#vina-slicebox-slider<?php echo $module->id; ?> .nav-dots').hide();
		var	$nav = $navDots.children('span');
		<?php endif; ?>
		
		<?php if($shadow) : ?>
		var	$shadow = $('#vina-slicebox-slider<?php echo $module->id; ?> .shadow').hide();
		<?php endif; ?>
		
		var	slicebox = $('#vina-slicebox-slider<?php echo $module->id; ?> .sb-slider').slicebox({
				onReady : function() {
					<?php if($arrows) : ?>
					$navArrows.show();
					<?php endif; ?>
					
					<?php if($dots) : ?>
					$navDots.show();
					<?php endif; ?>
					
					<?php if($shadow) : ?>
					$shadow.show();
					<?php endif; ?>
				},
				onBeforeChange : function(pos) {
					<?php if($dots) : ?>
					$nav.removeClass('nav-dot-current');
					$nav.eq(pos).addClass('nav-dot-current');
					<?php endif; ?>
				},
				orientation : 		'<?php echo $orientation; ?>',
				perspective : 		<?php echo $perspective; ?>,
				cuboidsCount : 		<?php echo $cuboidsCount; ?>,
				cuboidsRandom : 	<?php echo $cuboidsRandom ? 'true' : 'false'; ?>,
				maxCuboidsCount : 	<?php echo $maxCuboidsCount; ?>,
				disperseFactor : 	<?php echo $disperseFactor; ?>,
				colorHiddenSides : 	'<?php echo $colorHiddenSides; ?>',
				sequentialFactor : 	<?php echo $sequentialFactor; ?>,
				speed : 			<?php echo $speed; ?>,
				autoplay : 			<?php echo $autoplay; ?>,
				interval: 			<?php echo $interval; ?>,
			}),
			
			init = function() {
				initEvents();
			},
			initEvents = function() {
				// add navigation events
				<?php if($arrows) : ?>
				$navArrows.children(':first').on('click', function() {
					slicebox.next();
					return false;
				});
				$navArrows.children(':last').on('click', function() {
					slicebox.previous();
					return false;
				});
				<?php endif; ?>
				
				<?php if($dots) : ?>
				$nav.each(function(i) {
					$(this).on('click', function(event) {
						var $dot = $(this);
						if(!slicebox.isActive()) {
							$nav.removeClass('nav-dot-current');
							$dot.addClass('nav-dot-current');
						}
						slicebox.jump(i + 1);
						return false;
					});
				});
				<?php endif; ?>
			};
			
			return { init : init };
	})();
	
	Page<?php echo $module->id; ?>.init();
});
</script>