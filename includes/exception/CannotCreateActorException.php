<?php
/**
 * Exception thrown when some operation failed
 *
 * @license GPL-2.0-or-later
 * @file
 *
 * @since 1.31
 */

namespace MediaWiki\Exception;

use RuntimeException;
use Wikimedia\NormalizedException\INormalizedException;
use Wikimedia\NormalizedException\NormalizedExceptionTrait;

/**
 * Exception thrown when an actor can't be created.
 * @newable
 */
class CannotCreateActorException extends RuntimeException implements INormalizedException {
	use NormalizedExceptionTrait;

	public function __construct( string $normalizedMessage, array $messageContext = [] ) {
		$this->normalizedMessage = $normalizedMessage;
		$this->messageContext = $messageContext;
		parent::__construct(
			self::getMessageFromNormalizedMessage( $normalizedMessage, $messageContext )
		);
	}
}

/** @deprecated class alias since 1.44 */
class_alias( CannotCreateActorException::class, 'CannotCreateActorException' );
