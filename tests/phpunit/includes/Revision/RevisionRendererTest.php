<?php

namespace MediaWiki\Tests\Revision;

use Content;
use LogicException;
use MediaWiki\CommentStore\CommentStoreComment;
use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\Content\Renderer\ContentRenderer;
use MediaWiki\Content\WikitextContent;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Page\PageReference;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Revision\MainSlotRoleHandler;
use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionRenderer;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Revision\SlotRoleRegistry;
use MediaWiki\Storage\NameTableStore;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use MediaWiki\Title\TitleFactory;
use MediaWiki\User\UserIdentityValue;
use MediaWikiIntegrationTestCase;
use ParserOptions;
use PHPUnit\Framework\MockObject\MockObject;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\ILoadBalancer;
use Wikimedia\Rdbms\SelectQueryBuilder;

/**
 * @covers \MediaWiki\Revision\RevisionRenderer
 * @group Database
 */
class RevisionRendererTest extends MediaWikiIntegrationTestCase {
	use MockAuthorityTrait;

	private $fakePage;

	protected function setUp(): void {
		parent::setUp();
		$this->fakePage = PageIdentityValue::localIdentity( 7, NS_MAIN, __CLASS__ );
	}

	/**
	 * @param IDatabase&MockObject $db
	 * @param int $maxRev
	 * @return IDatabase&MockObject
	 */
	private function mockDatabaseConnection( $db, $maxRev = 100 ) {
		$db->method( 'selectField' )
			->willReturnCallback(
				function ( $table, $fields, $cond ) use ( $maxRev ) {
					return $this->selectFieldCallback(
						$table,
						$fields,
						$cond,
						$maxRev
					);
				}
			);
		$db->method( 'newSelectQueryBuilder' )->willReturnCallback( static fn () => new SelectQueryBuilder( $db ) );

		return $db;
	}

	/**
	 * @param int $maxRev
	 * @param bool $usePrimary
	 * @param ContentRenderer|null $contentRenderer
	 * @return RevisionRenderer
	 */
	private function newRevisionRenderer(
		$maxRev = 100,
		$usePrimary = false,
		$contentRenderer = null
	) {
		$dbIndex = $usePrimary ? DB_PRIMARY : DB_REPLICA;
		$cr = $contentRenderer ?? $this->getServiceContainer()->getContentRenderer();

		/** @var ILoadBalancer|MockObject $lb */
		$lb = $this->createMock( ILoadBalancer::class );
		$lb->method( 'getConnection' )
			->with( $dbIndex )
			->willReturn( $this->mockDatabaseConnection( $this->createMock( IDatabase::class ), $maxRev ) );

		/** @var NameTableStore|MockObject $slotRoles */
		$slotRoles = $this->createMock( NameTableStore::class );
		$slotRoles->method( 'getMap' )
			->willReturn( [] );

		$roleReg = new SlotRoleRegistry( $slotRoles );
		$roleReg->defineRole( SlotRecord::MAIN, function () {
			return new MainSlotRoleHandler(
				[],
				$this->createMock( IContentHandlerFactory::class ),
				$this->createMock( HookContainer::class ),
				$this->createMock( TitleFactory::class )
			);
		} );
		$roleReg->defineRoleWithModel( 'aux', CONTENT_MODEL_WIKITEXT );

		return new RevisionRenderer( $lb, $roleReg, $cr );
	}

	private function selectFieldCallback( $table, $fields, $cond, $maxRev ) {
		if ( [ $table, $fields, $cond ] === [ [ 'revision' ], 'MAX(rev_id)', [] ] ) {
			return $maxRev;
		}

		$this->fail( 'Unexpected call to selectField' );
		throw new LogicException( 'Ooops' ); // Can't happen, make analyzer happy
	}

