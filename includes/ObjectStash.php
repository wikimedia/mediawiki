<?php
/**
 * Functions to get stash objects.
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

use MediaWiki\Logger\LoggerFactory;

/**
 * Functions to get stash objects
 *
 * Stash objects are BagOStuff instances suitable for storing light
 * weight data that is not canonically stored elsewhere (such as RDBMS).
 * Stashes should be configured to propagate changes to all data-centers.
 *
 * Callers should be prepared for:
 *   - a) Writes to be slower in non-"primary" (e.g. HTTP GET/HEAD only) DCs
 *   - b) Reads to be eventually consistent, e.g. for get()/getMulti()
 * In general, this means avoiding updates on idempotent HTTP requests and
 * avoiding an assumption of perfect serializability (or accepting anomalies).
 * Reads may be eventually consistent or data might rollback as nodes flap.
 *
 * @since 1.26
 */
class ObjectStash {
	/** @var Array Map of (id => BagOStuff) */
	public static $instances = array();

	/**
	 * Get a cached instance of the specified type of stash object.
	 *
	 * @param string $id
	 * @return BagOStuff
	 */
	static function getInstance( $id ) {
		if ( isset( self::$instances[$id] ) ) {
			return self::$instances[$id];
		}

		$object = self::newFromId( $id );
		self::$instances[$id] = $object;

		return $object;
	}

	/**
	 * Clear all the stash instances
	 */
	static function clear() {
		self::$instances = array();
	}

	/**
	 * Create a new stash object of the specified type.
	 *
	 * @param string $id
	 *
	 * @throws MWException
	 * @return BagOStuff
	 */
	static function newFromId( $id ) {
		global $wgObjectStashes;

		if ( !isset( $wgObjectStashes[$id] ) ) {
			throw new InvalidArgumentException(
				"Invalid object stash type \"$id\" requested. " .
				"It is not present in \$wgObjectStashes." );
		}

		return self::newFromParams( $wgObjectStashes[$id] );
	}

	/**
	 * Create a new stash object from parameters
	 *
	 * @param array $params
	 * @return BagOStuff
	 * @throws InvalidArgumentException
	 */
	static function newFromParams( array $params ) {
		if ( isset( $params['loggroup'] ) ) {
			$params['logger'] = LoggerFactory::getInstance( $params['loggroup'] );
		}
		if ( isset( $params['class'] ) ) {
			$class = $params['class'];
			return new $class( $params );
		} else {
			throw new InvalidArgumentException(
				"The definition of stash type \""
				. print_r( $params, true ) . "\" lacks both "
				. "factory and class parameters." );
		}
	}

	/**
	 * @return BagOStuff
	 */
	static function getMainInstance() {
		global $wgMainStash;

		return self::getInstance( $wgMainStash );
	}
}
