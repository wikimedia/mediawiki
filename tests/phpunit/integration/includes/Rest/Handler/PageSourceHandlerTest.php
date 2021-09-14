<?php

namespace MediaWiki\Tests\Rest\Handler;

use BagOStuff;
use Exception;
use HashConfig;
use MediaWiki\Rest\Handler\PageSourceHandler;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\RequestData;
use MediaWiki\Revision\SlotRecord;
use MediaWikiIntegrationTestCase;
use TextContent;
use Wikimedia\Message\MessageValue;
use WikiPage;

/**
 * @covers \MediaWiki\Rest\Handler\PageSourceHandler
 * @group Database
 */
class PageSourceHandlerTest extends MediaWikiIntegrationTestCase {
	use HandlerTestTrait;

	private const WIKITEXT = 'Hello \'\'\'World\'\'\'';

	private const HTML = '<p>Hello <b>World</b></p>';

	protected function setUp(): void {
		parent::setUp();

		// Clean up these tables after each test
		$this->tablesUsed = [
			'page',
			'revision',
			'comment',
			'text',
			'content'
		];
	}

	/**
	 * @param BagOStuff|null $cache
	 * @return PageSourceHandler
	 * @throws Exception
	 */
	private function newHandler( BagOStuff $cache = null ): PageSourceHandler {
		$handler = new PageSourceHandler(
			new HashConfig( [
				'RightsUrl' => 'https://example.com/rights',
				'RightsText' => 'some rights',
			] ),
			$this->getServiceContainer()->getRevisionLookup(),
			$this->getServiceContainer()->getTitleFormatter(),
			$this->getServiceContainer()->getPageStore()
		);

		return $handler;
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
		$this->executeHandler( $handler, $request );
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
