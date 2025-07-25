<?php

use MediaWiki\Message\Message;
use Wikimedia\Message\MessageSpecifier;

/**
 * A simple {@link MessageLocalizer} implementation for use in tests.
 * By default, it sets the message language to 'qqx',
 * to make the tests independent of the wiki configuration.
 *
 * @author Lucas Werkmeister
 * @license GPL-2.0-or-later
 */
class MockMessageLocalizer implements MessageLocalizer {

	/**
	 * @var string|null
	 */
	private $languageCode;

	/**
	 * @param string|null $languageCode The language code to use for messages by default.
	 * You can specify null to use the user language,
	 * but this is not recommended as it may make your tests depend on the wiki configuration.
	 */
	public function __construct( $languageCode = 'qqx' ) {
		$this->languageCode = $languageCode;
	}

	/**
	 * Get a Message object.
	 * Parameters are the same as {@link wfMessage()}.
	 *
	 * @param string|string[]|MessageSpecifier $key Message key, or array of keys,
	 *   or a MessageSpecifier.
	 * @param MessageParam|MessageSpecifier|string|int|float|list<MessageParam|MessageSpecifier|string|int|float> ...$params
	 *   See Message::params()
	 * @return Message
	 */
	public function msg( $key, ...$params ) {
		$message = wfMessage( $key, ...$params );

		if ( $this->languageCode !== null ) {
			$message->inLanguage( $this->languageCode );
		}

		return $message;
	}

}
