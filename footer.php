<?php

/**
 * The template for displaying the footer.
 *
 * @package WordPress
 * @subpackage Terrific
 * @since Terrific 1.0
 */

?>
</div>
<div class="separator"><div></div></div>
<div class="fat-footer">
 <div class="unit size1of4"><?php terrific_module('Categories', array('skin' => 'Footer', 'title' => 'Categories')); ?></div>
 <div class="unit size1of4"><?php terrific_module('Archive', array('skin' => 'Footer', 'title' => 'Archives')); ?></div>
 <div class="unit size1of4"><?php terrific_module('Navigation', array('skin' => 'Footer', 'template' => 'more')); ?></div>
 <div class="unit size1of4 lastUnit"><?php terrific_module('Navigation', array('skin' => 'Footer', 'template' => 'subscribe')); ?></div>
</div>
<div class="separator"><div></div></div>
<?php terrific_module('Footer') ?>
</div>
<?php get_footer('empty') ?>
<script type='text/javascript'>
(function($) {
	$(document).ready(function() {
		var $page = $('body');
		var application = new Tc.Application($page);
		application.registerModules();
		application.start();
	});
})(Tc.$);
</script>