<?php

namespace Wikimedia\Message;

/**
 * Converts MessageValue message specifiers to localized plain text in a certain language.
 *
 * The caller cannot modify the details of message translation, such as which
 * of multiple sources the message is taken from. Any such flags may be injected
 * into the factory constructor.
 *
 * Implementations of TextFormatter are not required to perfectly format
 * any message in any language. Implementations should make a best effort to
 * produce human-readable text.
 *
 * @package MediaWiki\MessageFormatter
 */
interface ITextFormatter {
	/**
	 * Get the internal language code in which format() is
	 * @return string
	 */
	public function getLangCode();

	/**
	 * Convert a MessageValue to text.
	 *
	 * The result is not safe for use as raw HTML.
	 *
	 * @param MessageValue $message
	 * @return string
	 */
	public function format( MessageValue $message );
}
