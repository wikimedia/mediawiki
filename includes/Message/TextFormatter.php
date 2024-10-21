<?php

namespace MediaWiki\Message;

use Wikimedia\Message\ITextFormatter;
use Wikimedia\Message\MessageSpecifier;

/**
 * The MediaWiki-specific implementation of ITextFormatter
 */
class TextFormatter implements ITextFormatter {
	/** @var string */
	private $langCode;

	/** @var string */
	private $format;

	/**
	 * Construct a TextFormatter.
	 *
	 * The type signature may change without notice as dependencies are added
	 * to the constructor. External callers should use
	 * MediaWikiServices::getMessageFormatterFactory()
	 *
	 * @internal
	 * @param string $langCode
	 * @param string $format
	 */
	public function __construct(
		string $langCode,
		string $format = Message::FORMAT_TEXT
	) {
		$this->langCode = $langCode;
		$this->format = $format;
	}

	public function getLangCode() {
		return $this->langCode;
	}

	/**
	 * Allow the Message class to be mocked in tests by constructing objects in
	 * a protected method.
	 *
	 * @internal
	 * @param MessageSpecifier $spec
	 * @return Message
	 */
	protected function createMessage( MessageSpecifier $spec ) {
		return Message::newFromSpecifier( $spec );
	}

	public function format( MessageSpecifier $mv ): string {
		$message = $this->createMessage( $mv );
		$message->inLanguage( $this->langCode );
		return $message->toString( $this->format );
	}
}
