<?php

require_once( 'AjaxFunctions.php' );

if ( ! $wgUseAjax ) {
	die ( -1 );
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
