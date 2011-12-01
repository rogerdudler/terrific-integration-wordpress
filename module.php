<?php

/**
 * Template Name: Terrific Module
 * Description: A page template that just shows a module
 *
 * @package WordPress
 * @subpackage Terrific
 * @since Terrific 1.0
 */

get_header('empty'); ?>
<div class="page overflow">
	<div class="body overflow">
		<div class="line overflow">
			<div class="unit size1of1 overflow">
			<?php terrific_module($_REQUEST['id']); ?>
			</div>
		</div>
	</div>
</div>
<?php get_footer('empty'); ?>