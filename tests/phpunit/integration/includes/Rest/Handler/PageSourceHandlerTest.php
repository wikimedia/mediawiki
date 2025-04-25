<?php

namespace MediaWiki\Tests\Rest\Handler;

use Exception;
use MediaWiki\Content\TextContent;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\WikiPage;
use MediaWiki\Rest\Handler\PageSourceHandler;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\RequestData;
use MediaWiki\Revision\SlotRecord;
use MediaWikiIntegrationTestCase;
use Wikimedia\Message\MessageValue;

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

		$htmlUrl = 'https://wiki.example.com/rest/mock/page/Talk%3ASourceEndpointTestPage%2Fwith%2Fslashes/html';

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

	public function testExecuteRestbaseCompat() {
		$page = $this->getExistingTestPage( 'Talk:SourceEndpointTestPage/with/slashes' );
		$request = new RequestData(
			[
				'pathParams' => [ 'title' => $page->getTitle()->getPrefixedText() ],
				'headers' => [ 'x-restbase-compat' => 'true' ]
			]

		);

		$htmlUrl = 'https://wiki.example.com/rest/mock/page/Talk%3ASourceEndpointTestPage%2Fwith%2Fslashes/html';

		$handler = $this->newHandler();
		$config = [ 'format' => 'bare' ];
		$data = $this->executeHandlerAndGetBodyData( $handler, $request, $config );

		$this->assertRestbaseCompatibleResponseData( $page, $data );
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

	private function assertRestbaseCompatibleResponseData( WikiPage $page, array $data ): void {
		$this->assertArrayHasKey( 'items', $data );
		$this->assertSame( $page->getTitle()->getPrefixedDBkey(), $data['items'][0]['title'] );
		$this->assertSame( $page->getId(), $data['items'][0]['page_id'] );
		$this->assertSame( $page->getLatest(), $data['items'][0]['rev'] );
		$this->assertSame( $page->getNamespace(), $data['items'][0]['namespace'] );
		$this->assertSame( $page->getUser(), $data['items'][0]['user_id'] );
		$this->assertSame( $page->getUserText(), $data['items'][0]['user_text'] );
		$this->assertSame(
			wfTimestampOrNull( TS_ISO_8601, $page->getTimestamp() ),
			$data['items'][0]['timestamp']
		);
		$this->assertSame( $page->getComment(), $data['items'][0]['comment'] );
		$this->assertSame( [], $data['items'][0]['tags'] );
		$this->assertSame( [], $data['items'][0]['restrictions'] );
		$this->assertSame(
			$page->getTitle()->getPageLanguage()->getCode(),
			$data['items'][0]['page_language']
		);
		$this->assertSame( $page->isRedirect(), $data['items'][0]['redirect'] );
	}

}
