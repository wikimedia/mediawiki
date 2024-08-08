<?php

use MediaWiki\ResourceLoader\ForeignResourceManager;
use MediaWiki\ResourceLoader\ForeignResourceNetworkException;
use PHPUnit\Framework\TestCase;

/**
 * Verify MediaWiki core's foreign-resources.yaml.
 *
 * This test is under integration/ instead of structure/ because the latter
 * also runs in CI for skin and extension repos (T203694).
 *
 * @coversNothing
 */
class ForeignResourceStructureTest extends TestCase {

	public function testVerifyIntegrity() {
		global $IP;
		$out = '';
		$frm = new ForeignResourceManager(
			"{$IP}/resources/lib/foreign-resources.yaml",
			"{$IP}/resources/lib",
			static function ( $text ) use ( &$out ) {
				$out .= $text;
			}
		);

		// The "verify" action verifies two things:
		// 1. Mismatching SRI hashes.
		//    These throw an exception with the actual/expect values
		//    to place in foreign-resources.yaml.
		// 2. Mismatching file contents.
		//    These print messages about each mismatching file,
		//    and then we add our help text afterward for how to
		//    automatically update the file resources.

		$helpUpdate = '
	To update a foreign resource, run:
	$ php maintenance/manageForeignResources.php update <moduleName>
		';

		try {
			$this->assertTrue( $frm->run( 'verify', 'all' ), "$out\n$helpUpdate" );
		} catch ( ForeignResourceNetworkException $e ) {
			$this->markTestSkipped( 'Network error: ' . $e->getMessage() );
		}
	}
}
