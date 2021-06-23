<?php

/**
 * @covers ResourceLoaderFilePath
 */
class ResourceLoaderFilePathTest extends MediaWikiUnitTestCase {

	public function testConstructor() {
		$path = new ResourceLoaderFilePath( 'dummy/path', '/local', '/remote' );

		$this->assertInstanceOf( ResourceLoaderFilePath::class, $path );
	}

	public function testGetterSimple() {
		$path = new ResourceLoaderFilePath( 'dummy/path', '/local', '/remote' );

		$this->assertSame( '/local/dummy/path', $path->getLocalPath() );
		$this->assertSame( '/remote/dummy/path', $path->getRemotePath() );
		$this->assertSame( '/local', $path->getLocalBasePath() );
		$this->assertSame( '/remote', $path->getRemoteBasePath() );
		$this->assertSame( 'dummy/path', $path->getPath() );
	}

	public function testGetterWebRoot() {
		$path = new ResourceLoaderFilePath( 'dummy/path', '/local', '/' );

		$this->assertSame( '/local/dummy/path', $path->getLocalPath() );
		// No double slash (T284391)
		$this->assertSame( '/dummy/path', $path->getRemotePath() );
		$this->assertSame( '/local', $path->getLocalBasePath() );
		$this->assertSame( '/', $path->getRemoteBasePath() );
		$this->assertSame( 'dummy/path', $path->getPath() );
	}

	public function testGetterNoBase() {
		$path = new ResourceLoaderFilePath( 'dummy/path', '', '' );

		// No transformation
		$this->assertSame( 'dummy/path', $path->getLocalPath() );
		$this->assertSame( 'dummy/path', $path->getRemotePath() );
		$this->assertSame( '', $path->getLocalBasePath() );
		$this->assertSame( '', $path->getRemoteBasePath() );
		$this->assertSame( 'dummy/path', $path->getPath() );
	}
}
