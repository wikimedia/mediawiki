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
	/** @var StatusValue */
	protected $sv;

	/** @var mixed */
	public $value;
	/** @var array Map of (key => bool) to indicate success of each part of batch operations */
	public $success = array();
	/** @var int Counter for batch operations */
	public $successCount = 0;
	/** @var int Counter for batch operations */
	public $failCount = 0;

	/** @var callable */
	public $cleanCallback = false;

	/**
	 * @param StatusValue $sv [optional]
	 */
	public function __construct( StatusValue $sv = null ) {
		$this->sv = ( $sv === null ) ? new StatusValue() : $sv;
		// B/C field aliases
		$this->value =& $this->sv->value;
		$this->successCount =& $this->sv->successCount;
		$this->failCount =& $this->sv->failCount;
		$this->success =& $this->sv->success;
	}

	/**
	 * Succinct helper method to wrap a StatusValue
	 *
	 * This is is useful when formatting StatusValue objects:
	 * @code
	 *     $this->getOutput()->addHtml( Status::wrap( $sv )->getHTML() );
	 * @endcode
	 *
	 * @param StatusValue|Status $sv
	 * @return Status
	 */
	public static function wrap( $sv ) {
		return $sv instanceof Status ? $sv : new self( $sv );
	}

	/**
	 * Factory function for fatal errors
	 *
	 * @param string|Message $message Message name or object
	 * @return Status
	 */
	public static function newFatal( $message /*, parameters...*/ ) {
		return new self( call_user_func_array(
			array( 'StatusValue', 'newFatal' ), func_get_args()
		) );
	}

	/**
	 * Factory function for good results
	 *
	 * @param mixed $value
	 * @return Status
	 */
	public static function newGood( $value = null ) {
		$sv = new StatusValue();
		$sv->value = $value;

		return new self( $sv );
	}

	/**
	 * Change operation result
	 *
	 * @param bool $ok Whether the operation completed
	 * @param mixed $value
	 */
	public function setResult( $ok, $value = null ) {
		$this->sv->setResult( $ok, $value );
	}

	/**
	 * Returns whether the operation completed and didn't have any error or
	 * warnings
	 *
	 * @return bool
	 */
	public function isGood() {
		return $this->sv->isGood();
	}

	/**
	 * Returns whether the operation completed
	 *
	 * @return bool
	 */
	public function isOK() {
		return $this->sv->isOK();
	}

	/**
	 * Add a new warning
	 *
	 * @param string|Message $message Message name or object
	 */
	public function warning( $message /*, parameters... */ ) {
		call_user_func_array( array( $this->sv, 'warning' ), func_get_args() );
	}

	/**
	 * Add an error, do not set fatal flag
	 * This can be used for non-fatal errors
	 *
	 * @param string|Message $message Message name or object
	 */
	public function error( $message /*, parameters... */ ) {
		call_user_func_array( array( $this->sv, 'error' ), func_get_args() );
	}

	/**
	 * Add an error and set OK to false, indicating that the operation
	 * as a whole was fatal
	 *
	 * @param string|Message $message Message name or object
	 */
	public function fatal( $message /*, parameters... */ ) {
		call_user_func_array( array( $this->sv, 'fatal' ), func_get_args() );
	}

	/**
	 * @param array $params
	 * @return array
	 */
	protected function cleanParams( array $params ) {
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
	 * @param string|bool $shortContext A short enclosing context message name, to
	 *        be used when there is a single error
	 * @param string|bool $longContext A long enclosing context message name, for a list
	 * @return string
	 */
	public function getWikiText( $shortContext = false, $longContext = false ) {
		$rawErrors = $this->sv->getErrors();
		if ( count( $rawErrors ) == 0 ) {
			if ( $this->sv->isOK() ) {
				$this->sv->fatal( 'internalerror_info',
					__METHOD__ . " called for a good result, this is incorrect\n" );
			} else {
				$this->sv->fatal( 'internalerror_info',
					__METHOD__ . ": Invalid result object: no error text but not OK\n" );
			}
			$rawErrors = $this->sv->getErrors(); // just added a fatal
		}
		if ( count( $rawErrors ) == 1 ) {
			$s = $this->getErrorMessage( $rawErrors[0] )->plain();
			if ( $shortContext ) {
				$s = wfMessage( $shortContext, $s )->plain();
			} elseif ( $longContext ) {
				$s = wfMessage( $longContext, "* $s\n" )->plain();
			}
		} else {
			$errors = $this->getErrorMessageArray( $rawErrors );
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
		$rawErrors = $this->sv->getErrors();
		if ( count( $rawErrors ) == 0 ) {
			if ( $this->sv->isOK() ) {
				$this->sv->fatal( 'internalerror_info',
					__METHOD__ . " called for a good result, this is incorrect\n" );
			} else {
				$this->sv->fatal( 'internalerror_info',
					__METHOD__ . ": Invalid result object: no error text but not OK\n" );
			}
			$rawErrors = $this->sv->getErrors(); // just added a fatal
		}
		if ( count( $rawErrors ) == 1 ) {
			$s = $this->getErrorMessage( $rawErrors[0] );
			if ( $shortContext ) {
				$s = wfMessage( $shortContext, $s );
			} elseif ( $longContext ) {
				$wrapper = new RawMessage( "* \$1\n" );
				$wrapper->params( $s )->parse();
				$s = wfMessage( $longContext, $wrapper );
			}
		} else {
			$msgs = $this->getErrorMessageArray( $rawErrors );
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
	 * @param mixed $error With an array & two values keyed by
	 * 'message' and 'params', use those keys-value pairs.
	 * Otherwise, if its an array, just use the first value as the
	 * message and the remaining items as the params.
	 *
	 * @return Message
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
	 * @param string $shortContext A short enclosing context message name, to
	 *        be used when there is a single error
	 * @param string $longContext A long enclosing context message name, for a list
	 * @return string
	 */
	public function getHTML( $shortContext = false, $longContext = false ) {
		$text = $this->getWikiText( $shortContext, $longContext );
		$out = MessageCache::singleton()->parse( $text, null, true, true );
		return $out instanceof ParserOutput ? $out->getText() : $out;
	}

	/**
	 * Return an array with a Message object for each error.
	 * @param array $errors
	 * @return Message[]
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
		$this->sv->merge( $other->sv, $overwriteValue );
	}

	/**
	 * Get the list of errors (but not warnings)
	 *
	 * @return array A list in which each entry is an array with a message key as its first element.
	 *         The remaining array elements are the message parameters.
	 * @deprecated 1.25
	 */
	public function getErrorsArray() {
		return $this->getStatusArray( 'error' );
	}

	/**
	 * Get the list of warnings (but not errors)
	 *
	 * @return array A list in which each entry is an array with a message key as its first element.
	 *         The remaining array elements are the message parameters.
	 * @deprecated 1.25
	 */
	public function getWarningsArray() {
		return $this->getStatusArray( 'warning' );
	}

	/**
	 * Returns a list of status messages of the given type (or all if false)
	 *
	 * @note: this handles RawMessage poorly
	 *
	 * @param string $type
	 * @return array
	 */
	protected function getStatusArray( $type = false ) {
		$result = array();

		foreach ( $this->sv->getErrors() as $error ) {
			if ( $type === false || $error['type'] === $type ) {
				if ( $error['message'] instanceof MessageSpecifier ) {
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
	 * @param string $type
	 *
	 * @return array
	 */
	public function getErrorsByType( $type ) {
		return $this->sv->getErrorsByType( $type );
	}

	/**
	 * Returns true if the specified message is present as a warning or error
	 *
	 * @param string|Message $message Message key or object to search for
	 *
	 * @return bool
	 */
	public function hasMessage( $message ) {
		return $this->sv->hasMessage( $message );
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
		return $this->sv->replaceMessage( $source, $dest );
	}

	/**
	 * @return mixed
	 */
	public function getValue() {
		return $this->sv->getValue();
	}

	/**
	 * Backwards compatibility logic
	 *
	 * @param string $name
	 */
	function __get( $name ) {
		if ( $name === 'ok' ) {
			return $this->sv->isOK();
		} elseif ( $name === 'errors' ) {
			return $this->sv->getErrors();
		}
		throw new Exception( "Cannot get '$name' property." );
	}

	/**
	 * Backwards compatibility logic
	 *
	 * @param string $name
	 * @param mixed $value
	 */
	function __set( $name, $value ) {
		if ( $name === 'ok' ) {
			$this->sv->setOK( $value );
		} elseif ( !property_exists( $this, $name ) ) {
			// Caller is using undeclared ad-hoc properties
			$this->$name = $value;
		} else {
			throw new Exception( "Cannot set '$name' property." );
		}
	}

	/**
	 * @return string
	 */
	public function __toString() {
		return $this->sv->__toString();
	}

	/**
	 * Don't save the callback when serializing, because Closures can't be
	 * serialized and we're going to clear it in __wakeup anyway.
	 */
	function __sleep() {
		$keys = array_keys( get_object_vars( $this ) );
		return array_diff( $keys, array( 'cleanCallback' ) );
	}

	/**
	 * Sanitize the callback parameter on wakeup, to avoid arbitrary execution.
	 */
	function __wakeup() {
		$this->cleanCallback = false;
	}
}
