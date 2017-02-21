<?php

class MessagesTest extends PHPUnit_Framework_TestCase {
	public function testMessageKeys() {
		$l10nCache = Language::getLocalisationCache();
		$messageNames = $l10nCache->getSubitemList( 'en', 'messages' );
		$nonLcfirst = array_filter( $messageNames, function ( $key ) {
			return $key !== lcfirst( $key );
		} );
		// - assertEquals in foreach shows only the first failure.
		// - assertEquals with full array_map lcfirst result is slow.
		$this->assertEquals(
			[],
			$nonLowerCase,
			'Message keys must be first-letter case-insensitive (lcfirst is the convention)'
		);
	}
}
