<?php
/**
 * @defgroup Ajax Ajax
 *
 * @file
 * @ingroup Ajax
 * Handle ajax requests and send them to the proper handler.
 */

if ( !( defined( 'MEDIAWIKI' ) && $wgUseAjax ) ) {
	die( 1 );
}

require_once( 'AjaxFunctions.php' );

/**
 * Object-Oriented Ajax functions.
 * @ingroup Ajax
 */
class AjaxDispatcher {
	/** Name of the requested handler */
	private $func_name = null;

	/** Arguments passed */
	private $args = array();

	/** Load up our object with user supplied data */
	public function __construct( WebRequest $req ) {
		wfProfileIn( __METHOD__ );

		$rs = $req->getVal( 'rs' );
		if( $rs !== null ) {
			$this->func_name = $rs;
		}
		$rsargs = $req->getVal( 'rsargs' );
		if( $rsargs !== null ) {
			$this->args = $rsargs;
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

		if ( is_null( $this->func_name ) ) {
			return;
		}

		wfProfileIn( __METHOD__ );

		if ( ! in_array( $this->func_name, $wgAjaxExportList ) ) {
			wfDebug( __METHOD__ . ' Bad Request for unknown function ' . $this->func_name . "\n" );

			wfHttpError(
				400,
				'Bad Request',
				"unknown function " . (string) $this->func_name
			);
		} else {
			wfDebug( __METHOD__ . ' dispatching ' . $this->func_name . "\n" );

			if ( strpos( $this->func_name, '::' ) !== false ) {
				$func = explode( '::', $this->func_name, 2 );
			} else {
				$func = $this->func_name;
			}

			try {
				$result = call_user_func_array( $func, $this->args );

				if ( $result === false || $result === null ) {
					wfDebug( __METHOD__ . ' ERROR while dispatching '
							. $this->func_name . "(" . var_export( $this->args, true ) . "): "
							. "no data returned\n" );

					wfHttpError( 500, 'Internal Error',
						"{$this->func_name} returned no data" );
				} else {
					if ( is_string( $result ) ) {
						$result = new AjaxResponse( $result );
					}

					$result->sendHeaders();
					$result->printText();

					wfDebug( __METHOD__ . ' dispatch complete for ' . $this->func_name . "\n" );
				}
			} catch ( Exception $e ) {
				wfDebug( __METHOD__ . ' ERROR while dispatching '
						. $this->func_name . "(" . var_export( $this->args, true ) . "): "
						. get_class( $e ) . ": " . $e->getMessage() . "\n" );

				if ( !headers_sent() ) {
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
