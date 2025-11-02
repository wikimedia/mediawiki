<?php

namespace MediaWiki\Message;

use Wikimedia\Message\IMessageFormatterFactory;
use Wikimedia\Message\ITextFormatter;

/**
 * The MediaWiki-specific implementation of IMessageFormatterFactory
 *
 * To obtain an instance, use \MediaWiki\MediaWikiServices::getMessageFormatterFactory().
 *
 * @ingroup Language
 */
class MessageFormatterFactory implements IMessageFormatterFactory {
	private string $format;
	private array $textFormatters = [];

	/**
	 * @internal For use by ServiceWiring only
	 * @param string $format which if the Message::FORMAT_* to use in the formatters.
	 */
	public function __construct( string $format = Message::FORMAT_TEXT ) {
		$this->format = $format;
	}

	/**
	 * @inheritDoc
	 */
	public function getTextFormatter( string $langCode ): ITextFormatter {
		if ( !isset( $this->textFormatters[$langCode] ) ) {
			$this->textFormatters[$langCode] = new TextFormatter(
				$langCode, $this->format );
		}
		return $this->textFormatters[$langCode];
	}
}
