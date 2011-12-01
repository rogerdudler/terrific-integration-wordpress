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