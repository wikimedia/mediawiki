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
 * It simply wraps a nested array() structure, adding some functions to simplify array's modifications.
 * As various modules execute, they add different pieces of information to this result,
 * structuring it as it will be given to the client.
 *
 * Each subarray may either be a dictionary - key-value pairs with unique keys,
 * or lists, where the items are added using $data[] = $value notation.
 *
 * There are two special key values that change how XML output is generated:
 *   '_element' This key sets the tag name for the rest of the elements in the current array.
 *              It is only inserted if the formatter returned true for getNeedsRawData()
 *   '*'        This key has special meaning only to the XML formatter, and is outputed as is
 * 				for all others. In XML it becomes the content of the current element.
 *
 * @ingroup API
 */
class ApiResult extends ApiBase {

	private $mData, $mIsRawMode, $mSize, $mCheckingSize;

	/**
	 * Constructor
	 * @param $main ApiMain object
	 */
	public function __construct( $main ) {
		parent::__construct( $main, 'result' );
		$this->mIsRawMode = false;
		$this->mCheckingSize = true;
		$this->reset();
	}

	/**
	 * Clear the current result data.
	 */
	public function reset() {
		$this->mData = array();
		$this->mSize = 0;
	}

	/**
	 * Call this function when special elements such as '_element'
	 * are needed by the formatter, for example in XML printing.
	 */
	public function setRawMode() {
		$this->mIsRawMode = true;
	}

	/**
	 * Returns true whether the formatter requested raw data.
	 * @return bool
	 */
	public function getIsRawMode() {
		return $this->mIsRawMode;
	}

	/**
	 * Get the result's internal data array (read-only)
	 * @return array
	 */
	public function getData() {
		return $this->mData;
	}

	/**
	 * Get the 'real' size of a result item. This means the strlen() of the item,
	 * or the sum of the strlen()s of the elements if the item is an array.
	 * @param $value mixed
	 * @return int
	 */
	public static function size( $value ) {
		$s = 0;
		if ( is_array( $value ) ) {
			foreach ( $value as $v ) {
				$s += self::size( $v );
			}
		} elseif ( !is_object( $value ) ) {
			// Objects can't always be cast to string
			$s = strlen( $value );
		}
		return $s;
	}

	/**
	 * Get the size of the result, i.e. the amount of bytes in it
	 * @return int
	 */
	public function getSize() {
		return $this->mSize;
	}

	/**
	 * Disable size checking in addValue(). Don't use this unless you
	 * REALLY know what you're doing. Values added while size checking
	 * was disabled will not be counted (ever)
	 */
	public function disableSizeCheck() {
		$this->mCheckingSize = false;
	}

	/**
	 * Re-enable size checking in addValue()
	 */
	public function enableSizeCheck() {
		$this->mCheckingSize = true;
	}

	/**
	 * Add an output value to the array by name.
	 * Verifies that value with the same name has not been added before.
	 * @param $arr array to add $value to
	 * @param $name string Index of $arr to add $value at
	 * @param $value mixed
	 * @param $overwrite bool Whether overwriting an existing element is allowed
	 */
	public static function setElement( &$arr, $name, $value, $overwrite = false ) {
		if ( $arr === null || $name === null || $value === null || !is_array( $arr ) || is_array( $name ) ) {
			ApiBase::dieDebug( __METHOD__, 'Bad parameter' );
		}

		if ( !isset ( $arr[$name] ) || $overwrite ) {
			$arr[$name] = $value;
		} elseif ( is_array( $arr[$name] ) && is_array( $value ) ) {
			$merged = array_intersect_key( $arr[$name], $value );
			if ( !count( $merged ) ) {
				$arr[$name] += $value;
			} else {
				ApiBase::dieDebug( __METHOD__, "Attempting to merge element $name" );
			}
		} else {
			ApiBase::dieDebug( __METHOD__, "Attempting to add element $name=$value, existing value is {$arr[$name]}" );
		}
	}

	/**
	 * Adds a content element to an array.
	 * Use this function instead of hardcoding the '*' element.
	 * @param $arr array to add the content element to
	 * @param $value Mixed
	 * @param $subElemName string when present, content element is created
	 *  as a sub item of $arr. Use this parameter to create elements in
	 *  format "<elem>text</elem>" without attributes.
	 */
	public static function setContent( &$arr, $value, $subElemName = null ) {
		if ( is_array( $value ) ) {
			ApiBase::dieDebug( __METHOD__, 'Bad parameter' );
		}
		if ( is_null( $subElemName ) ) {
			ApiResult::setElement( $arr, '*', $value );
		} else {
			if ( !isset( $arr[$subElemName] ) ) {
				$arr[$subElemName] = array();
			}
			ApiResult::setElement( $arr[$subElemName], '*', $value );
		}
	}

	/**
	 * In case the array contains indexed values (in addition to named),
	 * give all indexed values the given tag name. This function MUST be
	 * called on every array that has numerical indexes.
	 * @param $arr array
	 * @param $tag string Tag name
	 */
	public function setIndexedTagName( &$arr, $tag ) {
		// In raw mode, add the '_element', otherwise just ignore
		if ( !$this->getIsRawMode() ) {
			return;
		}
		if ( $arr === null || $tag === null || !is_array( $arr ) || is_array( $tag ) ) {
			ApiBase::dieDebug( __METHOD__, 'Bad parameter' );
		}
		// Do not use setElement() as it is ok to call this more than once
		$arr['_element'] = $tag;
	}

