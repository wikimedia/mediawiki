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

use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\IReadableDatabase;

/**
 * Helper class for DAO classes
 *
 * @since 1.26
 */
class DBAccessObjectUtils implements IDBAccessObject {
	/**
	 * @param int $bitfield
	 * @param int $flags IDBAccessObject::READ_* constant
	 * @return bool Bitfield has flag $flag set
	 */
	public static function hasFlags( $bitfield, $flags ) {
		return ( $bitfield & $flags ) == $flags;
	}

	/**
	 * Get an appropriate DB index, options, and fallback DB index for a query
	 *
	 * The fallback DB index and options are to be used if the entity is not found
	 * with the initial DB index, typically querying the primary DB to avoid lag
	 *
	 * @param int $bitfield Bitfield of IDBAccessObject::READ_* constants
	 * @return array List of DB indexes and options in this order:
	 *   - DB_PRIMARY or DB_REPLICA constant for the initial query
	 *   - SELECT options array for the initial query
	 *   - DB_PRIMARY constant for the fallback query; null if no fallback should happen
	 *   - SELECT options array for the fallback query; empty if no fallback should happen
	 */
	public static function getDBOptions( $bitfield ) {
		if ( self::hasFlags( $bitfield, self::READ_LATEST_IMMUTABLE ) ) {
			$index = DB_REPLICA; // override READ_LATEST if set
			$fallbackIndex = DB_PRIMARY;
		} elseif ( self::hasFlags( $bitfield, self::READ_LATEST ) ) {
			$index = DB_PRIMARY;
			$fallbackIndex = null;
		} else {
			$index = DB_REPLICA;
			$fallbackIndex = null;
		}

		$lockingOptions = [];
		if ( self::hasFlags( $bitfield, self::READ_EXCLUSIVE ) ) {
			$lockingOptions[] = 'FOR UPDATE';
		} elseif ( self::hasFlags( $bitfield, self::READ_LOCKING ) ) {
			$lockingOptions[] = 'LOCK IN SHARE MODE';
		}

		if ( $fallbackIndex !== null ) {
			$options = []; // locks on DB_REPLICA make no sense
			$fallbackOptions = $lockingOptions;
		} else {
			$options = $lockingOptions;
			$fallbackOptions = []; // no fallback
		}

		return [ $index, $options, $fallbackIndex, $fallbackOptions ];
	}

	/**
	 * Takes $index from ::getDBOptions() and return proper Database object
	 *
	 * Use of this function (and getDBOptions) is discouraged.
	 *
	 * @param IConnectionProvider $dbProvider
	 * @param int $index either DB_REPLICA or DB_PRIMARY
	 * @return IReadableDatabase
	 */
	public static function getDBFromIndex( IConnectionProvider $dbProvider, int $index ): IReadableDatabase {
		if ( $index === DB_PRIMARY ) {
			return $dbProvider->getPrimaryDatabase();
		} elseif ( $index === DB_REPLICA ) {
			return $dbProvider->getReplicaDatabase();
		} else {
			throw new InvalidArgumentException( '$index must be either DB_REPLICA or DB_PRIMARY' );
		}
	}
}
