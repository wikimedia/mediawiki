<?php

namespace MediaWiki\Tests\Unit\Language\Dependency;

use MediaWiki\Language\Dependency\FileDependency;
use MediaWikiUnitTestCase;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\Language\Dependency\FileDependency
 * @group Language
 */
class FileDependencyTest extends MediaWikiUnitTestCase {

	public function testMissingFile() {
		$dep = new FileDependency( __DIR__ . '/missing.json' );
		$dep = unserialize( serialize( $dep ) );

		$this->assertFalse( TestingAccessWrapper::newFromObject( $dep )->timestamp, 'timestamp of missing file' );
		$this->assertFalse( $dep->isExpired(), 'still missing' );
	}

	public function testExistingFile() {
		$file = tempnam( sys_get_temp_dir(), 'filedep' );
		$dep = new FileDependency( $file );
		$dep = unserialize( serialize( $dep ) );

		$this->assertSame( filemtime( $file ), TestingAccessWrapper::newFromObject( $dep )->timestamp, 'timestamp of existing file' );
		$this->assertFalse( $dep->isExpired(), 'not modified' );

		unlink( $file );
		$this->assertTrue( $dep->isExpired(), 'deleted' );
	}
}
