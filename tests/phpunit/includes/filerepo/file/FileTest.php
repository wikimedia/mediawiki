<?php

class FooFile extends File {
};

class FileTest extends MediaWikiMediaTestCase {
	protected $addedGlobals = array();

	protected function setUp() {
		parent::setUp();

		// This is necessary because setMwGlobals is unhappy if we try to set a global that doesn't
		// exist in the local environment
		foreach ( array( 'wgThumbnailBuckets', 'wgThumbnailMinimumBucketDistance' ) as $global ) {
			if ( !array_key_exists( $global, $GLOBALS ) ) {
				$GLOBALS[ $global ] = null;
				$this->addedGlobals[] = $global;
			}
		}
	}

	protected function tearDown() {
		parent::tearDown();

		if ( !empty( $this->addedGlobals ) ) {
			foreach ( $this->addedGlobals as $addedGlobal ) {
				unset( $GLOBALS[ $addedGlobal ] );
			}
		}
	}

	/**
	 * @dataProvider getThumbnailBucketProvider
	 * @covers File::getThumbnailBucket
	 */
	public function testGetThumbnailBucket( $data ) {
		$this->setMwGlobals( 'wgThumbnailBuckets', $data['buckets'] );
		$this->setMwGlobals( 'wgThumbnailMinimumBucketDistance', $data['minimumBucketDistance'] );

		$fileMock = $this->getMock( 'FooFile',
			array( 'getWidth' ),
			array( 'fileMock', false ) );
		$fileMock->expects( $this->any() )->method( 'getWidth' )->will(
			$this->returnValue( $data['width'] ) );

		$this->assertEquals(
			$data['expectedBucket'],
			$fileMock->getThumbnailBucket( $data['requestedWidth'] ),
			$data['message'] );
	}

	public function getThumbnailBucketProvider() {
		$defaultBuckets = array( 256, 512, 1024, 2048, 4096 );

		return array(
			array( array(
				'buckets' => $defaultBuckets,
				'minimumBucketDistance' => 0,
				'width' => 3000,
				'requestedWidth' => 120,
				'expectedBucket' => 256,
				'message' => 'Picking bucket bigger than requested size'
			) ),
			array( array(
				'buckets' => $defaultBuckets,
				'minimumBucketDistance' => 0,
				'width' => 3000,
				'requestedWidth' => 300,
				'expectedBucket' => 512,
				'message' => 'Picking bucket bigger than requested size'
			) ),
			array( array(
				'buckets' => $defaultBuckets,
				'minimumBucketDistance' => 0,
				'width' => 3000,
				'requestedWidth' => 1024,
				'expectedBucket' => 2048,
				'message' => 'Picking bucket bigger than requested size'
			) ),
			array( array(
				'buckets' => $defaultBuckets,
				'minimumBucketDistance' => 0,
				'width' => 3000,
				'requestedWidth' => 2048,
				'expectedBucket' => false,
				'message' => 'Picking no bucket because none is bigger than the requested size'
			) ),
			array( array(
				'buckets' => $defaultBuckets,
				'minimumBucketDistance' => 0,
				'width' => 3000,
				'requestedWidth' => 3500,
				'expectedBucket' => false,
				'message' => 'Picking no bucket because requested size is bigger than original'
			) ),
			array( array(
				'buckets' => array( 1024 ),
				'minimumBucketDistance' => 0,
				'width' => 3000,
				'requestedWidth' => 1024,
				'expectedBucket' => false,
				'message' => 'Picking no bucket because requested size equals biggest bucket'
			) ),
			array( array(
				'buckets' => null,
				'minimumBucketDistance' => 0,
				'width' => 3000,
				'requestedWidth' => 1024,
				'expectedBucket' => false,
				'message' => 'Picking no bucket because no buckets have been specified'
			) ),
			array( array(
				'buckets' => array( 256, 512 ),
				'minimumBucketDistance' => 10,
				'width' => 3000,
				'requestedWidth' => 245,
				'expectedBucket' => 256,
				'message' => 'Requested width is distant enough from next bucket for it to be picked'
			) ),
			array( array(
				'buckets' => array( 256, 512 ),
				'minimumBucketDistance' => 10,
				'width' => 3000,
				'requestedWidth' => 246,
				'expectedBucket' => 512,
				'message' => 'Requested width is too close to next bucket, picking next one'
			) ),
		);
	}

