<?php

namespace MediaWiki\Tests\Rest\Handler;

use MediaWiki\MainConfigNames;
use MediaWiki\Rest\Handler\MediaFileThumbnailsHandler;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\RequestData;
use MediaWikiLangTestCase;
use Wikimedia\Message\MessageValue;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\Rest\Handler\MediaFileThumbnailsHandler
 *
 * @group Database
 */
class MediaFileThumbnailsHandlerTest extends MediaWikiLangTestCase {

	use MediaTestTrait;

	public function addDBDataOnce() {
		$this->editPage( 'File:' . __CLASS__ . '.jpg', 'Test image description' );
	}

	private function newHandler() {
		return new MediaFileThumbnailsHandler(
			$this->makeMockRepoGroup( [ __CLASS__ . '.jpg' ] ),
			$this->getServiceContainer()->getPageStore()
		);
	}

	private function newNonThumbnailableHandler() {
		$title = $this->makeMockTitle( 'File:' . __CLASS__ . '.wav', [ 'namespace' => NS_FILE ] );

		$file = $this->createNoOpMock(
			\MediaWiki\FileRepo\File\LocalFile::class,
			[ 'exists', 'allowInlineDisplay', 'getHandler', 'getTitle' ]
		);

		$file->method( 'exists' )->willReturn( true );
		$file->method( 'allowInlineDisplay' )->willReturn( false ); // Non thumbnailable
		$file->method( 'getHandler' )->willReturn( null );
		$file->method( 'getTitle' )->willReturn( $title );

		$repoGroup = $this->createNoOpMock(
			\MediaWiki\FileRepo\RepoGroup::class,
			[ 'findFile' ]
		);
		$repoGroup->method( 'findFile' )->willReturn( $file );

		return new MediaFileThumbnailsHandler(
			$repoGroup,
			$this->getServiceContainer()->getPageStore()
		);
	}

	public function testExecute() {
		$title = __CLASS__ . '.jpg';
		$request = new RequestData( [ 'pathParams' => [ 'title' => $title ] ] );

		$this->overrideConfigValue( MainConfigNames::ThumbnailSteps, [ 20, 50, 100, 200 ] );

		$handler = $this->newHandler();
		$data = $this->executeHandlerAndGetBodyData( $handler, $request );

		$this->assertSame( $title, $data['title'] );
		$this->assertSame( 'test', $data['original']['mediatype'] );
		$this->assertSame( 600, $data['original']['width'] );
		$this->assertSame( 400, $data['original']['height'] );
		$this->assertSame( 'https://media.example.com/static/' . $title, $data['original']['url'] );

		$this->assertSame(
			[
				[ 'width' => 20, 'height' => 13, 'url' => 'https://media.example.com/static/thumb/' . $title, 'mime' => 'image/jpeg' ],
				[ 'width' => 50, 'height' => 33, 'url' => 'https://media.example.com/static/thumb/' . $title, 'mime' => 'image/jpeg' ],
				[ 'width' => 100, 'height' => 67, 'url' => 'https://media.example.com/static/thumb/' . $title, 'mime' => 'image/jpeg' ],
				[ 'width' => 200, 'height' => 133, 'url' => 'https://media.example.com/static/thumb/' . $title, 'mime' => 'image/jpeg' ],
			],
			$data['thumbnails']
		);
	}

	public function testCacheControl() {
		$title = 'File:' . __CLASS__ . '.jpg';
		$request = new RequestData( [ 'pathParams' => [ 'title' => $title ] ] );

		$this->overrideConfigValue( MainConfigNames::ThumbnailSteps, [ 20, 50, 100, 200 ] );

		$handler = $this->newHandler();
		$response = $this->executeHandler( $handler, $request );

		$this->assertSame(
			'Thu, 02 Jan 2020 03:04:05 GMT',
			$response->getHeaderLine( 'Last-Modified' )
		);
		$this->assertSame(
			'"DEADBEEF"',
			$response->getHeaderLine( 'ETag' )
		);
	}

	public function testExecuteNotThumbnailable() {
		$title = __CLASS__ . '.jpg';
		$request = new RequestData( [ 'pathParams' => [ 'title' => $title ] ] );

		$handler = $this->newNonThumbnailableHandler();

		$this->expectExceptionObject(
			new LocalizedHttpException( new MessageValue( 'rest-file-not-thumbnailable' ), 400 )
		);
		$this->executeHandler( $handler, $request );
	}

	public function testGenerateResponseSpec() {
		$handler = $this->newHandler();
		$this->initHandler( $handler, new RequestData( [] ) );
		$wrapper = TestingAccessWrapper::newFromObject( $handler );
		$spec = $wrapper->generateResponseSpec( 'GET' );

		$this->assertSame(
			[ 'rest-file-not-thumbnailable' ],
			$spec['400']['content']['application/json']['schema']['allOf'][1]['properties']['errorKey']['enum']
		);
		$this->assertSame(
			'The file (File:Fennec_Fox.jpg) does not support public thumbnail generation.',
			$spec['400']['content']['application/json']['example']['message']
		);
		$this->assertSame(
			[ 'rest-permission-denied-title' ],
			$spec['403']['content']['application/json']['schema']['allOf'][1]['properties']['errorKey']['enum']
		);
		$this->assertSame(
			'The user does not have rights to read title (File:Fennec_Fox.jpg)',
			$spec['403']['content']['application/json']['example']['message']
		);
		$notFoundSchema = $spec['404']['content']['application/json']['schema']['oneOf'];
		$this->assertSame(
			[ 'rest-nonexistent-title' ],
			$notFoundSchema[0]['allOf'][1]['properties']['errorKey']['enum']
		);
		$this->assertSame(
			[ 'rest-cannot-load-file' ],
			$notFoundSchema[1]['allOf'][1]['properties']['errorKey']['enum']
		);
		$this->assertSame(
			'The specified page (File:Fennec_Fox.jpg) does not exist',
			$spec['404']['content']['application/json']['example']['message']
		);
	}
}
