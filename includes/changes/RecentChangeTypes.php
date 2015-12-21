<?php
/**
 * Registry of RecentChange types.
 *
 * @todo add support for rc_source.
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
 * @ingroup Changes
 */
class RecentChangeTypes {

	/**
	 * @var array Array of change types
	 */
	private static $changeTypes = array(
		'edit' => RC_EDIT,
		'new' => RC_NEW,
		'log' => RC_LOG,
		'external' => RC_EXTERNAL,
		'categorize' => RC_CATEGORIZE,
	);

	/**
	 * Parsing text to RC_* constants
	 * @since 1.27
	 * @param string|array $type
	 * @throws MWException
	 * @return int|array RC_TYPE
	 */
	public static function parseToRCType( $type ) {
		if ( is_array( $type ) ) {
			$retval = array();
			foreach ( $type as $t ) {
				$retval[] = RecentChange::parseToRCType( $t );
			}

			return $retval;
		}

		if ( !array_key_exists( $type, self::$changeTypes ) ) {
			throw new MWException( "Unknown type '$type'" );
		}
		return self::$changeTypes[$type];
	}

	/**
	 * Parsing RC_* constants to human-readable test
	 * @since 1.27
	 * @param int $rcType
	 * @return string $type
	 */
	public static function parseFromRCType( $rcType ) {
		return array_search( $rcType, self::$changeTypes, true ) ?: "$rcType";
	}

	/**
	 * Get an array of all change types
	 *
	 * @since 1.27
	 *
	 * @return array
	 */
	public static function getChangeTypes() {
		return array_keys( self::$changeTypes );
	}

}
