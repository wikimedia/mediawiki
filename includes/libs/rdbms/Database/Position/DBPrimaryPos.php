<?php

namespace Wikimedia\Rdbms;

use Stringable;

/**
 * An object representing a primary or replica DB position in a replicated setup.
 *
 * The implementation details of this opaque type are up to the database subclass.
 *
 * @since 1.37
 */
interface DBPrimaryPos extends Stringable {
	/**
	 * @since 1.25
	 * @return float UNIX timestamp
	 */
	public function asOfTime();

	/**
	 * @since 1.27
	 * @param DBPrimaryPos $pos
	 * @return bool Whether this position is at or higher than $pos
	 */
	public function hasReached( DBPrimaryPos $pos );

	/**
	 * @since 1.27
	 * @return string
	 */
	public function __toString();

	/**
	 * Deserialization from storage
	 *
	 * @since 1.39
	 * @param array $data Representation as returned from ::toArray()
	 * @return DBPrimaryPos
	 */
	public static function newFromArray( array $data );

	/**
	 * Serialization for storage
	 *
	 * @since 1.39
	 * @return array Representation for use by ::newFromArray()
	 */
	public function toArray(): array;

}
