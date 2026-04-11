<?php
/**
 * Contains a class for dealing with individual log entries
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
 * @author Niklas Laxström
 * @license GPL-2.0-or-later
 * @since 1.19
 */

use MediaWiki\Registration\ExtensionRegistry;

/**
 * Extends the LogEntry Interface with some basic functionality
 *
 * @since 1.19
 */
abstract class LogEntryBase implements LogEntry {

	public function getFullType() {
		return $this->getType() . '/' . $this->getSubtype();
	}

	public function isDeleted( $field ) {
		return ( $this->getDeleted() & $field ) === $field;
	}

	/**
	 * Whether the parameters for this log are stored in new or
	 * old format.
	 *
	 * @return bool
	 */
	public function isLegacy() {
		return false;
	}

	/**
	 * Create a blob from a parameter array
	 *
	 * @since 1.26
	 * @param array $params
	 * @return string
	 */
	public static function makeParamBlob( $params ) {
		return serialize( (array)$params );
	}

	/**
	 * Extract a parameter array from a blob
	 *
	 * @since 1.26
	 * @param string $blob
	 * @param ?string $logType Full log type of the corresponding log entry (see LogEntryBase::getFullType())
	 * @return array|false Array on success, false if the blob is not serialized data
	 */
	public static function extractParams( $blob, ?string $logType = null ) {
		// Don't allow serialized class instances, because log entries can be imported from user input (T422244).
		// However, extensions currently may store arbitrary data in log params, so we need a way to allow
		// such log entries to be handled. This is discouraged and the option may be removed in the future.
		$attribute = ExtensionRegistry::getInstance()->getAttribute( 'LogParamsAllowedClasses' );
		if ( $logType && array_key_exists( $logType, $attribute ) && is_array( $attribute[$logType] ) ) {
			$allowedClasses = $attribute[$logType];
		} else {
			$allowedClasses = false;
		}
		// Suppress errors about invalid serialized data, because this is also called
		// for legacy log entries, which don't use serialization
		// phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged
		$result = @unserialize( $blob, [ 'allowed_classes' => $allowedClasses ] );
		if ( $result !== false && !is_array( $result ) ) {
			// Non-arrays are not allowed here
			return false;
		}
		return $result;
	}

	/**
	 * Whether the parameters for this log entry contain forbidden serialized data.
	 */
	public static function containsUnsafeParams( array $params ): bool {
		$result = false;
		$params = [ $params ];
		array_walk_recursive( $params, static function ( $val ) use ( &$result ) {
			if ( $val instanceof \__PHP_Incomplete_Class ) {
				$result = true;
			}
		} );
		return $result;
	}
}
