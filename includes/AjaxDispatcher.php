<?php

if( !defined( 'MEDIAWIKI' ) )
        die( 1 );

if ( ! $wgUseAjax ) {
	die( 1 );
}

require_once( 'AjaxFunctions.php' );

class AjaxDispatcher {
	var $mode;
	var $func_name;
	var $args;

	function AjaxDispatcher() {
		wfProfileIn( __METHOD__ );

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
		wfProfileOut( __METHOD__ );
	}

	function performAction() {
		global $wgAjaxExportList, $wgOut;
		
		if ( empty( $this->mode ) ) {
			return;
		}
		wfProfileIn( __METHOD__ );

		if (! in_array( $this->func_name, $wgAjaxExportList ) ) {
			header( 'Status: 400 Bad Request', true, 400 );
			print "unknown function " . htmlspecialchars( (string) $this->func_name );
		} else {
			try {
				$result = call_user_func_array($this->func_name, $this->args);
				
				if ( $result === false || $result === NULL ) {
					header( 'Status: 500 Internal Error', true, 500 );
					echo "{$this->func_name} returned no data";
				}
				else {
					if ( is_string( $result ) ) {
						$result= new AjaxResponse( $result );
					}
					
					$result->sendHeaders();
					$result->printText();
				}

			} catch (Exception $e) {
				if (!headers_sent()) {
					header( 'Status: 500 Internal Error', true, 500 );
					print $e->getMessage();
				} else {
					print $e->getMessage();
				}
			}
		}
		
		wfProfileOut( __METHOD__ );
		$wgOut = null;
	}
}

?>
