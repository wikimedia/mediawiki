<?php

class FooFile extends File {
};

class FileTest extends MediaWikiMediaTestCase {
	protected $addedGlobal = false;

	protected function setUp() {
		parent::setUp();

		if ( !array_key_exists( 'wgThumbnailBuckets', $GLOBALS ) ) {
			$GLOBALS['wgThumbnailBuckets'] = null;
			$this->addedGlobal = true;
		}
	}

	protected function tearDown() {
		parent::tearDown();

		if ( $this->addedGlobal ) {
			unset( $GLOBALS['wgThumbnailBuckets'] );
		}
	}

	/**
	 * @covers File::getThumbnailBucket
	 */
	public function testGetThumbnailBucket() {
		$this->setMwGlobals( 'wgThumbnailBuckets', array( 256, 512, 1024, 2048, 4096 ) );

		$fileMock = $this->getMock( 'FooFile',
			array( 'getWidth' ),
			array( 'fileMock', false ) );
		$fileMock->expects( $this->any() )->method( 'getWidth' )->will( $this->returnValue( 3000 ) );

		$this->assertEquals( 256, $fileMock->getThumbnailBucket( 120 ) );
		$this->assertEquals( 512, $fileMock->getThumbnailBucket( 300 ) );
		$this->assertEquals( 2048, $fileMock->getThumbnailBucket( 1024 ) );
		$this->assertEquals( false, $fileMock->getThumbnailBucket( 2048 ) );
		$this->assertEquals( false, $fileMock->getThumbnailBucket( 3500 ) );

		$this->setMwGlobals( 'wgThumbnailBuckets', array( 1024 ) );

		$this->assertEquals( false, $fileMock->getThumbnailBucket( 1024 ) );

		$this->setMwGlobals( 'wgThumbnailBuckets', null );

		$this->assertEquals( false, $fileMock->getThumbnailBucket( 1024 ) );
	}

	protected function getSourcePathFileMock(
		$supportsBucketing = true,
		$tmpThumbCache = null,
		$thumbnailBucket = 1024 ) {

		$backendMock = $this->getMock( 'FSFileBackend',
			array(),
			array( array( 'name' => 'backendMock', 'wikiId' => wfWikiId() ) ) );

		$repoMock = $this->getMock( 'FileRepo',
			array( 'fileExists', 'getLocalReference' ),
			array( array( 'name' => 'repoMock', 'backend' => $backendMock ) ) );

		$fsFile = new FSFile( 'fsFilePath' );

		$repoMock->expects( $this->any() )->method( 'fileExists' )->will(
			$this->returnValue( true ) );

		$repoMock->expects( $this->any() )->method( 'getLocalReference' )->will(
			$this->returnValue( $fsFile ) );

		$handlerMock = $this->getMock( 'BitmapHandler', array( 'supportsBucketing' ) );
		$handlerMock->expects( $this->any() )->method( 'supportsBucketing' )->will(
			$this->returnValue( $supportsBucketing ) );

		$fileMock = $this->getMock( 'FooFile',
			array( 'getThumbnailBucket', 'getLocalRefPath', 'getHandler' ),
			array( 'fileMock', $repoMock ) );

		$fileMock->expects( $this->any() )->method( 'getThumbnailBucket' )->will(
			$this->returnValue( $thumbnailBucket ) );

		$fileMock->expects( $this->any() )->method( 'getLocalRefPath' )->will(
			$this->returnValue( 'localRefPath' ) );

		$fileMock->expects( $this->any() )->method( 'getHandler' )->will(
			$this->returnValue( $handlerMock ) );

		$reflection = new ReflectionClass( $fileMock );
		$reflection_property = $reflection->getProperty( 'handler' );
		$reflection_property->setAccessible( true );
		$reflection_property->setValue( $fileMock, $handlerMock );

		if ( !is_null( $tmpThumbCache ) ) {
			$reflection_property = $reflection->getProperty( 'tmpThumbCache' );
			$reflection_property->setAccessible( true );
			$reflection_property->setValue( $fileMock, $tmpThumbCache );
		}

		return $fileMock;
	}

	/**
	 * @covers File::getSourcePath
	 */
	public function testGetSourcePath() {
		$fileMock = $this->getSourcePathFileMock();

		$this->assertEquals( 'fsFilePath', $fileMock->getSourcePath(
			array( 'physicalWidth' => 2048 ) ), 'Path downloaded from storage' );

		$fileMock = $this->getSourcePathFileMock( true, array( 1024 => '/tmp/shouldnotexist' + rand() ) );

		$this->assertEquals( 'fsFilePath', $fileMock->getSourcePath(
			array( 'physicalWidth' => 2048 ) ),
			'Path download from storage because temp file is missing' );

		$fileMock = $this->getSourcePathFileMock( true, array( 1024 => '/tmp' ) );

		$this->assertEquals( '/tmp', $fileMock->getSourcePath(
			array( 'physicalWidth' => 2048 ) ), 'Temporary path because temp file was found' );

		$fileMock = $this->getSourcePathFileMock( false );

		$this->assertEquals( 'localRefPath', $fileMock->getSourcePath(
			array( 'physicalWidth' => 2048 ) ),
			'Non-bucketed path because bucketing is unsupported by handler' );

		$fileMock = $this->getSourcePathFileMock( true, null, false );

		$this->assertEquals( 'localRefPath', $fileMock->getSourcePath(
			array( 'physicalWidth' => 2048 ) ),
			'Non-bucketed path because there is no suitable bucket size' );

		$this->assertEquals( 'localRefPath', $fileMock->getSourcePath( array() ),
			'Non-bucketed path because no width provided' );
	}

	/**
	 * @covers File::generateBucketsIfNeeded
	 */
	public function testGenerateBucketsIfNeeded() {
		$this->setMwGlobals( 'wgThumbnailBuckets', array( 256, 512, 1024, 2048, 4096 ) );

		$backendMock = $this->getMock( 'FSFileBackend',
			array(),
			array( array( 'name' => 'backendMock', 'wikiId' => wfWikiId() ) ) );

		$repoMock = $this->getMock( 'FileRepo',
			array( 'fileExists', 'getLocalReference' ),
			array( array( 'name' => 'repoMock', 'backend' => $backendMock ) ) );

		$fileMock = $this->getMock( 'FooFile',
			array( 'getWidth', 'getBucketThumbPath' ),
			array( 'fileMock', $repoMock ) );

		$reflectionMethod = new ReflectionMethod( 'FooFile', 'generateBucketsIfNeeded' );
		$reflectionMethod->setAccessible( true );

		$fileMock->expects( $this->at( 0 ) )->method( 'getWidth' )->will( $this->returnValue( 256 ) );
		$fileMock->expects( $this->at( 1 ) )->method( 'getWidth' )->will( $this->returnValue( 5000 ) );

		$this->assertEquals( false,
			$reflectionMethod->invoke(
				$fileMock,
				array( 'physicalWidth' => 256, 'physicalHeight' => 100 ) ) );

		$fileMock->expects( $this->at( 0 ) )->method( 'getBucketThumbPath' );
		$repoMock->expects( $this->at( 0 ) )->method( 'fileExists' )->will( $this->returnValue( true ) );

		$this->assertEquals( false,
			$reflectionMethod->invoke(
				$fileMock,
				array( 'physicalWidth' => 300, 'physicalHeight' => 200 ) ) );
	}
}
