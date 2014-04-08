<?php
/**
 * Generic operation result.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

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
 *
 * The recommended pattern for Status objects is to return a Status object
 * unconditionally, i.e. both on success and on failure -- so that the
 * developer of the calling code is reminded that the function can fail, and
 * so that a lack of error-handling will be explicit.
 */
class Status {
	/** @var bool */
	public $ok = true;

	/** @var mixed */
	public $value;

	/** Counters for batch operations */
	/** @var int */
	public $successCount = 0;

	/** @var int */
	public $failCount = 0;

	/** Array to indicate which items of the batch operations were successful */
	/** @var array */
	public $success = array();

	/** @var array */
	public $errors = array();

	/** @var callable */
	public $cleanCallback = false;

	/**
	 * Factory function for fatal errors
	 *
	 * @param string|Message $message message name or object
	 * @return Status
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
	 * @return Status
	 */
	static function newGood( $value = null ) {
		$result = new self;
		$result->value = $value;
		return $result;
	}

	/**
	 * Change operation result
	 *
	 * @param bool $ok Whether the operation completed
	 * @param mixed $value
	 */
	public function setResult( $ok, $value = null ) {
		$this->ok = $ok;
		$this->value = $value;
	}

	/**
	 * Returns whether the operation completed and didn't have any error or
	 * warnings
	 *
	 * @return Boolean
	 */
	public function isGood() {
		return $this->ok && !$this->errors;
	}

	/**
	 * Returns whether the operation completed
	 *
	 * @return Boolean
	 */
	public function isOK() {
		return $this->ok;
	}