	public function testGetRenderedRevision_new() {
		$renderer = $this->newRevisionRenderer( 100 );

		$rev = new MutableRevisionRecord( $this->fakePage );
		$rev->setUser( new UserIdentityValue( 9, 'Frank' ) );
		$rev->setTimestamp( '20180101000003' );
		$rev->setComment( CommentStoreComment::newUnsavedComment( '' ) );

		$text = "";
		$text .= "* page:{{PAGENAME}}\n";
		$text .= "* rev:{{REVISIONID}}\n";
		$text .= "* user:{{REVISIONUSER}}\n";
		$text .= "* time:{{REVISIONTIMESTAMP}}\n";
		$text .= "* [[Link It]]\n";

		$rev->setContent( SlotRecord::MAIN, new WikitextContent( $text ) );

		$options = ParserOptions::newFromAnon();
		$rr = $renderer->getRenderedRevision( $rev, $options );

		$this->assertFalse( $rr->isContentDeleted(), 'isContentDeleted' );

		$this->assertSame( $rev, $rr->getRevision() );
		$this->assertSame( $options, $rr->getOptions() );

		$html = $rr->getRevisionParserOutput()->getText();

		$this->assertStringContainsString( 'page:' . __CLASS__, $html );
		$this->assertStringContainsString( 'rev:101', $html ); // from speculativeRevIdCallback
		$this->assertStringContainsString( 'user:Frank', $html );
		$this->assertStringContainsString( 'time:20180101000003', $html );

		$this->assertSame( $html, $rr->getSlotParserOutput( SlotRecord::MAIN )->getText() );
	}

	public function testGetRenderedRevision_current() {
		$renderer = $this->newRevisionRenderer( 100 );

		$rev = new MutableRevisionRecord( $this->fakePage );
		$rev->setId( 21 ); // current!
		$rev->setUser( new UserIdentityValue( 9, 'Frank' ) );
		$rev->setTimestamp( '20180101000003' );
		$rev->setComment( CommentStoreComment::newUnsavedComment( '' ) );

		$text = "";
		$text .= "* page:{{PAGENAME}}\n";
		$text .= "* rev:{{REVISIONID}}\n";
		$text .= "* user:{{REVISIONUSER}}\n";
		$text .= "* time:{{REVISIONTIMESTAMP}}\n";

		$rev->setContent( SlotRecord::MAIN, new WikitextContent( $text ) );

		$options = ParserOptions::newFromAnon();
		$rr = $renderer->getRenderedRevision( $rev, $options );

		$this->assertFalse( $rr->isContentDeleted(), 'isContentDeleted' );

		$this->assertSame( $rev, $rr->getRevision() );
		$this->assertSame( $options, $rr->getOptions() );

		$html = $rr->getRevisionParserOutput()->getText();

		$this->assertStringContainsString( 'page:' . __CLASS__, $html );
		$this->assertStringContainsString( 'rev:21', $html );
		$this->assertStringContainsString( 'user:Frank', $html );
		$this->assertStringContainsString( 'time:20180101000003', $html );

		$this->assertSame( $html, $rr->getSlotParserOutput( SlotRecord::MAIN )->getText() );
	}

	public function testGetRenderedRevision_master() {
		$renderer = $this->newRevisionRenderer( 100, true ); // use master

		$rev = new MutableRevisionRecord( $this->fakePage );
		$rev->setId( 21 ); // current!
		$rev->setUser( new UserIdentityValue( 9, 'Frank' ) );
		$rev->setTimestamp( '20180101000003' );
		$rev->setComment( CommentStoreComment::newUnsavedComment( '' ) );

		$text = "";
		$text .= "* page:{{PAGENAME}}\n";
		$text .= "* rev:{{REVISIONID}}\n";
		$text .= "* user:{{REVISIONUSER}}\n";
		$text .= "* time:{{REVISIONTIMESTAMP}}\n";

		$rev->setContent( SlotRecord::MAIN, new WikitextContent( $text ) );

		$options = ParserOptions::newFromAnon();
		$rr = $renderer->getRenderedRevision( $rev, $options, null, [ 'use-master' => true ] );

		$this->assertFalse( $rr->isContentDeleted(), 'isContentDeleted' );

		$html = $rr->getRevisionParserOutput()->getText();

		$this->assertStringContainsString( 'rev:21', $html );

		$this->assertSame( $html, $rr->getSlotParserOutput( SlotRecord::MAIN )->getText() );
	}

