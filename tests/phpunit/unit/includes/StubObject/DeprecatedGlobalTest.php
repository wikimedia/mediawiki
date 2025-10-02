<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

use MediaWiki\StubObject\DeprecatedGlobal;
use Wikimedia\ObjectCache\HashBagOStuff;

/**
 * @covers \MediaWiki\StubObject\DeprecatedGlobal
 */
class DeprecatedGlobalTest extends MediaWikiUnitTestCase {
	public function testObjectDeStub() {
		global $wgDummy;

		$wgDummy = new DeprecatedGlobal( 'wgDummy', new HashBagOStuff(), '1.30' );
		$this->assertInstanceOf( DeprecatedGlobal::class, $wgDummy );

		$this->hideDeprecated( '$wgDummy' );
		// Trigger de-stubification
		$wgDummy->get( 'foo' );

		$this->assertInstanceOf( HashBagOStuff::class, $wgDummy );
	}

	public function testLazyLoad() {
		global $wgDummyLazy;

		$called = false;
		$factory = static function () use ( &$called ) {
			$called = true;
			return new HashBagOStuff();
		};

		$wgDummyLazy = new DeprecatedGlobal( 'wgDummyLazy', $factory, '1.30' );
		$this->assertInstanceOf( DeprecatedGlobal::class, $wgDummyLazy );

		$this->hideDeprecated( '$wgDummyLazy' );
		$this->assertFalse( $called );
		// Trigger de-stubification
		$wgDummyLazy->get( 'foo' );
		$this->assertTrue( $called );
		$this->assertInstanceOf( HashBagOStuff::class, $wgDummyLazy );
	}

	public function testWarning() {
		global $wgDummy1;

		$wgDummy1 = new DeprecatedGlobal( 'wgDummy1', new HashBagOStuff(), '1.30' );
		$this->expectDeprecationAndContinue( '/Use of \$wgDummy1 was deprecated in MediaWiki 1\.30/' );
		$wgDummy1->get( 'foo' );
		$this->assertInstanceOf( HashBagOStuff::class, $wgDummy1 );
	}

}
