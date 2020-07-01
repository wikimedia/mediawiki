<?php

/**
 * These tests should work regardless of $wgCapitalLinks
 * @todo Split tests into providers and test methods
 */

class LocalFileTest extends MediaWikiIntegrationTestCase {
	private static function getDefaultInfo() {
		return [
			'name' => 'test',
			'directory' => '/testdir',
			'url' => '/testurl',
			'hashLevels' => 2,
			'transformVia404' => false,
			'backend' => new FSFileBackend( [
				'name' => 'local-backend',
				'wikiId' => wfWikiID(),
				'containerPaths' => [
					'cont1' => "/testdir/local-backend/tempimages/cont1",
					'cont2' => "/testdir/local-backend/tempimages/cont2"
				]
			] )
		];
	}

	/**
	 * @covers File::getHashPath
	 * @dataProvider provideGetHashPath
	 * @param string $expected
	 * @param bool $capitalLinks
	 * @param array $info
	 */
	public function testGetHashPath( $expected, $capitalLinks, array $info ) {
		$this->setMwGlobals( 'wgCapitalLinks', $capitalLinks );
		$this->assertSame( $expected, ( new LocalRepo( $info + self::getDefaultInfo() ) )
			->newFile( 'test!' )->getHashPath() );
	}

	public static function provideGetHashPath() {
		return [
			[ '', true, [ 'hashLevels' => 0 ] ],
			[ 'a/a2/', true, [ 'hashLevels' => 2 ] ],
			[ 'c/c4/', false, [ 'initialCapital' => false ] ],
		];
	}

	/**
	 * @covers File::getRel
	 * @dataProvider provideGetRel
	 * @param string $expected
	 * @param bool $capitalLinks
	 * @param array $info
	 */
	public function testGetRel( $expected, $capitalLinks, array $info ) {
		$this->setMwGlobals( 'wgCapitalLinks', $capitalLinks );

		$this->assertSame( $expected, ( new LocalRepo( $info + self::getDefaultInfo() ) )
			->newFile( 'test!' )->getRel() );
	}

	public static function provideGetRel() {
		return [
			[ 'Test!', true, [ 'hashLevels' => 0 ] ],
			[ 'a/a2/Test!', true, [ 'hashLevels' => 2 ] ],
			[ 'c/c4/test!', false, [ 'initialCapital' => false ] ],
		];
	}

	/**
	 * @covers File::getUrlRel
	 * @dataProvider provideGetUrlRel
	 * @param string $expected
	 * @param bool $capitalLinks
	 * @param array $info
	 */
	public function testGetUrlRel( $expected, $capitalLinks, array $info ) {
		$this->setMwGlobals( 'wgCapitalLinks', $capitalLinks );

		$this->assertSame( $expected, ( new LocalRepo( $info + self::getDefaultInfo() ) )
			->newFile( 'test!' )->getUrlRel() );
	}

	public static function provideGetUrlRel() {
		return [
			[ 'Test%21', true, [ 'hashLevels' => 0 ] ],
			[ 'a/a2/Test%21', true, [ 'hashLevels' => 2 ] ],
			[ 'c/c4/test%21', false, [ 'initialCapital' => false ] ],
		];
	}

	/**
	 * @covers File::getArchivePath
	 * @dataProvider provideGetArchivePath
	 * @param string $expected
	 * @param bool $capitalLinks
	 * @param array $info
	 * @param array $args
	 */
	public function testGetArchivePath( $expected, $capitalLinks, array $info, array $args ) {
		$this->setMwGlobals( 'wgCapitalLinks', $capitalLinks );

		$this->assertSame( $expected, ( new LocalRepo( $info + self::getDefaultInfo() ) )
			->newFile( 'test!' )->getArchivePath( ...$args ) );
	}

