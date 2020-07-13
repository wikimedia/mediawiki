<?php

namespace Wikimedia\Rdbms;

use Serializable;

/**
 * An object representing a master or replica DB position in a replicated setup.
 *
 * The implementation details of this opaque type are up to the database subclass.
 *
 * @stable to implement
 */
interface DBMasterPos extends Serializable {
	/**
	 * @return float UNIX timestamp
	 * @since 1.25
	 */
	public function asOfTime();

	/**
	 * @param DBMasterPos $pos
	 * @return bool Whether this position is at or higher than $pos
	 * @since 1.27
	 */
	public function hasReached( DBMasterPos $pos );

	/**
	 * @param DBMasterPos $pos
	 * @return bool Whether this position appears to be for the same channel as another
	 * @since 1.27
	 */
	public function channelsMatch( DBMasterPos $pos );

	/**
	 * @return string
	 * @since 1.27
	 */
	public function __toString();
}
