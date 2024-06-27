<?php
/**
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

use MediaWiki\MediaWikiServices;

/**
 * This class represents the result of the API operations.
 * It simply wraps a nested array structure, adding some functions to simplify
 * array's modifications. As various modules execute, they add different pieces
 * of information to this result, structuring it as it will be given to the client.
 *
 * Each subarray may either be a dictionary - key-value pairs with unique keys,
 * or lists, where the items are added using $data[] = $value notation.
 *
 * @since 1.25 this is no longer a subclass of ApiBase
 * @ingroup API
 */
class ApiResult implements ApiSerializable {

	/**
	 * Override existing value in addValue(), setValue(), and similar functions
	 * @since 1.21
	 */
	public const OVERRIDE = 1;

	/**
	 * For addValue(), setValue() and similar functions, if the value does not
	 * exist, add it as the first element. In case the new value has no name
	 * (numerical index), all indexes will be renumbered.
	 * @since 1.21
	 */
	public const ADD_ON_TOP = 2;

	/**
	 * For addValue() and similar functions, do not check size while adding a value
	 * Don't use this unless you REALLY know what you're doing.
	 * Values added while the size checking was disabled will never be counted.
	 * Ignored for setValue() and similar functions.
	 * @since 1.24
	 */
	public const NO_SIZE_CHECK = 4;

	/**
	 * For addValue(), setValue() and similar functions, do not validate data.
	 * Also disables size checking. If you think you need to use this, you're
	 * probably wrong.
	 * @since 1.25
	 */
	public const NO_VALIDATE = self::NO_SIZE_CHECK | 8;

	/**
	 * Key for the 'indexed tag name' metadata item. Value is string.
	 * @since 1.25
	 */
	public const META_INDEXED_TAG_NAME = '_element';

	/**
	 * Key for the 'subelements' metadata item. Value is string[].
	 * @since 1.25
	 */
	public const META_SUBELEMENTS = '_subelements';

	/**
	 * Key for the 'preserve keys' metadata item. Value is string[].
	 * @since 1.25
	 */
	public const META_PRESERVE_KEYS = '_preservekeys';

	/**
	 * Key for the 'content' metadata item. Value is string.
	 * @since 1.25
	 */
	public const META_CONTENT = '_content';

	/**
	 * Key for the 'type' metadata item. Value is one of the following strings:
	 *  - default: Like 'array' if all (non-metadata) keys are numeric with no
	 *    gaps, otherwise like 'assoc'.
	 *  - array: Keys are used for ordering, but are not output. In a format
	 *    like JSON, outputs as [].
	 *  - assoc: In a format like JSON, outputs as {}.
	 *  - kvp: For a format like XML where object keys have a restricted
	 *    character set, use an alternative output format. For example,
	 *    <container><item name="key">value</item></container> rather than
	 *    <container key="value" />
	 *  - BCarray: Like 'array' normally, 'default' in backwards-compatibility mode.
	 *  - BCassoc: Like 'assoc' normally, 'default' in backwards-compatibility mode.
	 *  - BCkvp: Like 'kvp' normally. In backwards-compatibility mode, forces
	 *    the alternative output format for all formats, for example
	 *    [{"name":key,"*":value}] in JSON. META_KVP_KEY_NAME must also be set.
	 * @since 1.25
	 */
	public const META_TYPE = '_type';

	/**
	 * Key for the metadata item whose value specifies the name used for the
	 * kvp key in the alternative output format with META_TYPE 'kvp' or
	 * 'BCkvp', i.e. the "name" in <container><item name="key">value</item></container>.
	 * Value is string.
	 * @since 1.25
	 */
	public const META_KVP_KEY_NAME = '_kvpkeyname';

	/**
	 * Key for the metadata item that indicates that the KVP key should be
	 * added into an assoc value, i.e. {"key":{"val1":"a","val2":"b"}}
	 * transforms to {"name":"key","val1":"a","val2":"b"} rather than
	 * {"name":"key","value":{"val1":"a","val2":"b"}}.
	 * Value is boolean.
	 * @since 1.26
	 */
	public const META_KVP_MERGE = '_kvpmerge';

	/**
	 * Key for the 'BC bools' metadata item. Value is string[].
	 * Note no setter is provided.
	 * @since 1.25
	 */
	public const META_BC_BOOLS = '_BC_bools';

	/**
	 * Key for the 'BC subelements' metadata item. Value is string[].
	 * Note no setter is provided.
	 * @since 1.25
	 */
	public const META_BC_SUBELEMENTS = '_BC_subelements';

	private $data, $size, $maxSize;
	private $errorFormatter;

