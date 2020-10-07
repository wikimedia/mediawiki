<?php
/**
 * This file deals with UID generation.
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
use MediaWiki\MediaWikiServices;
use Wikimedia\UUID\GlobalIdGenerator;

/**
 * Class for getting statistically unique IDs
 *
 * @since 1.21
 * @deprecated Since 1.35; use GlobalIdGenerator instead
 */
class UIDGenerator {
	/** @var int B/C constant (deprecated since 1.35) */
	public const QUICK_RAND = 0; // b/c
	/** @var int B/C constant (deprecated since 1.35) */
	public const QUICK_VOLATILE = GlobalIdGenerator::QUICK_VOLATILE;

	/**
	 * Get a statistically unique 88-bit unsigned integer ID string.
	 * The bits of the UID are prefixed with the time (down to the millisecond).
	 *
	 * These IDs are suitable as values for the shard key of distributed data.
	 * If a column uses these as values, it should be declared UNIQUE to handle collisions.
	 * New rows almost always have higher UIDs, which makes B-TREE updates on INSERT fast.
	 * They can also be stored "DECIMAL(27) UNSIGNED" or BINARY(11) in MySQL.
	 *
	 * UID generation is serialized on each server (as the node ID is for the whole machine).
	 *
	 * @param int $base Specifies a base other than 10
	 * @return string Number
	 * @throws RuntimeException
	 */
	public static function newTimestampedUID88( $base = 10 ) {
		$gen = MediaWikiServices::getInstance()->getGlobalIdGenerator();

		return $gen->newTimestampedUID88( $base );
	}

	/**
	 * Get a statistically unique 128-bit unsigned integer ID string.
	 * The bits of the UID are prefixed with the time (down to the millisecond).
	 *
	 * These IDs are suitable as globally unique IDs, without any enforced uniqueness.
	 * New rows almost always have higher UIDs, which makes B-TREE updates on INSERT fast.
	 * They can also be stored as "DECIMAL(39) UNSIGNED" or BINARY(16) in MySQL.
	 *
	 * UID generation is serialized on each server (as the node ID is for the whole machine).
	 *
	 * @param int $base Specifies a base other than 10
	 * @return string Number
	 * @throws RuntimeException
	 */
	public static function newTimestampedUID128( $base = 10 ) {
		$gen = MediaWikiServices::getInstance()->getGlobalIdGenerator();

		return $gen->newTimestampedUID128( $base );
	}

	/**
	 * Return an RFC4122 compliant v1 UUID
	 *
	 * @return string
	 * @throws RuntimeException
	 * @since 1.27
	 */
	public static function newUUIDv1() {
		$gen = MediaWikiServices::getInstance()->getGlobalIdGenerator();

		return $gen->newUUIDv1();
	}

	/**
	 * Return an RFC4122 compliant v1 UUID
	 *
	 * @return string 32 hex characters with no hyphens
	 * @throws RuntimeException
	 * @since 1.27
	 */
	public static function newRawUUIDv1() {
		$gen = MediaWikiServices::getInstance()->getGlobalIdGenerator();

		return $gen->newRawUUIDv1();
	}

	/**
	 * Get timestamp in a specified format from UUIDv1
	 *
	 * @param string $uuid the UUID to get the timestamp from
	 * @param int $format the format to convert the timestamp to. Default: TS_MW
	 * @return string|false timestamp in requested format or false
	 */
	public static function getTimestampFromUUIDv1( string $uuid, int $format = TS_MW ) {
		$gen = MediaWikiServices::getInstance()->getGlobalIdGenerator();

		return $gen->getTimestampFromUUIDv1( $uuid, $format );
	}

	/**
	 * Return an RFC4122 compliant v4 UUID
	 *
	 * @param int $flags Bitfield (unused)
	 * @return string
	 * @throws RuntimeException
	 */
	public static function newUUIDv4( $flags = 0 ) {
		$gen = MediaWikiServices::getInstance()->getGlobalIdGenerator();

		return $gen->newUUIDv4();
	}

	/**
	 * Return an RFC4122 compliant v4 UUID
	 *
	 * @param int $flags Bitfield (unused)
	 * @return string 32 hex characters with no hyphens
	 * @throws RuntimeException
	 */
	public static function newRawUUIDv4( $flags = 0 ) {
		$gen = MediaWikiServices::getInstance()->getGlobalIdGenerator();

		return $gen->newRawUUIDv4();
	}

	/**
	 * Return an ID that is sequential *only* for this node and bucket
	 *
	 * These IDs are suitable for per-host sequence numbers, e.g. for some packet protocols.
	 * If UIDGenerator::QUICK_VOLATILE is used the counter might reset on server restart.
	 *
	 * @param string $bucket Arbitrary bucket name (should be ASCII)
	 * @param int $bits Bit size (<=48) of resulting numbers before wrap-around
	 * @param int $flags (supports UIDGenerator::QUICK_VOLATILE)
	 * @return float Integer value as float
	 * @since 1.23
	 */
	public static function newSequentialPerNodeID( $bucket, $bits = 48, $flags = 0 ) {
		$gen = MediaWikiServices::getInstance()->getGlobalIdGenerator();

		return $gen->newSequentialPerNodeID( $bucket, $bits, $flags );
	}

	/**
	 * Return IDs that are sequential *only* for this node and bucket
	 *
	 * @see UIDGenerator::newSequentialPerNodeID()
	 * @param string $bucket Arbitrary bucket name (should be ASCII)
	 * @param int $bits Bit size (16 to 48) of resulting numbers before wrap-around
	 * @param int $count Number of IDs to return
	 * @param int $flags (supports UIDGenerator::QUICK_VOLATILE)
	 * @return array Ordered list of float integer values
	 * @since 1.23
	 */
	public static function newSequentialPerNodeIDs( $bucket, $bits, $count, $flags = 0 ) {
		$gen = MediaWikiServices::getInstance()->getGlobalIdGenerator();

		return $gen->newSequentialPerNodeIDs( $bucket, $bits, $count, $flags );
	}
}
