<?php

/**
 * Generic operation result class
 * Has warning/error list, boolean status and arbitrary value
 */
class FileRepoStatus {
	var $ok = true;
	var $value;

	/** Counters for batch operations */
	var $successCount = 0, $failCount = 0;

	/*semi-private*/ var $errors = array();
	/*semi-private*/ var $cleanCallback = false;

	/**
	 * Factory function for fatal errors
	 */
	static function newFatal( $repo, $message /*, parameters...*/ ) {
		$params = array_slice( func_get_args(), 1 );
		$result = new self( $repo );
		call_user_func_array( array( &$result, 'error' ), $params );
		$result->ok = false;
	}

	static function newGood( $repo = false, $value = null ) {
		$result = new self( $repo );
		$result->value = $value;
		return $result;
	}
	
	function __construct( $repo = false ) {
		if ( $repo ) {
			$this->cleanCallback = $repo->getErrorCleanupFunction();
		}
	}

	function setResult( $ok, $value = null ) {
		$this->ok = $ok;
		$this->value = $value;
	}

	function isGood() {
		return $this->ok && !$this->errors;
	}

	function isOK() {
		return $this->ok;
	}

	function warning( $message /*, parameters... */ ) {
		$params = array_slice( func_get_args(), 1 );		
		$this->errors[] = array( 
			'type' => 'warning', 
			'message' => $message, 
			'params' => $params );
	}

	/**
	 * Add an error, do not set fatal flag
	 * This can be used for non-fatal errors
	 */
	function error( $message /*, parameters... */ ) {
		$params = array_slice( func_get_args(), 1 );		
		$this->errors[] = array( 
			'type' => 'error', 
			'message' => $message, 
			'params' => $params );
	}

	/**
	 * Add an error and set OK to false, indicating that the operation as a whole was fatal
	 */
	function fatal( $message /*, parameters... */ ) {
		$params = array_slice( func_get_args(), 1 );		
		$this->errors[] = array( 
			'type' => 'error', 
			'message' => $message, 
			'params' => $params );
		$this->ok = false;
	}

	protected function cleanParams( $params ) {
		if ( !$this->cleanCallback ) {
			return $params;
		}
		$cleanParams = array();
		foreach ( $params as $i => $param ) {
			$cleanParams[$i] = call_user_func( $this->cleanCallback, $param );
		}
		return $cleanParams;
	}

	protected function getItemXML( $item ) {
		$params = $this->cleanParams( $item['params'] );
		$xml = "<{$item['type']}>\n" . 
			Xml::element( 'message', null, $item['message'] ) . "\n" .
			Xml::element( 'text', null, wfMsgReal( $item['message'], $params ) ) ."\n";
		foreach ( $params as $param ) {
			$xml .= Xml::element( 'param', null, $param );
		}
		$xml .= "</{$this->type}>\n";
		return $xml;
	}

	/**
	 * Get the error list as XML
	 */
	function getXML() {
		$xml = "<errors>\n";
		foreach ( $this->errors as $error ) {
			$xml .= $this->getItemXML( $error );
		}
		$xml .= "</errors>\n";
		return $xml;
	}

	/**
	 * Get the error list as a wikitext formatted list
	 * @param string $shortContext A short enclosing context message name, to be used 
	 *     when there is a single error
	 * @param string $longContext A long enclosing context message name, for a list
	 */
	function getWikiText( $shortContext = false, $longContext = false ) {
		if ( count( $this->errors ) == 0 ) {
			if ( $this->ok ) {
				$this->fatal( 'internalerror_info',
					__METHOD__." called for a good result, this is incorrect\n" );
			} else {
				$this->fatal( 'internalerror_info', 
					__METHOD__.": Invalid result object: no error text but not OK\n" );
			}
		}
		if ( count( $this->errors ) == 1 ) {
			$params = array_map( 'wfEscapeWikiText', $this->cleanParams( $this->errors[0]['params'] ) );
			$s = wfMsgReal( $this->errors[0]['message'], $params );
			if ( $shortContext ) {
				$s = wfMsg( $shortContext, $s );
			} elseif ( $longContext ) {
				$s = wfMsg( $longContext, "* $s\n" );
			}
		} else {
			$s = '';
			foreach ( $this->errors as $error ) {
				$params = array_map( 'wfEscapeWikiText', $this->cleanParams( $error['params'] ) );
				$s .= '* ' . wfMsgReal( $error['message'], $params ) . "\n";
			}
			if ( $longContext ) {
				$s = wfMsg( $longContext, $s );
			} elseif ( $shortContext ) {
				$s = wfMsg( $shortContext, "\n* $s\n" );
			}
		}
		return $s;
	}

	/**
	 * Merge another status object into this one
	 */
	function merge( $other, $overwriteValue = false ) {
		$this->errors = array_merge( $this->errors, $other->errors );
		$this->ok = $this->ok && $other->ok;
		if ( $overwriteValue ) {
			$this->value = $other->value;
		}
		$this->successCount += $other->successCount;
		$this->failCount += $other->failCount;
	}
}
