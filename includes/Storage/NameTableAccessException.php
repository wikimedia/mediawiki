<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Storage;

use RuntimeException;

/**
 * Exception representing a failure to look up a row from a name table.
 *
 * @newable
 * @since 1.31
 */
class NameTableAccessException extends RuntimeException {

	/**
	 * @param string $tableName
	 * @param string $accessType
	 * @param string|int $accessValue
	 * @return NameTableAccessException
	 */
	public static function newFromDetails( $tableName, $accessType, $accessValue ) {
		$message = "Failed to access name from {$tableName} using {$accessType} = {$accessValue}";
		return new self( $message );
	}

}
