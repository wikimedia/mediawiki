<?php
/**
 *
 *
 * Created on Sep 4, 2006
 *
 * Copyright © 2006 Yuri Astrakhan "<Firstname><Lastname>@gmail.com"
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
 * This class represents the result of the API operations.
 * It simply wraps a nested array() structure, adding some functions to simplify
 * array's modifications. As various modules execute, they add different pieces
 * of information to this result, structuring it as it will be given to the client.
 *
 * Each subarray may either be a dictionary - key-value pairs with unique keys,
 * or lists, where the items are added using $data[] = $value notation.
 *
 * @since 1.25 this is no longer a subclass of ApiBase
 * @ingroup API
 */
class ApiResult {

	/**
	 * Override existing value in addValue(), setValue(), and similar functions
	 * @since 1.21
	 */
	const OVERRIDE = 1;

	/**
	 * For addValue(), setValue() and similar functions, if the value does not
	 * exist, add it as the first element. In case the new value has no name
	 * (numerical index), all indexes will be renumbered.
	 * @since 1.21
	 */
	const ADD_ON_TOP = 2;

	/**
	 * For addValue() and similar functions, do not check size while adding a value
	 * Don't use this unless you REALLY know what you're doing.
	 * Values added while the size checking was disabled will never be counted.
	 * Ignored for setValue() and similar functions.
	 * @since 1.24
	 */
	const NO_SIZE_CHECK = 4;

	/**
	 * For addValue(), setValue() and similar functions, do not validate data.
	 * Also disables size checking. If you think you need to use this, you're
	 * probably wrong.
	 * @since 1.25
	 */
	const NO_VALIDATE = 12;

	/**
	 * Key for the 'indexed tag name' metadata item. Value is string.
	 * @since 1.25
	 */
	const META_INDEXED_TAG_NAME = '_element';

	/**
	 * Key for the 'subelements' metadata item. Value is string[].
	 * @since 1.25
	 */
	const META_SUBELEMENTS = '_subelements';

	/**
	 * Key for the 'preserve keys' metadata item. Value is string[].
	 * @since 1.25
	 */
	const META_PRESERVE_KEYS = '_preservekeys';

	/**
	 * Key for the 'content' metadata item. Value is string.
	 * @since 1.25
	 */
	const META_CONTENT = '_content';

	/**
	 * Key for the 'type' metadata item. Value is a string.
	 * @see self::setArrayType()
	 * @since 1.25
	 */
	const META_TYPE = '_type';

	/**
	 * Key for when META_TYPE is 'kvp'. Value is string.
	 * @since 1.25
	 */
	const META_KVP_KEY_NAME = '_kvpkeyname';

	/**
	 * Key for the 'BC bools' metadata item. Value is string[].
	 * Note no setter is provided.
	 * @since 1.25
	 */
	const META_BC_BOOLS = '_BC_bools';

	/**
	 * Key for the 'BC subelements' metadata item. Value is string[].
	 * Note no setter is provided.
	 * @since 1.25
	 */
	const META_BC_SUBELEMENTS = '_BC_subelements';

	private $context;
	private $mData, $mIsRawMode, $mSize, $mCheckingSize;
	private $errorFormat, $errorLang, $errorUseDB;

	private $continueAllModules = array();
	private $continueGeneratedModules = array();
	private $continuationData = array();
	private $generatorContinuationData = array();
	private $generatorParams = array();
	private $generatorDone = false;

	/**
	 * @param IContextSource $context
	 * @param string $errorFormat Format for errors and warnings:
	 *  'wikitext': Expanded wikitext
	 *  'html': HTML
	 *  'raw': Array of message key and parameters
	 *  'bc': Unexpanded wikitext concatenated into a single string
	 * @param Language|string $errorLang Language to use for errors and warnings.
	 * @param bool $useDB Whether to use local translations for errors and warnings.
	 */
	public function __construct(
		IContextSource $context, $errorFormat = 'bc', $errorLang = 'en', $errorUseDB = false
	) {
		$this->context = $context;
		$this->errorFormat = $errorFormat;
		$this->errorLang = $errorLang;
		$this->errorUseDB = $errorUseDB;
		$this->errorContext = null;
		$this->mIsRawMode = false;
		$this->mCheckingSize = true;
		$this->reset();
	}

	/************************************************************************//**
	 * @name   Content
	 * @{
	 */

	/**
	 * Clear the current result data.
	 */
	public function reset() {
		$this->mData = array();
		$this->mSize = 0;
	}

	/**
	 * Get the result data array
	 *
	 * The returned value should be considered read-only.
	 *
	 * @since 1.25
	 * @param array|string|null $path Path to fetch, see ApiResult::addValue
	 * @return mixed Result data, or null if not found
	 */
	public function getResultData( $path = array() ) {
		$path = (array)$path;
		if ( !$path ) {
			return $this->mData;
		}

		$last = array_pop( $path );
		$ret = &$this->path( $path, 'dummy' );
		return isset( $ret[$last] ) ? $ret[$last] : null;
	}

	/**
	 * Get the size of the result, i.e. the amount of bytes in it
	 * @return int
	 */
	public function getSize() {
		return $this->mSize;
	}

	/**
	 * Add an output value to the array by name.
	 *
	 * Verifies that value with the same name has not been added before.
	 *
	 * @since 1.25
	 * @param array &$arr To add $value to
	 * @param string|int|null $name Index of $arr to add $value at,
	 *   or null to use the next numeric index.
	 * @param mixed $value
	 * @param int $flags Zero or more OR-ed flags like OVERRIDE | ADD_ON_TOP.
	 */
	public static function setValue( array &$arr, $name, $value, $flags = 0 ) {
		if ( $name === null ) {
			if ( $flags & ApiResult::ADD_ON_TOP ) {
				array_unshift( $arr, $value );
			} else {
				array_push( $arr, $value );
			}
			return;
		}

		if ( !( $flags & ApiResult::NO_VALIDATE ) ) {
			$value = self::validateValue( $value );
		}

		$exists = isset( $arr[$name] );
		if ( !$exists || ( $flags & ApiResult::OVERRIDE ) ) {
			if ( !$exists && ( $flags & ApiResult::ADD_ON_TOP ) ) {
				$arr = array( $name => $value ) + $arr;
			} else {
				$arr[$name] = $value;
			}
		} elseif ( is_array( $arr[$name] ) && is_array( $value ) ) {
			$conflicts = array_intersect_key( $arr[$name], $value );
			if ( !$conflicts ) {
				$arr[$name] += $value;
			} else {
				$keys = join( ', ', array_keys( $conflicts ) );
				throw new RuntimeException( "Conflicting keys ($keys) when attempting to merge element $name" );
			}
		} else {
			throw new RuntimeException( "Attempting to add element $name=$value, existing value is {$arr[$name]}" );
		}
	}

