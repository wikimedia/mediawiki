<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

use MediaWiki\Exception\ILocalizedException;
use MediaWiki\Message\Message;
use MediaWiki\Parser\Sanitizer;
use Wikimedia\Message\MessageSpecifier;

/**
 * @newable
 * @ingroup Upload
 */
class UploadStashException extends RuntimeException implements ILocalizedException {
	/** @var string|array|MessageSpecifier */
	protected $messageSpec;

	/**
	 * @param string|array|MessageSpecifier $messageSpec See Message::newFromSpecifier
	 * @param int $code Exception code
	 * @param Throwable|null $previous The previous exception used for the exception
	 *  chaining.
	 */
	public function __construct( $messageSpec, $code = 0, ?Throwable $previous = null ) {
		$this->messageSpec = $messageSpec;

		$msg = $this->getMessageObject()->text();
		$msg = preg_replace( '!</?(var|kbd|samp|code)>!', '"', $msg );
		$msg = Sanitizer::stripAllTags( $msg );
		parent::__construct( $msg, $code, $previous );
	}

	/** @inheritDoc */
	public function getMessageObject() {
		return Message::newFromSpecifier( $this->messageSpec );
	}
}
