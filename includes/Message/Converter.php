<?php

namespace MediaWiki\Message;

use Wikimedia\Message\MessageSpecifier;
use Wikimedia\Message\MessageValue;

/**
 * Converter between Message and MessageValue
 * @since 1.35
 * @deprecated since 1.43
 */
class Converter {

	/**
	 * Convert a Message to a MessageValue
	 * @deprecated since 1.43 Use MessageValue::newFromSpecifier() instead
	 * @param MessageSpecifier $m
	 * @return MessageValue
	 */
	public function convertMessage( MessageSpecifier $m ) {
		return MessageValue::newFromSpecifier( $m );
	}

	/**
	 * Convert a MessageValue to a Message
	 * @deprecated since 1.43 Use Message::newFromSpecifier() instead
	 * @param MessageValue $mv
	 * @return Message
	 */
	public function convertMessageValue( MessageValue $mv ) {
		return Message::newFromSpecifier( $mv );
	}

}