	/**
	 * Validate a value for addition to the result
	 * @param mixed $value
	 */
	private static function validateValue( $value ) {
		if ( is_object( $value ) ) {
			// Note we use is_callable() here instead of instanceof because
			// ApiSerializable is an informal protocol (see docs there for details).
			if ( is_callable( array( $value, 'serializeForApiResult' ) ) ) {
				$oldValue = $value;
				$value = $value->serializeForApiResult();
				if ( is_object( $value ) ) {
					throw new UnexpectedValueException(
						get_class( $oldValue ) . "::serializeForApiResult() returned an object of class " .
							get_class( $value )
					);
				}

				// Recursive call instead of fall-through so we can throw a
				// better exception message.
				try {
					return self::validateValue( $value );
				} catch ( Exception $ex ) {
					throw new UnexpectedValueException(
						get_class( $oldValue ) . "::serializeForApiResult() returned an invalid value: " .
							$ex->getMessage(),
						0,
						$ex
					);
				}
			} elseif ( is_callable( array( $value, '__toString' ) ) ) {
				$value = (string)$value;
			} else {
				$value = (array)$value + array( self::META_TYPE => 'assoc' );
			}
		}
		if ( is_array( $value ) ) {
			foreach ( $value as $k => $v ) {
				$value[$k] = self::validateValue( $v );
			}
		} elseif ( is_float( $value ) && !is_finite( $value ) ) {
			throw new InvalidArgumentException( "Cannot add non-finite floats to ApiResult" );
		} elseif ( $value !== null && !is_scalar( $value ) ) {
			$type = gettype( $value );
			if ( is_resource( $value ) ) {
				$type .= '(' . get_resource_type( $value ) . ')';
			}
			throw new InvalidArgumentException( "Cannot add $type to ApiResult" );
		}

		return $value;
	}

	/**
	 * Add value to the output data at the given path.
	 *
	 * Path can be an indexed array, each element specifying the branch at which to add the new
	 * value. Setting $path to array('a','b','c') is equivalent to data['a']['b']['c'] = $value.
	 * If $path is null, the value will be inserted at the data root.
	 *
	 * @param array|string|int|null $path
	 * @param string|int|null $name See ApiResult::setValue()
	 * @param mixed $value
	 * @param int $flags Zero or more OR-ed flags like OVERRIDE | ADD_ON_TOP.
	 *   This parameter used to be boolean, and the value of OVERRIDE=1 was specifically
	 *   chosen so that it would be backwards compatible with the new method signature.
	 * @return bool True if $value fits in the result, false if not
	 * @since 1.21 int $flags replaced boolean $override
	 */
	public function addValue( $path, $name, $value, $flags = 0 ) {
		$arr = &$this->path( $path, ( $flags & ApiResult::ADD_ON_TOP ) ? 'prepend' : 'append' );

		if ( $this->mCheckingSize && !( $flags & ApiResult::NO_SIZE_CHECK ) ) {
			$newsize = $this->mSize + self::size( $value );
			$maxResultSize = $this->context->getConfig()->get( 'APIMaxResultSize' );
			if ( $newsize > $maxResultSize ) {
				/// @todo Add i18n message when replacing calls to ->setWarning()
				$msg = new RawMessage( "This result was truncated because it would otherwise be " .
					" larger than the limit of $1 bytes" );
				$msg->numParams( $maxResultSize );
				$this->addWarning( 'result', $msg, 'truncatedresult' );
				return false;
			}
			$this->mSize = $newsize;
		}

		self::setValue( $arr, $name, $value, $flags );
		return true;
	}

	/**
	 * Remove an output value to the array by name.
	 *
	 * @since 1.25
	 * @param array &$arr To remove $value from
	 * @param string|int $name Index of $arr to remove
	 * @return mixed Old value, or null
	 */
	public static function deleteValue( array &$arr, $name ) {
		$ret = null;
		if ( isset( $arr[$name] ) ) {
			$ret = $arr[$name];
			unset( $arr[$name] );
		}
		return $ret;
	}

	/**
	 * Remove value from the output data at the given path.
	 *
	 * @since 1.25
	 * @param array|string|null $path See ApiResult::addValue()
	 * @param string|int|null $name Index to remove at $path.
	 *   If null, $path itself is removed.
	 * @param int $flags Flags used when adding the value
	 * @return mixed Old value, or null
	 */
	public function removeValue( $path, $name, $flags = 0 ) {
		$path = (array)$path;
		if ( $name === null ) {
			if ( !$path ) {
				throw new InvalidArgumentException( 'Cannot remove the data root' );
			}
			$name = array_pop( $path );
		}
		$ret = self::deleteValue( $this->path( $path, 'dummy' ), $name );
		if ( $this->mCheckingSize && !( $flags & ApiResult::NO_SIZE_CHECK ) ) {
			$newsize = $this->mSize - self::size( $ret );
			$this->mSize = max( $newsize, 0 );
		}
		return $ret;
	}

	/**
	 * Add an output value to the array by name and mark as META_CONTENT.
	 *
	 * @since 1.25
	 * @param array &$arr To add $value to
	 * @param string|int $name Index of $arr to add $value at.
	 * @param mixed $value
	 * @param int $flags Zero or more OR-ed flags like OVERRIDE | ADD_ON_TOP.
	 */
	public static function setContentValue( array &$arr, $name, $value, $flags = 0 ) {
		if ( $name === null ) {
			throw new InvalidArgumentException( 'Content value must be named' );
		}
		self::setContentField( $arr, $name, $flags );
		self::setValue( $arr, $name, $value, $flags );
	}

	/**
	 * Add value to the output data at the given path and mark as META_CONTENT
	 *
	 * @since 1.25
	 * @param array|string|null $path See ApiResult::addValue()
	 * @param string|int $name See ApiResult::setValue()
	 * @param mixed $value
	 * @param int $flags Zero or more OR-ed flags like OVERRIDE | ADD_ON_TOP.
	 * @return bool True if $value fits in the result, false if not
	 */
	public function addContentValue( $path, $name, $value, $flags = 0 ) {
		if ( $name === null ) {
			throw new InvalidArgumentException( 'Content value must be named' );
		}
		$this->defineContentField( $path, $name, $flags );
		$this->addValue( $path, $name, $value, $flags );
	}

	/**
	 * Add a parsed limit=max to the result.
	 *
	 * @since 1.25
	 * @param string $moduleName
	 * @param int $limit
	 */
	public function addParsedLimit( $moduleName, $limit ) {
		// Add value, allowing overwriting
		$this->addValue( 'limits', $moduleName, $limit,
			ApiResult::OVERRIDE | ApiResult::NO_SIZE_CHECK );
	}