	/**
	 * @param int|false $maxSize Maximum result "size", or false for no limit
	 */
	public function __construct( $maxSize ) {
		$this->maxSize = $maxSize;
		$this->reset();
	}

	/**
	 * @since 1.25
	 * @param ApiErrorFormatter $formatter
	 */
	public function setErrorFormatter( ApiErrorFormatter $formatter ) {
		$this->errorFormatter = $formatter;
	}

	/**
	 * Allow for adding one ApiResult into another
	 * @since 1.25
	 * @return mixed
	 */
	public function serializeForApiResult() {
		return $this->data;
	}

	/***************************************************************************/
	// region   Content
	/** @name   Content */

	/**
	 * Clear the current result data.
	 */
	public function reset() {
		$this->data = [
			self::META_TYPE => 'assoc', // Usually what's desired
		];
		$this->size = 0;
	}

	/**
	 * Get the result data array
	 *
	 * The returned value should be considered read-only.
	 *
	 * Transformations include:
	 *
	 * Custom: (callable) Applied before other transformations. Signature is
	 *  function ( &$data, &$metadata ), return value is ignored. Called for
	 *  each nested array.
	 *
	 * BC: (array) This transformation does various adjustments to bring the
	 *  output in line with the pre-1.25 result format. The value array is a
	 *  list of flags: 'nobool', 'no*', 'nosub'.
	 *  - Boolean-valued items are changed to '' if true or removed if false,
	 *    unless listed in META_BC_BOOLS. This may be skipped by including
	 *    'nobool' in the value array.
	 *  - The tag named by META_CONTENT is renamed to '*', and META_CONTENT is
	 *    set to '*'. This may be skipped by including 'no*' in the value
	 *    array.
	 *  - Tags listed in META_BC_SUBELEMENTS will have their values changed to
	 *    [ '*' => $value ]. This may be skipped by including 'nosub' in
	 *    the value array.
	 *  - If META_TYPE is 'BCarray', set it to 'default'
	 *  - If META_TYPE is 'BCassoc', set it to 'default'
	 *  - If META_TYPE is 'BCkvp', perform the transformation (even if
	 *    the Types transformation is not being applied).
	 *
	 * Types: (assoc) Apply transformations based on META_TYPE. The values
	 * array is an associative array with the following possible keys:
	 *  - AssocAsObject: (bool) If true, return arrays with META_TYPE 'assoc'
	 *    as objects.
	 *  - ArmorKVP: (string) If provided, transform arrays with META_TYPE 'kvp'
	 *    and 'BCkvp' into arrays of two-element arrays, something like this:
	 *      $output = [];
	 *      foreach ( $input as $key => $value ) {
	 *          $pair = [];
	 *          $pair[$META_KVP_KEY_NAME ?: $ArmorKVP_value] = $key;
	 *          ApiResult::setContentValue( $pair, 'value', $value );
	 *          $output[] = $pair;
	 *      }
	 *
	 * Strip: (string) Strips metadata keys from the result.
	 *  - 'all': Strip all metadata, recursively
	 *  - 'base': Strip metadata at the top-level only.
	 *  - 'none': Do not strip metadata.
	 *  - 'bc': Like 'all', but leave certain pre-1.25 keys.
	 *
	 * @since 1.25
	 * @param array|string|null $path Path to fetch, see ApiResult::addValue
	 * @param array $transforms See above
	 * @return mixed Result data, or null if not found
	 */
	public function getResultData( $path = [], $transforms = [] ) {
		$path = (array)$path;
		if ( !$path ) {
			return self::applyTransformations( $this->data, $transforms );
		}

		$last = array_pop( $path );
		$ret = &$this->path( $path, 'dummy' );
		if ( !isset( $ret[$last] ) ) {
			return null;
		} elseif ( is_array( $ret[$last] ) ) {
			return self::applyTransformations( $ret[$last], $transforms );
		} else {
			return $ret[$last];
		}
	}

