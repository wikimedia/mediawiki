<?php
/**
 * Class to walk into a list of Title objects.
 *
 * Note: this entire file is a byte-for-byte copy of UserArrayFromResult.php
 * with s/User/Title/.  If anyone can figure out how to do this nicely
 * with inheritance or something, please do so.
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Title;

use Countable;
use Iterator;
use Wikimedia\Rdbms\IResultWrapper;

/**
 * @newable
 * @note marked as newable in 1.35 for lack of a better alternative,
 *       but should probably become part of the TitleFactory service.
 */
class TitleArrayFromResult implements Countable, Iterator {
	/** @var IResultWrapper */
	public $res;

	/** @var int */
	public $key;

	/** @var Title|false */
	public $current;

	/**
	 * @stable to call
	 *
	 * @param IResultWrapper $res
	 */
	public function __construct( $res ) {
		$this->res = $res;
		$this->key = 0;
		$this->setCurrent( $this->res->current() );
	}

	/**
	 * @param \stdClass|false $row
	 * @return void
	 */
	protected function setCurrent( $row ) {
		if ( $row === false ) {
			$this->current = false;
		} else {
			$this->current = Title::newFromRow( $row );
		}
	}

	public function count(): int {
		return $this->res->numRows();
	}

	public function current(): Title {
		return $this->current;
	}

	public function key(): int {
		return $this->key;
	}

	public function next(): void {
		$row = $this->res->fetchObject();
		$this->setCurrent( $row );
		$this->key++;
	}

	public function rewind(): void {
		$this->res->rewind();
		$this->key = 0;
		$this->setCurrent( $this->res->current() );
	}

	public function valid(): bool {
		return $this->current !== false;
	}
}

/** @deprecated class alias since 1.41 */
class_alias( TitleArrayFromResult::class, 'TitleArrayFromResult' );