	/**
	 * Add a new warning
	 *
	 * @param string|Message $message message name or object
	 */
	public function warning( $message /*, parameters... */ ) {
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
	 * @param string|Message $message message name or object
	 */
	public function error( $message /*, parameters... */ ) {
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
	 * @param string|Message $message message name or object
	 */
	public function fatal( $message /*, parameters... */ ) {
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
	public function __wakeup() {
		$this->cleanCallback = false;
	}

	/**
	 * @param $params array
	 * @return array
	 */
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

	/**
	 * Get the error list as a wikitext formatted list
	 *
	 * @param string $shortContext a short enclosing context message name, to
	 *        be used when there is a single error
	 * @param string $longContext a long enclosing context message name, for a list
	 * @return String
	 */
	public function getWikiText( $shortContext = false, $longContext = false ) {
		if ( count( $this->errors ) == 0 ) {
			if ( $this->ok ) {
				$this->fatal( 'internalerror_info',
					__METHOD__ . " called for a good result, this is incorrect\n" );
			} else {
				$this->fatal( 'internalerror_info',
					__METHOD__ . ": Invalid result object: no error text but not OK\n" );
			}
		}
		if ( count( $this->errors ) == 1 ) {
			$s = $this->getErrorMessage( $this->errors[0] )->plain();
			if ( $shortContext ) {
				$s = wfMessage( $shortContext, $s )->plain();
			} elseif ( $longContext ) {
				$s = wfMessage( $longContext, "* $s\n" )->plain();
			}
		} else {
			$errors = $this->getErrorMessageArray( $this->errors );
			foreach ( $errors as &$error ) {
				$error = $error->plain();
			}
			$s = '* ' . implode( "\n* ", $errors ) . "\n";
			if ( $longContext ) {
				$s = wfMessage( $longContext, $s )->plain();
			} elseif ( $shortContext ) {
				$s = wfMessage( $shortContext, "\n$s\n" )->plain();
			}
		}
		return $s;
	}

	/**
	 * Get the error list as a Message object
	 *
	 * @param string|string[] $shortContext A short enclosing context message name (or an array of
	 * message names), to be used when there is a single error.
	 * @param string|string[] $longContext A long enclosing context message name (or an array of
	 * message names), for a list.
	 *
	 * @return Message
	 */
	public function getMessage( $shortContext = false, $longContext = false ) {
		if ( count( $this->errors ) == 0 ) {
			if ( $this->ok ) {
				$this->fatal( 'internalerror_info',
					__METHOD__ . " called for a good result, this is incorrect\n" );
			} else {
				$this->fatal( 'internalerror_info',
					__METHOD__ . ": Invalid result object: no error text but not OK\n" );
			}
		}
		if ( count( $this->errors ) == 1 ) {
			$s = $this->getErrorMessage( $this->errors[0] );
			if ( $shortContext ) {
				$s = wfMessage( $shortContext, $s );
			} elseif ( $longContext ) {
				$wrapper = new RawMessage( "* \$1\n" );
				$wrapper->params( $s )->parse();
				$s = wfMessage( $longContext, $wrapper );
			}
		} else {
			$msgs = $this->getErrorMessageArray( $this->errors );
			$msgCount = count( $msgs );

			if ( $shortContext ) {
				$msgCount++;
			}

			$s = new RawMessage( '* $' . implode( "\n* \$", range( 1, $msgCount ) ) );
			$s->params( $msgs )->parse();

			if ( $longContext ) {
				$s = wfMessage( $longContext, $s );
			} elseif ( $shortContext ) {
				$wrapper = new RawMessage( "\n\$1\n", $s );
				$wrapper->parse();
				$s = wfMessage( $shortContext, $wrapper );
			}
		}

		return $s;
	}

	/**
	 * Return the message for a single error.
	 * @param $error Mixed With an array & two values keyed by
	 * 'message' and 'params', use those keys-value pairs.
	 * Otherwise, if its an array, just use the first value as the
	 * message and the remaining items as the params.
	 *
	 * @return String
	 */
	protected function getErrorMessage( $error ) {
		if ( is_array( $error ) ) {
			if ( isset( $error['message'] ) && $error['message'] instanceof Message ) {
				$msg = $error['message'];
			} elseif ( isset( $error['message'] ) && isset( $error['params'] ) ) {
				$msg = wfMessage( $error['message'],
					array_map( 'wfEscapeWikiText', $this->cleanParams( $error['params'] ) ) );
			} else {
				$msgName = array_shift( $error );
				$msg = wfMessage( $msgName,
					array_map( 'wfEscapeWikiText', $this->cleanParams( $error ) ) );
			}
		} else {
			$msg = wfMessage( $error );
		}
		return $msg;
	}

	/**
	 * Get the error message as HTML. This is done by parsing the wikitext error
	 * message.
	 * @param string $shortContext a short enclosing context message name, to
	 *        be used when there is a single error
	 * @param string $longContext a long enclosing context message name, for a list
	 * @return String
	 */
	public function getHTML( $shortContext = false, $longContext = false ) {
		$text = $this->getWikiText( $shortContext, $longContext );
		$out = MessageCache::singleton()->parse( $text, null, true, true );
		return $out instanceof ParserOutput ? $out->getText() : $out;
	}

	/**
	 * Return an array with the wikitext for each item in the array.
	 * @param $errors Array
	 * @return Array
	 */
	protected function getErrorMessageArray( $errors ) {
		return array_map( array( $this, 'getErrorMessage' ), $errors );
	}

	/**
	 * Merge another status object into this one
	 *
	 * @param Status $other Other Status object
	 * @param bool $overwriteValue Whether to override the "value" member
	 */
	public function merge( $other, $overwriteValue = false ) {
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
	 * @return array A list in which each entry is an array with a message key as its first element.
	 *         The remaining array elements are the message parameters.
	 */
	public function getErrorsArray() {
		return $this->getStatusArray( "error" );
	}

	/**
	 * Get the list of warnings (but not errors)
	 *
	 * @return array A list in which each entry is an array with a message key as its first element.
	 *         The remaining array elements are the message parameters.
	 */
	public function getWarningsArray() {
		return $this->getStatusArray( "warning" );
	}

	/**
	 * Returns a list of status messages of the given type
	 * @param $type String
	 * @return Array
	 */
	protected function getStatusArray( $type ) {
		$result = array();
		foreach ( $this->errors as $error ) {
			if ( $error['type'] === $type ) {
				if ( $error['message'] instanceof Message ) {
					$result[] = array_merge(
						array( $error['message']->getKey() ),
						$error['message']->getParams()
					);
				} elseif ( $error['params'] ) {
					$result[] = array_merge( array( $error['message'] ), $error['params'] );
				} else {
					$result[] = array( $error['message'] );
				}
			}
		}

		return $result;
	}

	/**
	 * Returns a list of status messages of the given type, with message and
	 * params left untouched, like a sane version of getStatusArray
	 *
	 * @param $type String
	 *
	 * @return Array
	 */
	public function getErrorsByType( $type ) {
		$result = array();
		foreach ( $this->errors as $error ) {
			if ( $error['type'] === $type ) {
				$result[] = $error;
			}
		}
		return $result;
	}

	/**
	 * Returns true if the specified message is present as a warning or error
	 *
	 * Note, due to the lack of tools for comparing Message objects, this
	 * function will not work when using a Message object as a parameter.
	 *
	 * @param string $msg message name
	 * @return Boolean
	 */
	public function hasMessage( $msg ) {
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
	 * Note, due to the lack of tools for comparing Message objects, this
	 * function will not work when using a Message object as the search parameter.
	 *
	 * @param Message|string $source Message key or object to search for
	 * @param Message|string $dest Replacement message key or object
	 * @return bool Return true if the replacement was done, false otherwise.
	 */
	public function replaceMessage( $source, $dest ) {
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
	 * @return mixed
	 */
	public function getValue() {
		return $this->value;
	}
}