	/**
	 * @dataProvider getSourcePathProvider
	 * @covers File::getSourcePath
	 */
	public function testGetSourcePath( $data ) {
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
			$this->returnValue( $data['supportsBucketing'] ) );

		$fileMock = $this->getMock( 'FooFile',
			array( 'getThumbnailBucket', 'getLocalRefPath', 'getHandler' ),
			array( 'fileMock', $repoMock ) );

		$fileMock->expects( $this->any() )->method( 'getThumbnailBucket' )->will(
			$this->returnValue( $data['thumbnailBucket'] ) );

		$fileMock->expects( $this->any() )->method( 'getLocalRefPath' )->will(
			$this->returnValue( 'localRefPath' ) );

		$fileMock->expects( $this->any() )->method( 'getHandler' )->will(
			$this->returnValue( $handlerMock ) );

		$reflection = new ReflectionClass( $fileMock );
		$reflection_property = $reflection->getProperty( 'handler' );
		$reflection_property->setAccessible( true );
		$reflection_property->setValue( $fileMock, $handlerMock );

		if ( !is_null( $data['tmpThumbCache'] ) ) {
			$reflection_property = $reflection->getProperty( 'tmpThumbCache' );
			$reflection_property->setAccessible( true );
			$reflection_property->setValue( $fileMock, $data['tmpThumbCache'] );
		}