	public function testGetRenderedRevision_known() {
		$renderer = $this->newRevisionRenderer( 100, true ); // use master

		$rev = new MutableRevisionRecord( $this->fakePage );
		$rev->setId( 21 ); // current!
		$rev->setUser( new UserIdentityValue( 9, 'Frank' ) );
		$rev->setTimestamp( '20180101000003' );
		$rev->setComment( CommentStoreComment::newUnsavedComment( '' ) );

		$text = "uncached text";
		$rev->setContent( SlotRecord::MAIN, new WikitextContent( $text ) );

		$output = new ParserOutput( 'cached text' );

		$options = ParserOptions::newFromAnon();
		$rr = $renderer->getRenderedRevision(
			$rev,
			$options,
			null,
			[ 'known-revision-output' => $output ]
		);

		$this->assertSame( $output, $rr->getRevisionParserOutput() );
		$this->assertSame( 'cached text', $rr->getRevisionParserOutput()->getText() );
		$this->assertSame( 'cached text', $rr->getSlotParserOutput( SlotRecord::MAIN )->getText() );
	}

	public function testGetRenderedRevision_old() {
		$renderer = $this->newRevisionRenderer( 100 );

		$rev = new MutableRevisionRecord( $this->fakePage );
		$rev->setId( 11 ); // old!
		$rev->setUser( new UserIdentityValue( 9, 'Frank' ) );
		$rev->setTimestamp( '20180101000003' );
		$rev->setComment( CommentStoreComment::newUnsavedComment( '' ) );

		$text = "";
		$text .= "* page:{{PAGENAME}}\n";
		$text .= "* rev:{{REVISIONID}}\n";
		$text .= "* user:{{REVISIONUSER}}\n";
		$text .= "* time:{{REVISIONTIMESTAMP}}\n";

		$rev->setContent( SlotRecord::MAIN, new WikitextContent( $text ) );

		$options = ParserOptions::newFromAnon();
		$rr = $renderer->getRenderedRevision( $rev, $options );

		$this->assertFalse( $rr->isContentDeleted(), 'isContentDeleted' );

		$this->assertSame( $rev, $rr->getRevision() );
		$this->assertSame( $options, $rr->getOptions() );

		$html = $rr->getRevisionParserOutput()->getText();

		$this->assertStringContainsString( 'page:' . __CLASS__, $html );
		$this->assertStringContainsString( 'rev:11', $html );
		$this->assertStringContainsString( 'user:Frank', $html );
		$this->assertStringContainsString( 'time:20180101000003', $html );

		$this->assertSame( $html, $rr->getSlotParserOutput( SlotRecord::MAIN )->getText() );
	}

	public function testGetRenderedRevision_suppressed() {
		$renderer = $this->newRevisionRenderer( 100 );

		$rev = new MutableRevisionRecord( $this->fakePage );
		$rev->setId( 11 ); // old!
		$rev->setVisibility( RevisionRecord::DELETED_TEXT ); // suppressed!
		$rev->setUser( new UserIdentityValue( 9, 'Frank' ) );
		$rev->setTimestamp( '20180101000003' );
		$rev->setComment( CommentStoreComment::newUnsavedComment( '' ) );

		$text = "";
		$text .= "* page:{{PAGENAME}}\n";
		$text .= "* rev:{{REVISIONID}}\n";
		$text .= "* user:{{REVISIONUSER}}\n";
		$text .= "* time:{{REVISIONTIMESTAMP}}\n";

		$rev->setContent( SlotRecord::MAIN, new WikitextContent( $text ) );

		$options = ParserOptions::newFromAnon();
		$rr = $renderer->getRenderedRevision( $rev, $options );

		$this->assertNull( $rr, 'getRenderedRevision' );
	}

