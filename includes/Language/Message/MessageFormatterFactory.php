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
	public function getTextFormatter( string $langCode, bool $useDatabase = true ): ITextFormatter {
		$key = $langCode . ( $useDatabase ? '/db' : '/nodb' );

		if ( !isset( $this->textFormatters[$key] ) ) {
			$this->textFormatters[$key] = new TextFormatter(
				$langCode, $this->format, $useDatabase );
		}
		return $this->textFormatters[$key];
	}
}
