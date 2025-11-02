<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\User;

use stdClass;
use Wikimedia\Rdbms\IResultWrapper;

/**
 * @internal Call and type against UserArray instead.
 */
class UserArrayFromResult extends UserArray {

	private IResultWrapper $res;
	private int $key = 0;
	private ?User $current = null;

	public function __construct( IResultWrapper $res ) {
		$this->res = $res;
		$this->rewind();
	}

	/**
	 * @param stdClass|null|false $row
	 * @return void
	 */
	protected function setCurrent( $row ) {
		$this->current = $row instanceof stdClass ? User::newFromRow( $row ) : null;
	}

	public function count(): int {
		return $this->res->numRows();
	}

	public function current(): User {
		return $this->current;
	}

	public function key(): int {
		return $this->key;
	}

	public function next(): void {
		$this->setCurrent( $this->res->fetchObject() );
		$this->key++;
	}

	public function rewind(): void {
		$this->res->rewind();
		$this->key = 0;
		$this->setCurrent( $this->res->current() );
	}

	public function valid(): bool {
		return (bool)$this->current;
	}
}

/** @deprecated class alias since 1.41 */
class_alias( UserArrayFromResult::class, 'UserArrayFromResult' );
