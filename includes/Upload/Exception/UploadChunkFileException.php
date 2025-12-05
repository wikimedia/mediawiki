<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Upload
 */

namespace MediaWiki\Upload\Exception;

use RuntimeException;
use Wikimedia\NormalizedException\INormalizedException;
use Wikimedia\NormalizedException\NormalizedExceptionTrait;

/**
 * @newable
 */
class UploadChunkFileException extends RuntimeException implements INormalizedException {
	use NormalizedExceptionTrait;

	public function __construct( string $message, array $context = [] ) {
		$this->normalizedMessage = $message;
		$this->messageContext = $context;

		parent::__construct(
			self::getMessageFromNormalizedMessage( $this->normalizedMessage, $this->messageContext )
		);
	}
}
