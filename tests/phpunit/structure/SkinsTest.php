<?php

use MediaWiki\MediaWikiServices;
use MediaWiki\Message\Message;

/**
 * Checks for making sure registered skins are sensible.
 *
 * @coversNothing
 * @group Database
 */
class SkinsTest extends MediaWikiIntegrationTestCase {

	public static function provideSkinConstructor() {
		$services = MediaWikiServices::getInstance();
		$skinFactory = $services->getSkinFactory();
		foreach ( array_keys( $skinFactory->getInstalledSkins() ) as $skin ) {
			yield [ $skinFactory, $skin ];
		}
	}

	/**
	 * @dataProvider provideSkinConstructor
	 *
	 * @param string $skinFactory
	 * @param string $skinName
	 */
	public function testConstructor( SkinFactory $skinFactory, string $skinName ) {
		$skin = $skinFactory->makeSkin( $skinName );
		$options = $skin->getOptions();
		$messages = $options['messages'] ?? [];
		$this->assertSame( $options['name'], $skinName );
		foreach ( $messages as $message ) {
			$msg = new Message( $message );
			$this->assertSame( true, $msg->exists(), "Skin references message that does not exist: $message" );
		}
	}
}
