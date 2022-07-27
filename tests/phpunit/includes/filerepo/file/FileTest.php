<?php

use MediaWiki\MainConfigNames;
use MediaWiki\Page\PageIdentityValue;

class FileTest extends MediaWikiMediaTestCase {

	/**
	 * @param string $filename
	 * @param bool $expected
	 * @dataProvider providerCanAnimate
	 * @covers File::canAnimateThumbIfAppropriate
	 */
	public function testCanAnimateThumbIfAppropriate( $filename, $expected ) {
		$this->overrideConfigValue( MainConfigNames::MaxAnimatedGifArea, 9000 );
		$file = $this->dataFile( $filename );
		$this->assertEquals( $expected, $file->canAnimateThumbIfAppropriate() );
	}

	public function providerCanAnimate() {
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
		$this->overrideConfigValues( [
			MainConfigNames::ThumbnailBuckets => $data['buckets'],
			MainConfigNames::ThumbnailMinimumBucketDistance => $data['minimumBucketDistance'],
		] );

		$fileMock = $this->getMockBuilder( File::class )
			->setConstructorArgs( [ 'fileMock', false ] )
			->onlyMethods( [ 'getWidth' ] )
			->getMockForAbstractClass();

		$fileMock->method( 'getWidth' )
			->willReturn( $data['width'] );

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
		$backendMock = $this->getMockBuilder( FSFileBackend::class )
			->setConstructorArgs( [ [ 'name' => 'backendMock', 'wikiId' => WikiMap::getCurrentWikiId() ] ] )
			->getMock();

		$repoMock = $this->getMockBuilder( FileRepo::class )
			->setConstructorArgs( [ [ 'name' => 'repoMock', 'backend' => $backendMock ] ] )
			->onlyMethods( [ 'fileExists', 'getLocalReference' ] )
			->getMock();

		$tempDir = wfTempDir();
		$fsFile = new FSFile( 'fsFilePath' );

		$repoMock->method( 'fileExists' )
			->willReturn( true );

		$repoMock->method( 'getLocalReference' )
			->willReturn( $fsFile );

		$handlerMock = $this->getMockBuilder( BitmapHandler::class )
			->onlyMethods( [ 'supportsBucketing' ] )->getMock();
		$handlerMock->method( 'supportsBucketing' )
			->willReturn( $data['supportsBucketing'] );

		$fileMock = $this->getMockBuilder( File::class )
			->setConstructorArgs( [ 'fileMock', $repoMock ] )
			->onlyMethods( [ 'getThumbnailBucket', 'getLocalRefPath', 'getHandler' ] )
			->getMockForAbstractClass();

		$fileMock->method( 'getThumbnailBucket' )
			->willReturn( $data['thumbnailBucket'] );

		$fileMock->method( 'getLocalRefPath' )
			->willReturn( 'localRefPath' );

		$fileMock->method( 'getHandler' )
			->willReturn( $handlerMock );

		$reflection = new ReflectionClass( $fileMock );
		$reflection_property = $reflection->getProperty( 'handler' );
		$reflection_property->setAccessible( true );
		$reflection_property->setValue( $fileMock, $handlerMock );

		if ( $data['tmpBucketedThumbCache'] !== null ) {
			foreach ( $data['tmpBucketedThumbCache'] as $bucket => &$tmpBucketed ) {
				$tmpBucketed = str_replace( '/tmp', $tempDir, $tmpBucketed );
			}
			$reflection_property = $reflection->getProperty( 'tmpBucketedThumbCache' );
			$reflection_property->setAccessible( true );
			$reflection_property->setValue( $fileMock, $data['tmpBucketedThumbCache'] );
		}

		$result = $fileMock->getThumbnailSource(
			[ 'physicalWidth' => $data['physicalWidth'] ] );

		$this->assertEquals(
			str_replace( '/tmp', $tempDir, $data['expectedPath'] ),
			$result['path'],
			$data['message']
		);
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
				'tmpBucketedThumbCache' => [ 1024 => '/tmp/shouldnotexist' . rand() ],
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
		$this->overrideConfigValue( MainConfigNames::ThumbnailBuckets, $data['buckets'] );

		$backendMock = $this->getMockBuilder( FSFileBackend::class )
			->setConstructorArgs( [ [ 'name' => 'backendMock', 'wikiId' => WikiMap::getCurrentWikiId() ] ] )
			->getMock();

		$repoMock = $this->getMockBuilder( FileRepo::class )
			->setConstructorArgs( [ [ 'name' => 'repoMock', 'backend' => $backendMock ] ] )
			->onlyMethods( [ 'fileExists', 'getLocalReference' ] )
			->getMock();

		$fileMock = $this->getMockBuilder( File::class )
			->setConstructorArgs( [ 'fileMock', $repoMock ] )
			->onlyMethods( [ 'getWidth', 'getBucketThumbPath', 'makeTransformTmpFile',
				'generateAndSaveThumb', 'getHandler' ] )
			->getMockForAbstractClass();

		$handlerMock = $this->getMockBuilder( JpegHandler::class )
			->onlyMethods( [ 'supportsBucketing' ] )->getMock();
		$handlerMock->method( 'supportsBucketing' )
			->willReturn( true );

		$fileMock->method( 'getHandler' )
			->willReturn( $handlerMock );

		$reflectionMethod = new ReflectionMethod( File::class, 'generateBucketsIfNeeded' );
		$reflectionMethod->setAccessible( true );

		$fileMock->method( 'getWidth' )
			->willReturn( $data['width'] );

		$fileMock->expects( $data['expectedGetBucketThumbPathCalls'] )
			->method( 'getBucketThumbPath' );

		$repoMock->expects( $data['expectedFileExistsCalls'] )
			->method( 'fileExists' )
			->willReturn( $data['fileExistsReturn'] );

		$fileMock->expects( $data['expectedMakeTransformTmpFile'] )
			->method( 'makeTransformTmpFile' )
			->willReturn( $data['makeTransformTmpFileReturn'] );

		$fileMock->expects( $data['expectedGenerateAndSaveThumb'] )
			->method( 'generateAndSaveThumb' )
			->willReturn( $data['generateAndSaveThumbReturn'] );

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

	/**
	 * @covers File::getDisplayWidthHeight
	 * @dataProvider providerGetDisplayWidthHeight
	 * @param array $dim Array [maxWidth, maxHeight, width, height]
	 * @param array $expected Array [width, height] The width and height we expect to display at
	 */
	public function testGetDisplayWidthHeight( $dim, $expected ) {
		$fileMock = $this->getMockBuilder( File::class )
			->setConstructorArgs( [ 'fileMock', false ] )
			->onlyMethods( [ 'getWidth', 'getHeight' ] )
			->getMockForAbstractClass();

		$fileMock->method( 'getWidth' )->willReturn( $dim[2] );
		$fileMock->method( 'getHeight' )->willReturn( $dim[3] );

		$actual = $fileMock->getDisplayWidthHeight( $dim[0], $dim[1] );
		$this->assertEquals( $expected, $actual );
	}

	public function providerGetDisplayWidthHeight() {
		return [
			[
				[ 1024.0, 768.0, 600.0, 600.0 ],
				[ 600.0, 600.0 ]
			],
			[
				[ 1024.0, 768.0, 1600.0, 600.0 ],
				[ 1024.0, 384.0 ]
			],
			[
				[ 1024.0, 768.0, 1024.0, 768.0 ],
				[ 1024.0, 768.0 ]
			],
			[
				[ 1024.0, 768.0, 800.0, 1000.0 ],
				[ 614.0, 768.0 ]
			],
			[
				[ 1024.0, 768.0, 0, 1000 ],
				[ 0, 0 ]
			],
			[
				[ 1024.0, 768.0, 2000, 0 ],
				[ 0, 0 ]
			],
		];
	}

	public function provideNormalizeTitle() {
		yield [ 'some name.jpg', 'Some_name.jpg' ];
		yield [ new TitleValue( NS_FILE, 'Some_name.jpg' ), 'Some_name.jpg' ];
		yield [ new TitleValue( NS_MEDIA, 'Some_name.jpg' ), 'Some_name.jpg' ];
		yield [ new PageIdentityValue( 0, NS_FILE, 'Some_name.jpg', false ), 'Some_name.jpg' ];
	}

	/**
	 * @covers File::normalizeTitle
	 * @dataProvider provideNormalizeTitle
	 */
	public function testNormalizeTitle( $title, $expected ) {
		$actual = File::normalizeTitle( $title );

		$this->assertSame( NS_FILE, $actual->getNamespace() );
		$this->assertSame( $expected, $actual->getDBkey() );
	}

	public function provideNormalizeTitleFails() {
		yield [ '' ];
		yield [ '#' ];
		yield [ new TitleValue( NS_USER, 'Some_name.jpg' ) ];
		yield [ new PageIdentityValue( 0, NS_USER, 'Some_name.jpg', false ) ];
	}

	/**
	 * @covers File::normalizeTitle
	 * @dataProvider provideNormalizeTitleFails
	 */
	public function testNormalizeTitleFails( $title ) {
		$actual = File::normalizeTitle( $title );
		$this->assertNull( $actual );

		$this->expectException( MWException::class );
		File::normalizeTitle( $title, 'exception' );
	}

	/**
	 * @covers File::setHandlerState
	 * @covers File::getHandlerState
	 */
	public function testSetHandlerState() {
		$obj = (object)[];
		$file = new class extends File {
			public function __construct() {
			}
		};
		$this->assertNull( $file->getHandlerState( 'test' ) );
		$file->setHandlerState( 'test', $obj );
		$this->assertSame( $obj, $file->getHandlerState( 'test' ) );
	}
}
