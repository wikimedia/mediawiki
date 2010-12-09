<?php

/**
 * Generic operation result class
 * Has warning/error list, boolean status and arbitrary value
 *
 * "Good" means the operation was completed with no warnings or errors.
 *
 * "OK" means the operation was partially or wholly completed.
 *
 * An operation which is not OK should have errors so that the user can be
 * informed as to what went wrong. Calling the fatal() function sets an error
 * message and simultaneously switches off the OK flag.
 */
class Status {
	var $ok = true;
	var $value;

	/** Counters for batch operations */
	var $successCount = 0, $failCount = 0;

	/*semi-private*/ var $errors = array();
	/*semi-private*/ var $cleanCallback = false;

	/**
	 * Factory function for fatal errors
	 *
	 * @param $message String: message name
	 */
	static function newFatal( $message /*, parameters...*/ ) {
		$params = func_get_args();
		$result = new self;
		call_user_func_array( array( &$result, 'error' ), $params );
		$result->ok = false;
		return $result;
	}

	/**
	 * Factory function for good results
	 *
	 * @param $value Mixed
	 */
	static function newGood( $value = null ) {
		$result = new self;
		$result->value = $value;
		return $result;
	}

	/**
	 * Change operation result
	 *
	 * @param $ok Boolean: whether to operation completed
	 * @param $value Mixed
	 */
	function setResult( $ok, $value = null ) {
		$this->ok = $ok;
		$this->value = $value;
	}

	/**
	 * Returns whether the operation completed and didn't have any error or
	 * warnings
	 *
	 * @return Boolean
	 */
	function isGood() {
		return $this->ok && !$this->errors;
	}

	/**
	 * Returns whether the operation completed
	 *
	 * @return Boolean
	 */
	function isOK() {
		return $this->ok;
	}

	/**
	 * Add a new warning
	 *
	 * @param $message String: message name
	 */
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
	 *
	 * @param $message String: message name
	 */
	function error( $message /*, parameters... */ ) {
		$params = array_slice( func_get_args(), 1 );
		$this->errors[] = array(
			'type' => 'error',
			'message' => $message,
			'params' => $params );
	}

	/**
	 * Add an error and set OK to false, indicating that the operation
	 * as a whole was fatal
	 *
	 * @param $message String: message name
	 */
	function fatal( $message /*, parameters... */ ) {
		$params = array_slice( func_get_args(), 1 );
		$this->errors[] = array(
			'type' => 'error',
			'message' => $message,
			'params' => $params );
		$this->ok = false;
	}

	/**
	 * Sanitize the callback parameter on wakeup, to avoid arbitrary execution.
	 */
	function __wakeup() {
		$this->cleanCallback = false;
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
	 *
	 * @param $shortContext String: a short enclosing context message name, to
	 *        be used when there is a single error
	 * @param $longContext String: a long enclosing context message name, for a list
	 * @return String
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
			$s = $this->getWikiTextForError( $this->errors[0], $this->errors[0]  );
			if ( $shortContext ) {
				$s = wfMsgNoTrans( $shortContext, $s );
			} elseif ( $longContext ) {
				$s = wfMsgNoTrans( $longContext, "* $s\n" );
			}
		} else {
			$s = '* '. implode("\n* ",
				$this->getWikiTextArray( $this->errors ) ) . "\n";
			if ( $longContext ) {
				$s = wfMsgNoTrans( $longContext, $s );
			} elseif ( $shortContext ) {
				$s = wfMsgNoTrans( $shortContext, "\n$s\n" );
			}
		}
		return $s;
	}

	/**
	 * Return the wiki text for a single error.
	 * @param $error Mixed With an array & two values keyed by
	 * 'message' and 'params', use those keys-value pairs.
	 * Otherwise, if its an array, just use the first value as the
	 * message and the remaining items as the params.
	 *
	 * @return String
	 */
	protected function getWikiTextForError( $error ) {
		if ( is_array( $error ) ) {
			if ( isset( $error['message'] ) && isset( $error['params'] ) ) {
				return wfMsgReal( $error['message'],
					array_map( 'wfEscapeWikiText', $this->cleanParams( $error['params'] ) ),
					true, false, false );
			} else {
				$message = array_shift($error);
				return wfMsgReal( $message,
					array_map( 'wfEscapeWikiText', $this->cleanParams( $error ) ),
					true, false, false );
			}
		} else {
			return wfMsgReal( $error, array(), true, false, false);
		}
	}

	/**
	 * Return an array with the wikitext for each item in the array.
	 * @param $errors Array
	 * @return Array
	 */
	function getWikiTextArray( $errors ) {
		return array_map( array( $this, 'getWikiTextForError' ), $errors );
	}

	/**
	 * Merge another status object into this one
	 *
	 * @param $other Other Status object
	 * @param $overwriteValue Boolean: whether to override the "value" member
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

	/**
	 * Get the list of errors (but not warnings)
	 *
	 * @return Array
	 */
	function getErrorsArray() {
		return $this->getStatusArray( "error" );
	}

	/**
	 * Get the list of warnings (but not errors)
	 *
	 * @return Array
	 */
	function getWarningsArray() {
		return $this->getStatusArray( "warning" );
	}

	/**
	 * Returns a list of status messages of the given type
	 * @param $type String
	 *
	 * @return Array
	 */
	protected function getStatusArray( $type ) {
		$result = array();
		foreach ( $this->errors as $error ) {
			if ( $error['type'] === $type ) {
				if( $error['params'] ) {
					$result[] = array_merge( array( $error['message'] ), $error['params'] );
				} else {
					$result[] = $error['message'];
				}
			}
		}
		return $result;
	}
	/**
	 * Returns true if the specified message is present as a warning or error
	 *
	 * @param $msg String: message name
	 * @return Boolean
	 */
	function hasMessage( $msg ) {
		foreach ( $this->errors as $error ) {
			if ( $error['message'] === $msg ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * If the specified source message exists, replace it with the specified 
	 * destination message, but keep the same parameters as in the original error.
	 *
	 * Return true if the replacement was done, false otherwise.
	 */
	function replaceMessage( $source, $dest ) {
		$replaced = false;
		foreach ( $this->errors as $index => $error ) {
			if ( $error['message'] === $source ) {
				$this->errors[$index]['message'] = $dest;
				$replaced = true;
			}
		}
		return $replaced;
	}

	/**
	 * Backward compatibility function for WikiError -> Status migration
	 *
	 * @return String
	 */
	public function getMessage() {
		return $this->getWikiText();
	}
}