	public function testGetRenderedRevision_privileged() {
		$renderer = $this->newRevisionRenderer( 100 );

		$rev = new MutableRevisionRecord( $this->fakePage );
		$rev->setId( 11 ); // old!
		$rev->setVisibility( RevisionRecord::DELETED_TEXT ); // suppressed!
		$rev->setUser( new UserIdentityValue( 9, 'Frank' ) );
		$rev->setTimestamp( '20180101000003' );
		$rev->setComment( CommentStoreComment::newUnsavedComment( '' ) );

		$text = "";
		$text .= "* page:{{PAGENAME}}\n";
		$text .= "* rev:{{REVISIONID}}\n";
		$text .= "* user:{{REVISIONUSER}}\n";
		$text .= "* time:{{REVISIONTIMESTAMP}}\n";

		$rev->setContent( SlotRecord::MAIN, new WikitextContent( $text ) );

		$options = ParserOptions::newFromAnon();
		$sysop = $this->mockRegisteredUltimateAuthority();
		$rr = $renderer->getRenderedRevision( $rev, $options, $sysop );

		$this->assertNotNull( $rr, 'getRenderedRevision' );
		$this->assertTrue( $rr->isContentDeleted(), 'isContentDeleted' );

		$this->assertSame( $rev, $rr->getRevision() );
		$this->assertSame( $options, $rr->getOptions() );

		$html = $rr->getRevisionParserOutput()->getText();

		// Suppressed content should be visible for sysops
		$this->assertStringContainsString( 'page:' . __CLASS__, $html );
		$this->assertStringContainsString( 'rev:11', $html );
		$this->assertStringContainsString( 'user:Frank', $html );
		$this->assertStringContainsString( 'time:20180101000003', $html );

		$this->assertSame( $html, $rr->getSlotParserOutput( SlotRecord::MAIN )->getText() );
	}

	public function testGetRenderedRevision_raw() {
		$renderer = $this->newRevisionRenderer( 100 );

		$rev = new MutableRevisionRecord( $this->fakePage );
		$rev->setId( 11 ); // old!
		$rev->setVisibility( RevisionRecord::DELETED_TEXT ); // suppressed!
		$rev->setUser( new UserIdentityValue( 9, 'Frank' ) );
		$rev->setTimestamp( '20180101000003' );
		$rev->setComment( CommentStoreComment::newUnsavedComment( '' ) );

		$text = "";
		$text .= "* page:{{PAGENAME}}\n";
		$text .= "* rev:{{REVISIONID}}\n";
		$text .= "* user:{{REVISIONUSER}}\n";
		$text .= "* time:{{REVISIONTIMESTAMP}}\n";

		$rev->setContent( SlotRecord::MAIN, new WikitextContent( $text ) );

		$options = ParserOptions::newFromAnon();
		$rr = $renderer->getRenderedRevision(
			$rev,
			$options,
			null,
			[ 'audience' => RevisionRecord::RAW ]
		);

		$this->assertTrue( $rr->isContentDeleted(), 'isContentDeleted' );

		$this->assertSame( $rev, $rr->getRevision() );
		$this->assertSame( $options, $rr->getOptions() );

		$parserOutput = $rr->getRevisionParserOutput();
		// Assert parser output recorded timestamp and parsed rev_id
		$this->assertSame( $rev->getId(), $parserOutput->getCacheRevisionId() );
		$this->assertSame( $rev->getTimestamp(), $parserOutput->getRevisionTimestamp() );

		$html = $parserOutput->getText();

		// Suppressed content should be visible in raw mode
		$this->assertStringContainsString( 'page:' . __CLASS__, $html );
		$this->assertStringContainsString( 'rev:11', $html );
		$this->assertStringContainsString( 'user:Frank', $html );
		$this->assertStringContainsString( 'time:20180101000003', $html );

		$this->assertSame( $html, $rr->getSlotParserOutput( SlotRecord::MAIN )->getText() );
	}

