<?php

namespace Wikimedia\Message;

/**
 * ITextFormatter is a simplified interface to the Message class. It converts
 * MessageValue message specifiers to localized text in a certain language.
 *
 * MessageValue supports message keys, and parameters with a wide variety of
 * types. It does not expose any details of how messages are retrieved from
 * storage or what format they are stored in.
 *
 * Thus, TextFormatter supports single message keys, but not the concept of
 * presence or absence of a key from storage. So it does not support
 * fallback sequences of multiple keys.
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
	function getLangCode();

	/**
	 * Convert a MessageValue to text.
	 *
	 * The result is not safe for use as raw HTML.
	 *
	 * @param MessageValue $message
	 * @return string
	 */
	function format( MessageValue $message );
}
