<?php
/**
 * DO NOT use it in production.
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Tests\Unit\Libs\Rdbms;

use Wikimedia\Rdbms\Platform\SQLPlatform;

class SQLPlatformTestHelper extends SQLPlatform {
	/**
	 * @var bool Value to return from unionSupportsOrderAndLimit()
	 */
	protected $unionSupportsOrderAndLimit = true;

	/**
	 * TODO: remove
	 *
	 * This was previously a stub for an abstract method, but now this is the
	 * only override. But many tests depend on unquoted table names appearing
	 * in query strings.
	 *
	 * @param string $s
	 * @return string
	 */
	public function addIdentifierQuotes( $s ) {
		return $s;
	}

	/** @inheritDoc */
	public function useIndexClause( $index ) {
		return "FORCE INDEX (" . $index . ")";
	}

	/** @inheritDoc */
	public function ignoreIndexClause( $index ) {
		return "IGNORE INDEX (" . $index . ")";
	}

	/** @inheritDoc */
	public function unionSupportsOrderAndLimit() {
		return $this->unionSupportsOrderAndLimit;
	}

	public function setUnionSupportsOrderAndLimit( bool $v ) {
		$this->unionSupportsOrderAndLimit = $v;
	}
}
