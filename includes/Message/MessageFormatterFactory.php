<?php

namespace MediaWiki\Message;

use Message;
use Wikimedia\Message\IMessageFormatterFactory;
use Wikimedia\Message\ITextFormatter;

/**
 * The MediaWiki-specific implementation of IMessageFormatterFactory
 */
class MessageFormatterFactory implements IMessageFormatterFactory {

	/** @var string */
	private $format;

	/** @var array */
	private $textFormatters = [];

	/**
	 * Required parameters may be added to this function without deprecation.
	 * External callers should use MediaWikiServices::getMessageFormatterFactory().
	 *
	 * @param string $format which if the Message::FORMAT_* to use in the formatters.
	 * @internal
	 */
	public function __construct( string $format = Message::FORMAT_TEXT ) {
		$this->format = $format;
	}

	/**
	 * @inheritDoc
	 */
	public function getTextFormatter( $langCode ): ITextFormatter {
		if ( !isset( $this->textFormatters[$langCode] ) ) {
			$this->textFormatters[$langCode] = new TextFormatter(
				$langCode, new Converter(), $this->format );
		}
		return $this->textFormatters[$langCode];
	}
}