	/**
	 * Add a warning to the result
	 * @since 1.25
	 * @param string $moduleName
	 * @param Message|array|string $msg i18n message for the warning
	 * @param string $code Machine-readable code for the warning. Defaults to
	 *   $msg->apiMessageCode if set, then to the message key.
	 * @param array $data Machine-readable data for the warning, if any.
	 *   Defaults to $msg->apiMessageData if set.
	 */
	public function addWarning( $moduleName, $msg, $code = null, $data = null ) {
		$this->addWarningOrError( 'warnings', $moduleName, $msg, $code, $data );
	}

	/**
	 * Add an error to the result
	 * @since 1.25
	 * @param string $moduleName
	 * @param Message|array|string $msg i18n message for the error
	 * @param string $code Machine-readable code for the warning. Defaults to
	 *   $msg->apiMessageCode if set, then to the message key.
	 * @param array $data Machine-readable data for the warning, if any.
	 *   Defaults to $msg->apiMessageData if set.
	 */
	public function addError( $moduleName, $msg, $code = null, $data = null ) {
		$this->addWarningOrError( 'errors', $moduleName, $msg, $code, $data );
	}

	/**
	 * Add warnings and errors from a Status object to the result
	 * @since 1.25
	 * @param string $moduleName
	 * @param Status $status
	 */
	public function addMessagesFromStatus( $moduleName, Status $status ) {
		foreach ( $status->errors as $error ) {
			if ( $error['type'] === 'error' ) {
				$tag = 'errors';
			} else {
				// Assume any unknown type is a warning
				$tag = 'warnings';
			}

			if ( is_array( $error ) && isset( $error['message'] ) ) {
				// Normal case
				if ( $error['message'] instanceof Message ) {
					$msg = $error['message'];
				} else {
					$error += array( 'params' => array() );
					$msg = $this->context->msg( $error['message'], $error['params'] );
				}
			} elseif ( is_array( $error ) ) {
				// Weird case handled by Message::getErrorMessage
				$msg = call_user_func_array( array( $this, 'msg' ), $error );
			} else {
				// Another weird case handled by Message::getErrorMessage
				$msg = $this->context->msg( $error );
			}

			$this->addWarningOrError( $tag, $moduleName, $msg, null, null );
		}
	}

	/**
	 * @param string $tag 'warnings' or 'error'
	 * @param string $moduleName
	 * @param Message|array|string $msg
	 * @param string|null $code
	 * @param array|null $data
	 */
	private function addWarningOrError( $tag, $moduleName, $msg, $code, $data ) {
		if ( $msg instanceof Message ) {
			if ( $code === null && isset( $msg->apiMessageCode ) ) {
				$code = $msg->apiMessageCode;
			}
			if ( $data === null && isset( $msg->apiMessageData ) ) {
				$data = $msg->apiMessageData;
			}
		}

		if ( $this->errorContext === null ) {
			$this->errorContext = new DerivativeContext( $this->context );
			$this->errorContext->setLanguage( $this->errorLang );
		}
		$msg = ApiBase::makeMessage( $msg, $this->errorContext )
			->useDatabase( $this->errorUseDB );

		if ( $code === null ) {
			$code = $msg->getKey();
		}

		switch ( $this->errorFormat ) {
			case 'bc':
				$value = $msg->plain();

				if ( $tag === 'errors' ) {
					// In BC mode, only one error
					$value = array(
						'code' => $code,
						'info' => $value,
					);
					if ( $data !== null ) {
						$value += $data;
					}
					if ( isset( ApiBase::$messageMap[$code] ) ) {
						// Backwards compatibility
						$value['code'] = ApiBase::$messageMap[$code]['code'];
					}
					$this->addValue( null, 'error', $value,
						ApiResult::OVERRIDE | ApiResult::ADD_ON_TOP | ApiResult::NO_SIZE_CHECK );
				} else {
					// Don't add duplicate warnings
					if ( isset( $this->mData['warnings'][$moduleName] ) ) {
						$oldWarning = $this->mData['warnings'][$moduleName]['warnings'];
						$warnPos = strpos( $oldWarning, $value );
						// If $value was found in $oldWarning, check if it starts at 0 or after "\n"
						if ( $warnPos !== false && ( $warnPos === 0 || $oldWarning[$warnPos - 1] === "\n" ) ) {
							// Check if $value is followed by "\n" or the end of the $oldWarning
							$warnPos += strlen( $value );
							if ( strlen( $oldWarning ) <= $warnPos || $oldWarning[$warnPos] === "\n" ) {
								return;
							}
						}
						// If there is a warning already, append it to the existing one
						$value = "$oldWarning\n$value";
					}
					$this->addContentValue( array( $tag, $moduleName ), $tag, $value,
						ApiResult::OVERRIDE | ApiResult::ADD_ON_TOP | ApiResult::NO_SIZE_CHECK );
				}
				return;

			case 'wikitext':
				$value = array(
					'text' => $msg->text(),
					self::META_CONTENT => 'text',
				);
				break;

			case 'html':
				$value = array(
					'html' => $msg->parse(),
					self::META_CONTENT => 'html',
				);
				break;

			case 'raw':
				$value = array(
					'key' => $msg->getKey(),
					'params' => $msg->getParams(),
				);
				break;

			case 'none':
				$value = array();
				break;
		}

		$value = array( 'code' => $code ) + $value;
		if ( $data !== null ) {
			$value += $data;
		}

		$arr = &$this->path( array( $tag, $moduleName ), 'prepend' );
		if ( !in_array( $value, $arr ) ) {
			$arr[] = $value;
		}
	}

	/**@}*/

	/************************************************************************//**
	 * @name   Metadata
	 * @{
	 */

	/**
	 * Set the name of the content field name (META_CONTENT)
	 *
	 * @since 1.25
	 * @param array &$arr
	 * @param string|int $name Name of the field
	 * @param int $flags Zero or more OR-ed flags like OVERRIDE | ADD_ON_TOP.
	 */
	public static function setContentField( array &$arr, $name, $flags = 0 ) {
		if ( isset( $arr[self::META_CONTENT] ) &&
			isset( $arr[$arr[self::META_CONTENT]] ) &&
			!( $flags & self::OVERRIDE )
		) {
			throw new RuntimeException(
				"Attempting to set content element as $name when " . $arr[self::META_CONTENT] .
					" is already set as the content element"
			);
		}
		$arr[self::META_CONTENT] = $name;
	}

	/**
	 * Set the name of the content field name (META_CONTENT)
	 *
	 * @since 1.25
	 * @param array|string|null $path See ApiResult::addValue()
	 * @param string|int $name Name of the field
	 * @param int $flags Zero or more OR-ed flags like OVERRIDE | ADD_ON_TOP.
	 */
	public function defineContentField( $path, $name, $flags = 0 ) {
		$arr = &$this->path( $path, ( $flags & ApiResult::ADD_ON_TOP ) ? 'prepend' : 'append' );
		self::setContentField( $arr, $name, $flags );
	}

