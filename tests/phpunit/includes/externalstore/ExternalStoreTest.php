<?php

class ExternalStoreTest extends MediaWikiIntegrationTestCase {

	/**
	 * @covers ExternalStore::fetchFromURL
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

	/**
	 * @covers ExternalStore::fetchFromURL
	 */
	public function testExternalFetchFromURL_someExternalStore() {
		$this->setService(
			'ExternalStoreFactory',
			new ExternalStoreFactory( [ 'ForTesting' ], [ 'ForTesting://cluster1' ], 'test-id' )
		);

		$this->assertEquals(
			'Hello',
			ExternalStore::fetchFromURL( 'ForTesting://cluster1/200' ),
			'Allow FOO://cluster1/200'
		);
		$this->assertEquals(
			'Hello',
			ExternalStore::fetchFromURL( 'ForTesting://cluster1/300/0' ),
			'Allow FOO://cluster1/300/0'
		);
		# Assertions for r68900
		$this->assertFalse(
			ExternalStore::fetchFromURL( 'ftp.example.org' ),
			'Deny domain ftp.example.org'
		);
		$this->assertFalse(
			ExternalStore::fetchFromURL( '/example.txt' ),
			'Deny path /example.txt'
		);
		$this->assertFalse(
			ExternalStore::fetchFromURL( 'http://' ),
			'Deny protocol http://'
		);
	}
}
