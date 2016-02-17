<?php
/**
 * This file contains database access object related constants.
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
 * @ingroup Database
 */

/**
 * Helper class for DAO classes
 *
 * @since 1.26
 */
class DBAccessObjectUtils {
	/**
	 * @param integer $bitfield
	 * @param integer $flags IDBAccessObject::READ_* constant
	 * @return bool Bitfield has flag $flag set
	 */
	public static function hasFlags( $bitfield, $flags ) {
		return ( $bitfield & $flags ) == $flags;
	}

	/**
	 * Get an appropriate DB index and options for a query
	 *
	 * @param integer $bitfield
	 * @return array (DB_MASTER/DB_SLAVE, SELECT options array)
	 */
	public static function getDBOptions( $bitfield ) {
		$index = self::hasFlags( $bitfield, IDBAccessObject::READ_LATEST )
			? DB_MASTER
			: DB_SLAVE;

		$options = [];
		if ( self::hasFlags( $bitfield, IDBAccessObject::READ_EXCLUSIVE ) ) {
			$options[] = 'FOR UPDATE';
		} elseif ( self::hasFlags( $bitfield, IDBAccessObject::READ_LOCKING ) ) {
			$options[] = 'LOCK IN SHARE MODE';
		}

		return [ $index, $options ];
	}
}
