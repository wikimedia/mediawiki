<?php

namespace Wikimedia\Message;

/**
 * A simple factory providing a message formatter for a given language code.
 *
 * @see ITextFormatter
 */
interface IMessageFormatterFactory {
	/**
	 * Get a text message formatter for a given language.
	 *
	 * @param string $langCode The language code
	 * @return ITextFormatter
	 */
	public function getTextFormatter( $langCode ): ITextFormatter;
}
