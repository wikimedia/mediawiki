<?php
/**
 * External Store tests
 */

class ExternalStoreTest extends PHPUnit_Framework_TestCase {
	private $saved_wgExternalStores;

	function setUp() {
		global $wgExternalStores;
		$this->saved_wgExternalStores = $wgExternalStores ;
	}

	function tearDown() {
		global $wgExternalStores;
		$wgExternalStores = $this->saved_wgExternalStores ;
	}

	function testExternalStoreDoesNotFetchIncorrectURL() {
		global $wgExternalStores;
		$wgExternalStores = true;

		# Assertions for r68900
		$this->assertFalse(
			ExternalStore::fetchFromURL( 'http://' ) );
		$this->assertFalse(
			ExternalStore::fetchFromURL( 'ftp.wikimedia.org' ) );
		$this->assertFalse(
			ExternalStore::fetchFromURL( '/super.txt' ) );
	}
}

