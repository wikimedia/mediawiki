<?php

namespace MediaWiki\RecentChanges\ChangesListQuery;

use Countable;
use stdClass;
use Wikimedia\Rdbms\FakeResultWrapper;
use Wikimedia\Rdbms\IResultWrapper;

/**
 * The result of ChangesListQuery execution
 * @since 1.45
 */
class ChangesListResult implements Countable {
	/**
	 * @internal
	 * @param stdClass[]|IResultWrapper $rows
	 * @param callable $highlighter
	 */
	public function __construct(
		private $rows,
		private $highlighter
	) {
	}

	/**
	 * Get the rows as objects
	 * @return iterable<stdClass>
	 */
	public function getRows() {
		return $this->rows;
	}

	/**
	 * Wrap the rows in a result wrapper
	 * @return IResultWrapper
	 */
	public function getResultWrapper(): IResultWrapper {
		if ( $this->rows instanceof IResultWrapper ) {
			return $this->rows;
		} else {
			return new FakeResultWrapper( $this->rows );
		}
	}

	public function count(): int {
		return count( $this->rows );
	}

	/**
	 * Evaluate any highlights registered with ChangesListQuery::highlight(), returning
	 * the names of the active highlights.
	 *
	 * @param stdClass $row One of the rows returned by getRows()
	 * @return array<string,bool>
	 */
	public function getHighlightsFromRow( stdClass $row ): array {
		return ( $this->highlighter )( $row );
	}
}
