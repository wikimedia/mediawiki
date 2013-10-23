<?php
/**
 * External Store tests
 */

class ExternalStoreTest extends MediaWikiTestCase {

	public function testExternalFetchFromURL() {
		$this->setMwGlobals( 'wgExternalStores', false );

		$this->assertFalse(
			ExternalStore::fetchFromURL( 'FOO://cluster1/200' ),
			'Deny if wgExternalStores is not set to a non-empty array'
		);

		$this->setMwGlobals( 'wgExternalStores', array( 'FOO' ) );

		$this->assertEquals(
			ExternalStore::fetchFromURL( 'FOO://cluster1/200' ),
			'Hello',
			'Allow FOO://cluster1/200'
		);
		$this->assertEquals(
			ExternalStore::fetchFromURL( 'FOO://cluster1/300/0' ),
			'Hello',
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

class ExternalStoreFOO {

	protected $data = array(
		'cluster1' => array(
			'200' => 'Hello',
			'300' => array(
				'Hello', 'World',
			),
		),
	);

	/**
	 * Fetch data from given URL
	 * @param $url String: an url of the form FOO://cluster/id or FOO://cluster/id/itemid.
	 * @return mixed
	 */
	function fetchFromURL( $url ) {
		// Based on ExternalStoreDB
		$path = explode( '/', $url );
		$cluster = $path[2];
		$id = $path[3];
		if ( isset( $path[4] ) ) {
			$itemID = $path[4];
		} else {
			$itemID = false;
		}

		if ( !isset( $this->data[$cluster][$id] ) ) {
			return null;
		}

		if ( $itemID !== false && is_array( $this->data[$cluster][$id] ) && isset( $this->data[$cluster][$id][$itemID] ) ) {
			return $this->data[$cluster][$id][$itemID];
		}

		return $this->data[$cluster][$id];
	}
}