	/**
	 * Causes the elements with the specified names to be output as
	 * subelements rather than attributes.
	 * @since 1.25 is static
	 * @param array &$arr
	 * @param array|string|int $names The element name(s) to be output as subelements
	 */
	public static function setSubelements( array &$arr, $names ) {
		if ( !isset( $arr[self::META_SUBELEMENTS] ) ) {
			$arr[self::META_SUBELEMENTS] = (array)$names;
		} else {
			$arr[self::META_SUBELEMENTS] = array_merge( $arr[self::META_SUBELEMENTS], (array)$names );
		}
	}

	/**
	 * Causes the elements with the specified names to be output as
	 * subelements rather than attributes.
	 * @since 1.25
	 * @param array|string|null $path See ApiResult::addValue()
	 * @param array|string|int $names The element name(s) to be output as subelements
	 */
	public function defineSubelements( $path, $names ) {
		$arr = &$this->path( $path );
		self::setSubelements( $arr, $names );
	}

	/**
	 * Causes the elements with the specified names to be output as
	 * attributes (when possible) rather than as subelements.
	 * @since 1.25
	 * @param array &$arr
	 * @param array|string|int $names The element name(s) to not be output as subelements
	 */
	public static function unsetSubelements( array &$arr, $names ) {
		if ( isset( $arr[self::META_SUBELEMENTS] ) ) {
			$arr[self::META_SUBELEMENTS] = array_diff( $arr[self::META_SUBELEMENTS], (array)$names );
		}
	}

	/**
	 * Causes the elements with the specified names to be output as
	 * attributes (when possible) rather than as subelements.
	 * @since 1.25
	 * @param array|string|null $path See ApiResult::addValue()
	 * @param array|string|int $names The element name(s) to not be output as subelements
	 */
	public function undefineSubelements( $path, $names ) {
		$arr = &$this->path( $path );
		self::unsetSubelements( $arr, $names );
	}

	/**
	 * Set the tag name for numeric-keyed values in XML format
	 * @since 1.25 is static
	 * @param array &$arr
	 * @param string $tag Tag name
	 */
	public static function setIndexedTagName( array &$arr, $tag ) {
		if ( !is_string( $tag ) ) {
			throw new InvalidArgumentException( 'Bad tag name' );
		}
		$arr[self::META_INDEXED_TAG_NAME] = $tag;
	}

	/**
	 * Set the tag name for numeric-keyed values in XML format
	 * @since 1.25
	 * @param array|string|null $path See ApiResult::addValue()
	 * @param string $tag Tag name
	 */
	public function defineIndexedTagName( $path, $tag ) {
		$arr = &$this->path( $path );
		self::setIndexedTagName( $arr, $tag );
	}

	/**
	 * Set indexed tag name on all subarrays of $arr
	 *
	 * Does not set the tag name for $arr itself.
	 *
	 * @since 1.25
	 * @param array &$arr
	 * @param string $tag Tag name
	 */
	public static function setIndexedTagNameOnSubarrays( array &$arr, $tag ) {
		if ( !is_string( $tag ) ) {
			throw new InvalidArgumentException( 'Bad tag name' );
		}
		foreach ( $arr as $k => &$v ) {
			if ( !self::isMetadataKey( $k ) && is_array( $v ) ) {
				$v[self::META_INDEXED_TAG_NAME] = $tag;
				self::setIndexedTagNameOnSubarrays( $v, $tag );
			}
		}
	}

	/**
	 * Set indexed tag name recursively on all subarrays
	 *
	 * Does not set the tag name for $arr itself.
	 *
	 * @since 1.25
	 * @param array|string|null $path See ApiResult::addValue()
	 * @param string $tag Tag name
	 */
	public function defineIndexedTagNameOnSubarrays( $path, $tag ) {
		$arr = &$this->path( $path );
		self::setIndexedTagNameOnSubarrays( $arr, $tag );
	}

	/**
	 * Preserve specified keys.
	 *
	 * This prevents XML name mangling and preventing keys from being removed
	 * by self::removeMetadata().
	 *
	 * @since 1.25
	 * @param array &$arr
	 * @param array|string $names The element name(s) to preserve
	 */
	public static function setPreserveKeys( array &$arr, $names ) {
		if ( !isset( $arr[self::META_PRESERVE_KEYS] ) ) {
			$arr[self::META_PRESERVE_KEYS] = (array)$names;
		} else {
			$arr[self::META_PRESERVE_KEYS] = array_merge( $arr[self::META_PRESERVE_KEYS], (array)$names );
		}
	}

	/**
	 * Preserve specified keys.
	 * @since 1.25
	 * @see self::setPreserveKeys()
	 * @param array|string|null $path See ApiResult::addValue()
	 * @param array|string $names The element name(s) to preserve
	 */
	public function definePreserveKeys( $path, $names ) {
		$arr = &$this->path( $path );
		self::setPreserveKeys( $arr, $names );
	}

	/**
	 * Don't preserve specified keys.
	 * @since 1.25
	 * @see self::setPreserveKeys()
	 * @param array &$arr
	 * @param array|string $names The element name(s) to not preserve
	 */
	public static function unsetPreserveKeys( array &$arr, $names ) {
		if ( isset( $arr[self::META_PRESERVE_KEYS] ) ) {
			$arr[self::META_PRESERVE_KEYS] = array_diff( $arr[self::META_PRESERVE_KEYS], (array)$names );
		}
	}

	/**
	 * Don't preserve specified keys.
	 * @since 1.25
	 * @see self::setPreserveKeys()
	 * @param array|string|null $path See ApiResult::addValue()
	 * @param array|string $names The element name(s) to not preserve
	 */
	public function undefinePreserveKeys( $path, $names ) {
		$arr = &$this->path( $path );
		self::unsetPreserveKeys( $arr, $names );
	}

	/**
	 * Set the array data type
	 *
	 * @since 1.25
	 * @param array &$arr
	 * @param string $type See ApiResult::transformForTypes()
	 * @param string $kvpKeyName Key name for 'kvp' and 'BCkvp'
	 */
	public static function setArrayType( array &$arr, $type, $kvpKeyName = null ) {
		if ( !in_array( $type, array( 'default', 'array', 'assoc', 'kvp', 'BCarray', 'BCassoc', 'BCkvp' ), true ) ) {
			throw new InvalidArgumentException( 'Bad type' );
		}
		$arr[self::META_TYPE] = $type;
		if ( is_string( $kvpKeyName ) ) {
			$arr[self::META_KVP_KEY_NAME] = $kvpKeyName;
		}
	}

