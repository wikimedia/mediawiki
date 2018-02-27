<?php

/**
 * @covers ExternalStoreFactory
 */
class ExternalStoreFactoryTest extends PHPUnit\Framework\TestCase {

	use MediaWikiCoversValidator;

	public function testExternalStoreFactory_noStores() {
		$factory = new ExternalStoreFactory( [], [], 'test-id' );
		$this->assertFalse( $factory->getStoreObject( 'ForTesting' ) );
		$this->assertFalse( $factory->getStoreObject( 'foo' ) );
	}

	public function provideStoreNames() {
		yield 'Same case as construction' => [ 'ForTesting' ];
		yield 'All lower case' => [ 'fortesting' ];
		yield 'All upper case' => [ 'FORTESTING' ];
		yield 'Mix of cases' => [ 'FOrTEsTInG' ];
	}

	/**
	 * @dataProvider provideStoreNames
	 */
	public function testExternalStoreFactory_someStore_protoMatch( $proto ) {
		$factory = new ExternalStoreFactory( [ 'ForTesting' ], [], 'test-id' );
		$store = $factory->getStoreObject( $proto );
		$this->assertInstanceOf( ExternalStoreForTesting::class, $store );
	}

	/**
	 * @dataProvider provideStoreNames
	 */
	public function testExternalStoreFactory_someStore_noProtoMatch( $proto ) {
		$factory = new ExternalStoreFactory( [ 'SomeOtherClassName' ], [], 'test-id' );
		$store = $factory->getStoreObject( $proto );
		$this->assertFalse( $store );
	}

}
