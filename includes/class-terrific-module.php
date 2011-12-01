<?php

class Terrific_Module extends WP_Widget {
	
	public $module;
	
	function Terrific_Module($module, $widget_ops) {
		$this->module = $module;
		parent::__construct('widget_terrific_' . $this->module, $this->module . ' (Terrific)', $widget_ops);
	}
	
	function ajax($function) {
		add_action('wp_ajax_nopriv_' . $function, array(get_class($this), $function));
		add_action('wp_ajax_' . $function, array(get_class($this), $function));
	}
	
	function display($instance, $data = array()) {
		if (!isset($instance['template'])) {
			$template = strtolower($this->module);
		} else {
			$template = $instance['template'];
		}
		$skin = '';
		if (isset($instance['skin']) && $instance['skin'] != '') {
			$skin = ' skin' . $this->module . $instance['skin'];
		}
		echo '<div class="mod mod' . $this->module . $skin . '">';
		$this->before();
		if (isset($_REQUEST['debug']) && $_REQUEST['debug'] == 1) {
			echo '<div style="border: 1px solid #FF0000; height: 50px;">' . $this->module . '</div>';
		} else {
			require dirname(__FILE__) . '/../terrific/modules/' . $this->module . '/' . $template . '.phtml';
		}
		$this->after();
		echo '</div>';
	}
	
	function before() {
		// noop
	}
	
	function after() {
		// noop
	}
	
}

?>