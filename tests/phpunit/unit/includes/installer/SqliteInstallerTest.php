<?php

/**
 * @group sqlite
 * @group Database
 * @group medium
 */
class SqliteInstallerTest extends \MediaWikiUnitTestCase {
	/**
	 * @covers SqliteInstaller::checkDataDir
	 */
	public function testCheckDataDir() {
		$method = new ReflectionMethod( SqliteInstaller::class, 'checkDataDir' );
		$method->setAccessible( true );

		# Test 1: Should return fatal Status if $dir exist and it un-writable
		if ( ( isset( $_SERVER['USER'] ) && $_SERVER['USER'] !== 'root' ) && !wfIsWindows() ) {
			// We can't simulate this environment under Windows or login as root
			$dir = sys_get_temp_dir() . '/' . uniqid( 'MediaWikiTest' );
			mkdir( $dir, 0000 );
			/** @var Status $status */
			$status = $method->invoke( null, $dir );
			$this->assertFalse( $status->isGood() );
			$this->assertSame( 'config-sqlite-dir-unwritable', $status->getErrors()[0]['message'] );
			rmdir( $dir );
		}

		# Test 2: Should return fatal Status if $dir not exist and it parent also not exist
		$dir = sys_get_temp_dir() . '/' . uniqid( 'MediaWikiTest' ) . '/' . uniqid( 'MediaWikiTest' );
		$status = $method->invoke( null, $dir );
		$this->assertFalse( $status->isGood() );

		# Test 3: Should return good Status if $dir not exist and it parent writable
		$dir = sys_get_temp_dir() . '/' . uniqid( 'MediaWikiTest' );
		/** @var Status $status */
		$status = $method->invoke( null, $dir );
		$this->assertTrue( $status->isGood() );
	}

	/**
	 * @covers SqliteInstaller::createDataDir
	 */
	public function testCreateDataDir() {
		$method = new ReflectionMethod( SqliteInstaller::class, 'createDataDir' );
		$method->setAccessible( true );

		# Test 1: Should return fatal Status if $dir not exist and it parent un-writable
		if ( ( isset( $_SERVER['USER'] ) && $_SERVER['USER'] !== 'root' ) && !wfIsWindows() ) {
			// We can't simulate this environment under Windows or login as root
			$random = uniqid( 'MediaWikiTest' );
			$dir = sys_get_temp_dir() . '/' . $random . '/' . uniqid( 'MediaWikiTest' );
			mkdir( sys_get_temp_dir() . "/$random", 0000 );
			/** @var Status $status */
			$status = $method->invoke( null, $dir );
			$this->assertFalse( $status->isGood() );
			$this->assertSame( 'config-sqlite-mkdir-error', $status->getErrors()[0]['message'] );
			rmdir( sys_get_temp_dir() . "/$random" );
		}

		# Test 2: Test .htaccess content after created successfully
		$dir = sys_get_temp_dir() . '/' . uniqid( 'MediaWikiTest' );
		$status = $method->invoke( null, $dir );
		$this->assertTrue( $status->isGood() );
		$this->assertSame( "Deny from all\n", file_get_contents( "$dir/.htaccess" ) );
		unlink( "$dir/.htaccess" );
		rmdir( $dir );
	}
}
