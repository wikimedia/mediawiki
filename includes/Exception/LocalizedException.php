<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Exception;

use Exception;
use MediaWiki\Message\Message;
use MediaWiki\Parser\Sanitizer;
use Throwable;
use Wikimedia\Message\MessageSpecifier;

/**
 * Basic localized exception.
 *
 * @newable
 * @stable to extend
 * @since 1.29
 * @ingroup Exception
 * @note Don't use this in a situation where MessageCache is not functional.
 */
class LocalizedException extends Exception implements ILocalizedException {
	/** @var string|array|MessageSpecifier */
	protected $messageSpec;

	/**
	 * @stable to call
	 * @param string|array|MessageSpecifier $messageSpec See Message::newFromSpecifier
	 * @param int $code
	 * @param Throwable|null $previous The previous exception used for the exception
	 *  chaining.
	 */
	public function __construct( $messageSpec, $code = 0, ?Throwable $previous = null ) {
		$this->messageSpec = $messageSpec;

		// Exception->getMessage() should be in plain English, not localized.
		// So fetch the English version of the message, without local
		// customizations, and make a basic attempt to turn markup into text.
		$msg = $this->getMessageObject()->inLanguage( 'en' )->useDatabase( false )->text();
		$msg = preg_replace( '!</?(var|kbd|samp|code)>!', '"', $msg );
		$msg = Sanitizer::stripAllTags( $msg );
		parent::__construct( $msg, $code, $previous );
	}

	/** @inheritDoc */
	public function getMessageObject() {
		return Message::newFromSpecifier( $this->messageSpec );
	}
}

/** @deprecated class alias since 1.44 */
class_alias( LocalizedException::class, 'LocalizedException' );
