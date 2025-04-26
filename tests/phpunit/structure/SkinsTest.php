<?php

use MediaWiki\MediaWikiServices;
use MediaWiki\Message\Message;
use MediaWiki\Skin\SkinFactory;

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
		foreach ( $skinFactory->getInstalledSkins() as $skin => $_ ) {
			yield $skin => [ $skinFactory, $skin ];
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
		$missingMessages = [];
		foreach ( $messages as $message ) {
			$msg = new Message( $message );
			if ( !$msg->exists() ) {
				$missingMessages[] = $message;
			}
		}
		$this->assertEquals( [], $missingMessages, 'Skin references messages that does not exists' );
	}
}
