<?php

namespace MediaWiki\Tests\Rest\Handler;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\Content\TextContent;
use MediaWiki\MainConfigNames;
use MediaWiki\MainConfigSchema;
use MediaWiki\Rest\Handler\Helper\HtmlOutputRendererHelper;
use MediaWiki\Rest\Handler\Helper\PageRestHelperFactory;
use MediaWiki\Rest\Handler\Helper\RevisionContentHelper;
use MediaWiki\Rest\Handler\RevisionHandler;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\RequestData;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;
use MediaWikiIntegrationTestCase;
use Wikimedia\Message\MessageValue;
use Wikimedia\Stats\StatsFactory;
use Wikimedia\Timestamp\TimestampFormat as TS;

/**
 * @covers \MediaWiki\Rest\Handler\RevisionHandler
 * @group Database
 */
class RevisionHandlerTest extends MediaWikiIntegrationTestCase {
	use HandlerTestTrait;
	use HTMLHandlerTestTrait;

	private const WIKITEXT = 'Hello \'\'\'World\'\'\'';

	private const HTML = '>World<';

	protected function setUp(): void {
		parent::setUp();

		$this->overrideConfigValues( [
			MainConfigNames::RightsUrl => 'https://example.com/rights',
			MainConfigNames::RightsText => 'some rights',
		] );
	}

	private function newHandler(): RevisionHandler {
		$services = $this->getServiceContainer();
		$config = [
			MainConfigNames::RightsUrl => 'https://example.com/rights',
			MainConfigNames::RightsText => 'some rights',
			MainConfigNames::ParsoidCacheConfig =>
				MainConfigSchema::getDefaultValue( MainConfigNames::ParsoidCacheConfig )
		];

		$helperFactory = $this->createNoOpMock(
			PageRestHelperFactory::class,
			[ 'newRevisionContentHelper', 'newHtmlOutputRendererHelper' ]
		);

		$helperFactory->method( 'newRevisionContentHelper' )
			->willReturnCallback( static fn () => new RevisionContentHelper(
				new ServiceOptions( RevisionContentHelper::CONSTRUCTOR_OPTIONS, $config ),
				$services->getRevisionLookup(),
				$services->getTitleFormatter(),
				$services->getPageStore(),
				$services->getTitleFactory(),
				$services->getConnectionProvider(),
				$services->getChangeTagsStore(),
				$services->getShadowPageLoader(),
			) );

		$parsoidOutputStash = $this->getParsoidOutputStash();
		$helperFactory->method( 'newHtmlOutputRendererHelper' )
			->willReturnCallback( static function ( $page, $parameters, $authority, $revision, $lenientRevHandling ) use ( $services, $parsoidOutputStash ) {
				return new HtmlOutputRendererHelper(
					$parsoidOutputStash,
					StatsFactory::newNull(),
					$services->getParserOutputAccess(),
					$services->getPageStore(),
					$services->getRevisionLookup(),
					$services->getRevisionRenderer(),
					$services->getParsoidSiteConfig(),
					$services->getHtmlTransformFactory(),
					$services->getContentHandlerFactory(),
					$services->getLanguageFactory(),
					$page,
					$parameters,
					$authority,
					$revision,
					$lenientRevHandling
				);
			} );

		return new RevisionHandler( $helperFactory );
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
		[ , $revisions ] = $this->getExistingPageWithRevisions( __METHOD__ );

		$firstRev = $revisions['first'];
		$request = new RequestData(
			[ 'pathParams' => [ 'id' => $firstRev->getId() ] ]
		);

		$htmlUrl = "https://wiki.example.com/rest/mock/revision/{$firstRev->getId()}/html";

		$handler = $this->newHandler();
		$config = [ 'prop' => [] ];
		$data = $this->executeHandlerAndGetBodyData( $handler, $request, $config );

		$this->assertResponseData( $firstRev, $data );
		$this->assertSame( $htmlUrl, $data['html_url'] );
		$this->assertArrayNotHasKey( 'source', $data );
		$this->assertArrayNotHasKey( 'html', $data );
	}

	public function testExecuteSource() {
		[ , $revisions ] = $this->getExistingPageWithRevisions( __METHOD__ );

		$firstRev = $revisions['first'];
		$request = new RequestData(
			[ 'pathParams' => [ 'id' => $firstRev->getId() ] ]
		);

		$handler = $this->newHandler();
		$config = [ 'prop' => [ 'source' ] ];
		$data = $this->executeHandlerAndGetBodyData( $handler, $request, $config );

		/** @var TextContent $content */
		$content = $firstRev->getContent( SlotRecord::MAIN );

		$this->assertResponseData( $firstRev, $data );
		$this->assertSame( $content->getText(), $data['source'] );
	}

	public function testExecuteWithHtml() {
		[ , $revisions ] = $this->getExistingPageWithRevisions( __METHOD__ );

		$firstRev = $revisions['first'];
		$request = new RequestData(
			[ 'pathParams' => [ 'id' => $firstRev->getId() ] ]
		);

		$handler = $this->newHandler();
		$config = [ 'prop' => [ 'html' ] ];
		$data = $this->executeHandlerAndGetBodyData( $handler, $request, $config );

		$this->assertResponseData( $firstRev, $data );
		$this->assertStringContainsString( '<!DOCTYPE html>', $data['html'] );
		$this->assertStringContainsString( '<html', $data['html'] );
		$this->assertStringContainsString( self::HTML, $data['html'] );
		// When HTML is embedded, no html_url link is emitted.
		$this->assertArrayNotHasKey( 'html_url', $data );
	}

	public function testExecuteSourceAndHtml() {
		[ , $revisions ] = $this->getExistingPageWithRevisions( __METHOD__ );

		$firstRev = $revisions['first'];
		$request = new RequestData(
			[ 'pathParams' => [ 'id' => $firstRev->getId() ] ]
		);

		$handler = $this->newHandler();
		$config = [ 'prop' => [ 'source', 'html' ] ];
		$data = $this->executeHandlerAndGetBodyData( $handler, $request, $config );

		/** @var TextContent $content */
		$content = $firstRev->getContent( SlotRecord::MAIN );

		$this->assertResponseData( $firstRev, $data );
		// Both source and rendered HTML are present in a single response.
		$this->assertSame( $content->getText(), $data['source'] );
		$this->assertStringContainsString( '<!DOCTYPE html>', $data['html'] );
		$this->assertStringContainsString( self::HTML, $data['html'] );
	}

	public function testETagVariesByOutputProp() {
		[ , $revisions ] = $this->getExistingPageWithRevisions( __METHOD__ );
		$request = new RequestData(
			[ 'pathParams' => [ 'id' => $revisions['first']->getId() ] ]
		);

		$htmlETag = $this->executeHandler( $this->newHandler(), $request, [ 'prop' => [ 'html' ] ] )
			->getHeaderLine( 'ETag' );
		$sourceHtmlETag = $this->executeHandler( $this->newHandler(), $request, [ 'prop' => [ 'source', 'html' ] ] )
			->getHeaderLine( 'ETag' );

		$this->assertNotSame( '', $htmlETag );
		// prop=html and prop=source|html must not produce the same ETag.
		$this->assertNotSame( $htmlETag, $sourceHtmlETag );
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

	private function assertResponseData( RevisionRecord $rev, array $data ): void {
		$this->assertSame( $rev->getId(), $data['id'] );
		$this->assertSame( $rev->getSize(), $data['size'] );
		$this->assertSame( $rev->isMinor(), $data['minor'] );
		$this->assertSame(
			wfTimestampOrNull( TS::ISO_8601, $rev->getTimestamp() ),
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