	public static function provideGetArchivePath() {
		return [
			[ 'mwstore://local-backend/test-public/archive', true, [ 'hashLevels' => 0 ], [] ],
			[ 'mwstore://local-backend/test-public/archive/a/a2', true, [ 'hashLevels' => 2 ], [] ],
			[
				'mwstore://local-backend/test-public/archive/!',
				true, [ 'hashLevels' => 0 ], [ '!' ]
			], [
				'mwstore://local-backend/test-public/archive/a/a2/!',
				true, [ 'hashLevels' => 2 ], [ '!' ]
			],
		];
	}

	/**
	 * @covers File::getThumbPath
	 * @dataProvider provideGetThumbPath
	 * @param string $expected
	 * @param bool $capitalLinks
	 * @param array $info
	 * @param array $args
	 */
	public function testGetThumbPath( $expected, $capitalLinks, array $info, array $args ) {
		$this->setMwGlobals( 'wgCapitalLinks', $capitalLinks );

		$this->assertSame( $expected, ( new LocalRepo( $info + self::getDefaultInfo() ) )
			->newFile( 'test!' )->getThumbPath( ...$args ) );
	}

	public static function provideGetThumbPath() {
		return [
			[ 'mwstore://local-backend/test-thumb/Test!', true, [ 'hashLevels' => 0 ], [] ],
			[ 'mwstore://local-backend/test-thumb/a/a2/Test!', true, [ 'hashLevels' => 2 ], [] ],
			[
				'mwstore://local-backend/test-thumb/Test!/x',
				true, [ 'hashLevels' => 0 ], [ 'x' ]
			], [
				'mwstore://local-backend/test-thumb/a/a2/Test!/x',
				true, [ 'hashLevels' => 2 ], [ 'x' ]
			],
		];
	}

	/**
	 * @covers File::getArchiveUrl
	 * @dataProvider provideGetArchiveUrl
	 * @param string $expected
	 * @param bool $capitalLinks
	 * @param array $info
	 * @param array $args
	 */
	public function testGetArchiveUrl( $expected, $capitalLinks, array $info, array $args ) {
		$this->setMwGlobals( 'wgCapitalLinks', $capitalLinks );

		$this->assertSame( $expected, ( new LocalRepo( $info + self::getDefaultInfo() ) )
			->newFile( 'test!' )->getArchiveUrl( ...$args ) );
	}

	public static function provideGetArchiveUrl() {
		return [
			[ '/testurl/archive', true, [ 'hashLevels' => 0 ], [] ],
			[ '/testurl/archive/a/a2', true, [ 'hashLevels' => 2 ], [] ],
			[ '/testurl/archive/%21', true, [ 'hashLevels' => 0 ], [ '!' ] ],
			[ '/testurl/archive/a/a2/%21', true, [ 'hashLevels' => 2 ], [ '!' ] ],
		];
	}

	/**
	 * @covers File::getThumbUrl
	 * @dataProvider provideGetThumbUrl
	 * @param string $expected
	 * @param bool $capitalLinks
	 * @param array $info
	 * @param array $args
	 */
	public function testGetThumbUrl( $expected, $capitalLinks, array $info, array $args ) {
		$this->setMwGlobals( 'wgCapitalLinks', $capitalLinks );

		$this->assertSame( $expected, ( new LocalRepo( $info + self::getDefaultInfo() ) )
			->newFile( 'test!' )->getThumbUrl( ...$args ) );
	}

	public static function provideGetThumbUrl() {
		return [
			[ '/testurl/thumb/Test%21', true, [ 'hashLevels' => 0 ], [] ],
			[ '/testurl/thumb/a/a2/Test%21', true, [ 'hashLevels' => 2 ], [] ],
			[ '/testurl/thumb/Test%21/x', true, [ 'hashLevels' => 0 ], [ 'x' ] ],
			[ '/testurl/thumb/a/a2/Test%21/x', true, [ 'hashLevels' => 2 ], [ 'x' ] ],
		];
	}

	/**
	 * @covers File::getArchiveVirtualUrl
	 * @dataProvider provideGetArchiveVirtualUrl
	 * @param string $expected
	 * @param bool $capitalLinks
	 * @param array $info
	 * @param array $args
	 */
	public function testGetArchiveVirtualUrl(
		$expected, $capitalLinks, array $info, array $args
	) {
		$this->setMwGlobals( 'wgCapitalLinks', $capitalLinks );

		$this->assertSame( $expected, ( new LocalRepo( $info + self::getDefaultInfo() ) )
			->newFile( 'test!' )->getArchiveVirtualUrl( ...$args ) );
	}