		$this->assertEquals( $data['expectedPath'], $fileMock->getSourcePath(
			array( 'physicalWidth' => $data['physicalWidth'] ) ), $data['message'] );
	}

	public function getSourcePathProvider() {
		return array(
			array( array(
				'supportsBucketing' => true,
				'tmpThumbCache' => null,
				'thumbnailBucket' => 1024,
				'physicalWidth' => 2048,
				'expectedPath' => 'fsFilePath',
				'message' => 'Path downloaded from storage'
			) ),
			array( array(
				'supportsBucketing' => true,
				'tmpThumbCache' => array( 1024 => '/tmp/shouldnotexist' + rand() ),
				'thumbnailBucket' => 1024,
				'physicalWidth' => 2048,
				'expectedPath' => 'fsFilePath',
				'message' => 'Path downloaded from storage because temp file is missing'
			) ),
			array( array(
				'supportsBucketing' => true,
				'tmpThumbCache' => array( 1024 => '/tmp' ),
				'thumbnailBucket' => 1024,
				'physicalWidth' => 2048,
				'expectedPath' => '/tmp',
				'message' => 'Temporary path because temp file was found'
			) ),
			array( array(
				'supportsBucketing' => false,
				'tmpThumbCache' => null,
				'thumbnailBucket' => 1024,
				'physicalWidth' => 2048,
				'expectedPath' => 'localRefPath',
				'message' => 'Original file path because bucketing is unsupported by handler'
			) ),
			array( array(
				'supportsBucketing' => true,
				'tmpThumbCache' => null,
				'thumbnailBucket' => false,
				'physicalWidth' => 2048,
				'expectedPath' => 'localRefPath',
				'message' => 'Original file path because no width provided'
			) ),
		);
	}

	/**
	 * @dataProvider generateBucketsIfNeededProvider
	 * @covers File::generateBucketsIfNeeded
	 */
	public function testGenerateBucketsIfNeeded( $data ) {
		$this->setMwGlobals( 'wgThumbnailBuckets', $data['buckets'] );

		$backendMock = $this->getMock( 'FSFileBackend',
			array(),
			array( array( 'name' => 'backendMock', 'wikiId' => wfWikiId() ) ) );

		$repoMock = $this->getMock( 'FileRepo',
			array( 'fileExists', 'getLocalReference' ),
			array( array( 'name' => 'repoMock', 'backend' => $backendMock ) ) );

		$fileMock = $this->getMock( 'FooFile',
			array( 'getWidth', 'getBucketThumbPath', 'makeTransformTmpFile', 'generateAndSaveThumb' ),
			array( 'fileMock', $repoMock ) );

		$reflectionMethod = new ReflectionMethod( 'FooFile', 'generateBucketsIfNeeded' );
		$reflectionMethod->setAccessible( true );

		$fileMock->expects( $this->any() )
			->method( 'getWidth' )
			->will( $this->returnValue( $data['width'] ) );

		$fileMock->expects( $data['expectedGetBucketThumbPathCalls'] )
			->method( 'getBucketThumbPath' );

		$repoMock->expects( $data['expectedFileExistsCalls'] )
			->method( 'fileExists' )
			->will( $this->returnValue( $data['fileExistsReturn'] ) );

		$fileMock->expects( $data['expectedMakeTransformTmpFile'] )
			->method( 'makeTransformTmpFile' )
			->will( $this->returnValue( $data['makeTransformTmpFileReturn'] ) );

		$fileMock->expects( $data['expectedGenerateAndSaveThumb'] )
			->method( 'generateAndSaveThumb' )
			->will( $this->returnValue( $data['generateAndSaveThumbReturn'] ) );

		$this->assertEquals( $data['expectedResult'],
			$reflectionMethod->invoke(
				$fileMock,
				array(
					'physicalWidth' => $data['physicalWidth'],
					'physicalHeight' => $data['physicalHeight'] )
				),
				$data['message'] );
	}

	public function generateBucketsIfNeededProvider() {
		$defaultBuckets = array( 256, 512, 1024, 2048, 4096 );

		return array(
			array( array(
				'buckets' => $defaultBuckets,
				'width' => 256,
				'physicalWidth' => 256,
				'physicalHeight' => 100,
				'expectedGetBucketThumbPathCalls' => $this->never(),
				'expectedFileExistsCalls' => $this->never(),
				'fileExistsReturn' => null,
				'expectedMakeTransformTmpFile' => $this->never(),
				'makeTransformTmpFileReturn' => false,
				'expectedGenerateAndSaveThumb' => $this->never(),
				'generateAndSaveThumbReturn' => false,
				'expectedResult' => false,
				'message' => 'No bucket found, nothing to generate'
			) ),
			array( array(
				'buckets' => $defaultBuckets,
				'width' => 5000,
				'physicalWidth' => 300,
				'physicalHeight' => 200,
				'expectedGetBucketThumbPathCalls' => $this->once(),
				'expectedFileExistsCalls' => $this->once(),
				'fileExistsReturn' => true,
				'expectedMakeTransformTmpFile' => $this->never(),
				'makeTransformTmpFileReturn' => false,
				'expectedGenerateAndSaveThumb' => $this->never(),
				'generateAndSaveThumbReturn' => false,
				'expectedResult' => false,
				'message' => 'File already exists, no reason to generate buckets'
			) ),
			array( array(
				'buckets' => $defaultBuckets,
				'width' => 5000,
				'physicalWidth' => 300,
				'physicalHeight' => 200,
				'expectedGetBucketThumbPathCalls' => $this->once(),
				'expectedFileExistsCalls' => $this->once(),
				'fileExistsReturn' => false,
				'expectedMakeTransformTmpFile' => $this->once(),
				'makeTransformTmpFileReturn' => false,
				'expectedGenerateAndSaveThumb' => $this->never(),
				'generateAndSaveThumbReturn' => false,
				'expectedResult' => false,
				'message' => 'Cannot generate temp file for bucket'
			) ),
			array( array(
				'buckets' => $defaultBuckets,
				'width' => 5000,
				'physicalWidth' => 300,
				'physicalHeight' => 200,
				'expectedGetBucketThumbPathCalls' => $this->once(),
				'expectedFileExistsCalls' => $this->once(),
				'fileExistsReturn' => false,
				'expectedMakeTransformTmpFile' => $this->once(),
				'makeTransformTmpFileReturn' => new TempFSFile( '/tmp/foo' ),
				'expectedGenerateAndSaveThumb' => $this->once(),
				'generateAndSaveThumbReturn' => false,
				'expectedResult' => false,
				'message' => 'Bucket image could not be generated'
			) ),
			array( array(
				'buckets' => $defaultBuckets,
				'width' => 5000,
				'physicalWidth' => 300,
				'physicalHeight' => 200,
				'expectedGetBucketThumbPathCalls' => $this->once(),
				'expectedFileExistsCalls' => $this->once(),
				'fileExistsReturn' => false,
				'expectedMakeTransformTmpFile' => $this->once(),
				'makeTransformTmpFileReturn' => new TempFSFile( '/tmp/foo' ),
				'expectedGenerateAndSaveThumb' => $this->once(),
				'generateAndSaveThumbReturn' => new ThumbnailImage( false, 'bar', false, false ),
				'expectedResult' => true,
				'message' => 'Bucket image could not be generated'
			) ),
		);
	}
}
