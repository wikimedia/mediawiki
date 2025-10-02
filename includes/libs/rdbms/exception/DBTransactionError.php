<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */
namespace Wikimedia\Rdbms;

use Wikimedia\NormalizedException\INormalizedException;
use Wikimedia\NormalizedException\NormalizedExceptionTrait;

/**
 * @newable
 * @ingroup Database
 */
class DBTransactionError extends DBExpectedError implements INormalizedException {

	use NormalizedExceptionTrait;

	/**
	 * @stable to call
	 * @param IDatabase|null $db
	 * @param string $error
	 * @param array $params parameters to be passed down to the i18n message
	 * @param \Throwable|null $prev
	 * @param array $errorParams PSR-3 message context
	 */
	public function __construct(
		?IDatabase $db, $error, array $params = [], ?\Throwable $prev = null, $errorParams = []
	) {
		$this->normalizedMessage = $error;
		$this->messageContext = $errorParams;
		parent::__construct(
			$db,
			self::getMessageFromNormalizedMessage( $error, $params ),
			$params,
			$prev
		);
	}
}
