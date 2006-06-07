<?php

//$wgRequestTime = microtime();

// unset( $IP );
// @ini_set( 'allow_url_fopen', 0 ); # For security...

# Valid web server entry point, enable includes.
# Please don't move this line to includes/Defines.php. This line essentially defines
# a valid entry point. If you put it in includes/Defines.php, then any script that includes
# it becomes an entry point, thereby defeating its purpose.
// define( 'MEDIAWIKI', true );
// require_once( './includes/Defines.php' );
// require_once( './LocalSettings.php' );
// require_once( 'includes/Setup.php' );
require_once( 'AjaxFunctions.php' );

if ( ! $wgUseAjax ) {
	die( 1 );
}

class AjaxDispatcher {
	var $mode;
	var $func_name;
	var $args;

	function AjaxDispatcher() {
		global $wgAjaxCachePolicy;

		wfProfileIn( 'AjaxDispatcher::AjaxDispatcher' );

		$wgAjaxCachePolicy = new AjaxCachePolicy();

		$this->mode = "";

		if (! empty($_GET["rs"])) {
			$this->mode = "get";
		}

		if (!empty($_POST["rs"])) {
			$this->mode = "post";
		}

		if ($this->mode == "get") {
			$this->func_name = $_GET["rs"];
			if (! empty($_GET["rsargs"])) {
				$this->args = $_GET["rsargs"];
			} else {
				$this->args = array();
			}
		} else {
			$this->func_name = $_POST["rs"];
			if (! empty($_POST["rsargs"])) {
				$this->args = $_POST["rsargs"];
			} else {
				$this->args = array();
			}
		}
		wfProfileOut( 'AjaxDispatcher::AjaxDispatcher' );
	}

	function performAction() {
		global $wgAjaxCachePolicy, $wgAjaxExportList;
		if ( empty( $this->mode ) ) {
			return;
		}
		wfProfileIn( 'AjaxDispatcher::performAction' );

		if (! in_array( $this->func_name, $wgAjaxExportList ) ) {
			echo "-:{$this->func_name} not callable";
		} else {
			echo "+:";
			$result = call_user_func_array($this->func_name, $this->args);
			header( 'Content-Type: text/html; charset=utf-8', true );
			$wgAjaxCachePolicy->writeHeader();
			echo $result;
		}
		wfProfileOut( 'AjaxDispatcher::performAction' );
		exit;
	}
}

?>
