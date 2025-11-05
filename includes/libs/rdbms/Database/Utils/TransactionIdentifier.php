<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */
namespace Wikimedia\Rdbms;

use Stringable;

/**
 * Class used for token representing identifiers for atomic transactions from IDatabase instances
 *
 * @ingroup Database
 * @internal
 */
class TransactionIdentifier implements Stringable {
	/** @var string Application-side ID of the active transaction or an empty string otherwise */
	private $id = '';

	public function __construct() {
		static $nextId;
		$nextId = ( $nextId !== null ? $nextId++ : mt_rand() ) % 0xffff;
		$this->id = sprintf( '%06x', mt_rand( 0, 0xffffff ) ) . sprintf( '%04x', $nextId );
	}

	public function __toString() {
		return $this->id;
	}
}