	/**
	 * Calls setIndexedTagName() on each sub-array of $arr
	 * @param $arr array
	 * @param $tag string Tag name
	 */
	public function setIndexedTagName_recursive( &$arr, $tag ) {
		if ( !is_array( $arr ) ) {
			return;
		}
		foreach ( $arr as &$a ) {
			if ( !is_array( $a ) ) {
				continue;
			}
			$this->setIndexedTagName( $a, $tag );
			$this->setIndexedTagName_recursive( $a, $tag );
		}
	}

	/**
	 * Calls setIndexedTagName() on an array already in the result.
	 * Don't specify a path to a value that's not in the result, or
	 * you'll get nasty errors.
	 * @param $path array Path to the array, like addValue()'s $path
	 * @param $tag string
	 */
	public function setIndexedTagName_internal( $path, $tag ) {
		$data = &$this->mData;
		foreach ( (array)$path as $p ) {
			if ( !isset( $data[$p] ) ) {
				$data[$p] = array();
			}
			$data = &$data[$p];
		}
		if ( is_null( $data ) ) {
			return;
		}
		$this->setIndexedTagName( $data, $tag );
	}

	/**
	 * Add value to the output data at the given path.
	 * Path can be an indexed array, each element specifying the branch at which to add the new
	 * value. Setting $path to array('a','b','c') is equivalent to data['a']['b']['c'] = $value.
	 * If $path is null, the value will be inserted at the data root.
	 * If $name is empty, the $value is added as a next list element data[] = $value.
	 *
	 * @param $path array|string|null
	 * @param $name string
	 * @param $value mixed
	 * @param $overwrite bool
	 *
	 * @return bool True if $value fits in the result, false if not
	 */
	public function addValue( $path, $name, $value, $overwrite = false ) {
		global $wgAPIMaxResultSize;

		$data = &$this->mData;
		if ( $this->mCheckingSize ) {
			$newsize = $this->mSize + self::size( $value );
			if ( $newsize > $wgAPIMaxResultSize ) {
				$this->setWarning(
					"This result was truncated because it would otherwise be larger than the " .
							"limit of {$wgAPIMaxResultSize} bytes" );
				return false;
			}
			$this->mSize = $newsize;
		}

		if ( !is_null( $path ) ) {
			if ( is_array( $path ) ) {
				foreach ( $path as $p ) {
					if ( !isset( $data[$p] ) ) {
						$data[$p] = array();
					}
					$data = &$data[$p];
				}
			} else {
				if ( !isset( $data[$path] ) ) {
					$data[$path] = array();
				}
				$data = &$data[$path];
			}
		}

		if ( !$name ) {
			$data[] = $value; // Add list element
		} else {
			self::setElement( $data, $name, $value, $overwrite ); // Add named element
		}
		return true;
	}

	/**
	 * Add a parsed limit=max to the result.
	 *
	 * @param $moduleName string
	 * @param $limit int
	 */
	public function setParsedLimit( $moduleName, $limit ) {
		// Add value, allowing overwriting
		$this->addValue( 'limits', $moduleName, $limit, true );
	}

	/**
	 * Unset a value previously added to the result set.
	 * Fails silently if the value isn't found.
	 * For parameters, see addValue()
	 * @param $path array
	 * @param $name string
	 */
	public function unsetValue( $path, $name ) {
		$data = &$this->mData;
		if ( !is_null( $path ) ) {
			foreach ( (array)$path as $p ) {
				if ( !isset( $data[$p] ) ) {
					return;
				}
				$data = &$data[$p];
			}
		}
		$this->mSize -= self::size( $data[$name] );
		unset( $data[$name] );
	}

	/**
	 * Ensure all values in this result are valid UTF-8.
	 */
	public function cleanUpUTF8() {
		array_walk_recursive( $this->mData, array( 'ApiResult', 'cleanUp_helper' ) );
	}

	/**
	 * Callback function for cleanUpUTF8()
	 *
	 * @param $s string
	 */
	private static function cleanUp_helper( &$s ) {
		if ( !is_string( $s ) ) {
			return;
		}
		global $wgContLang;
		$s = $wgContLang->normalize( $s );
	}

	/**
	 * Converts a Status object to an array suitable for addValue
	 * @param Status $status
	 * @param string $errorType
	 * @return array
	 */
	public function convertStatusToArray( $status, $errorType = 'error' ) {
		if ( $status->isGood() ) {
			return array();
		}

		$result = array();
		foreach ( $status->getErrorsByType( $errorType ) as $error ) {
			$this->setIndexedTagName( $error['params'], 'param' );
			$result[] = $error;
		}
		$this->setIndexedTagName( $result, $errorType );
		return $result;
	}

	public function execute() {
		ApiBase::dieDebug( __METHOD__, 'execute() is not supported on Result object' );
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
