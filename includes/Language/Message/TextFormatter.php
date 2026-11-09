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

	/**
	 * @param string $langCode Which language to localize to.
	 * @param string $format See the Message::FORMAT_XXX constants.
	 *        The default is FORMAT_TEXT, which will resolve templates
	 *        and parser functions.
	 * @param bool $useDatabase Whether to check the database for local
	 *        message overrides.
	 *
	 * @internal For use by ServiceWiring only
	 */
	public function __construct(
		private readonly string $langCode,
		private readonly string $format = Message::FORMAT_TEXT,
		private readonly bool $useDatabase = true,
	) {
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

	public function format( MessageSpecifier $message ): string {
		return $this->createMessage( $message )
			->inLanguage( $this->langCode )
			->useDatabase( $this->useDatabase )
			->toString( $this->format );
	}
}
