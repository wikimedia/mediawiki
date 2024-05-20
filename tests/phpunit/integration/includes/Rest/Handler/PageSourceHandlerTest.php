<?php

namespace MediaWiki\Tests\Rest\Handler;

use Exception;
use MediaWiki\Content\TextContent;
use MediaWiki\MainConfigNames;
use MediaWiki\Rest\Handler\PageSourceHandler;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\RequestData;
use MediaWiki\Revision\SlotRecord;
use MediaWikiIntegrationTestCase;
use Wikimedia\Message\MessageValue;
use WikiPage;

/**
 * @covers \MediaWiki\Rest\Handler\PageSourceHandler
 * @group Database
 */
class PageSourceHandlerTest extends MediaWikiIntegrationTestCase {
	use HandlerTestTrait;
	use PageHandlerTestTrait;

	private const WIKITEXT = 'Hello \'\'\'World\'\'\'';

	private const HTML = '<p>Hello <b>World</b></p>';

	protected function setUp(): void {
		parent::setUp();

		$this->overrideConfigValues( [
			MainConfigNames::RightsUrl => 'https://example.com/rights',
			MainConfigNames::RightsText => 'some rights',
		] );
	}

	/**
	 * @return PageSourceHandler
	 * @throws Exception
	 */
	private function newHandler(): PageSourceHandler {
		return $this->newPageSourceHandler();
	}

	public function testExecuteBare() {
		$page = $this->getExistingTestPage( 'Talk:SourceEndpointTestPage/with/slashes' );
		$request = new RequestData(
			[ 'pathParams' => [ 'title' => $page->getTitle()->getPrefixedText() ] ]
		);

		$htmlUrl = 'https://wiki.example.com/rest/v1/page/Talk%3ASourceEndpointTestPage%2Fwith%2Fslashes/html';

		$handler = $this->newHandler();
		$config = [ 'format' => 'bare' ];
		$data = $this->executeHandlerAndGetBodyData( $handler, $request, $config );

		$this->assertResponseData( $page, $data );
		$this->assertSame( $htmlUrl, $data['html_url'] );
	}

	public function testExecuteSource() {
		$page = $this->getExistingTestPage( 'Talk:SourceEndpointTestPage/with/slashes' );
		$request = new RequestData(
			[ 'pathParams' => [ 'title' => $page->getTitle()->getPrefixedText() ] ]
		);

		$handler = $this->newHandler();
		$config = [ 'format' => 'source' ];
		$data = $this->executeHandlerAndGetBodyData( $handler, $request, $config );

		/** @var TextContent $content */
		$content = $page->getRevisionRecord()->getContent( SlotRecord::MAIN );

		$this->assertResponseData( $page, $data );
		$this->assertSame( $content->getText(), $data['source'] );
	}

	public function testExecute_missingparam() {
		$request = new RequestData();

		$this->expectExceptionObject(
			new LocalizedHttpException(
				new MessageValue( "paramvalidator-missingparam", [ 'title' ] ),
				400
			)
		);

		$handler = $this->newHandler();
		$this->executeHandler( $handler, $request );
	}

	public function testExecute_error() {
		$request = new RequestData( [ 'pathParams' => [ 'title' => 'DoesNotExist8237456assda1234' ] ] );

		$this->expectExceptionObject(
			new LocalizedHttpException(
				new MessageValue( "rest-nonexistent-title", [ 'testing' ] ),
				404
			)
		);

		$handler = $this->newHandler();
		$config = [ 'format' => 'bare' ];
		$this->executeHandler( $handler, $request, $config );
	}

	public function testExecute_message() {
		$request = new RequestData( [ 'pathParams' => [ 'title' => 'MediaWiki:Ok' ] ] );

		$this->expectExceptionObject(
			new LocalizedHttpException(
				new MessageValue( "rest-nonexistent-title", [ 'testing' ] ),
				404
			)
		);

		$handler = $this->newHandler();
		$config = [ 'format' => 'bare' ];
		$this->executeHandler( $handler, $request, $config );
	}

	/**
	 * @param WikiPage $page
	 * @param array $data
	 */
	private function assertResponseData( WikiPage $page, array $data ): void {
		$this->assertSame( $page->getId(), $data['id'] );
		$this->assertSame( $page->getTitle()->getPrefixedDBkey(), $data['key'] );
		$this->assertSame( $page->getTitle()->getPrefixedText(), $data['title'] );
		$this->assertSame( $page->getLatest(), $data['latest']['id'] );
		$this->assertSame(
			wfTimestampOrNull( TS_ISO_8601, $page->getTimestamp() ),
			$data['latest']['timestamp']
		);
		$this->assertSame( CONTENT_MODEL_WIKITEXT, $data['content_model'] );
		$this->assertSame( 'https://example.com/rights', $data['license']['url'] );
		$this->assertSame( 'some rights', $data['license']['title'] );
	}

}
