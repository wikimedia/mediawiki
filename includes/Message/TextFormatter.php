<?php

namespace MediaWiki\Message;

use Message;
use Wikimedia\Message\ITextFormatter;
use Wikimedia\Message\MessageValue;

/**
 * The MediaWiki-specific implementation of ITextFormatter
 */
class TextFormatter implements ITextFormatter {
	/** @var Converter */
	private $converter;

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
	 * @param Converter $converter
	 * @param string $format
	 */
	public function __construct(
		string $langCode,
		Converter $converter,
		string $format = Message::FORMAT_TEXT
	) {
		$this->langCode = $langCode;
		$this->converter = $converter;
		$this->format = $format;
	}

	public function getLangCode() {
		return $this->langCode;
	}

	public function format( MessageValue $mv ) {
		$message = $this->converter->convertMessageValue( $mv );
		$message->inLanguage( $this->langCode );
		return $message->toString( $this->format );
	}
}
