<?php
/**
 * Exception representing a failure to look up a revision.
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Revision;

use RuntimeException;
use Throwable;
use Wikimedia\NormalizedException\INormalizedException;
use Wikimedia\NormalizedException\NormalizedExceptionTrait;

/**
 * Exception representing a failure to look up a revision.
 *
 * @newable
 * @since 1.31
 * @since 1.32 Renamed from MediaWiki\Storage\RevisionAccessException
 */
class RevisionAccessException extends RuntimeException implements INormalizedException {

	use NormalizedExceptionTrait;

	/**
	 * @stable to call
	 * @param string $normalizedMessage The exception message, with PSR-3 style placeholders.
	 * @param array $messageContext Message context, with values for the placeholders.
	 * @param int $code The exception code.
	 * @param Throwable|null $previous The previous throwable used for the exception chaining.
	 */
	public function __construct(
		string $normalizedMessage = '',
		array $messageContext = [],
		int $code = 0,
		?Throwable $previous = null
	) {
		$this->normalizedMessage = $normalizedMessage;
		$this->messageContext = $messageContext;
		parent::__construct(
			self::getMessageFromNormalizedMessage( $normalizedMessage, $messageContext ),
			$code,
			$previous
		);
	}
}