	/**
	 * Get the size of the result, i.e. the amount of bytes in it
	 * @return int
	 */
	public function getSize() {
		return $this->size;
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
		if ( ( $flags & self::NO_VALIDATE ) !== self::NO_VALIDATE ) {
			$value = self::validateValue( $value );
		}

		if ( $name === null ) {
			if ( $flags & self::ADD_ON_TOP ) {
				array_unshift( $arr, $value );
			} else {
				array_push( $arr, $value );
			}
			return;
		}

		$exists = isset( $arr[$name] );
		if ( !$exists || ( $flags & self::OVERRIDE ) ) {
			if ( !$exists && ( $flags & self::ADD_ON_TOP ) ) {
				$arr = [ $name => $value ] + $arr;
			} else {
				$arr[$name] = $value;
			}
		} elseif ( is_array( $arr[$name] ) && is_array( $value ) ) {
			$conflicts = array_intersect_key( $arr[$name], $value );
			if ( !$conflicts ) {
				$arr[$name] += $value;
			} else {
				$keys = implode( ', ', array_keys( $conflicts ) );
				throw new RuntimeException(
					"Conflicting keys ($keys) when attempting to merge element $name"
				);
			}
		} elseif ( $value !== $arr[$name] ) {
			throw new RuntimeException(
				"Attempting to add element $name=$value, existing value is {$arr[$name]}"
			);
		}
	}

	/**
	 * Validate a value for addition to the result
	 * @param mixed $value
	 * @return array|mixed|string
	 */
	private static function validateValue( $value ) {
		if ( is_object( $value ) ) {
			// Note we use is_callable() here instead of instanceof because
			// ApiSerializable is an informal protocol (see docs there for details).
			if ( is_callable( [ $value, 'serializeForApiResult' ] ) ) {
				$oldValue = $value;
				$value = $value->serializeForApiResult();
				if ( is_object( $value ) ) {
					throw new UnexpectedValueException(
						get_class( $oldValue ) . '::serializeForApiResult() returned an object of class ' .
							get_class( $value )
					);
				}

				// Recursive call instead of fall-through so we can throw a
				// better exception message.
				try {
					return self::validateValue( $value );
				} catch ( Exception $ex ) {
					throw new UnexpectedValueException(
						get_class( $oldValue ) . '::serializeForApiResult() returned an invalid value: ' .
							$ex->getMessage(),
						0,
						$ex
					);
				}
			} elseif ( is_callable( [ $value, '__toString' ] ) ) {
				$value = (string)$value;
			} else {
				$value = (array)$value + [ self::META_TYPE => 'assoc' ];
			}
		}

		if ( is_string( $value ) ) {
			// Optimization: avoid querying the service locator for each value.
			static $contentLanguage = null;
			if ( !$contentLanguage ) {
				$contentLanguage = MediaWikiServices::getInstance()->getContentLanguage();
			}
			$value = $contentLanguage->normalize( $value );
		} elseif ( is_array( $value ) ) {
			foreach ( $value as $k => $v ) {
				$value[$k] = self::validateValue( $v );
			}
		} elseif ( $value !== null && !is_scalar( $value ) ) {
			$type = gettype( $value );
			// phpcs:ignore MediaWiki.Usage.ForbiddenFunctions.is_resource
			if ( is_resource( $value ) ) {
				$type .= '(' . get_resource_type( $value ) . ')';
			}
			throw new InvalidArgumentException( "Cannot add $type to ApiResult" );
		} elseif ( is_float( $value ) && !is_finite( $value ) ) {
			throw new InvalidArgumentException( 'Cannot add non-finite floats to ApiResult' );
		}

		return $value;
	}

