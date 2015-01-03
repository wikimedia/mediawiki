<?php

class ComposerLockTest extends MediaWikiTestCase {

	private $lock;

	public function setUp() {
		parent::setUp();
		global $IP;
		$this->lock = "$IP/tests/phpunit/data/composer/composer.lock";
	}

	/**
	 * @covers ComposerLock::getHash
	 */
	public function testGetHash() {
		$lock = new ComposerLock( $this->lock );
		$this->assertEquals( 'a3bb80b0ac4c4a31e52574d48c032923', $lock->getHash() );
	}

	/**
	 * @covers ComposerLock::getInstalledDependencies
	 */
	public function testGetInstalledDependencies() {
		$lock = new ComposerLock( $this->lock );
		$this->assertArrayEquals( array(
			'wikimedia/cdb' => array(
				'version' => '1.0.1',
				'type' => 'library',
			),
			'cssjanus/cssjanus' => array(
				'version' => '1.1.1',
				'type' => 'library',
			),
			'leafo/lessphp' => array(
				'version' => '0.5.0',
				'type' => 'library',
			),
			'psr/log' => array(
				'version' => '1.0.0',
				'type' => 'library',
			),
			'oojs/oojs-ui' => array(
				'version' => '0.6.0',
				'type' => 'library',
			),
			'composer/installers' => array(
				'version' => '1.0.19',
				'type' => 'composer-installer',
			),
			'mediawiki/translate' => array(
				'version' => '2014.12',
				'type' => 'mediawiki-extension',
			),
			'mediawiki/universal-language-selector' => array(
				'version' => '2014.12',
				'type' => 'mediawiki-extension',
			),
		), $lock->getInstalledDependencies(), false, true );
	}

}
