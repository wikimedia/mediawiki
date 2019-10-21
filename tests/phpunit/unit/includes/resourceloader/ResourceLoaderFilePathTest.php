<?php

/**
 * @covers ResourceLoaderFilePath
 */
class ResourceLoaderFilePathTest extends MediaWikiUnitTestCase {

	public function testConstructor() {
		$path = new ResourceLoaderFilePath( 'dummy/path', '/local', '/remote' );

		$this->assertInstanceOf( ResourceLoaderFilePath::class, $path );
	}

	public function testGetters() {
		$path = new ResourceLoaderFilePath( 'dummy/path', '/local', '/remote' );

		$this->assertSame( '/local/dummy/path', $path->getLocalPath() );
		$this->assertSame( '/remote/dummy/path', $path->getRemotePath() );
		$this->assertSame( '/local', $path->getLocalBasePath() );
		$this->assertSame( '/remote', $path->getRemoteBasePath() );
		$this->assertSame( 'dummy/path', $path->getPath() );
	}
}
