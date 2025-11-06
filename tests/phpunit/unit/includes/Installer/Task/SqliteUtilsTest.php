<?php
namespace MediaWiki\Tests\Unit\Installer\Task;

use MediaWiki\Installer\Task\SqliteUtils;
use MediaWikiUnitTestCase;

/**
 * @group sqlite
 * @group medium
 */
class SqliteUtilsTest extends MediaWikiUnitTestCase {
	/**
	 * @covers \MediaWiki\Installer\Task\SqliteUtils::checkDataDir
	 */
	public function testCheckDataDir() {
		$utils = new SqliteUtils;

		# Test 1: Should return fatal Status if $dir exist and it un-writable
		if ( ( isset( $_SERVER['USER'] ) && $_SERVER['USER'] !== 'root' ) && !wfIsWindows() ) {
			// We can't simulate this environment under Windows or login as root
			$dir = sys_get_temp_dir() . '/' . uniqid( 'MediaWikiTest' );
			mkdir( $dir, 0000 );
			$status = $utils->checkDataDir( $dir );
			$this->assertStatusError( 'config-sqlite-dir-unwritable', $status );
			rmdir( $dir );
		}

		# Test 2: Should return fatal Status if $dir not exist and it parent also not exist
		$dir = sys_get_temp_dir() . '/' . uniqid( 'MediaWikiTest' ) . '/' . uniqid( 'MediaWikiTest' );
		$status = $utils->checkDataDir( $dir );
		$this->assertStatusNotGood( $status );

		# Test 3: Should return good Status if $dir not exist and it parent writable
		$dir = sys_get_temp_dir() . '/' . uniqid( 'MediaWikiTest' );
		$status = $utils->checkDataDir( $dir );
		$this->assertStatusGood( $status );
	}
}
