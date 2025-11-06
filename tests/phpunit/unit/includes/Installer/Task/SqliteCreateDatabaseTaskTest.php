<?php
namespace MediaWiki\Tests\Unit\Installer\Task;

use MediaWiki\Installer\Task\SqliteCreateDatabaseTask;
use MediaWiki\Status\Status;
use MediaWikiUnitTestCase;
use Wikimedia\TestingAccessWrapper;

class SqliteCreateDatabaseTaskTest extends MediaWikiUnitTestCase {

	/**
	 * @covers \MediaWiki\Installer\Task\SqliteCreateDatabaseTask::createDataDir
	 */
	public function testCreateDataDir() {
		/** @var SqliteCreateDatabaseTask $task */
		$task = TestingAccessWrapper::newFromObject( new SqliteCreateDatabaseTask() );

		# Test 1: Should return fatal Status if $dir not exist and it parent un-writable
		if ( ( isset( $_SERVER['USER'] ) && $_SERVER['USER'] !== 'root' ) && !wfIsWindows() ) {
			// We can't simulate this environment under Windows or login as root
			$random = uniqid( 'MediaWikiTest' );
			$dir = sys_get_temp_dir() . '/' . $random . '/' . uniqid( 'MediaWikiTest' );
			mkdir( sys_get_temp_dir() . "/$random", 0000 );
			/** @var Status $status */
			$status = $task->createDataDir( $dir );
			$this->assertStatusError( 'config-sqlite-mkdir-error', $status );
			rmdir( sys_get_temp_dir() . "/$random" );
		}

		# Test 2: Test .htaccess content after created successfully
		$dir = sys_get_temp_dir() . '/' . uniqid( 'MediaWikiTest' );
		$status = $task->createDataDir( $dir );
		$this->assertStatusGood( $status );
		$this->assertSame( "Require all denied\nSatisfy All\n", file_get_contents( "$dir/.htaccess" ) );
		unlink( "$dir/.htaccess" );
		rmdir( $dir );
	}
}
