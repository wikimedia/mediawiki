<?php

class FileTest extends MediaWikiMediaTestCase {

	/**
	 * @param string $filename
	 * @param bool $expected
	 * @dataProvider providerCanAnimate
	 */
	function testCanAnimateThumbIfAppropriate( $filename, $expected ) {
		$this->setMwGlobals( 'wgMaxAnimatedGifArea', 9000 );
		$file = $this->dataFile( $filename );
		$this->assertEquals( $file->canAnimateThumbIfAppropriate(), $expected );
	}

	function providerCanAnimate() {
		return [
			[ 'nonanimated.gif', true ],
			[ 'jpeg-comment-utf.jpg', true ],
			[ 'test.tiff', true ],
			[ 'Animated_PNG_example_bouncing_beach_ball.png', false ],
			[ 'greyscale-png.png', true ],
			[ 'Toll_Texas_1.svg', true ],
			[ 'LoremIpsum.djvu', true ],
			[ '80x60-2layers.xcf', true ],
			[ 'Soccer_ball_animated.svg', false ],
			[ 'Bishzilla_blink.gif', false ],
			[ 'animated.gif', true ],
		];
	}

	/**
	 * @dataProvider getThumbnailBucketProvider
	 * @covers File::getThumbnailBucket
	 */
	public function testGetThumbnailBucket( $data ) {
		$this->setMwGlobals( 'wgThumbnailBuckets', $data['buckets'] );
		$this->setMwGlobals( 'wgThumbnailMinimumBucketDistance', $data['minimumBucketDistance'] );

		$fileMock = $this->getMockBuilder( 'File' )
			->setConstructorArgs( [ 'fileMock', false ] )
			->setMethods( [ 'getWidth' ] )
			->getMockForAbstractClass();

		$fileMock->expects( $this->any() )
			->method( 'getWidth' )
			->will( $this->returnValue( $data['width'] ) );

		$this->assertEquals(
			$data['expectedBucket'],
			$fileMock->getThumbnailBucket( $data['requestedWidth'] ),
			$data['message'] );
	}

	public function getThumbnailBucketProvider() {
		$defaultBuckets = [ 256, 512, 1024, 2048, 4096 ];

		return [
			[ [
				'buckets' => $defaultBuckets,
				'minimumBucketDistance' => 0,
				'width' => 3000,
				'requestedWidth' => 120,
				'expectedBucket' => 256,
				'message' => 'Picking bucket bigger than requested size'
			] ],
			[ [
				'buckets' => $defaultBuckets,
				'minimumBucketDistance' => 0,
				'width' => 3000,
				'requestedWidth' => 300,
				'expectedBucket' => 512,
				'message' => 'Picking bucket bigger than requested size'
			] ],
			[ [
				'buckets' => $defaultBuckets,
				'minimumBucketDistance' => 0,
				'width' => 3000,
				'requestedWidth' => 1024,
				'expectedBucket' => 2048,
				'message' => 'Picking bucket bigger than requested size'
			] ],
			[ [
				'buckets' => $defaultBuckets,
				'minimumBucketDistance' => 0,
				'width' => 3000,
				'requestedWidth' => 2048,
				'expectedBucket' => false,
				'message' => 'Picking no bucket because none is bigger than the requested size'
			] ],
			[ [
				'buckets' => $defaultBuckets,
				'minimumBucketDistance' => 0,
				'width' => 3000,
				'requestedWidth' => 3500,
				'expectedBucket' => false,
				'message' => 'Picking no bucket because requested size is bigger than original'
			] ],
			[ [
				'buckets' => [ 1024 ],
				'minimumBucketDistance' => 0,
				'width' => 3000,
				'requestedWidth' => 1024,
				'expectedBucket' => false,
				'message' => 'Picking no bucket because requested size equals biggest bucket'
			] ],
			[ [
				'buckets' => null,
				'minimumBucketDistance' => 0,
				'width' => 3000,
				'requestedWidth' => 1024,
				'expectedBucket' => false,
				'message' => 'Picking no bucket because no buckets have been specified'
			] ],
			[ [
				'buckets' => [ 256, 512 ],
				'minimumBucketDistance' => 10,
				'width' => 3000,
				'requestedWidth' => 245,
				'expectedBucket' => 256,
				'message' => 'Requested width is distant enough from next bucket for it to be picked'
			] ],
			[ [
				'buckets' => [ 256, 512 ],
				'minimumBucketDistance' => 10,
				'width' => 3000,
				'requestedWidth' => 246,
				'expectedBucket' => 512,
				'message' => 'Requested width is too close to next bucket, picking next one'
			] ],
		];
	}

