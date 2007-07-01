<?php
/**
 * Handle ajax requests and send them to the proper handler.
 */

if( !(defined( 'MEDIAWIKI' ) && $wgUseAjax ) ) {
	die( 1 );
}

require_once( 'AjaxFunctions.php' );

/**
 * Object-Oriented Ajax functions.
 * @addtogroup Ajax
 */
class AjaxDispatcher {
	/** The way the request was made, either a 'get' or a 'post' */
	private $mode;

	/** Name of the requested handler */
	private $func_name;

	/** Arguments passed */
	private $args;

	/** Load up our object with user supplied data */
	function __construct() {
		wfProfileIn( __METHOD__ );

		$this->mode = "";

		if (! empty($_GET["rs"])) {
			$this->mode = "get";
		}

		if (!empty($_POST["rs"])) {
			$this->mode = "post";
		}

		switch( $this->mode ) {

		case 'get':
			$this->func_name = isset( $_GET["rs"] ) ? $_GET["rs"] : '';
			if (! empty($_GET["rsargs"])) {
				$this->args = $_GET["rsargs"];
			} else {
				$this->args = array();
			}
		break;

		case 'post':
			$this->func_name = isset( $_POST["rs"] ) ? $_POST["rs"] : '';
			if (! empty($_POST["rsargs"])) {
				$this->args = $_POST["rsargs"];
			} else {
				$this->args = array();
			}
		break;

		default:
			return;
			# Or we could throw an exception:
			#throw new MWException( __METHOD__ . ' called without any data (mode empty).' );

		}

		wfProfileOut( __METHOD__ );
	}

	/** Pass the request to our internal function.
	 * BEWARE! Data are passed as they have been supplied by the user,
	 * they should be carefully handled in the function processing the
	 * request.
	 */
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
			if ( strpos( $this->func_name, '::' ) !== false ) {
				$func = explode( '::', $this->func_name, 2 );
			} else {
				$func = $this->func_name;
			}
			try {
				$result = call_user_func_array($func, $this->args);

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


