<?php

class MessagesTest extends PHPUnit_Framework_TestCase {
	public function testMessageKeys() {
		$l10nCache = Language::getLocalisationCache();
		$messageNames = $l10nCache->getSubitemList( 'en', 'messages' );
		foreach ( $messageNames as $key ) {
			$this->assertEquals( strtolower( $key ), $key, 'Lowercase message key' );
		}
	}
}