	/**
	 * Add value to the output data at the given path.
	 *
	 * Path can be an indexed array, each element specifying the branch at which to add the new
	 * value. Setting $path to [ 'a', 'b', 'c' ] is equivalent to data['a']['b']['c'] = $value.
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
		$arr = &$this->path( $path, ( $flags & self::ADD_ON_TOP ) ? 'prepend' : 'append' );

		if ( !( $flags & self::NO_SIZE_CHECK ) ) {
			// self::size needs the validated value. Then flag
			// to not re-validate later.
			$value = self::validateValue( $value );
			$flags |= self::NO_VALIDATE;

			$newsize = $this->size + self::size( $value );
			if ( $this->maxSize !== false && $newsize > $this->maxSize ) {
				$this->errorFormatter->addWarning(
					'result', [ 'apiwarn-truncatedresult', Message::numParam( $this->maxSize ) ]
				);
				return false;
			}
			$this->size = $newsize;
		}

		self::setValue( $arr, $name, $value, $flags );
		return true;
	}

	/**
	 * Remove an output value to the array by name.
	 * @param array &$arr To remove $value from
	 * @param string|int $name Index of $arr to remove
	 * @return mixed Old value, or null
	 */
	public static function unsetValue( array &$arr, $name ) {
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
		$ret = self::unsetValue( $this->path( $path, 'dummy' ), $name );
		if ( !( $flags & self::NO_SIZE_CHECK ) ) {
			$newsize = $this->size - self::size( $ret );
			$this->size = max( $newsize, 0 );
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
		$this->addContentField( $path, $name, $flags );
		return $this->addValue( $path, $name, $value, $flags );
	}

	/**
	 * Add the numeric limit for a limit=max to the result.
	 *
	 * @since 1.25
	 * @param string $moduleName
	 * @param int $limit
	 */
	public function addParsedLimit( $moduleName, $limit ) {
		// Add value, allowing overwriting
		$this->addValue( 'limits', $moduleName, $limit,
			self::OVERRIDE | self::NO_SIZE_CHECK );
	}

	// endregion -- end of Content

	/***************************************************************************/
	// region   Metadata
	/** @name   Metadata */

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
					' is already set as the content element'
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
	public function addContentField( $path, $name, $flags = 0 ) {
		$arr = &$this->path( $path, ( $flags & self::ADD_ON_TOP ) ? 'prepend' : 'append' );
		self::setContentField( $arr, $name, $flags );
	}

	/**
	 * Causes the elements with the specified names to be output as
	 * subelements rather than attributes.
	 * @since 1.25 is static
	 * @param array &$arr
	 * @param array|string|int $names The element name(s) to be output as subelements
	 */
	public static function setSubelementsList( array &$arr, $names ) {
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
	public function addSubelementsList( $path, $names ) {
		$arr = &$this->path( $path );
		self::setSubelementsList( $arr, $names );
	}

	/**
	 * Causes the elements with the specified names to be output as
	 * attributes (when possible) rather than as subelements.
	 * @since 1.25
	 * @param array &$arr
	 * @param array|string|int $names The element name(s) to not be output as subelements
	 */
	public static function unsetSubelementsList( array &$arr, $names ) {
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
	public function removeSubelementsList( $path, $names ) {
		$arr = &$this->path( $path );
		self::unsetSubelementsList( $arr, $names );
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
	public function addIndexedTagName( $path, $tag ) {
		$arr = &$this->path( $path );
		self::setIndexedTagName( $arr, $tag );
	}

	/**
	 * Set indexed tag name on $arr and all subarrays
	 *
	 * @since 1.25
	 * @param array &$arr
	 * @param string $tag Tag name
	 */
	public static function setIndexedTagNameRecursive( array &$arr, $tag ) {
		if ( !is_string( $tag ) ) {
			throw new InvalidArgumentException( 'Bad tag name' );
		}
		$arr[self::META_INDEXED_TAG_NAME] = $tag;
		foreach ( $arr as $k => &$v ) {
			if ( is_array( $v ) && !self::isMetadataKey( $k ) ) {
				self::setIndexedTagNameRecursive( $v, $tag );
			}
		}
	}

	/**
	 * Set indexed tag name on $path and all subarrays
	 *
	 * @since 1.25
	 * @param array|string|null $path See ApiResult::addValue()
	 * @param string $tag Tag name
	 */
	public function addIndexedTagNameRecursive( $path, $tag ) {
		$arr = &$this->path( $path );
		self::setIndexedTagNameRecursive( $arr, $tag );
	}

	/**
	 * Preserve specified keys.
	 *
	 * This prevents XML name mangling and preventing keys from being removed
	 * by self::stripMetadata().
	 *
	 * @since 1.25
	 * @param array &$arr
	 * @param array|string $names The element name(s) to preserve
	 */
	public static function setPreserveKeysList( array &$arr, $names ) {
		if ( !isset( $arr[self::META_PRESERVE_KEYS] ) ) {
			$arr[self::META_PRESERVE_KEYS] = (array)$names;
		} else {
			$arr[self::META_PRESERVE_KEYS] = array_merge( $arr[self::META_PRESERVE_KEYS], (array)$names );
		}
	}

	/**
	 * Preserve specified keys.
	 * @since 1.25
	 * @see self::setPreserveKeysList()
	 * @param array|string|null $path See ApiResult::addValue()
	 * @param array|string $names The element name(s) to preserve
	 */
	public function addPreserveKeysList( $path, $names ) {
		$arr = &$this->path( $path );
		self::setPreserveKeysList( $arr, $names );
	}

	/**
	 * Don't preserve specified keys.
	 * @since 1.25
	 * @see self::setPreserveKeysList()
	 * @param array &$arr
	 * @param array|string $names The element name(s) to not preserve
	 */
	public static function unsetPreserveKeysList( array &$arr, $names ) {
		if ( isset( $arr[self::META_PRESERVE_KEYS] ) ) {
			$arr[self::META_PRESERVE_KEYS] = array_diff( $arr[self::META_PRESERVE_KEYS], (array)$names );
		}
	}

	/**
	 * Don't preserve specified keys.
	 * @since 1.25
	 * @see self::setPreserveKeysList()
	 * @param array|string|null $path See ApiResult::addValue()
	 * @param array|string $names The element name(s) to not preserve
	 */
	public function removePreserveKeysList( $path, $names ) {
		$arr = &$this->path( $path );
		self::unsetPreserveKeysList( $arr, $names );
	}

	/**
	 * Set the array data type
	 *
	 * @since 1.25
	 * @param array &$arr
	 * @param string $type See ApiResult::META_TYPE
	 * @param string|null $kvpKeyName See ApiResult::META_KVP_KEY_NAME
	 */
	public static function setArrayType( array &$arr, $type, $kvpKeyName = null ) {
		if ( !in_array( $type, [
				'default', 'array', 'assoc', 'kvp', 'BCarray', 'BCassoc', 'BCkvp'
				], true ) ) {
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
	 * @param string $tag See ApiResult::META_TYPE
	 * @param string|null $kvpKeyName See ApiResult::META_KVP_KEY_NAME
	 */
	public function addArrayType( $path, $tag, $kvpKeyName = null ) {
		$arr = &$this->path( $path );
		self::setArrayType( $arr, $tag, $kvpKeyName );
	}

	/**
	 * Set the array data type recursively
	 * @since 1.25
	 * @param array &$arr
	 * @param string $type See ApiResult::META_TYPE
	 * @param string|null $kvpKeyName See ApiResult::META_KVP_KEY_NAME
	 */
	public static function setArrayTypeRecursive( array &$arr, $type, $kvpKeyName = null ) {
		self::setArrayType( $arr, $type, $kvpKeyName );
		foreach ( $arr as $k => &$v ) {
			if ( is_array( $v ) && !self::isMetadataKey( $k ) ) {
				self::setArrayTypeRecursive( $v, $type, $kvpKeyName );
			}
		}
	}

	/**
	 * Set the array data type for a path recursively
	 * @since 1.25
	 * @param array|string|null $path See ApiResult::addValue()
	 * @param string $tag See ApiResult::META_TYPE
	 * @param string|null $kvpKeyName See ApiResult::META_KVP_KEY_NAME
	 */
	public function addArrayTypeRecursive( $path, $tag, $kvpKeyName = null ) {
		$arr = &$this->path( $path );
		self::setArrayTypeRecursive( $arr, $tag, $kvpKeyName );
	}

	// endregion -- end of Metadata

	/***************************************************************************/
	// region   Utility
	/** @name   Utility */

	/**
	 * Test whether a key should be considered metadata
	 *
	 * @param string|int $key
	 * @return bool
	 */
	public static function isMetadataKey( $key ) {
		// Optimization: This is a very hot and highly optimized code path. Note that ord() only
		// considers the first character and also works with empty strings and integers.
		// 95 corresponds to the '_' character.
		return ord( $key ) === 95;
	}

	/**
	 * Apply transformations to an array, returning the transformed array.
	 *
	 * @see ApiResult::getResultData()
	 * @since 1.25
	 * @param array $dataIn
	 * @param array $transforms
	 * @return array|stdClass
	 */
	protected static function applyTransformations( array $dataIn, array $transforms ) {
		$strip = $transforms['Strip'] ?? 'none';
		if ( $strip === 'base' ) {
			$transforms['Strip'] = 'none';
		}
		$transformTypes = $transforms['Types'] ?? null;
		if ( $transformTypes !== null && !is_array( $transformTypes ) ) {
			throw new InvalidArgumentException( __METHOD__ . ':Value for "Types" must be an array' );
		}

		$metadata = [];
		$data = self::stripMetadataNonRecursive( $dataIn, $metadata );

		if ( isset( $transforms['Custom'] ) ) {
			if ( !is_callable( $transforms['Custom'] ) ) {
				throw new InvalidArgumentException( __METHOD__ . ': Value for "Custom" must be callable' );
			}
			call_user_func_array( $transforms['Custom'], [ &$data, &$metadata ] );
		}

		if ( ( isset( $transforms['BC'] ) || $transformTypes !== null ) &&
			isset( $metadata[self::META_TYPE] ) && $metadata[self::META_TYPE] === 'BCkvp' &&
			!isset( $metadata[self::META_KVP_KEY_NAME] )
		) {
			throw new UnexpectedValueException( 'Type "BCkvp" used without setting ' .
				'ApiResult::META_KVP_KEY_NAME metadata item' );
		}

		// BC transformations
		$boolKeys = null;
		if ( isset( $transforms['BC'] ) ) {
			if ( !is_array( $transforms['BC'] ) ) {
				throw new InvalidArgumentException( __METHOD__ . ':Value for "BC" must be an array' );
			}
			if ( !in_array( 'nobool', $transforms['BC'], true ) ) {
				$boolKeys = isset( $metadata[self::META_BC_BOOLS] )
					? array_fill_keys( $metadata[self::META_BC_BOOLS], true )
					: [];
			}

			if ( !in_array( 'no*', $transforms['BC'], true ) &&
				isset( $metadata[self::META_CONTENT] ) && $metadata[self::META_CONTENT] !== '*'
			) {
				$k = $metadata[self::META_CONTENT];
				$data['*'] = $data[$k];
				unset( $data[$k] );
				$metadata[self::META_CONTENT] = '*';
			}

			if ( !in_array( 'nosub', $transforms['BC'], true ) &&
				isset( $metadata[self::META_BC_SUBELEMENTS] )
			) {
				foreach ( $metadata[self::META_BC_SUBELEMENTS] as $k ) {
					if ( isset( $data[$k] ) ) {
						$data[$k] = [
							'*' => $data[$k],
							self::META_CONTENT => '*',
							self::META_TYPE => 'assoc',
						];
					}
				}
			}

			if ( isset( $metadata[self::META_TYPE] ) ) {
				switch ( $metadata[self::META_TYPE] ) {
					case 'BCarray':
					case 'BCassoc':
						$metadata[self::META_TYPE] = 'default';
						break;
					case 'BCkvp':
						$transformTypes['ArmorKVP'] = $metadata[self::META_KVP_KEY_NAME];
						break;
				}
			}
		}

		// Figure out type, do recursive calls, and do boolean transform if necessary
		$defaultType = 'array';
		$maxKey = -1;
		foreach ( $data as $k => &$v ) {
			$v = is_array( $v ) ? self::applyTransformations( $v, $transforms ) : $v;
			if ( $boolKeys !== null && is_bool( $v ) && !isset( $boolKeys[$k] ) ) {
				if ( !$v ) {
					unset( $data[$k] );
					continue;
				}
				$v = '';
			}
			if ( is_string( $k ) ) {
				$defaultType = 'assoc';
			} elseif ( $k > $maxKey ) {
				$maxKey = $k;
			}
		}
		unset( $v );

		// Determine which metadata to keep
		switch ( $strip ) {
			case 'all':
			case 'base':
				$keepMetadata = [];
				break;
			case 'none':
				$keepMetadata = &$metadata;
				break;
			case 'bc':
				// @phan-suppress-next-line PhanTypeMismatchArgumentNullableInternal Type mismatch on pass-by-ref args
				$keepMetadata = array_intersect_key( $metadata, [
					self::META_INDEXED_TAG_NAME => 1,
					self::META_SUBELEMENTS => 1,
				] );
				break;
			default:
				throw new InvalidArgumentException( __METHOD__ . ': Unknown value for "Strip"' );
		}

		// No type transformation
		if ( $transformTypes === null ) {
			return $data + $keepMetadata;
		}

		if ( $defaultType === 'array' && $maxKey !== count( $data ) - 1 ) {
			$defaultType = 'assoc';
		}

		// Override type, if provided
		$type = $defaultType;
		if ( isset( $metadata[self::META_TYPE] ) && $metadata[self::META_TYPE] !== 'default' ) {
			$type = $metadata[self::META_TYPE];
		}
		if ( ( $type === 'kvp' || $type === 'BCkvp' ) &&
			empty( $transformTypes['ArmorKVP'] )
		) {
			$type = 'assoc';
		} elseif ( $type === 'BCarray' ) {
			$type = 'array';
		} elseif ( $type === 'BCassoc' ) {
			$type = 'assoc';
		}

		// Apply transformation
		switch ( $type ) {
			case 'assoc':
				$metadata[self::META_TYPE] = 'assoc';
				$data += $keepMetadata;
				return empty( $transformTypes['AssocAsObject'] ) ? $data : (object)$data;

			case 'array':
				// Sort items in ascending order by key. Note that $data may contain a mix of number and string keys,
				// for which the sorting behavior of krsort() with SORT_REGULAR is inconsistent between PHP versions.
				// Given a comparison of a string key and a number key, PHP < 8.2 coerces the string key into a number
				// (which yields zero if the string was non-numeric), and then performs the comparison,
				// while PHP >= 8.2 makes the behavior consistent with stricter numeric comparisons introduced by
				// PHP 8.0 in that if the string key is non-numeric, it converts the number key into a string
				// and compares those two strings instead. We therefore use a custom comparison function
				// implementing PHP >= 8.2 ordering semantics to ensure consistent ordering of items
				// irrespective of the PHP version (T326480).
				uksort( $data, static function ( $a, $b ): int {
					// In a comparison of a number or numeric string with a non-numeric string,
					// coerce both values into a string prior to comparing and compare the resulting strings.
					// Note that PHP prior to 8.0 did not consider numeric strings with trailing whitespace
					// to be numeric, so trim the inputs prior to the numeric checks to make the behavior
					// consistent across PHP versions.
					if ( is_numeric( trim( $a ) ) xor is_numeric( trim( $b ) ) ) {
						return (string)$a <=> (string)$b;
					}

					return $a <=> $b;
				} );

				$data = array_values( $data );
				$metadata[self::META_TYPE] = 'array';
				// @phan-suppress-next-line PhanTypeMismatchReturnNullable Type mismatch on pass-by-ref args
				return $data + $keepMetadata;

			case 'kvp':
			case 'BCkvp':
				$key = $metadata[self::META_KVP_KEY_NAME] ?? $transformTypes['ArmorKVP'];
				$valKey = isset( $transforms['BC'] ) ? '*' : 'value';
				$assocAsObject = !empty( $transformTypes['AssocAsObject'] );
				$merge = !empty( $metadata[self::META_KVP_MERGE] );

				$ret = [];
				foreach ( $data as $k => $v ) {
					if ( $merge && ( is_array( $v ) || is_object( $v ) ) ) {
						$vArr = (array)$v;
						if ( isset( $vArr[self::META_TYPE] ) ) {
							$mergeType = $vArr[self::META_TYPE];
						} elseif ( is_object( $v ) ) {
							$mergeType = 'assoc';
						} else {
							$keys = array_keys( $vArr );
							sort( $keys, SORT_NUMERIC );
							$mergeType = ( $keys === array_keys( $keys ) ) ? 'array' : 'assoc';
						}
					} else {
						$mergeType = 'n/a';
					}
					if ( $mergeType === 'assoc' ) {
						// @phan-suppress-next-line PhanPossiblyUndeclaredVariable vArr set when used
						$item = $vArr + [
							$key => $k,
						];
						if ( $strip === 'none' ) {
							self::setPreserveKeysList( $item, [ $key ] );
						}
					} else {
						$item = [
							$key => $k,
							$valKey => $v,
						];
						if ( $strip === 'none' ) {
							$item += [
								self::META_PRESERVE_KEYS => [ $key ],
								self::META_CONTENT => $valKey,
								self::META_TYPE => 'assoc',
							];
						}
					}
					$ret[] = $assocAsObject ? (object)$item : $item;
				}
				$metadata[self::META_TYPE] = 'array';

				// @phan-suppress-next-line PhanTypeMismatchReturnNullable Type mismatch on pass-by-ref args
				return $ret + $keepMetadata;

			default:
				throw new UnexpectedValueException( "Unknown type '$type'" );
		}
	}

	/**
	 * Recursively remove metadata keys from a data array or object
	 *
	 * Note this removes all potential metadata keys, not just the defined
	 * ones.
	 *
	 * @since 1.25
	 * @param array|stdClass $data
	 * @return array|stdClass
	 */
	public static function stripMetadata( $data ) {
		if ( is_array( $data ) || is_object( $data ) ) {
			$isObj = is_object( $data );
			if ( $isObj ) {
				$data = (array)$data;
			}
			$preserveKeys = isset( $data[self::META_PRESERVE_KEYS] )
				? (array)$data[self::META_PRESERVE_KEYS]
				: [];
			foreach ( $data as $k => $v ) {
				if ( self::isMetadataKey( $k ) && !in_array( $k, $preserveKeys, true ) ) {
					unset( $data[$k] );
				} elseif ( is_array( $v ) || is_object( $v ) ) {
					$data[$k] = self::stripMetadata( $v );
				}
			}
			if ( $isObj ) {
				$data = (object)$data;
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
	 * @param array|stdClass $data
	 * @param array|null &$metadata Store metadata here, if provided
	 * @return array|stdClass
	 */
	public static function stripMetadataNonRecursive( $data, &$metadata = null ) {
		if ( !is_array( $metadata ) ) {
			$metadata = [];
		}
		if ( is_array( $data ) || is_object( $data ) ) {
			$isObj = is_object( $data );
			if ( $isObj ) {
				$data = (array)$data;
			}
			$preserveKeys = isset( $data[self::META_PRESERVE_KEYS] )
				? (array)$data[self::META_PRESERVE_KEYS]
				: [];
			foreach ( $data as $k => $v ) {
				if ( self::isMetadataKey( $k ) && !in_array( $k, $preserveKeys, true ) ) {
					$metadata[$k] = $v;
					unset( $data[$k] );
				}
			}
			if ( $isObj ) {
				$data = (object)$data;
			}
		}
		return $data;
	}

	/**
	 * Get the 'real' size of a result item. This means the strlen() of the item,
	 * or the sum of the strlen()s of the elements if the item is an array.
	 * @param mixed $value Validated value (see self::validateValue())
	 * @return int
	 */
	private static function size( $value ) {
		$s = 0;
		if ( is_array( $value ) ) {
			foreach ( $value as $k => $v ) {
				if ( !self::isMetadataKey( $k ) ) {
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
		$ret = &$this->data;
		foreach ( $path as $i => $k ) {
			if ( !isset( $ret[$k] ) ) {
				switch ( $create ) {
					case 'append':
						$ret[$k] = [];
						break;
					case 'prepend':
						$ret = [ $k => [] ] + $ret;
						break;
					case 'dummy':
						$tmp = [];
						return $tmp;
					default:
						$fail = implode( '.', array_slice( $path, 0, $i + 1 ) );
						throw new InvalidArgumentException( "Path $fail does not exist" );
				}
			}
			if ( !is_array( $ret[$k] ) ) {
				$fail = implode( '.', array_slice( $path, 0, $i + 1 ) );
				throw new InvalidArgumentException( "Path $fail is not an array" );
			}
			$ret = &$ret[$k];
		}
		return $ret;
	}

	/**
	 * Add the correct metadata to an array of vars we want to export through
	 * the API.
	 *
	 * @param array $vars
	 * @param bool $forceHash
	 * @return array
	 */
	public static function addMetadataToResultVars( $vars, $forceHash = true ) {
		// Process subarrays and determine if this is a JS [] or {}
		$hash = $forceHash;
		$maxKey = -1;
		$bools = [];
		foreach ( $vars as $k => $v ) {
			if ( is_array( $v ) || is_object( $v ) ) {
				$vars[$k] = self::addMetadataToResultVars( (array)$v, is_object( $v ) );
			} elseif ( is_bool( $v ) ) {
				// Better here to use real bools even in BC formats
				$bools[] = $k;
			}
			if ( is_string( $k ) ) {
				$hash = true;
			} elseif ( $k > $maxKey ) {
				$maxKey = $k;
			}
		}
		if ( !$hash && $maxKey !== count( $vars ) - 1 ) {
			$hash = true;
		}

		// Set metadata appropriately
		if ( $hash ) {
			// Get the list of keys we actually care about. Unfortunately, we can't support
			// certain keys that conflict with ApiResult metadata.
			$keys = array_diff( array_keys( $vars ), [
				self::META_TYPE, self::META_PRESERVE_KEYS, self::META_KVP_KEY_NAME,
				self::META_INDEXED_TAG_NAME, self::META_BC_BOOLS
			] );

			return [
				self::META_TYPE => 'kvp',
				self::META_KVP_KEY_NAME => 'key',
				self::META_PRESERVE_KEYS => $keys,
				self::META_BC_BOOLS => $bools,
				self::META_INDEXED_TAG_NAME => 'var',
			] + $vars;
		} else {
			return [
				self::META_TYPE => 'array',
				self::META_BC_BOOLS => $bools,
				self::META_INDEXED_TAG_NAME => 'value',
			] + $vars;
		}
	}

	/**
	 * Format an expiry timestamp for API output
	 * @since 1.29
	 * @param string $expiry Expiry timestamp, likely from the database
	 * @param string $infinity Use this string for infinite expiry
	 *  (only use this to maintain backward compatibility with existing output)
	 * @return string Formatted expiry
	 */
	public static function formatExpiry( $expiry, $infinity = 'infinity' ) {
		static $dbInfinity;
		if ( $dbInfinity === null ) {
			$dbInfinity = wfGetDB( DB_REPLICA )->getInfinity();
		}

		if ( $expiry === '' || $expiry === null || $expiry === false ||
			wfIsInfinity( $expiry ) || $expiry === $dbInfinity
		) {
			return $infinity;
		} else {
			return wfTimestamp( TS_ISO_8601, $expiry );
		}
	}

	// endregion -- end of Utility

}

/*
 * This file uses VisualStudio style region/endregion fold markers which are
 * recognised by PHPStorm. If modelines are enabled, the following editor
 * configuration will also enable folding in vim, if it is in the last 5 lines
 * of the file. We also use "@name" which creates sections in Doxygen.
 *
 * vim: foldmarker=//\ region,//\ endregion foldmethod=marker
 */
