<?php

namespace MediaWiki\Settings;

use RuntimeException;
use Throwable;
use Wikimedia\NormalizedException\INormalizedException;
use Wikimedia\NormalizedException\NormalizedExceptionTrait;

class SettingsBuilderException extends RuntimeException implements INormalizedException {
	use NormalizedExceptionTrait;

	/**
	 * @param string $normalizedMessage The exception message, with PSR-3 style placeholders.
	 * @param array $messageContext Message context, with values for the placeholders.
	 * @param int $code The exception code.
	 * @param Throwable|null $previous The previous throwable used for the exception chaining.
	 */
	public function __construct(
		string $normalizedMessage = '',
		array $messageContext = [],
		int $code = 0,
		Throwable $previous = null
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
