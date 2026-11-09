<?php

namespace Wikimedia\Message;

/**
 * A simple factory providing a message formatter for a given language code.
 *
 * @see ITextFormatter
 * @stable to type
 */
interface IMessageFormatterFactory {
	/**
	 * Get a text message formatter for a given language.
	 *
	 * @param string $langCode The language code
	 * @param bool $useDatabase Whether to check the database for local
	 *        message overrides.
	 *
	 * @return ITextFormatter
	 */
	public function getTextFormatter( string $langCode, bool $useDatabase = true ): ITextFormatter;
}
