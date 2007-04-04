<?php

if( !defined( 'MEDIAWIKI' ) )
        die( 1 );

if ( ! $wgUseAjax ) {
	die( 1 );
}

require_once( 'AjaxFunctions.php' );

/**
 * @todo Document - e.g. Provide top-level description of this class.
 */
class AjaxDispatcher {
	var $mode;
	var $func_name;
	var $args;

	function __construct() {
		wfProfileIn( __METHOD__ );

		$this->mode = "";

		if (! empty($_GET["rs"])) {
			$this->mode = "get";
		}

		if (!empty($_POST["rs"])) {
			$this->mode = "post";
		}

		if ($this->mode == "get") {
			$this->func_name = isset( $_GET["rs"] ) ? $_GET["rs"] : '';
			if (! empty($_GET["rsargs"])) {
				$this->args = $_GET["rsargs"];
			} else {
				$this->args = array();
			}
		} else {
			$this->func_name = isset( $_POST["rs"] ) ? $_POST["rs"] : '';
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
			wfHttpError( 400, 'Bad Request',
				"unknown function " . (string) $this->func_name );
		} else {
			try {
				$result = call_user_func_array($this->func_name, $this->args);

				if ( $result === false || $result === NULL ) {
					wfHttpError( 500, 'Internal Error',
						"{$this->func_name} returned no data" );
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
					wfHttpError( 500, 'Internal Error',
						$e->getMessage() );
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
