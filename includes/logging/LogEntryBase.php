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

namespace MediaWiki\Logging;

/**
 * Extends the LogEntry Interface with some basic functionality
 *
 * @since 1.19
 */
abstract class LogEntryBase implements LogEntry {

	/** @inheritDoc */
	public function getFullType() {
		return $this->getType() . '/' . $this->getSubtype();
	}

	/** @inheritDoc */
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
	 * @return array|false
	 */
	public static function extractParams( $blob ) {
		return unserialize( $blob );
	}
}

/** @deprecated class alias since 1.44 */
class_alias( LogEntryBase::class, 'LogEntryBase' );