	/**
	 * Set the array data type for a path
	 * @since 1.25
	 * @param array|string|null $path See ApiResult::addValue()
	 * @param string $type See ApiResult::transformForTypes()
	 * @param string $kvpKeyName See ApiResult::setArrayType()
	 */
	public function defineArrayType( $path, $tag, $kvpKeyName = null ) {
		$arr = &$this->path( $path );
		self::setArrayType( $arr, $tag, $kvpKeyName );
	}

	/**
	 * Set the array data type recursively
	 * @since 1.25
	 * @param array &$arr
	 * @param string $type See ApiResult::transformForTypes()
	 * @param string $kvpKeyName See ApiResult::setArrayType()
	 */
	public static function setArrayTypeRecursive( array &$arr, $type, $kvpKeyName = null ) {
		self::setArrayType( $arr, $type, $kvpKeyName );
		foreach ( $arr as $k => &$v ) {
			if ( !self::isMetadataKey( $k ) && is_array( $v ) ) {
				self::setArrayTypeRecursive( $v, $type, $kvpKeyName );
			}
		}
	}

	/**
	 * Set the array data type for a path recursively
	 * @since 1.25
	 * @param array|string|null $path See ApiResult::addValue()
	 * @param string $type See ApiResult::transformForTypes()
	 * @param string $kvpKeyName See ApiResult::setArrayType()
	 */
	public function defineArrayTypeRecursive( $path, $tag, $kvpKeyName = null ) {
		$arr = &$this->path( $path );
		self::setArrayTypeRecursive( $arr, $tag, $kvpKeyName );
	}

	/**@}*/

	/************************************************************************//**
	 * @name   Continuation
	 * @{
	 */

	/**
	 * Parse a 'continue' parameter and return status information.
	 *
	 * This must be balanced by a call to endContinuation().
	 *
	 * @since 1.24
	 * @param string|null $continue The "continue" parameter, if any
	 * @param ApiBase[] $allModules Contains ApiBase instances that will be executed
	 * @param array $generatedModules Names of modules that depend on the generator
	 * @return array Two elements: a boolean indicating if the generator is done,
	 *   and an array of modules to actually execute.
	 */
	public function beginContinuation(
		$continue, array $allModules = array(), array $generatedModules = array()
	) {
		$this->continueGeneratedModules = $generatedModules
			? array_combine( $generatedModules, $generatedModules )
			: array();
		$this->continuationData = array();
		$this->generatorContinuationData = array();
		$this->generatorParams = array();

		$skip = array();
		if ( is_string( $continue ) && $continue !== '' ) {
			$continue = explode( '||', $continue );
			if ( count( $continue ) !== 2 ) {
				throw new UsageException(
					'Invalid continue param. You should pass the original value returned by the previous query',
					'badcontinue'
				);
			}
			$this->generatorDone = ( $continue[0] === '-' );
			$skip = explode( '|', $continue[1] );
			if ( !$this->generatorDone ) {
				$this->generatorParams = explode( '|', $continue[0] );
			} else {
				// When the generator is complete, don't run any modules that
				// depend on it.
				$skip += $this->continueGeneratedModules;
			}
		}

		$this->continueAllModules = array();
		$runModules = array();
		foreach ( $allModules as $module ) {
			$name = $module->getModuleName();
			if ( in_array( $name, $skip ) ) {
				$this->continueAllModules[$name] = false;
				// Prevent spurious "unused parameter" warnings
				$module->extractRequestParams();
			} else {
				$this->continueAllModules[$name] = true;
				$runModules[] = $module;
			}
		}

		return array(
			$this->generatorDone,
			$runModules,
		);
	}

	/**
	 * Set the continuation parameter for a module
	 *
	 * @since 1.25
	 * @param ApiBase $module
	 * @param string $paramName
	 * @param string|array $paramValue
	 * @throws UnexpectedValueException
	 */
	public function addContinueParam( ApiBase $module, $paramName, $paramValue ) {
		$name = $module->getModuleName();
		if ( !isset( $this->continueAllModules[$name] ) ) {
			throw new UnexpectedValueException(
				"Module '$name' called ApiResult::addContinueParam but was not " .
				'passed to ApiResult::beginContinuation'
			);
		}
		if ( !$this->continueAllModules[$name] ) {
			throw new UnexpectedValueException(
				"Module '$name' was not supposed to have been executed, but " .
				'it was executed anyway'
			);
		}
		$paramName = $module->encodeParamName( $paramName );
		if ( is_array( $paramValue ) ) {
			$paramValue = join( '|', $paramValue );
		}
		$this->continuationData[$name][$paramName] = $paramValue;
	}

	/**
	 * Set the continuation parameter for the generator module
	 *
	 * @since 1.25
	 * @param ApiBase $module
	 * @param string $paramName
	 * @param string|array $paramValue
	 */
	public function addGeneratorContinueParam( ApiBase $module, $paramName, $paramValue ) {
		$name = $module->getModuleName();
		$paramName = $module->encodeParamName( $paramName );
		if ( is_array( $paramValue ) ) {
			$paramValue = join( '|', $paramValue );
		}
		$this->generatorContinuationData[$name][$paramName] = $paramValue;
	}

	/**
	 * Close continuation, writing the data into the result
	 *
	 * @since 1.24
	 * @param string $style 'standard' for the new style since 1.21, 'raw' for
	 *   the style used in 1.20 and earlier.
	 */
	public function endContinuation( $style = 'standard' ) {
		if ( $style === 'raw' ) {
			$key = 'query-continue';
			$data = array_merge_recursive(
				$this->continuationData, $this->generatorContinuationData
			);
		} else {
			$key = 'continue';
			$data = array();
			$batchcomplete = false;

			$finishedModules = array_diff(
				array_keys( $this->continueAllModules ),
				array_keys( $this->continuationData )
			);

			// First, grab the non-generator-using continuation data
			$continuationData = array_diff_key(
				$this->continuationData, $this->continueGeneratedModules
			);
			foreach ( $continuationData as $module => $kvp ) {
				$data += $kvp;
			}

			// Next, handle the generator-using continuation data
			$continuationData = array_intersect_key(
				$this->continuationData, $this->continueGeneratedModules
			);
			if ( $continuationData ) {
				// Some modules are unfinished: include those params, and copy
				// the generator params.
				foreach ( $continuationData as $module => $kvp ) {
					$data += $kvp;
				}
				$data += array_intersect_key(
					$this->context->getRequest()->getValues(),
					array_flip( $this->generatorParams )
				);
			} elseif ( $this->generatorContinuationData ) {
				// All the generator-using modules are complete, but the
				// generator isn't. Continue the generator and restart the
				// generator-using modules
				$this->generatorParams = array();
				foreach ( $this->generatorContinuationData as $kvp ) {
					$this->generatorParams = array_merge(
						$this->generatorParams, array_keys( $kvp )
					);
					$data += $kvp;
				}
				$finishedModules = array_diff(
					$finishedModules, $this->continueGeneratedModules
				);
				$batchcomplete = true;
			} else {
				// Generator and prop modules are all done. Mark it so.
				$this->generatorDone = true;
				$batchcomplete = true;
			}

			// Set 'continue' if any continuation data is set or if the generator
			// still needs to run
			if ( $data || !$this->generatorDone ) {
				$data['continue'] =
					( $this->generatorDone ? '-' : join( '|', $this->generatorParams ) ) .
					'||' . join( '|', $finishedModules );
			}

			if ( $batchcomplete ) {
				$this->addValue( null, 'batchcomplete', '', ApiResult::ADD_ON_TOP | ApiResult::NO_SIZE_CHECK );
			}
		}
		if ( $data ) {
			$this->addValue( null, $key, $data, ApiResult::ADD_ON_TOP | ApiResult::NO_SIZE_CHECK );
		}
	}

