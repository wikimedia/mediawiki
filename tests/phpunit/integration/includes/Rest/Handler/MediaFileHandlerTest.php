<?php

namespace MediaWiki\Tests\Rest\Handler;

use MediaWiki\Rest\Handler\MediaFileHandler;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\RequestData;
use Title;
use Wikimedia\Message\MessageValue;

/**
 * @covers \MediaWiki\Rest\Handler\MediaFileHandler
 *
 * @group Database
 */
class MediaFileHandlerTest extends \MediaWikiLangTestCase {

	use MediaTestTrait;

	public function addDBDataOnce() {
		$this->editPage( 'File:' . __CLASS__ . '.jpg', 'Test image description' );
	}

	private function newHandler() {
		return new MediaFileHandler(
			$this->makeMockPermissionManager(),
			$this->makeMockRepoGroup()
		);
	}

	private function assertFile( $expected, $actual ) {
		foreach ( $expected as $key => $value ) {
			$this->assertArrayHasKey( $key, $actual );
			$this->assertSame( $value, $actual[$key], $key );
		}
	}

	public function testExecute() {
		// NOTE: "File:" namespace prefix is optional for title parameter.
		$title = __CLASS__ . '.jpg';
		$request = new RequestData( [ 'pathParams' => [ 'title' => $title ] ] );

		$handler = $this->newHandler();
		$data = $this->executeHandlerAndGetBodyData( $handler, $request );

		$this->assertFile(
			[
				'title' => $title,
				'file_description_url' => 'https://example.com/wiki/File:' . $title,
				'latest' => [
					'timestamp' => '2020-01-02T03:04:05Z',
					'user' => [
						'id' => 7,
						'name' => 'Alice',
					],
				],
				'preferred' => [
					'mediatype' => 'test',
					'size' => null,
					'width' => 64,
					'height' => 64,
					'duration' => 678,
					'url' => 'https://media.example.com/static/thumb/' . $title,
				],
				'original' => [
					'mediatype' => 'test',
					'size' => 12345,
					'width' => 600,
					'height' => 400,
					'duration' => 678,
					'url' => 'https://media.example.com/static/' . $title,
				],
				'thumbnail' => [
					'mediatype' => 'test',
					'size' => null,
					'width' => 64,
					'height' => 64,
					'duration' => 678,
					'url' => 'https://media.example.com/static/thumb/' . $title,
				],
			],
			$data
		);
	}

	public function testCacheControl() {
		$title = 'File:' . __CLASS__ . '.jpg';
		$request = new RequestData( [ 'pathParams' => [ 'title' => $title ] ] );

		$handler = $this->newHandler();
		$response = $this->executeHandler( $handler, $request );

		// Mock image timestamp from MediaTestTrait::makeMockFile
		$this->assertSame(
			'Thu, 02 Jan 2020 03:04:05 GMT',
			$response->getHeaderLine( 'Last-Modified' )
		);
		// Mock image hash from MediaTestTrait::makeMockFile
		$this->assertSame(
			'"DEADBEEF"',
			$response->getHeaderLine( 'ETag' )
		);
	}

	public function testExecute_notFound() {
		// NOTE: MediaTestTrait::makeMockRepoGroup() will treat files with "missing" in
		// the name as non-existent.
		$title = __CLASS__ . '_Missing.png';
		$request = new RequestData( [ 'pathParams' => [ 'title' => $title ] ] );

		$handler = $this->newHandler();

		$this->expectExceptionObject(
			new LocalizedHttpException( new MessageValue( 'rest-nonexistent-title' ), 404 )
		);
		$this->executeHandler( $handler, $request );
	}

	public function testExecute_wrongNamespace() {
		$title = Title::newFromText( 'User:' . __CLASS__ . '.jpg' );
		$this->editPage( $title->getPrefixedDBkey(), 'First' );
		$request = new RequestData( [ 'pathParams' => [ 'title' => $title->getPrefixedDBkey() ] ] );

		$handler = $this->newHandler();

		$this->expectExceptionObject(
			new LocalizedHttpException( new MessageValue( 'rest-cannot-load-file' ), 404 )
		);
		$this->executeHandler( $handler, $request );
	}

}
