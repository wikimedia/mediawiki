<?php

class MessagesTest extends PHPUnit_Framework_TestCase {
	public function testMessageKeys() {
		$l10nCache = Language::getLocalisationCache();
		$messageNames = $l10nCache->getSubitemList( 'en', 'messages' );
		$nonLowerCase = array_filter( $messageNames, function ( $key ) {
			return $key !== strtolower( $key );
		} );
		// - assertEquals in foreach shows only the first failure.
		// - assertEquals with array_map strtolower is slow.
		$this->assertEquals(
			[],
			$nonLowerCase,
			'Message keys should be lowercase'
		);
	}
}
