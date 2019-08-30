<?php

namespace MediaWiki\Message;

use Wikimedia\Message\IMessageFormatterFactory;
use Wikimedia\Message\ITextFormatter;

/**
 * The MediaWiki-specific implementation of IMessageFormatterFactory
 */
class MessageFormatterFactory implements IMessageFormatterFactory {
	private $textFormatters = [];

	/**
	 * Required parameters may be added to this function without deprecation.
	 * External callers should use MediaWikiServices::getMessageFormatterFactory().
	 *
	 * @internal
	 */
	public function __construct() {
	}

	public function getTextFormatter( $langCode ): ITextFormatter {
		if ( !isset( $this->textFormatters[$langCode] ) ) {
			$this->textFormatters[$langCode] = new TextFormatter( $langCode );
		}
		return $this->textFormatters[$langCode];
	}
}
