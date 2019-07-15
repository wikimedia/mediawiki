<?php

class ResourceLoaderFilePathTest extends PHPUnit\Framework\TestCase {
	/**
	 * @covers ResourceLoaderFilePath::__construct
	 */
	public function testConstructor() {
		$resourceLoaderFilePath = new ResourceLoaderFilePath(
			'dummy/path', 'localBasePath', 'remoteBasePath'
		);

		$this->assertInstanceOf( ResourceLoaderFilePath::class, $resourceLoaderFilePath );
	}

	/**
	 * @covers ResourceLoaderFilePath::getLocalPath
	 */
	public function testGetLocalPath() {
		$resourceLoaderFilePath = new ResourceLoaderFilePath(
			'dummy/path', 'localBasePath', 'remoteBasePath'
		);

		$this->assertSame(
			'localBasePath/dummy/path', $resourceLoaderFilePath->getLocalPath()
		);
	}

	/**
	 * @covers ResourceLoaderFilePath::getRemotePath
	 */
	public function testGetRemotePath() {
		$resourceLoaderFilePath = new ResourceLoaderFilePath(
			'dummy/path', 'localBasePath', 'remoteBasePath'
		);

		$this->assertSame(
			'remoteBasePath/dummy/path', $resourceLoaderFilePath->getRemotePath()
		);
	}

	/**
	 * @covers ResourceLoaderFilePath::getPath
	 */
	public function testGetPath() {
		$resourceLoaderFilePath = new ResourceLoaderFilePath(
			'dummy/path', 'localBasePath', 'remoteBasePath'
		);

		$this->assertSame(
			'dummy/path', $resourceLoaderFilePath->getPath()
		);
	}
}
