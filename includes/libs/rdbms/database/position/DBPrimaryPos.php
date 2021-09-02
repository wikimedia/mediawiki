<?php

namespace Wikimedia\Rdbms;

use Serializable;

/**
 * An object representing a primary or replica DB position in a replicated setup.
 *
 * The implementation details of this opaque type are up to the database subclass.
 *
 * @since 1.37
 * @stable to implement
 */
interface DBPrimaryPos extends Serializable {
	/**
	 * @return float UNIX timestamp
	 * @since 1.25
	 */
	public function asOfTime();

	/**
	 * @param DBPrimaryPos $pos
	 * @return bool Whether this position is at or higher than $pos
	 * @since 1.27
	 */
	public function hasReached( DBPrimaryPos $pos );

	/**
	 * @param DBPrimaryPos $pos
	 * @return bool Whether this position appears to be for the same channel as another
	 * @since 1.27
	 */
	public function channelsMatch( DBPrimaryPos $pos );

	/**
	 * @return string
	 * @since 1.27
	 */
	public function __toString();
}

/**
 * Deprecated alias, renamed as of MediaWiki 1.37
 *
 * @deprecated since 1.37
 */
class_alias( DBPrimaryPos::class, 'Wikimedia\\Rdbms\\DBMasterPos' );
