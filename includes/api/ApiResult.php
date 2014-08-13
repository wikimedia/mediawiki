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
 * There are three special key values that change how XML output is generated:
 *   '_element'     This key sets the tag name for the rest of the elements in the current array.
 *                  It is only inserted if the formatter returned true for getNeedsRawData()
 *   '_subelements' This key causes the specified elements to be returned as subelements rather than attributes.
 *                  It is only inserted if the formatter returned true for getNeedsRawData()
 *   '*'            This key has special meaning only to the XML formatter, and is outputted as is
 *                  for all others. In XML it becomes the content of the current element.
 *
 * @ingroup API
 */
class ApiResult extends ApiBase {

	/**
	 * override existing value in addValue() and setElement()
	 * @since 1.21
	 */
	const OVERRIDE = 1;

	/**
	 * For addValue() and setElement(), if the value does not exist, add it as the first element.
	 * In case the new value has no name (numerical index), all indexes will be renumbered.
	 * @since 1.21
	 */
	const ADD_ON_TOP = 2;

	/**
	 * For addValue() and setElement(), do not check size while adding a value
	 * Don't use this unless you REALLY know what you're doing.
	 * Values added while the size checking was disabled will never be counted
	 * @since 1.24
	 */
	const NO_SIZE_CHECK = 4;

	private $mData, $mIsRawMode, $mSize, $mCheckingSize;

	private $continueAllModules = array();
	private $continueGeneratedModules = array();
	private $continuationData = array();
	private $generatorContinuationData = array();
	private $generatorParams = array();
	private $generatorDone = false;