	/**
	 * @dataProvider getThumbnailSourceProvider
	 * @covers File::getThumbnailSource
	 */
	public function testGetThumbnailSource( $data ) {
		$backendMock = $this->getMockBuilder( 'FSFileBackend' )
			->setConstructorArgs( [ [ 'name' => 'backendMock', 'wikiId' => wfWikiID() ] ] )
			->getMock();

		$repoMock = $this->getMockBuilder( 'FileRepo' )
			->setConstructorArgs( [ [ 'name' => 'repoMock', 'backend' => $backendMock ] ] )
			->setMethods( [ 'fileExists', 'getLocalReference' ] )
			->getMock();

		$fsFile = new FSFile( 'fsFilePath' );

		$repoMock->expects( $this->any() )
			->method( 'fileExists' )
			->will( $this->returnValue( true ) );

		$repoMock->expects( $this->any() )
			->method( 'getLocalReference' )
			->will( $this->returnValue( $fsFile ) );

		$handlerMock = $this->getMock( 'BitmapHandler', [ 'supportsBucketing' ] );
		$handlerMock->expects( $this->any() )
			->method( 'supportsBucketing' )
			->will( $this->returnValue( $data['supportsBucketing'] ) );

		$fileMock = $this->getMockBuilder( 'File' )
			->setConstructorArgs( [ 'fileMock', $repoMock ] )
			->setMethods( [ 'getThumbnailBucket', 'getLocalRefPath', 'getHandler' ] )
			->getMockForAbstractClass();

		$fileMock->expects( $this->any() )
			->method( 'getThumbnailBucket' )
			->will( $this->returnValue( $data['thumbnailBucket'] ) );

		$fileMock->expects( $this->any() )
			->method( 'getLocalRefPath' )
			->will( $this->returnValue( 'localRefPath' ) );

		$fileMock->expects( $this->any() )
			->method( 'getHandler' )
			->will( $this->returnValue( $handlerMock ) );

		$reflection = new ReflectionClass( $fileMock );
		$reflection_property = $reflection->getProperty( 'handler' );
		$reflection_property->setAccessible( true );
		$reflection_property->setValue( $fileMock, $handlerMock );

		if ( !is_null( $data['tmpBucketedThumbCache'] ) ) {
			$reflection_property = $reflection->getProperty( 'tmpBucketedThumbCache' );
			$reflection_property->setAccessible( true );
			$reflection_property->setValue( $fileMock, $data['tmpBucketedThumbCache'] );
		}

		$result = $fileMock->getThumbnailSource(
			[ 'physicalWidth' => $data['physicalWidth'] ] );

		$this->assertEquals( $data['expectedPath'], $result['path'], $data['message'] );
	}

	public function getThumbnailSourceProvider() {
		return [
			[ [
				'supportsBucketing' => true,
				'tmpBucketedThumbCache' => null,
				'thumbnailBucket' => 1024,
				'physicalWidth' => 2048,
				'expectedPath' => 'fsFilePath',
				'message' => 'Path downloaded from storage'
			] ],
			[ [
				'supportsBucketing' => true,
				'tmpBucketedThumbCache' => [ 1024 => '/tmp/shouldnotexist' + rand() ],
				'thumbnailBucket' => 1024,
				'physicalWidth' => 2048,
				'expectedPath' => 'fsFilePath',
				'message' => 'Path downloaded from storage because temp file is missing'
			] ],
			[ [
				'supportsBucketing' => true,
				'tmpBucketedThumbCache' => [ 1024 => '/tmp' ],
				'thumbnailBucket' => 1024,
				'physicalWidth' => 2048,
				'expectedPath' => '/tmp',
				'message' => 'Temporary path because temp file was found'
			] ],
			[ [
				'supportsBucketing' => false,
				'tmpBucketedThumbCache' => null,
				'thumbnailBucket' => 1024,
				'physicalWidth' => 2048,
				'expectedPath' => 'localRefPath',
				'message' => 'Original file path because bucketing is unsupported by handler'
			] ],
			[ [
				'supportsBucketing' => true,
				'tmpBucketedThumbCache' => null,
				'thumbnailBucket' => false,
				'physicalWidth' => 2048,
				'expectedPath' => 'localRefPath',
				'message' => 'Original file path because no width provided'
			] ],
		];
	}

	/**
	 * @dataProvider generateBucketsIfNeededProvider
	 * @covers File::generateBucketsIfNeeded
	 */
	public function testGenerateBucketsIfNeeded( $data ) {
		$this->setMwGlobals( 'wgThumbnailBuckets', $data['buckets'] );

		$backendMock = $this->getMockBuilder( 'FSFileBackend' )
			->setConstructorArgs( [ [ 'name' => 'backendMock', 'wikiId' => wfWikiID() ] ] )
			->getMock();

		$repoMock = $this->getMockBuilder( 'FileRepo' )
			->setConstructorArgs( [ [ 'name' => 'repoMock', 'backend' => $backendMock ] ] )
			->setMethods( [ 'fileExists', 'getLocalReference' ] )
			->getMock();

		$fileMock = $this->getMockBuilder( 'File' )
			->setConstructorArgs( [ 'fileMock', $repoMock ] )
			->setMethods( [ 'getWidth', 'getBucketThumbPath', 'makeTransformTmpFile',
				'generateAndSaveThumb', 'getHandler' ] )
			->getMockForAbstractClass();

		$handlerMock = $this->getMock( 'JpegHandler', [ 'supportsBucketing' ] );
		$handlerMock->expects( $this->any() )
			->method( 'supportsBucketing' )
			->will( $this->returnValue( true ) );

		$fileMock->expects( $this->any() )
			->method( 'getHandler' )
			->will( $this->returnValue( $handlerMock ) );

		$reflectionMethod = new ReflectionMethod( 'File', 'generateBucketsIfNeeded' );
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
				[
					'physicalWidth' => $data['physicalWidth'],
					'physicalHeight' => $data['physicalHeight'] ]
				),
				$data['message'] );
	}

	public function generateBucketsIfNeededProvider() {
		$defaultBuckets = [ 256, 512, 1024, 2048, 4096 ];

		return [
			[ [
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
			] ],
			[ [
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
			] ],
			[ [
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
			] ],
			[ [
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
			] ],
			[ [
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
			] ],
		];
	}
}
