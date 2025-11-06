<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */
namespace Wikimedia\Rdbms;

use Wikimedia\Message\MessageSpecifier;

/**
 * Base class for the more common types of database errors. These are known to occur
 * frequently, so we try to give friendly error messages for them.
 *
 * @newable
 * @ingroup Database
 * @since 1.23
 */
class DBExpectedError extends DBError implements MessageSpecifier {
	/** @var string[] Message parameters */
	protected $params;

	/**
	 * @stable to call
	 * @param IDatabase|null $db
	 * @param string $error
	 * @param array $params
	 * @param \Throwable|null $prev
	 */
	public function __construct(
		?IDatabase $db, $error, array $params = [], ?\Throwable $prev = null
	) {
		parent::__construct( $db, $error, $prev );
		$this->params = $params;
	}

	public function getKey(): string {
		return 'databaseerror-text';
	}

	public function getParams(): array {
		return $this->params;
	}
}
