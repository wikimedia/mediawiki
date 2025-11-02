<?php

namespace MediaWiki\Message;

use Wikimedia\Message\ITextFormatter;
use Wikimedia\Message\MessageSpecifier;

/**
 * The MediaWiki-specific implementation of ITextFormatter
 *
 * To obtain an instance, use \MediaWiki\MediaWikiServices::getMessageFormatterFactory()
 * and call MessageFormatterFactory::getTextFormatter.
 *
 * @ingroup Language
 */
class TextFormatter implements ITextFormatter {
	private string $langCode;
	private string $format;

	/**
	 * @internal For use by ServiceWiring only
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

	public function getLangCode(): string {
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
	protected function createMessage( MessageSpecifier $spec ): Message {
		return Message::newFromSpecifier( $spec );
	}

	public function format( MessageSpecifier $mv ): string {
		$message = $this->createMessage( $mv );
		$message->inLanguage( $this->langCode );
		return $message->toString( $this->format );
	}
}