	/**@}*/

	/************************************************************************//**
	 * @name   Utility
	 * @{
	 */

	/**
	 * Ensure all values in this result are valid UTF-8.
	 */
	public function cleanUpUTF8() {
		array_walk_recursive( $this->mData, function ( &$s ) {
			if ( !is_string( $s ) ) {
				return;
			}
			global $wgContLang;
			$s = $wgContLang->normalize( $s );
		} );
	}

	/**
	 * Test whether a key should be considered metadata
	 *
	 * @param string $key
	 * @return bool
	 */
	public static function isMetadataKey( $key ) {
		return substr( $key, 0, 1 ) === '_';
	}

	/**
	 * Recursively remove metadata keys from a data array or object
	 *
	 * Note this removes all potential metadata keys, not just the defined
	 * ones.
	 *
	 * @since 1.25
	 * @param array|object $data
	 * @return array|object
	 */
	public static function removeMetadata( $data ) {
		if ( is_array( $data ) ) {
			$preserveKeys = isset( $data[self::META_PRESERVE_KEYS] )
				? (array)$data[self::META_PRESERVE_KEYS]
				: array();
			foreach ( $data as $k => $v ) {
				if ( self::isMetadataKey( $k ) && !in_array( $k, $preserveKeys, true ) ) {
					unset( $data[$k] );
				} elseif ( is_array( $v ) || is_object( $v ) ) {
					$data[$k] = self::removeMetadata( $v );
				}
			}
		} elseif ( is_object( $data ) ) {
			$preserveKeys = isset( $data->{self::META_PRESERVE_KEYS} )
				? (array)$data->{self::META_PRESERVE_KEYS}
				: array();
			$old = $data;
			$data = new stdClass;
			foreach ( $old as $k => $v ) {
				if ( self::isMetadataKey( $k ) && !in_array( $k, $preserveKeys, true ) ) {
					/* Do nothing */
				} elseif ( is_array( $v ) || is_object( $v ) ) {
					$data->$k = self::removeMetadata( $v );
				} else {
					$data->$k = $v;
				}
			}
		}
		return $data;
	}

	/**
	 * Remove metadata keys from a data array or object, non-recursive
	 *
	 * Note this removes all potential metadata keys, not just the defined
	 * ones.
	 *
	 * @since 1.25
	 * @param array|object $data
	 * @param array &$metadata Store metadata here, if provided
	 * @return array|object
	 */
	public static function removeMetadataNonRecursive( $data, &$metadata = null ) {
		if ( !is_array( $metadata ) ) {
			$metadata = array();
		}
		if ( is_array( $data ) ) {
			$preserveKeys = isset( $data[self::META_PRESERVE_KEYS] )
				? (array)$data[self::META_PRESERVE_KEYS]
				: array();
			foreach ( $data as $k => $v ) {
				if ( self::isMetadataKey( $k ) && !in_array( $k, $preserveKeys, true ) ) {
					$metadata[$k] = $v;
					unset( $data[$k] );
				}
			}
		} elseif ( is_object( $data ) ) {
			$preserveKeys = isset( $data->{self::META_PRESERVE_KEYS} )
				? (array)$data->{self::META_PRESERVE_KEYS}
				: array();
			$old = $data;
			$data = new stdClass;
			foreach ( $old as $k => $v ) {
				if ( self::isMetadataKey( $k ) && !in_array( $k, $preserveKeys, true ) ) {
					$metadata[$k] = $v;
				} else {
					$data->$k = $v;
				}
			}
		}
		return $data;
	}

	/**
	 * Recursively apply backwards-compatibility transformations
	 *
	 * Transformations are:
	 * - Boolean-valued items are changed to '' if true or removed if false,
	 *   unless listed in META_BC_BOOLS.
	 * - The tag named by META_CONTENT is renamed to '*', and META_CONTENT is
	 *   set to '*'.
	 * - Tags listed in META_BC_SUBELEMENTS will have their values changed to
	 *   array( '*' => $value ).
	 * - If META_TYPE is 'BCarray', set it to 'default'
	 * - If META_TYPE is 'BCassoc', set it to 'default'
	 * - If META_TYPE is 'BCkvp', transform as self::transformForTypes()
	 *
	 * @since 1.25
	 * @note New code shouldn't need to use this
	 * @param array $data
	 * @return array
	 */
	public static function transformForBC( array $data ) {
		$metadata = array();
		$data = self::removeMetadataNonRecursive( $data, $metadata );

		$boolKeys = isset( $metadata[self::META_BC_BOOLS] ) ? $metadata[self::META_BC_BOOLS] : array();
		foreach ( $data as $k => $v ) {
			if ( $v === true && !in_array( $k, $boolKeys, true ) ) {
				$data[$k] = '';
			} elseif ( $v === false && !in_array( $k, $boolKeys, true ) ) {
				unset( $data[$k] );
			} elseif ( is_array( $v ) ) {
				$data[$k] = self::transformForBC( $v );
			}
		}

		if ( isset( $metadata[self::META_CONTENT] ) && $metadata[self::META_CONTENT] !== '*' ) {
			$k = $metadata[self::META_CONTENT];
			$data['*'] = $data[$k];
			unset( $data[$k] );
			$metadata[self::META_CONTENT] = '*';
		}

		if ( isset( $metadata[self::META_BC_SUBELEMENTS] ) ) {
			foreach ( $metadata[self::META_BC_SUBELEMENTS] as $key ) {
				$data[$key] = array(
					'*' => $data[$key],
					self::META_CONTENT => '*',
					self::META_TYPE => 'assoc',
				);
			}
		}

		if ( isset( $metadata[self::META_TYPE] ) ) {
			switch ( $metadata[self::META_TYPE] ) {
				case 'BCarray':
				case 'BCassoc':
					$metadata[self::META_TYPE] = 'default';
					break;
				case 'BCkvp':
					if ( !isset( $metadata[self::META_KVP_KEY_NAME] ) ) {
						throw new UnexpectedValueException( 'Type "BCkvp" used without setting ' .
							'ApiResult::META_KVP_KEY_NAME metadata item' );
					}
					$data = self::transformKVP( $data, $metadata, array( 'BC' => true ) );
					break;
			}
		}

		return $data + $metadata;
	}