	public function testGetRenderedRevision_multi() {
		$renderer = $this->newRevisionRenderer();

		$rev = new MutableRevisionRecord( $this->fakePage );
		$rev->setUser( new UserIdentityValue( 9, 'Frank' ) );
		$rev->setTimestamp( '20180101000003' );
		$rev->setComment( CommentStoreComment::newUnsavedComment( '' ) );

		$rev->setContent( SlotRecord::MAIN, new WikitextContent( '[[Kittens]]' ) );
		$rev->setContent( 'aux', new WikitextContent( '[[Goats]]' ) );

		$rr = $renderer->getRenderedRevision( $rev );

		$combinedOutput = $rr->getRevisionParserOutput();
		$mainOutput = $rr->getSlotParserOutput( SlotRecord::MAIN );
		$auxOutput = $rr->getSlotParserOutput( 'aux' );

		$combinedHtml = $combinedOutput->getText();
		$mainHtml = $mainOutput->getText();

		$this->assertNotSame( $combinedHtml, $mainHtml );

		$auxHtml = $auxOutput->getText();

		$this->assertStringContainsString( 'Kittens', $mainHtml );
		$this->assertStringContainsString( 'Goats', $auxHtml );
		$this->assertStringNotContainsString( 'Goats', $mainHtml );
		$this->assertStringNotContainsString( 'Kittens', $auxHtml );
		$this->assertStringContainsString( 'Kittens', $combinedHtml );
		$this->assertStringContainsString( 'Goats', $combinedHtml );
		$this->assertStringContainsString( '>aux<', $combinedHtml, 'slot header' );
		$this->assertStringNotContainsString(
			'<mw:slotheader',
			$combinedHtml,
			'slot header placeholder'
		);

		// make sure output wrapping works right
		$this->assertStringContainsString( 'class="mw-content-ltr mw-parser-output"', $mainHtml );
		$this->assertStringContainsString( 'class="mw-content-ltr mw-parser-output"', $auxHtml );
		$this->assertStringContainsString( 'class="mw-content-ltr mw-parser-output"', $combinedHtml );

		// there should be only one wrapper div
		$this->assertSame( 1, preg_match_all( '#class="[^"]*mw-parser-output"#', $combinedHtml ) );
		$this->assertStringNotContainsString( 'mw-parser-output"', $combinedOutput->getRawText() );

		$combinedLinks = $combinedOutput->getLinks();
		$mainLinks = $mainOutput->getLinks();
		$auxLinks = $auxOutput->getLinks();
		$this->assertTrue( isset( $combinedLinks[NS_MAIN]['Kittens'] ), 'links from main slot' );
		$this->assertTrue( isset( $combinedLinks[NS_MAIN]['Goats'] ), 'links from aux slot' );
		$this->assertFalse( isset( $mainLinks[NS_MAIN]['Goats'] ), 'no aux links in main' );
		$this->assertFalse( isset( $auxLinks[NS_MAIN]['Kittens'] ), 'no main links in aux' );

		// Same tests with Parsoid
		// T351026: We should get only main slot output in the combined output.
		// T351113 will have to update this test.
		$options = ParserOptions::newFromAnon();
		$options->setUseParsoid();
		$rr = $renderer->getRenderedRevision( $rev, $options );

		$combinedOutput = $rr->getRevisionParserOutput();
		$mainOutput = $rr->getSlotParserOutput( SlotRecord::MAIN );
		$combinedHtml = $combinedOutput->getText();
		$this->assertSame( $combinedHtml, $mainOutput->getText() );
		$this->assertSame( $combinedOutput->getLinks(), $mainOutput->getLinks() );
		$this->assertStringContainsString( 'class="mw-content-ltr mw-parser-output"', $combinedHtml );
		$this->assertStringContainsString( 'Kittens', $combinedHtml );
		$this->assertStringNotContainsString( 'Goats', $combinedHtml );
	}

	public function testGetRenderedRevision_noHtml() {
		$content = new WikitextContent( 'Whatever' );

		/** @var MockObject|ContentRenderer $mockContentRenderer */
		$mockContentRenderer = $this->getMockBuilder( ContentRenderer::class )
			->onlyMethods( [ 'getParserOutput' ] )
			->disableOriginalConstructor()
			->getMock();
		$mockContentRenderer->method( 'getParserOutput' )
			->willReturnCallback( function ( Content $content, PageReference $page, $revId = null,
				ParserOptions $options = null, $generateHtml = true
			) {
				if ( !$generateHtml ) {
					return new ParserOutput( null );
				} else {
					$this->fail( 'Should not be called with $generateHtml == true' );
					return null; // never happens, make analyzer happy
				}
			} );

		$renderer = $this->newRevisionRenderer( 100, false, $mockContentRenderer );

		$rev = new MutableRevisionRecord( $this->fakePage );
		$rev->setContent( SlotRecord::MAIN, $content );
		$rev->setContent( 'aux', $content );

		// NOTE: we are testing the private combineSlotOutput() callback here.
		$rr = $renderer->getRenderedRevision( $rev );

		$output = $rr->getSlotParserOutput( SlotRecord::MAIN, [ 'generate-html' => false ] );
		$this->assertFalse( $output->hasText(), 'hasText' );

		$output = $rr->getRevisionParserOutput( [ 'generate-html' => false ] );
		$this->assertFalse( $output->hasText(), 'hasText' );
	}

}
