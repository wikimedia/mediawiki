<?php

namespace MediaWiki\Tests\Revision;

use LogicException;
use MediaWiki\CommentStore\CommentStoreComment;
use MediaWiki\Content\Content;
use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\Content\Renderer\ContentRenderer;
use MediaWiki\Content\WikitextContent;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Page\PageReference;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Parser\ParserOutputLinkTypes;
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

	/** @var PageIdentity */
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

		$text = "";
		$text .= "* page:{{PAGENAME}}\n";
		$text .= "* rev:{{REVISIONID}}\n";
		$text .= "* user:{{REVISIONUSER}}\n";
		$text .= "* time:{{REVISIONTIMESTAMP}}\n";
		$text .= "* [[Link It]]\n";

		$rev = MutableRevisionRecord::newFromContent( $this->fakePage, new WikitextContent( $text ) )
			->setUser( new UserIdentityValue( 9, 'Frank' ) )
			->setTimestamp( '20180101000003' )
			->setComment( CommentStoreComment::newUnsavedComment( '' ) );

		$options = ParserOptions::newFromAnon();
		$rr = $renderer->getRenderedRevision( $rev, $options );

		$this->assertFalse( $rr->isContentDeleted(), 'isContentDeleted' );

		$this->assertSame( $rev, $rr->getRevision() );
		$this->assertSame( $options, $rr->getOptions() );

		$html = $rr->getRevisionParserOutput()->getContentHolderText();

		$this->assertStringContainsString( 'page:' . __CLASS__, $html );
		$this->assertStringContainsString( 'rev:101', $html ); // from speculativeRevIdCallback
		$this->assertStringContainsString( 'user:Frank', $html );
		$this->assertStringContainsString( 'time:20180101000003', $html );

		$this->assertSame( $html, $rr->getSlotParserOutput( SlotRecord::MAIN )->getContentHolderText() );
	}

	public function testGetRenderedRevision_current() {
		$renderer = $this->newRevisionRenderer( 100 );

		$text = "";
		$text .= "* page:{{PAGENAME}}\n";
		$text .= "* rev:{{REVISIONID}}\n";
		$text .= "* user:{{REVISIONUSER}}\n";
		$text .= "* time:{{REVISIONTIMESTAMP}}\n";

		$rev = MutableRevisionRecord::newFromContent( $this->fakePage, new WikitextContent( $text ) )
			->setId( 21 ) // current!
			->setUser( new UserIdentityValue( 9, 'Frank' ) )
			->setTimestamp( '20180101000003' )
			->setComment( CommentStoreComment::newUnsavedComment( '' ) );

		$options = ParserOptions::newFromAnon();
		$rr = $renderer->getRenderedRevision( $rev, $options );

		$this->assertFalse( $rr->isContentDeleted(), 'isContentDeleted' );

		$this->assertSame( $rev, $rr->getRevision() );
		$this->assertSame( $options, $rr->getOptions() );

		$html = $rr->getRevisionParserOutput()->getContentHolderText();

		$this->assertStringContainsString( 'page:' . __CLASS__, $html );
		$this->assertStringContainsString( 'rev:21', $html );
		$this->assertStringContainsString( 'user:Frank', $html );
		$this->assertStringContainsString( 'time:20180101000003', $html );

		$this->assertSame( $html, $rr->getSlotParserOutput( SlotRecord::MAIN )->getContentHolderText() );
	}

	public function testGetRenderedRevision_master() {
		$renderer = $this->newRevisionRenderer( 100, true ); // use master

		$text = "";
		$text .= "* page:{{PAGENAME}}\n";
		$text .= "* rev:{{REVISIONID}}\n";
		$text .= "* user:{{REVISIONUSER}}\n";
		$text .= "* time:{{REVISIONTIMESTAMP}}\n";

		$rev = MutableRevisionRecord::newFromContent( $this->fakePage, new WikitextContent( $text ) )
			->setId( 21 ) // current!
			->setUser( new UserIdentityValue( 9, 'Frank' ) )
			->setTimestamp( '20180101000003' )
			->setComment( CommentStoreComment::newUnsavedComment( '' ) );

		$options = ParserOptions::newFromAnon();
		$rr = $renderer->getRenderedRevision( $rev, $options, null, [ 'use-master' => true ] );

		$this->assertFalse( $rr->isContentDeleted(), 'isContentDeleted' );

		$html = $rr->getRevisionParserOutput()->getContentHolderText();

		$this->assertStringContainsString( 'rev:21', $html );

		$this->assertSame( $html, $rr->getSlotParserOutput( SlotRecord::MAIN )->getContentHolderText() );
	}

	public function testGetRenderedRevision_known() {
		$renderer = $this->newRevisionRenderer( 100, true ); // use master

		$text = "uncached text";
		$rev = MutableRevisionRecord::newFromContent( $this->fakePage, new WikitextContent( $text ) )
			->setId( 21 ) // current!
			->setUser( new UserIdentityValue( 9, 'Frank' ) )
			->setTimestamp( '20180101000003' )
			->setComment( CommentStoreComment::newUnsavedComment( '' ) );

		$output = new ParserOutput( 'cached text' );

		$options = ParserOptions::newFromAnon();
		$rr = $renderer->getRenderedRevision(
			$rev,
			$options,
			null,
			[ 'known-revision-output' => $output ]
		);

		$this->assertSame( $output, $rr->getRevisionParserOutput() );
		$this->assertSame( 'cached text', $rr->getRevisionParserOutput()->getContentHolderText() );
		$this->assertSame( 'cached text', $rr->getSlotParserOutput( SlotRecord::MAIN )->getContentHolderText() );
	}

	public function testGetRenderedRevision_old() {
		$renderer = $this->newRevisionRenderer( 100 );

		$text = "";
		$text .= "* page:{{PAGENAME}}\n";
		$text .= "* rev:{{REVISIONID}}\n";
		$text .= "* user:{{REVISIONUSER}}\n";
		$text .= "* time:{{REVISIONTIMESTAMP}}\n";

		$rev = MutableRevisionRecord::newFromContent( $this->fakePage, new WikitextContent( $text ) )
			->setId( 11 ) // old!
			->setUser( new UserIdentityValue( 9, 'Frank' ) )
			->setTimestamp( '20180101000003' )
			->setComment( CommentStoreComment::newUnsavedComment( '' ) );

		$options = ParserOptions::newFromAnon();
		$rr = $renderer->getRenderedRevision( $rev, $options );

		$this->assertFalse( $rr->isContentDeleted(), 'isContentDeleted' );

		$this->assertSame( $rev, $rr->getRevision() );
		$this->assertSame( $options, $rr->getOptions() );

		$html = $rr->getRevisionParserOutput()->getContentHolderText();

		$this->assertStringContainsString( 'page:' . __CLASS__, $html );
		$this->assertStringContainsString( 'rev:11', $html );
		$this->assertStringContainsString( 'user:Frank', $html );
		$this->assertStringContainsString( 'time:20180101000003', $html );

		$this->assertSame( $html, $rr->getSlotParserOutput( SlotRecord::MAIN )->getContentHolderText() );
	}

	public function testGetRenderedRevision_suppressed() {
		$renderer = $this->newRevisionRenderer( 100 );

		$text = "";
		$text .= "* page:{{PAGENAME}}\n";
		$text .= "* rev:{{REVISIONID}}\n";
		$text .= "* user:{{REVISIONUSER}}\n";
		$text .= "* time:{{REVISIONTIMESTAMP}}\n";

		$rev = MutableRevisionRecord::newFromContent( $this->fakePage, new WikitextContent( $text ) )
			->setId( 11 ) // old!
			->setVisibility( RevisionRecord::DELETED_TEXT ) // suppressed!
			->setUser( new UserIdentityValue( 9, 'Frank' ) )
			->setTimestamp( '20180101000003' )
			->setComment( CommentStoreComment::newUnsavedComment( '' ) );

		$options = ParserOptions::newFromAnon();
		$rr = $renderer->getRenderedRevision( $rev, $options );

		$this->assertNull( $rr, 'getRenderedRevision' );
	}

	public function testGetRenderedRevision_privileged() {
		$renderer = $this->newRevisionRenderer( 100 );

		$text = "";
		$text .= "* page:{{PAGENAME}}\n";
		$text .= "* rev:{{REVISIONID}}\n";
		$text .= "* user:{{REVISIONUSER}}\n";
		$text .= "* time:{{REVISIONTIMESTAMP}}\n";

		$rev = MutableRevisionRecord::newFromContent( $this->fakePage, new WikitextContent( $text ) )
			->setId( 11 ) // old!
			->setVisibility( RevisionRecord::DELETED_TEXT ) // suppressed!
			->setUser( new UserIdentityValue( 9, 'Frank' ) )
			->setTimestamp( '20180101000003' )
			->setComment( CommentStoreComment::newUnsavedComment( '' ) );

		$options = ParserOptions::newFromAnon();
		$sysop = $this->mockRegisteredUltimateAuthority();
		$rr = $renderer->getRenderedRevision( $rev, $options, $sysop );

		$this->assertNotNull( $rr, 'getRenderedRevision' );
		$this->assertTrue( $rr->isContentDeleted(), 'isContentDeleted' );

		$this->assertSame( $rev, $rr->getRevision() );
		$this->assertSame( $options, $rr->getOptions() );

		$html = $rr->getRevisionParserOutput()->getContentHolderText();

		// Suppressed content should be visible for sysops
		$this->assertStringContainsString( 'page:' . __CLASS__, $html );
		$this->assertStringContainsString( 'rev:11', $html );
		$this->assertStringContainsString( 'user:Frank', $html );
		$this->assertStringContainsString( 'time:20180101000003', $html );

		$this->assertSame( $html, $rr->getSlotParserOutput( SlotRecord::MAIN )->getContentHolderText() );
	}

	public function testGetRenderedRevision_raw() {
		$renderer = $this->newRevisionRenderer( 100 );

		$text = "";
		$text .= "* page:{{PAGENAME}}\n";
		$text .= "* rev:{{REVISIONID}}\n";
		$text .= "* user:{{REVISIONUSER}}\n";
		$text .= "* time:{{REVISIONTIMESTAMP}}\n";

		$rev = MutableRevisionRecord::newFromContent( $this->fakePage, new WikitextContent( $text ) )
			->setId( 11 ) // old!
			->setVisibility( RevisionRecord::DELETED_TEXT ) // suppressed!
			->setUser( new UserIdentityValue( 9, 'Frank' ) )
			->setTimestamp( '20180101000003' )
			->setComment( CommentStoreComment::newUnsavedComment( '' ) );

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

		$html = $parserOutput->getContentHolderText();

		// Suppressed content should be visible in raw mode
		$this->assertStringContainsString( 'page:' . __CLASS__, $html );
		$this->assertStringContainsString( 'rev:11', $html );
		$this->assertStringContainsString( 'user:Frank', $html );
		$this->assertStringContainsString( 'time:20180101000003', $html );

		$this->assertSame( $html, $rr->getSlotParserOutput( SlotRecord::MAIN )->getContentHolderText() );
	}

	public function testGetRenderedRevision_multi() {
		$renderer = $this->newRevisionRenderer();

		$rev = MutableRevisionRecord::newFromContent( $this->fakePage, new WikitextContent( '[[Kittens]]' ) )
			->setUser( new UserIdentityValue( 9, 'Frank' ) )
			->setTimestamp( '20180101000003' )
			->setComment( CommentStoreComment::newUnsavedComment( '' ) )
			->setContent( 'aux', new WikitextContent( '[[Goats]]' ) );

		$options = ParserOptions::newFromAnon();
		$rr = $renderer->getRenderedRevision( $rev, $options );

		$combinedOutput = $rr->getRevisionParserOutput();
		$mainOutput = $rr->getSlotParserOutput( SlotRecord::MAIN );
		$auxOutput = $rr->getSlotParserOutput( 'aux' );

		$pipeline = MediaWikiServices::getInstance()->getDefaultOutputPipeline();
		$combinedHtml = $pipeline->run( $combinedOutput, $options, [] )->getContentHolderText();
		$mainHtml = $pipeline->run( $mainOutput, $options, [] )->getContentHolderText();

		$this->assertNotSame( $combinedHtml, $mainHtml );

		$auxHtml = $pipeline->run( $auxOutput, $options, [] )->getContentHolderText();

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
		$this->assertStringNotContainsString( 'mw-parser-output"', $combinedOutput->getContentHolderText() );

		$this->assertTrue( self::linksContain( $combinedOutput, NS_MAIN, 'Kittens' ), 'links from main slot' );
		$this->assertTrue( self::linksContain( $combinedOutput, NS_MAIN, 'Goats' ), 'links from aux slot' );
		$this->assertFalse( self::linksContain( $mainOutput, NS_MAIN, 'Goats' ), 'no aux links in main' );
		$this->assertFalse( self::linksContain( $auxOutput, NS_MAIN, 'Kittens' ), 'no main links in aux' );

		// Same tests with Parsoid
		// T351026: We should get only main slot output in the combined output.
		// T351113 will have to update this test.
		$options = ParserOptions::newFromAnon();
		$options->setUseParsoid();
		$rr = $renderer->getRenderedRevision( $rev, $options );

		$combinedOutput = $rr->getRevisionParserOutput();
		$mainOutput = $rr->getSlotParserOutput( SlotRecord::MAIN );

		$combinedHtml = $pipeline->run( $combinedOutput, $options, [] )->getContentHolderText();
		$mainHtml = $pipeline->run( $mainOutput, $options, [] )->getContentHolderText();
		$this->assertSame( $combinedHtml, $mainHtml );
		$this->assertSame(
			$combinedOutput->getLinkList( ParserOutputLinkTypes::LOCAL ),
			$mainOutput->getLinkList( ParserOutputLinkTypes::LOCAL ) );
		$this->assertStringContainsString( 'class="mw-content-ltr mw-parser-output"', $mainHtml );
		$this->assertStringContainsString( 'Kittens', $combinedHtml );
		$this->assertStringNotContainsString( 'Goats', $combinedHtml );
	}

	protected static function linksContain( ParserOutput $parserOutput, int $ns, string $dbkey ) {
		return array_any(
			$parserOutput->getLinkList( ParserOutputLinkTypes::LOCAL, $ns ),
			static fn ( $item ) => $item['link']->getDBkey() === $dbkey
		);
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
				?ParserOptions $options = null, $hints = []
			) {
				if ( is_bool( $hints ) ) {
					$hints = [ 'generate-html' => $hints ];
				}
				$generateHtml = $hints['generate-html'] ?? true;
				$this->assertFalse( $generateHtml, 'Should not be called with $generateHtml == true' );
				return new ParserOutput( null );
			} );

		$renderer = $this->newRevisionRenderer( 100, false, $mockContentRenderer );

		$rev = MutableRevisionRecord::newFromContent( $this->fakePage, $content )
			->setContent( 'aux', $content );

		// NOTE: we are testing the private combineSlotOutput() callback here.
		$rr = $renderer->getRenderedRevision( $rev );

		$output = $rr->getSlotParserOutput( SlotRecord::MAIN, [ 'generate-html' => false ] );
		$this->assertFalse( $output->hasText(), 'hasText' );

		$output = $rr->getRevisionParserOutput( [ 'generate-html' => false ] );
		$this->assertFalse( $output->hasText(), 'hasText' );
	}

}
