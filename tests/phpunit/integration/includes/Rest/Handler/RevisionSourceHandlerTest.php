<?php

namespace MediaWiki\Tests\Rest\Handler;

use Exception;
use MediaWiki\Content\TextContent;
use MediaWiki\Rest\Handler\RevisionSourceHandler;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\RequestData;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;
use MediaWikiIntegrationTestCase;
use Wikimedia\Message\MessageValue;
use Wikimedia\ObjectCache\BagOStuff;

/**
 * @covers \MediaWiki\Rest\Handler\RevisionSourceHandler
 * @group Database
 */
class RevisionSourceHandlerTest extends MediaWikiIntegrationTestCase {
	use HandlerTestTrait;

	private const WIKITEXT = 'Hello \'\'\'World\'\'\'';

	private const HTML = '<p>Hello <b>World</b></p>';

	protected function setUp(): void {
		parent::setUp();

		$this->overrideConfigValues( [
			'RightsUrl' => 'https://example.com/rights',
			'RightsText' => 'some rights',
		] );
	}

	/**
	 * @param BagOStuff|null $cache
	 * @return RevisionSourceHandler
	 * @throws Exception
	 */
	private function newHandler( BagOStuff $cache = null ): RevisionSourceHandler {
		$handler = new RevisionSourceHandler(
			$this->getServiceContainer()->getPageRestHelperFactory()
		);

		return $handler;
	}

	private function getExistingPageWithRevisions( $name ) {
		$page = $this->getNonexistingTestPage( $name );

		$this->editPage( $page, self::WIKITEXT );
		$revisions['first'] = $page->getRevisionRecord();

		$this->editPage( $page, 'DEAD BEEF' );
		$revisions['latest'] = $page->getRevisionRecord();

		return [ $page, $revisions ];
	}

	public function testExecuteBare() {
		[ $page, $revisions ] = $this->getExistingPageWithRevisions( __METHOD__ );

		$firstRev = $revisions['first'];
		$request = new RequestData(
			[ 'pathParams' => [ 'id' => $firstRev->getId() ] ]
		);

		$htmlUrl = "https://wiki.example.com/rest/v1/revision/{$firstRev->getId()}/html";

		$handler = $this->newHandler();
		$config = [ 'format' => 'bare' ];
		$data = $this->executeHandlerAndGetBodyData( $handler, $request, $config );

		$this->assertResponseData( $firstRev, $data );
		$this->assertSame( $htmlUrl, $data['html_url'] );
	}

	public function testExecuteSource() {
		[ $page, $revisions ] = $this->getExistingPageWithRevisions( __METHOD__ );

		$firstRev = $revisions['first'];
		$request = new RequestData(
			[ 'pathParams' => [ 'id' => $firstRev->getId() ] ]
		);

		$handler = $this->newHandler();
		$config = [ 'format' => 'source' ];
		$data = $this->executeHandlerAndGetBodyData( $handler, $request, $config );

		/** @var TextContent $content */
		$content = $firstRev->getContent( SlotRecord::MAIN );

		$this->assertResponseData( $firstRev, $data );
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
		$request = new RequestData( [ 'pathParams' => [ 'id' => '2074398742' ] ] );

		$this->expectExceptionObject(
			new LocalizedHttpException(
				new MessageValue( "rest-nonexistent-revision", [ 'testing' ] ),
				404
			)
		);

		$handler = $this->newHandler();
		$this->executeHandler( $handler, $request );
	}

	/**
	 * @param RevisionRecord $rev
	 * @param array $data
	 */
	private function assertResponseData( RevisionRecord $rev, array $data ): void {
		$this->assertSame( $rev->getId(), $data['id'] );
		$this->assertSame( $rev->getSize(), $data['size'] );
		$this->assertSame( $rev->isMinor(), $data['minor'] );
		$this->assertSame(
			wfTimestampOrNull( TS_ISO_8601, $rev->getTimestamp() ),
			$data['timestamp']
		);
		$this->assertSame( $rev->getPage()->getId(), $data['page']['id'] );
		$this->assertSame( $rev->getPage()->getDBkey(), $data['page']['key'] ); // assume main namespace
		$this->assertSame(
			$rev->getPageAsLinkTarget()->getText(),
			$data['page']['title']
		); // assume main namespace
		$this->assertSame( CONTENT_MODEL_WIKITEXT, $data['content_model'] );
		$this->assertSame( 'https://example.com/rights', $data['license']['url'] );
		$this->assertSame( 'some rights', $data['license']['title'] );
		$this->assertSame( $rev->getComment()->text, $data['comment'] );
		$this->assertSame( $rev->getUser()->getId(), $data['user']['id'] );
		$this->assertSame( $rev->getUser()->getName(), $data['user']['name'] );
	}

}
