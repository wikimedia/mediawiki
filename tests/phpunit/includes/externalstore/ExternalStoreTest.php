<?php

class ExternalStoreTest extends MediaWikiIntegrationTestCase {

	/**
	 * @covers \ExternalStore::fetchFromURL
	 */
	public function testExternalFetchFromURL_noExternalStores() {
		$this->setService(
			'ExternalStoreFactory',
			new ExternalStoreFactory( [], [], 'test-id' )
		);

		$this->assertFalse(
			ExternalStore::fetchFromURL( 'ForTesting://cluster1/200' ),
			'Deny if wgExternalStores is not set to a non-empty array'
		);
	}

	public static function provideFetchFromURLWithStore() {
		yield [ 'Hello', 'ForTesting://cluster1/200', 'Allow ForTesting://cluster1/200' ];
		yield [ 'Hello', 'ForTesting://cluster1/300/0', 'Allow ForTesting://cluster1/300/0' ];

		// cases for r68900
		yield [ false, 'ftp.example.org', 'Deny domain ftp.example.org' ];
		yield [ false, '/example.txt', 'Deny path /example.txt' ];
		yield [ false, 'http://', 'Deny protocol http://' ];
	}

	/**
	 * @covers \ExternalStore::fetchFromURL
	 * @dataProvider provideFetchFromURLWithStore
	 */
	public function testExternalFetchFromURL_someExternalStore( $expect, $url, $msg ) {
		$this->setService(
			'ExternalStoreFactory',
			new ExternalStoreFactory( [ 'ForTesting' ], [ 'ForTesting://cluster1' ], 'test-id' )
		);

		$this->assertSame( $expect, ExternalStore::fetchFromURL( $url ), $msg );
	}
}
