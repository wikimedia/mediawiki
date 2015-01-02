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
		$this->assertEquals( 'cc6e7fc565b246cb30b0cac103a2b31e', $lock->getHash() );
	}

	/**
	 * @covers ComposerLock::getInstalledDependencies
	 */
	public function testGetInstalledDependencies() {
		$lock = new ComposerLock( $this->lock );
		$this->assertArrayEquals( array(
			'cdb/cdb' => '1.0.0',
			'cssjanus/cssjanus' => '1.1.1',
			'leafo/lessphp' => '0.5.0',
			'psr/log' => '1.0.0',
		), $lock->getInstalledDependencies(), false, true );
	}

}
