<?php
/**
 * This file contains database access object related constants.
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Database
 */

namespace Wikimedia\Rdbms;

use InvalidArgumentException;

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
	 * Get an appropriate DB index and options
	 *
	 * @deprecated since 1.43
	 * @param int $bitfield Bitfield of IDBAccessObject::READ_* constants
	 * @return array List of DB indexes and options in this order:
	 *   - DB_PRIMARY or DB_REPLICA constant for the initial query
	 *   - SELECT options array for the initial query
	 */
	public static function getDBOptions( $bitfield ) {
		wfDeprecated( __METHOD__, '1.43' );
		if ( self::hasFlags( $bitfield, IDBAccessObject::READ_LATEST_IMMUTABLE ) ) {
			$index = DB_REPLICA; // override READ_LATEST if set
		} elseif ( self::hasFlags( $bitfield, IDBAccessObject::READ_LATEST ) ) {
			$index = DB_PRIMARY;
		} else {
			$index = DB_REPLICA;
		}

		$lockingOptions = [];
		if ( self::hasFlags( $bitfield, IDBAccessObject::READ_EXCLUSIVE ) ) {
			$lockingOptions[] = 'FOR UPDATE';
		} elseif ( self::hasFlags( $bitfield, IDBAccessObject::READ_LOCKING ) ) {
			$lockingOptions[] = 'LOCK IN SHARE MODE';
		}

		return [ $index, $lockingOptions ];
	}

	/**
	 * Takes $index from ::getDBOptions() and return proper Database object
	 *
	 * @deprecated since 1.42
	 *
	 * @param IConnectionProvider $dbProvider
	 * @param int $index either DB_REPLICA or DB_PRIMARY
	 * @return IReadableDatabase
	 */
	public static function getDBFromIndex( IConnectionProvider $dbProvider, int $index ): IReadableDatabase {
		wfDeprecated( __METHOD__, '1.42' );
		if ( $index === DB_PRIMARY ) {
			return $dbProvider->getPrimaryDatabase();
		} elseif ( $index === DB_REPLICA ) {
			return $dbProvider->getReplicaDatabase();
		} else {
			throw new InvalidArgumentException( '$index must be either DB_REPLICA or DB_PRIMARY' );
		}
	}

	/**
	 * @param IConnectionProvider $dbProvider
	 * @param int $recency IDBAccessObject::READ_* constant
	 * @return IReadableDatabase
	 * @since 1.42
	 */
	public static function getDBFromRecency( IConnectionProvider $dbProvider, int $recency ): IReadableDatabase {
		if ( self::hasFlags( $recency, IDBAccessObject::READ_LATEST ) ) {
			return $dbProvider->getPrimaryDatabase();
		}
		return $dbProvider->getReplicaDatabase();
	}
}

/** @deprecated class alias since 1.43 */
class_alias( DBAccessObjectUtils::class, 'DBAccessObjectUtils' );
