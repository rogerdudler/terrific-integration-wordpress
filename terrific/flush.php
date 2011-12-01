<?php

$cache = glob(dirname(__FILE__) . '/../cache/*');
foreach ($cache as $entry) {
	@unlink($entry);
}

header('Location: ' . $_SERVER['HTTP_REFERER']);

?>