	/**
	 * Recursively transform an array for the 'type' metadata
	 *
	 * The transformations are:
	 *  - default: 'array' if all non-meta keys are numeric with no gaps,
	 *    otherwise like 'assoc'.
	 *  - array: Sort non-meta part by key then array_values().
	 *  - assoc: If $options['assocAsObject'], convert to object.
	 *    Otherwise no change.
	 *  - kvp: If $options['armorKVP'], convert to an array of 2-element arrays.
	 *  - BCarray: If $options['BC'], like 'default'. Otherwise like 'array'.
	 *  - BCassoc: If $options['BC'], like 'default'. Otherwise like 'assoc'.
	 *  - BCkvp: Like 'kvp', but forces conversion if $options['BC'].
	 *    Metadata META_KVP_KEY_NAME must be set.
	 *
	 * Options are:
	 *  - assocAsObject: (bool) Whether to transform assoc to objects.
	 *  - armorKVP: (string) If set, convert to an array of assocs. Value is
	 *    the key to use for the keys in the original array.
	 *  - BC: (bool) How to handle the 'BC' types.
	 *
	 * @param array $dataIn
	 * @param array $options
	 * @return array|object
	 */
	public static function transformForTypes( $dataIn, $options = array() ) {
		// First, separate metadata and transform all sub-arrays, and guess the
		// type.
		$metadata = array();
		$data = self::removeMetadataNonRecursive( $dataIn, $metadata );
		$defaultType = 'array';
		$maxKey = -1;
		foreach ( $data as $k => $v ) {
			$data[$k] = is_array( $v ) ? self::transformForTypes( $v, $options ) : $v;
			if ( is_string( $k ) ) {
				$defaultType = 'assoc';
			} elseif ( $k > $maxKey ) {
				$maxKey = $k;
			}
		}
		if ( $defaultType === 'array' && $maxKey !== count( $data ) - 1 ) {
			$defaultType = 'assoc';
		}

		// Override type, if provided
		$type = $defaultType;
		if ( isset( $metadata[self::META_TYPE] ) && $metadata[self::META_TYPE] !== 'default' ) {
			$type = $metadata[self::META_TYPE];
		}
		if ( $type === 'BCkvp' ) {
			if ( !isset( $metadata[self::META_KVP_KEY_NAME] ) ) {
				throw new UnexpectedValueException( 'Type "BCkvp" used without setting ' .
					'ApiResult::META_KVP_KEY_NAME metadata item' );
			}
			if ( empty( $options['BC'] ) ) {
				$type = 'kvp';
			}
		}
		if ( $type === 'kvp' && empty( $options['armorKVP'] ) ) {
			$type = 'assoc';
		}
		if ( $type === 'BCarray' ) {
			$type = empty( $options['BC'] ) ? 'array' : $defaultType;
		}
		if ( $type === 'BCassoc' ) {
			$type = empty( $options['BC'] ) ? 'assoc' : $defaultType;
		}

		// Apply transformation
		switch ( $type ) {
			case 'assoc':
				$metadata[self::META_TYPE] = 'assoc';
				$data += $metadata;
				return empty( $options['assocAsObject'] ) ? $data : (object)$data;

			case 'array':
				ksort( $data );
				$data = array_values( $data );
				$metadata[self::META_TYPE] = 'array';
				return $data + $metadata;

			case 'kvp':
			case 'BCkvp':
				$opts = $options;
				$opts['BC'] = ( $type === 'BCkvp' );
				return self::transformKVP( $data, $metadata, $opts );

			default:
				throw new UnexpectedValueException( "Unknown type '$type'" );
		}
	}

	/**
	 * Transform $data for KVP
	 * @param array $data
	 * @param array $metadata
	 * @param array $options
	 * @return array
	 */
	private static function transformKVP( array $data, array $metadata, array $options ) {
		$key = isset( $metadata[self::META_KVP_KEY_NAME] )
			? $metadata[self::META_KVP_KEY_NAME]
			: $options['armorKVP'];
		$valKey = empty( $options['BC'] ) ? 'value' : '*';
		$assocAsObject = !empty( $options['assocAsObject'] );

		$ret = array();
		foreach ( $data as $k => $v ) {
			$item = array(
				$key => $k,
				$valKey => $v,
				self::META_PRESERVE_KEYS => array( $key ),
				self::META_CONTENT => $valKey,
				self::META_TYPE => 'assoc',
			);
			if ( $assocAsObject ) {
				$item = (object)$item;
			}
			$ret[] = $item;
		}
		$metadata[self::META_TYPE] = 'array';

		return $ret + $metadata;
	}

	/**
	 * Get the 'real' size of a result item. This means the strlen() of the item,
	 * or the sum of the strlen()s of the elements if the item is an array.
	 * @param mixed $value
	 * @return int
	 */
	public static function size( $value ) {
		$s = 0;
		if ( is_array( $value ) ||
			is_object( $value ) && !is_callable( array( $value, '__toString' ) )
		) {
			foreach ( $value as $k => $v ) {
				if ( !self::isMetadataKey( $s ) ) {
					$s += self::size( $v );
				}
			}
		} elseif ( is_scalar( $value ) ) {
			$s = strlen( $value );
		}

		return $s;
	}

	/**
	 * Return a reference to the internal data at $path
	 *
	 * @param array|string|null $path
	 * @param string $create
	 *   If 'append', append empty arrays.
	 *   If 'prepend', prepend empty arrays.
	 *   If 'dummy', return a dummy array.
	 *   Else, raise an error.
	 * @return array
	 */
	private function &path( $path, $create = 'append' ) {
		$path = (array)$path;
		$ret = &$this->mData;
		foreach ( $path as $i => $k ) {
			if ( !isset( $ret[$k] ) ) {
				switch ( $create ) {
					case 'append':
						$ret[$k] = array();
						break;
					case 'prepend':
						$ret = array( $k => array() ) + $ret;
						break;
					case 'dummy':
						$tmp = array();
						return $tmp;
					default:
						$fail = join( '.', array_slice( $path, 0, $i + 1 ) );
						throw new InvalidArgumentException( "Path $fail does not exist" );
				}
			}
			if ( !is_array( $ret[$k] ) ) {
				$fail = join( '.', array_slice( $path, 0, $i + 1 ) );
				throw new InvalidArgumentException( "Path $fail is not an array" );
			}
			$ret = &$ret[$k];
		}
		return $ret;
	}