	/**
	 * @param ApiMain $main
	 */
	public function __construct( ApiMain $main ) {
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
	 * @since 1.23 $flag parameter added
	 * @param bool $flag Set the raw mode flag to this state
	 */
	public function setRawMode( $flag = true ) {
		$this->mIsRawMode = $flag;
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
	 * @param mixed $value
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
	 * @deprecated since 1.24, use ApiResult::NO_SIZE_CHECK
	 */
	public function disableSizeCheck() {
		$this->mCheckingSize = false;
	}

	/**
	 * Re-enable size checking in addValue()
	 * @deprecated since 1.24, use ApiResult::NO_SIZE_CHECK
	 */
	public function enableSizeCheck() {
		$this->mCheckingSize = true;
	}

	/**
	 * Add an output value to the array by name.
	 * Verifies that value with the same name has not been added before.
	 * @param array $arr To add $value to
	 * @param string $name Index of $arr to add $value at
	 * @param mixed $value
	 * @param int $flags Zero or more OR-ed flags like OVERRIDE | ADD_ON_TOP.
	 *    This parameter used to be boolean, and the value of OVERRIDE=1 was
	 *    specifically chosen so that it would be backwards compatible with the
	 *    new method signature.
	 *
	 * @since 1.21 int $flags replaced boolean $override
	 */
	public static function setElement( &$arr, $name, $value, $flags = 0 ) {
		if ( $arr === null || $name === null || $value === null
			|| !is_array( $arr ) || is_array( $name )
		) {
			ApiBase::dieDebug( __METHOD__, 'Bad parameter' );
		}

		$exists = isset( $arr[$name] );
		if ( !$exists || ( $flags & ApiResult::OVERRIDE ) ) {
			if ( !$exists && ( $flags & ApiResult::ADD_ON_TOP ) ) {
				$arr = array( $name => $value ) + $arr;
			} else {
				$arr[$name] = $value;
			}
		} elseif ( is_array( $arr[$name] ) && is_array( $value ) ) {
			$merged = array_intersect_key( $arr[$name], $value );
			if ( !count( $merged ) ) {
				$arr[$name] += $value;
			} else {
				ApiBase::dieDebug( __METHOD__, "Attempting to merge element $name" );
			}
		} else {
			ApiBase::dieDebug(
				__METHOD__,
				"Attempting to add element $name=$value, existing value is {$arr[$name]}"
			);
		}
	}

	/**
	 * Adds a content element to an array.
	 * Use this function instead of hardcoding the '*' element.
	 * @param array $arr To add the content element to
	 * @param mixed $value
	 * @param string $subElemName When present, content element is created
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
	 * Causes the elements with the specified names to be output as
	 * subelements rather than attributes.
	 * @param array $arr
	 * @param array|string $names The element name(s) to be output as subelements
	 */
	public function setSubelements( &$arr, $names ) {
		// In raw mode, add the '_subelements', otherwise just ignore
		if ( !$this->getIsRawMode() ) {
			return;
		}
		if ( $arr === null || $names === null || !is_array( $arr ) ) {
			ApiBase::dieDebug( __METHOD__, 'Bad parameter' );
		}
		if ( !is_array( $names ) ) {
			$names = array( $names );
		}
		if ( !isset( $arr['_subelements'] ) ) {
			$arr['_subelements'] = $names;
		} else {
			$arr['_subelements'] = array_merge( $arr['_subelements'], $names );
		}
	}

	/**
	 * In case the array contains indexed values (in addition to named),
	 * give all indexed values the given tag name. This function MUST be
	 * called on every array that has numerical indexes.
	 * @param array $arr
	 * @param string $tag Tag name
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
	 * @param array $arr
	 * @param string $tag Tag name
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
	 * @param array $path Path to the array, like addValue()'s $path
	 * @param string $tag
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
	 * @param array|string|null $path
	 * @param string $name
	 * @param mixed $value
	 * @param int $flags Zero or more OR-ed flags like OVERRIDE | ADD_ON_TOP.
	 *   This parameter used to be boolean, and the value of OVERRIDE=1 was specifically
	 *   chosen so that it would be backwards compatible with the new method signature.
	 * @return bool True if $value fits in the result, false if not
	 *
	 * @since 1.21 int $flags replaced boolean $override
	 */
	public function addValue( $path, $name, $value, $flags = 0 ) {
		$data = &$this->mData;
		if ( $this->mCheckingSize && !( $flags & ApiResult::NO_SIZE_CHECK ) ) {
			$newsize = $this->mSize + self::size( $value );
			$maxResultSize = $this->getConfig()->get( 'APIMaxResultSize' );
			if ( $newsize > $maxResultSize ) {
				$this->setWarning(
					"This result was truncated because it would otherwise be larger than the " .
						"limit of {$maxResultSize} bytes" );

				return false;
			}
			$this->mSize = $newsize;
		}

		$addOnTop = $flags & ApiResult::ADD_ON_TOP;
		if ( $path !== null ) {
			foreach ( (array)$path as $p ) {
				if ( !isset( $data[$p] ) ) {
					if ( $addOnTop ) {
						$data = array( $p => array() ) + $data;
						$addOnTop = false;
					} else {
						$data[$p] = array();
					}
				}
				$data = &$data[$p];
			}
		}

		if ( !$name ) {
			// Add list element
			if ( $addOnTop ) {
				// This element needs to be inserted in the beginning
				// Numerical indexes will be renumbered
				array_unshift( $data, $value );
			} else {
				// Add new value at the end
				$data[] = $value;
			}
		} else {
			// Add named element
			self::setElement( $data, $name, $value, $flags );
		}

		return true;
	}

	/**
	 * Add a parsed limit=max to the result.
	 *
	 * @param string $moduleName
	 * @param int $limit
	 */
	public function setParsedLimit( $moduleName, $limit ) {
		// Add value, allowing overwriting
		$this->addValue( 'limits', $moduleName, $limit, ApiResult::OVERRIDE );
	}

	/**
	 * Unset a value previously added to the result set.
	 * Fails silently if the value isn't found.
	 * For parameters, see addValue()
	 * @param array|null $path
	 * @param string $name
	 */
	public function unsetValue( $path, $name ) {
		$data = &$this->mData;
		if ( $path !== null ) {
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
	 * @param string $s
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
			$this->dieContinueUsageIf( count( $continue ) !== 2 );
			$this->generatorDone = ( $continue[0] === '-' );
			if ( !$this->generatorDone ) {
				$this->generatorParams = explode( '|', $continue[0] );
			}
			$skip = explode( '|', $continue[1] );
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
	 * @since 1.24
	 * @param ApiBase $module
	 * @param string $paramName
	 * @param string|array $paramValue
	 */
	public function setContinueParam( ApiBase $module, $paramName, $paramValue ) {
		$name = $module->getModuleName();
		if ( !isset( $this->continueAllModules[$name] ) ) {
			throw new MWException(
				"Module '$name' called ApiResult::setContinueParam but was not " .
				'passed to ApiResult::beginContinuation'
			);
		}
		if ( !$this->continueAllModules[$name] ) {
			throw new MWException(
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
	 * @since 1.24
	 * @param ApiBase $module
	 * @param string $paramName
	 * @param string|array $paramValue
	 */
	public function setGeneratorContinueParam( ApiBase $module, $paramName, $paramValue ) {
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
					$this->getMain()->getRequest()->getValues(),
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
			} else {
				// Generator and prop modules are all done. Mark it so.
				$this->generatorDone = true;
			}

			// Set 'continue' if any continuation data is set or if the generator
			// still needs to run
			if ( $data || !$this->generatorDone ) {
				$data['continue'] =
					( $this->generatorDone ? '-' : join( '|', $this->generatorParams ) ) .
					'||' . join( '|', $finishedModules );
			}
		}
		if ( $data ) {
			$this->addValue( null, $key, $data, ApiResult::ADD_ON_TOP | ApiResult::NO_SIZE_CHECK );
		}
	}
}