	public static function provideGetArchiveVirtualUrl() {
		return [
			[ 'mwrepo://test/public/archive', true, [ 'hashLevels' => 0 ], [] ],
			[ 'mwrepo://test/public/archive/a/a2', true, [ 'hashLevels' => 2 ], [] ],
			[ 'mwrepo://test/public/archive/%21', true, [ 'hashLevels' => 0 ], [ '!' ] ],
			[ 'mwrepo://test/public/archive/a/a2/%21', true, [ 'hashLevels' => 2 ], [ '!' ] ],
		];
	}

	/**
	 * @covers File::getThumbVirtualUrl
	 * @dataProvider provideGetThumbVirtualUrl
	 * @param string $expected
	 * @param bool $capitalLinks
	 * @param array $info
	 * @param array $args
	 */
	public function testGetThumbVirtualUrl( $expected, $capitalLinks, array $info, array $args ) {
		$this->setMwGlobals( 'wgCapitalLinks', $capitalLinks );

		$this->assertSame( $expected, ( new LocalRepo( $info + self::getDefaultInfo() ) )
			->newFile( 'test!' )->getThumbVirtualUrl( ...$args ) );
	}

	public static function provideGetThumbVirtualUrl() {
		return [
			[ 'mwrepo://test/thumb/Test%21', true, [ 'hashLevels' => 0 ], [] ],
			[ 'mwrepo://test/thumb/a/a2/Test%21', true, [ 'hashLevels' => 2 ], [] ],
			[ 'mwrepo://test/thumb/Test%21/%21', true, [ 'hashLevels' => 0 ], [ '!' ] ],
			[ 'mwrepo://test/thumb/a/a2/Test%21/%21', true, [ 'hashLevels' => 2 ], [ '!' ] ],
		];
	}

	/**
	 * @covers File::getUrl
	 * @dataProvider provideGetUrl
	 * @param string $expected
	 * @param bool $capitalLinks
	 * @param array $info
	 */
	public function testGetUrl( $expected, $capitalLinks, array $info ) {
		$this->setMwGlobals( 'wgCapitalLinks', $capitalLinks );

		$this->assertSame( $expected, ( new LocalRepo( $info + self::getDefaultInfo() ) )
			->newFile( 'test!' )->getUrl() );
	}

	public static function provideGetUrl() {
		return [
			[ '/testurl/Test%21', true, [ 'hashLevels' => 0 ] ],
			[ '/testurl/a/a2/Test%21', true, [ 'hashLevels' => 2 ] ],
		];
	}

	/**
	 * @covers ::wfLocalFile
	 */
	public function testWfLocalFile() {
		$file = wfLocalFile( "File:Some_file_that_probably_doesn't exist.png" );
		$this->assertThat(
			$file,
			$this->isInstanceOf( LocalFile::class ),
			'wfLocalFile() returns LocalFile for valid Titles'
		);
	}

	/**
	 * @covers File::getUser
	 */
	public function testGetUserForNonExistingFile() {
		$file = ( new LocalRepo( self::getDefaultInfo() ) )->newFile( 'test!' );
		$this->assertSame( 'Unknown user', $file->getUser() );
		$this->assertSame( 0, $file->getUser( 'id' ) );
	}

	/**
	 * @covers File::getUser
	 */
	public function testDescriptionShortUrlForNonExistingFile() {
		$file = ( new LocalRepo( self::getDefaultInfo() ) )->newFile( 'test!' );
		$this->assertNull( $file->getDescriptionShortUrl() );
	}

	/**
	 * @covers File::getUser
	 */
	public function testDescriptionTextForNonExistingFile() {
		$file = ( new LocalRepo( self::getDefaultInfo() ) )->newFile( 'test!' );
		$this->assertFalse( $file->getDescriptionText() );
	}
}