	/**
	 * Converts a Status object to an array suitable for addValue
	 * @param Status $status
	 * @param string $errorType
	 * @return array
	 */
	public static function convertStatusToArray( $status, $errorType = 'error' ) {
		if ( $status->isGood() ) {
			return array();
		}

		$result = array();
		foreach ( $status->getErrorsByType( $errorType ) as $error ) {
			self::setIndexedTagName( $error['params'], 'param' );
			$result[] = $error;
		}
		self::setIndexedTagName( $result, $errorType );

		return $result;
	}

	/**@}*/

	/************************************************************************//**
	 * @name   Deprecated
	 * @{
	 */

	/**
	 * Call this function when special elements such as '_element'
	 * are needed by the formatter, for example in XML printing.
	 * @deprecated since 1.25
	 * @since 1.23 $flag parameter added
	 * @param bool $flag Set the raw mode flag to this state
	 */
	public function setRawMode( $flag = true ) {
		$this->mIsRawMode = $flag;
	}

	/**
	 * Returns true whether the formatter requested raw data.
	 * @deprecated since 1.25
	 * @return bool
	 */
	public function getIsRawMode() {
		wfDeprecated( __METHOD__, '1.25' );
		return $this->mIsRawMode;
	}

	/**
	 * Get the result's internal data array (read-only)
	 * @deprecated since 1.25, use $this->getResultData() instead
	 * @return array
	 */
	public function getData() {
		wfDeprecated( __METHOD__, '1.25' );
		if ( $this->mIsRawMode ) {
			return $this->mData;
		} else {
			return self::removeMetadata( $this->mData, true );
		}
	}

	/**
	 * Disable size checking in addValue(). Don't use this unless you
	 * REALLY know what you're doing. Values added while size checking
	 * was disabled will not be counted (ever)
	 * @deprecated since 1.24, use ApiResult::NO_SIZE_CHECK
	 */
	public function disableSizeCheck() {
		wfDeprecated( __METHOD__, '1.24' );
		$this->mCheckingSize = false;
	}

	/**
	 * Re-enable size checking in addValue()
	 * @deprecated since 1.24, use ApiResult::NO_SIZE_CHECK
	 */
	public function enableSizeCheck() {
		wfDeprecated( __METHOD__, '1.24' );
		$this->mCheckingSize = true;
	}

	/**
	 * Alias for self::setValue()
	 *
	 * @since 1.21 int $flags replaced boolean $override
	 * @deprecated since 1.25, use self::setValue() instead
	 * @param array $arr To add $value to
	 * @param string $name Index of $arr to add $value at
	 * @param mixed $value
	 * @param int $flags Zero or more OR-ed flags like OVERRIDE | ADD_ON_TOP.
	 *    This parameter used to be boolean, and the value of OVERRIDE=1 was
	 *    specifically chosen so that it would be backwards compatible with the
	 *    new method signature.
	 */
	public static function setElement( &$arr, $name, $value, $flags = 0 ) {
		wfDeprecated( __METHOD__, '1.25' );
		return self::setValue( $arr, $name, $value, $flags );
	}

	/**
	 * Adds a content element to an array.
	 * Use this function instead of hardcoding the '*' element.
	 * @deprecated since 1.25, use self::setContentValue() instead
	 * @param array $arr To add the content element to
	 * @param mixed $value
	 * @param string $subElemName When present, content element is created
	 *  as a sub item of $arr. Use this parameter to create elements in
	 *  format "<elem>text</elem>" without attributes.
	 */
	public static function setContent( &$arr, $value, $subElemName = null ) {
		wfDeprecated( __METHOD__, '1.25' );
		if ( is_array( $value ) ) {
			throw new InvalidArgumentException( __METHOD__ . ': Bad parameter' );
		}
		if ( is_null( $subElemName ) ) {
			self::setContentValue( $arr, 'content', $value );
		} else {
			if ( !isset( $arr[$subElemName] ) ) {
				$arr[$subElemName] = array();
			}
			self::setContentValue( $arr[$subElemName], 'content', $value );
		}
	}

	/**
	 * Alias for self::removeValue()
	 * @deprecated since 1.25, use self::removeValue() instead
	 * @param array|null $path
	 * @param string $name
	 */
	public function unsetValue( $path, $name ) {
		wfDeprecated( __METHOD__, '1.25' );
		$this->removeValue( $path, $name );
	}

	/**
	 * Alias for self::setIndexedTagNameOnSubarrays
	 * @deprecated since 1.25, use self::setIndexedTagNameOnSubarrays() instead
	 * @param array $arr
	 * @param string $tag Tag name
	 */
	public function setIndexedTagName_recursive( &$arr, $tag ) {
		wfDeprecated( __METHOD__, '1.25' );
		if ( !is_array( $arr ) ) {
			return;
		}
		self::setIndexedTagNameOnSubarrays( $arr, $tag );
	}

	/**
	 * Alias for self::defineIndexedTagName()
	 * @deprecated since 1.25, use $this->defineIndexedTagName() instead
	 * @param array $path Path to the array, like addValue()'s $path
	 * @param string $tag
	 */
	public function setIndexedTagName_internal( $path, $tag ) {
		wfDeprecated( __METHOD__, '1.25' );
		$this->defineIndexedTagName( $path, $tag );
	}

	/**
	 * Alias for self::addParsedLimit()
	 * @deprecated since 1.25, use $this->addParsedLimit() instead
	 * @param string $moduleName
	 * @param int $limit
	 */
	public function setParsedLimit( $moduleName, $limit ) {
		wfDeprecated( __METHOD__, '1.25' );
		$this->addParsedLimit( $moduleName, $limit );
	}

	/**
	 * Alias for self::addContinueParam()
	 * @since 1.24
	 * @deprecated since 1.25, use $this->addContinueParam() instead
	 * @param ApiBase $module
	 * @param string $paramName
	 * @param string|array $paramValue
	 */
	public function setContinueParam( ApiBase $module, $paramName, $paramValue ) {
		wfDeprecated( __METHOD__, '1.25' );
		$this->addContinueParam( $module, $paramName, $paramValue );
	}

	/**
	 * Alias for self::addGeneratorContinueParam()
	 * @since 1.24
	 * @deprecated since 1.25, use $this->addGeneratorContinueParam() instead
	 * @param ApiBase $module
	 * @param string $paramName
	 * @param string|array $paramValue
	 */
	public function setGeneratorContinueParam( ApiBase $module, $paramName, $paramValue ) {
		wfDeprecated( __METHOD__, '1.25' );
		$this->addGeneratorContinueParam( $module, $paramName, $paramValue );
	}

	/**@}*/
}

/**
 * For really cool vim folding this needs to be at the end:
 * vim: foldmarker=@{,@} foldmethod=marker
 */
