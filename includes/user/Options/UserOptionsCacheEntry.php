<?php

namespace MediaWiki\User\Options;

use Wikimedia\Rdbms\IDBAccessObject;

/**
 * @internal
 */
class UserOptionsCacheEntry {
	/**
	 * @var array<string,mixed> Values modified by setOption(), queued for update
	 */
	public $modifiedValues = [];

	/**
	 * @var array<string,string> The source name for each value in $originalValues
	 */
	public $sources = [];

	/**
	 * @var array<string,string> The value of the $global parameter to setOption()
	 */
	public $globalUpdateActions = [];

	/**
	 * @var array<string,string>|null Cached original user options with all the
	 *   adjustments like time correction and hook changes applied. Ready to be
	 *   returned. Null if the original values have not been loaded
	 */
	public $originalValues;

	/** @var int|null Query flags used to retrieve options from database */
	public $recency;

	/**
	 * Determine if it's OK to use cached options values for a given user and query flags
	 *
	 * @param int $recency
	 * @return bool
	 */
	public function canUseCachedValues( $recency ) {
		$recencyUsed = $this->recency ?? IDBAccessObject::READ_NONE;
		return $recencyUsed >= $recency;
	}
}
