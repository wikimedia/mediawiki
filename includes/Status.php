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
class Status extends StatusValue {
	/** @var callable|false */
	public $cleanCallback = false;

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
		if ( $sv instanceof static ) {
			return $sv;
		}

		$result = new static();
		$result->ok =& $sv->ok;
		$result->errors =& $sv->errors;
		$result->value =& $sv->value;
		$result->successCount =& $sv->successCount;
		$result->failCount =& $sv->failCount;
		$result->success =& $sv->success;

		return $result;
	}

	/**
	 * Backwards compatibility logic
	 *
	 * @param string $name
	 * @return mixed
	 * @throws RuntimeException
	 */
	function __get( $name ) {
		if ( $name === 'ok' ) {
			return $this->isOK();
		}
		if ( $name === 'errors' ) {
			return $this->getErrors();
		}

		throw new RuntimeException( "Cannot get '$name' property." );
	}

	/**
	 * Change operation result
	 * Backwards compatibility logic
	 *
	 * @param string $name
	 * @param mixed $value
	 * @throws RuntimeException
	 */
	function __set( $name, $value ) {
		if ( $name === 'ok' ) {
			$this->setOK( $value );
		} elseif ( !property_exists( $this, $name ) ) {
			// Caller is using undeclared ad-hoc properties
			$this->$name = $value;
		} else {
			throw new RuntimeException( "Cannot set '$name' property." );
		}
	}

	/**
	 * Splits this Status object into two new Status objects, one which contains only
	 * the error messages, and one that contains the warnings, only. The returned array is
	 * defined as:
	 * [
	 *     0 => object(Status) # The Status with error messages, only
	 *     1 => object(Status) # The Status with warning messages, only
	 * ]
	 *
	 * @return Status[]
	 */
	public function splitByErrorType() {
		list( $errorsOnlyStatus, $warningsOnlyStatus ) = parent::splitByErrorType();
		$errorsOnlyStatus->cleanCallback =
			$warningsOnlyStatus->cleanCallback = $this->cleanCallback;

		return [ $errorsOnlyStatus, $warningsOnlyStatus ];
	}

	/**
	 * Returns the wrapped StatusValue object
	 * @return StatusValue
	 * @since 1.27
	 */
	public function getStatusValue() {
		return $this;
	}

	/**
	 * @param array $params
	 * @return array
	 */
	protected function cleanParams( array $params ) {
		if ( !$this->cleanCallback ) {
			return $params;
		}
		$cleanParams = [];
		foreach ( $params as $i => $param ) {
			$cleanParams[$i] = call_user_func( $this->cleanCallback, $param );
		}
		return $cleanParams;
	}

	/**
	 * @param string|Language|null|StubUserLang $lang Language to use for processing
	 *  messages, or null to default to the user language.
	 * @return Language|StubUserLang
	 */
	protected function languageFromParam( $lang ) {
		if ( $lang === null ) {
			return RequestContext::getMain()->getLanguage();
		}
		if ( $lang instanceof Language || $lang instanceof StubUserLang ) {
			return $lang;
		}
		return Language::factory( $lang );
	}

	/**
	 * Get the error list as a wikitext formatted list
	 *
	 * @param string|bool $shortContext A short enclosing context message name, to
	 *        be used when there is a single error
	 * @param string|bool $longContext A long enclosing context message name, for a list
	 * @param string|Language|null|StubUserLang $lang Language to use for processing messages
	 * @return string
	 */
	public function getWikiText( $shortContext = false, $longContext = false, $lang = null ) {
		$lang = $this->languageFromParam( $lang );

		$rawErrors = $this->getErrors();
		if ( count( $rawErrors ) === 0 ) {
			if ( $this->isOK() ) {
				$this->fatal( 'internalerror_info',
					__METHOD__ . " called for a good result, this is incorrect\n" );
			} else {
				$this->fatal( 'internalerror_info',
					__METHOD__ . ": Invalid result object: no error text but not OK\n" );
			}
			$rawErrors = $this->getErrors(); // just added a fatal
		}
		if ( count( $rawErrors ) === 1 ) {
			$s = $this->getErrorMessage( $rawErrors[0], $lang )->plain();
			if ( $shortContext ) {
				$s = wfMessage( $shortContext, $s )->inLanguage( $lang )->plain();
			} elseif ( $longContext ) {
				$s = wfMessage( $longContext, "* $s\n" )->inLanguage( $lang )->plain();
			}
		} else {
			$errors = $this->getErrorMessageArray( $rawErrors, $lang );
			foreach ( $errors as &$error ) {
				$error = $error->plain();
			}
			$s = '* ' . implode( "\n* ", $errors ) . "\n";
			if ( $longContext ) {
				$s = wfMessage( $longContext, $s )->inLanguage( $lang )->plain();
			} elseif ( $shortContext ) {
				$s = wfMessage( $shortContext, "\n$s\n" )->inLanguage( $lang )->plain();
			}
		}
		return $s;
	}

	/**
	 * Get a bullet list of the errors as a Message object.
	 *
	 * $shortContext and $longContext can be used to wrap the error list in some text.
	 * $shortContext will be preferred when there is a single error; $longContext will be
	 * preferred when there are multiple ones. In either case, $1 will be replaced with
	 * the list of errors.
	 *
	 * $shortContext is assumed to use $1 as an inline parameter: if there is a single item,
	 * it will not be made into a list; if there are multiple items, newlines will be inserted
	 * around the list.
	 * $longContext is assumed to use $1 as a standalone parameter; it will always receive a list.
	 *
	 * If both parameters are missing, and there is only one error, no bullet will be added.
	 *
	 * @param string|string[]|bool $shortContext A message name or an array of message names.
	 * @param string|string[]|bool $longContext A message name or an array of message names.
	 * @param string|Language|null $lang Language to use for processing messages
	 * @return Message
	 */
	public function getMessage( $shortContext = false, $longContext = false, $lang = null ) {
		$lang = $this->languageFromParam( $lang );

		$rawErrors = $this->getErrors();
		if ( count( $rawErrors ) === 0 ) {
			if ( $this->isOK() ) {
				$this->fatal( 'internalerror_info',
					__METHOD__ . " called for a good result, this is incorrect\n" );
			} else {
				$this->fatal( 'internalerror_info',
					__METHOD__ . ": Invalid result object: no error text but not OK\n" );
			}
			$rawErrors = $this->getErrors(); // just added a fatal
		}
		if ( count( $rawErrors ) === 1 ) {
			$s = $this->getErrorMessage( $rawErrors[0], $lang );
			if ( $shortContext ) {
				$s = wfMessage( $shortContext, $s )->inLanguage( $lang );
			} elseif ( $longContext ) {
				$wrapper = new RawMessage( "* \$1\n" );
				$wrapper->params( $s )->parse();
				$s = wfMessage( $longContext, $wrapper )->inLanguage( $lang );
			}
		} else {
			$msgs = $this->getErrorMessageArray( $rawErrors, $lang );
			$msgCount = count( $msgs );

			$s = new RawMessage( '* $' . implode( "\n* \$", range( 1, $msgCount ) ) );
			$s->params( $msgs )->parse();

			if ( $longContext ) {
				$s = wfMessage( $longContext, $s )->inLanguage( $lang );
			} elseif ( $shortContext ) {
				$wrapper = new RawMessage( "\n\$1\n", [ $s ] );
				$wrapper->parse();
				$s = wfMessage( $shortContext, $wrapper )->inLanguage( $lang );
			}
		}

		return $s;
	}

	/**
	 * Return the message for a single error
	 *
	 * The code string can be used a message key with per-language versions.
	 * If $error is an array, the "params" field is a list of parameters for the message.
	 *
	 * @param array|string $error Code string or (key: code string, params: string[]) map
	 * @param string|Language|null $lang Language to use for processing messages
	 * @return Message
	 */
	protected function getErrorMessage( $error, $lang = null ) {
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
		} elseif ( is_string( $error ) ) {
			$msg = wfMessage( $error );
		} else {
			throw new UnexpectedValueException( 'Got ' . get_class( $error ) . ' for key.' );
		}

		$msg->inLanguage( $this->languageFromParam( $lang ) );
		return $msg;
	}

	/**
	 * Get the error message as HTML. This is done by parsing the wikitext error message
	 * @param string|bool $shortContext A short enclosing context message name, to
	 *        be used when there is a single error
	 * @param string|bool $longContext A long enclosing context message name, for a list
	 * @param string|Language|null $lang Language to use for processing messages
	 * @return string
	 */
	public function getHTML( $shortContext = false, $longContext = false, $lang = null ) {
		$lang = $this->languageFromParam( $lang );
		$text = $this->getWikiText( $shortContext, $longContext, $lang );
		$out = MessageCache::singleton()->parse( $text, null, true, true, $lang );
		return $out instanceof ParserOutput
			? $out->getText( [ 'enableSectionEditLinks' => false ] )
			: $out;
	}

	/**
	 * Return an array with a Message object for each error.
	 * @param array $errors
	 * @param string|Language|null $lang Language to use for processing messages
	 * @return Message[]
	 */
	protected function getErrorMessageArray( $errors, $lang = null ) {
		$lang = $this->languageFromParam( $lang );
		return array_map( function ( $e ) use ( $lang ) {
			return $this->getErrorMessage( $e, $lang );
		}, $errors );
	}

	/**
	 * Get the list of errors (but not warnings)
	 *
	 * @return array A list in which each entry is an array with a message key as its first element.
	 *         The remaining array elements are the message parameters.
	 * @deprecated since 1.25
	 */
	public function getErrorsArray() {
		return $this->getStatusArray( 'error' );
	}

	/**
	 * Get the list of warnings (but not errors)
	 *
	 * @return array A list in which each entry is an array with a message key as its first element.
	 *         The remaining array elements are the message parameters.
	 * @deprecated since 1.25
	 */
	public function getWarningsArray() {
		return $this->getStatusArray( 'warning' );
	}

	/**
	 * Returns a list of status messages of the given type (or all if false)
	 *
	 * @note this handles RawMessage poorly
	 *
	 * @param string|bool $type
	 * @return array
	 */
	protected function getStatusArray( $type = false ) {
		$result = [];

		foreach ( $this->getErrors() as $error ) {
			if ( $type === false || $error['type'] === $type ) {
				if ( $error['message'] instanceof MessageSpecifier ) {
					$result[] = array_merge(
						[ $error['message']->getKey() ],
						$error['message']->getParams()
					);
				} elseif ( $error['params'] ) {
					$result[] = array_merge( [ $error['message'] ], $error['params'] );
				} else {
					$result[] = [ $error['message'] ];
				}
			}
		}

		return $result;
	}

	/**
	 * Don't save the callback when serializing, because Closures can't be
	 * serialized and we're going to clear it in __wakeup anyway.
	 * @return array
	 */
	function __sleep() {
		$keys = array_keys( get_object_vars( $this ) );
		return array_diff( $keys, [ 'cleanCallback' ] );
	}

	/**
	 * Sanitize the callback parameter on wakeup, to avoid arbitrary execution.
	 */
	function __wakeup() {
		$this->cleanCallback = false;
	}
}
