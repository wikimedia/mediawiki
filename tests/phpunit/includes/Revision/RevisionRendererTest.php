<?php

namespace MediaWiki\Tests\Revision;

use CommentStoreComment;
use Content;
use Language;
use LogicException;
use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Revision\MainSlotRoleHandler;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionRenderer;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Revision\SlotRoleRegistry;
use MediaWiki\Storage\NameTableStore;
use MediaWikiTestCase;
use MediaWiki\User\UserIdentityValue;
use ParserOptions;
use ParserOutput;
use PHPUnit\Framework\MockObject\MockObject;
use Title;
use User;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\ILoadBalancer;
use WikitextContent;

/**
 * @covers \MediaWiki\Revision\RevisionRenderer
 */
class RevisionRendererTest extends MediaWikiTestCase {

	/**
	 * @param int $articleId
	 * @param int $revisionId
	 * @return Title
	 */
	private function getMockTitle( $articleId, $revisionId ) {
		/** @var Title|MockObject $mock */
		$mock = $this->getMockBuilder( Title::class )
			->disableOriginalConstructor()
			->getMock();
		$mock->expects( $this->any() )
			->method( 'getNamespace' )
			->will( $this->returnValue( NS_MAIN ) );
		$mock->expects( $this->any() )
			->method( 'getText' )
			->will( $this->returnValue( __CLASS__ ) );
		$mock->expects( $this->any() )
			->method( 'getPrefixedText' )
			->will( $this->returnValue( __CLASS__ ) );
		$mock->expects( $this->any() )
			->method( 'getDBkey' )
			->will( $this->returnValue( __CLASS__ ) );
		$mock->expects( $this->any() )
			->method( 'getArticleID' )
			->will( $this->returnValue( $articleId ) );
		$mock->expects( $this->any() )
			->method( 'getLatestRevId' )
			->will( $this->returnValue( $revisionId ) );
		$mock->expects( $this->any() )
			->method( 'getContentModel' )
			->will( $this->returnValue( CONTENT_MODEL_WIKITEXT ) );
		$mock->expects( $this->any() )
			->method( 'getPageLanguage' )
			->will( $this->returnValue( Language::factory( 'en' ) ) );
		$mock->expects( $this->any() )
			->method( 'isContentPage' )
			->will( $this->returnValue( true ) );
		$mock->expects( $this->any() )
			->method( 'equals' )
			->willReturnCallback(
				function ( Title $other ) use ( $mock ) {
					return $mock->getArticleID() === $other->getArticleID();
				}
			);
		$mock->expects( $this->any() )
			->method( 'userCan' )
			->willReturnCallback(
				function ( $perm, User $user ) use ( $mock ) {
					return $user->isAllowed( $perm );
				}
			);

		return $mock;
	}

	/**
	 * @param int $maxRev
	 * @param int $linkCount
	 *
	 * @return IDatabase
	 */
	private function getMockDatabaseConnection( $maxRev = 100, $linkCount = 0 ) {
		/** @var IDatabase|MockObject $db */
		$db = $this->getMock( IDatabase::class );
		$db->method( 'selectField' )
			->willReturnCallback(
				function ( $table, $fields, $cond ) use ( $maxRev, $linkCount ) {
					return $this->selectFieldCallback(
						$table,
						$fields,
						$cond,
						$maxRev,
						$linkCount
					);
				}
			);

		return $db;
	}

	/**
	 * @return RevisionRenderer
	 */
	private function newRevisionRenderer( $maxRev = 100, $useMaster = false ) {
		$dbIndex = $useMaster ? DB_MASTER : DB_REPLICA;

		$db = $this->getMockDatabaseConnection( $maxRev );

		/** @var ILoadBalancer|MockObject $lb */
		$lb = $this->getMock( ILoadBalancer::class );
		$lb->method( 'getConnection' )
			->with( $dbIndex )
			->willReturn( $db );
		$lb->method( 'getConnectionRef' )
			->with( $dbIndex )
			->willReturn( $db );
		$lb->method( 'getLazyConnectionRef' )
			->with( $dbIndex )
			->willReturn( $db );

		/** @var NameTableStore|MockObject $slotRoles */
		$slotRoles = $this->getMockBuilder( NameTableStore::class )
			->disableOriginalConstructor()
			->getMock();
		$slotRoles->method( 'getMap' )
			->willReturn( [] );

		$roleReg = new SlotRoleRegistry( $slotRoles );
		$roleReg->defineRole( 'main', function () {
			return new MainSlotRoleHandler( [] );
		} );
		$roleReg->defineRoleWithModel( 'aux', CONTENT_MODEL_WIKITEXT );

		return new RevisionRenderer( $lb, $roleReg );
	}

	private function selectFieldCallback( $table, $fields, $cond, $maxRev ) {
		if ( [ $table, $fields, $cond ] === [ 'revision', 'MAX(rev_id)', [] ] ) {
			return $maxRev;
		}

		$this->fail( 'Unexpected call to selectField' );
		throw new LogicException( 'Ooops' ); // Can't happen, make analyzer happy
	}

	public function testGetRenderedRevision_new() {
		$renderer = $this->newRevisionRenderer( 100 );
		$title = $this->getMockTitle( 7, 21 );

		$rev = new MutableRevisionRecord( $title );
		$rev->setUser( new UserIdentityValue( 9, 'Frank', 0 ) );
		$rev->setTimestamp( '20180101000003' );
		$rev->setComment( CommentStoreComment::newUnsavedComment( '' ) );

		$text = "";
		$text .= "* page:{{PAGENAME}}\n";
		$text .= "* rev:{{REVISIONID}}\n";
		$text .= "* user:{{REVISIONUSER}}\n";
		$text .= "* time:{{REVISIONTIMESTAMP}}\n";
		$text .= "* [[Link It]]\n";

		$rev->setContent( SlotRecord::MAIN, new WikitextContent( $text ) );

		$options = ParserOptions::newCanonical( 'canonical' );
		$rr = $renderer->getRenderedRevision( $rev, $options );

		$this->assertFalse( $rr->isContentDeleted(), 'isContentDeleted' );

		$this->assertSame( $rev, $rr->getRevision() );
		$this->assertSame( $options, $rr->getOptions() );

		$html = $rr->getRevisionParserOutput()->getText();

		$this->assertContains( 'page:' . __CLASS__, $html );
		$this->assertContains( 'rev:101', $html ); // from speculativeRevIdCallback
		$this->assertContains( 'user:Frank', $html );
		$this->assertContains( 'time:20180101000003', $html );

		$this->assertSame( $html, $rr->getSlotParserOutput( SlotRecord::MAIN )->getText() );
	}

	public function testGetRenderedRevision_current() {
		$renderer = $this->newRevisionRenderer( 100 );
		$title = $this->getMockTitle( 7, 21 );

		$rev = new MutableRevisionRecord( $title );
		$rev->setId( 21 ); // current!
		$rev->setUser( new UserIdentityValue( 9, 'Frank', 0 ) );
		$rev->setTimestamp( '20180101000003' );
		$rev->setComment( CommentStoreComment::newUnsavedComment( '' ) );

		$text = "";
		$text .= "* page:{{PAGENAME}}\n";
		$text .= "* rev:{{REVISIONID}}\n";
		$text .= "* user:{{REVISIONUSER}}\n";
		$text .= "* time:{{REVISIONTIMESTAMP}}\n";

		$rev->setContent( SlotRecord::MAIN, new WikitextContent( $text ) );

		$options = ParserOptions::newCanonical( 'canonical' );
		$rr = $renderer->getRenderedRevision( $rev, $options );

		$this->assertFalse( $rr->isContentDeleted(), 'isContentDeleted' );

		$this->assertSame( $rev, $rr->getRevision() );
		$this->assertSame( $options, $rr->getOptions() );

		$html = $rr->getRevisionParserOutput()->getText();

		$this->assertContains( 'page:' . __CLASS__, $html );
		$this->assertContains( 'rev:21', $html );
		$this->assertContains( 'user:Frank', $html );
		$this->assertContains( 'time:20180101000003', $html );

		$this->assertSame( $html, $rr->getSlotParserOutput( SlotRecord::MAIN )->getText() );
	}

	public function testGetRenderedRevision_master() {
		$renderer = $this->newRevisionRenderer( 100, true ); // use master
		$title = $this->getMockTitle( 7, 21 );

		$rev = new MutableRevisionRecord( $title );
		$rev->setId( 21 ); // current!
		$rev->setUser( new UserIdentityValue( 9, 'Frank', 0 ) );
		$rev->setTimestamp( '20180101000003' );
		$rev->setComment( CommentStoreComment::newUnsavedComment( '' ) );

		$text = "";
		$text .= "* page:{{PAGENAME}}\n";
		$text .= "* rev:{{REVISIONID}}\n";
		$text .= "* user:{{REVISIONUSER}}\n";
		$text .= "* time:{{REVISIONTIMESTAMP}}\n";

		$rev->setContent( SlotRecord::MAIN, new WikitextContent( $text ) );

		$options = ParserOptions::newCanonical( 'canonical' );
		$rr = $renderer->getRenderedRevision( $rev, $options, null, [ 'use-master' => true ] );

		$this->assertFalse( $rr->isContentDeleted(), 'isContentDeleted' );

		$html = $rr->getRevisionParserOutput()->getText();

		$this->assertContains( 'rev:21', $html );

		$this->assertSame( $html, $rr->getSlotParserOutput( SlotRecord::MAIN )->getText() );
	}

	public function testGetRenderedRevision_known() {
		$renderer = $this->newRevisionRenderer( 100, true ); // use master
		$title = $this->getMockTitle( 7, 21 );

		$rev = new MutableRevisionRecord( $title );
		$rev->setId( 21 ); // current!
		$rev->setUser( new UserIdentityValue( 9, 'Frank', 0 ) );
		$rev->setTimestamp( '20180101000003' );
		$rev->setComment( CommentStoreComment::newUnsavedComment( '' ) );

		$text = "uncached text";
		$rev->setContent( SlotRecord::MAIN, new WikitextContent( $text ) );

		$output = new ParserOutput( 'cached text' );

		$options = ParserOptions::newCanonical( 'canonical' );
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
		$title = $this->getMockTitle( 7, 21 );

		$rev = new MutableRevisionRecord( $title );
		$rev->setId( 11 ); // old!
		$rev->setUser( new UserIdentityValue( 9, 'Frank', 0 ) );
		$rev->setTimestamp( '20180101000003' );
		$rev->setComment( CommentStoreComment::newUnsavedComment( '' ) );

		$text = "";
		$text .= "* page:{{PAGENAME}}\n";
		$text .= "* rev:{{REVISIONID}}\n";
		$text .= "* user:{{REVISIONUSER}}\n";
		$text .= "* time:{{REVISIONTIMESTAMP}}\n";

		$rev->setContent( SlotRecord::MAIN, new WikitextContent( $text ) );

		$options = ParserOptions::newCanonical( 'canonical' );
		$rr = $renderer->getRenderedRevision( $rev, $options );

		$this->assertFalse( $rr->isContentDeleted(), 'isContentDeleted' );

		$this->assertSame( $rev, $rr->getRevision() );
		$this->assertSame( $options, $rr->getOptions() );

		$html = $rr->getRevisionParserOutput()->getText();

		$this->assertContains( 'page:' . __CLASS__, $html );
		$this->assertContains( 'rev:11', $html );
		$this->assertContains( 'user:Frank', $html );
		$this->assertContains( 'time:20180101000003', $html );

		$this->assertSame( $html, $rr->getSlotParserOutput( 'main' )->getText() );
	}

	public function testGetRenderedRevision_suppressed() {
		$renderer = $this->newRevisionRenderer( 100 );
		$title = $this->getMockTitle( 7, 21 );

		$rev = new MutableRevisionRecord( $title );
		$rev->setId( 11 ); // old!
		$rev->setVisibility( RevisionRecord::DELETED_TEXT ); // suppressed!
		$rev->setUser( new UserIdentityValue( 9, 'Frank', 0 ) );
		$rev->setTimestamp( '20180101000003' );
		$rev->setComment( CommentStoreComment::newUnsavedComment( '' ) );

		$text = "";
		$text .= "* page:{{PAGENAME}}\n";
		$text .= "* rev:{{REVISIONID}}\n";
		$text .= "* user:{{REVISIONUSER}}\n";
		$text .= "* time:{{REVISIONTIMESTAMP}}\n";

		$rev->setContent( SlotRecord::MAIN, new WikitextContent( $text ) );

		$options = ParserOptions::newCanonical( 'canonical' );
		$rr = $renderer->getRenderedRevision( $rev, $options );

		$this->assertNull( $rr, 'getRenderedRevision' );
	}

	public function testGetRenderedRevision_privileged() {
		$renderer = $this->newRevisionRenderer( 100 );
		$title = $this->getMockTitle( 7, 21 );

		$rev = new MutableRevisionRecord( $title );
		$rev->setId( 11 ); // old!
		$rev->setVisibility( RevisionRecord::DELETED_TEXT ); // suppressed!
		$rev->setUser( new UserIdentityValue( 9, 'Frank', 0 ) );
		$rev->setTimestamp( '20180101000003' );
		$rev->setComment( CommentStoreComment::newUnsavedComment( '' ) );

		$text = "";
		$text .= "* page:{{PAGENAME}}\n";
		$text .= "* rev:{{REVISIONID}}\n";
		$text .= "* user:{{REVISIONUSER}}\n";
		$text .= "* time:{{REVISIONTIMESTAMP}}\n";

		$rev->setContent( SlotRecord::MAIN, new WikitextContent( $text ) );

		$options = ParserOptions::newCanonical( 'canonical' );
		$sysop = $this->getTestUser( [ 'sysop' ] )->getUser(); // privileged!
		$rr = $renderer->getRenderedRevision( $rev, $options, $sysop );

		$this->assertTrue( $rr->isContentDeleted(), 'isContentDeleted' );

		$this->assertSame( $rev, $rr->getRevision() );
		$this->assertSame( $options, $rr->getOptions() );

		$html = $rr->getRevisionParserOutput()->getText();

		// Suppressed content should be visible for sysops
		$this->assertContains( 'page:' . __CLASS__, $html );
		$this->assertContains( 'rev:11', $html );
		$this->assertContains( 'user:Frank', $html );
		$this->assertContains( 'time:20180101000003', $html );

		$this->assertSame( $html, $rr->getSlotParserOutput( 'main' )->getText() );
	}

	public function testGetRenderedRevision_raw() {
		$renderer = $this->newRevisionRenderer( 100 );
		$title = $this->getMockTitle( 7, 21 );

		$rev = new MutableRevisionRecord( $title );
		$rev->setId( 11 ); // old!
		$rev->setVisibility( RevisionRecord::DELETED_TEXT ); // suppressed!
		$rev->setUser( new UserIdentityValue( 9, 'Frank', 0 ) );
		$rev->setTimestamp( '20180101000003' );
		$rev->setComment( CommentStoreComment::newUnsavedComment( '' ) );

		$text = "";
		$text .= "* page:{{PAGENAME}}\n";
		$text .= "* rev:{{REVISIONID}}\n";
		$text .= "* user:{{REVISIONUSER}}\n";
		$text .= "* time:{{REVISIONTIMESTAMP}}\n";

		$rev->setContent( SlotRecord::MAIN, new WikitextContent( $text ) );

		$options = ParserOptions::newCanonical( 'canonical' );
		$rr = $renderer->getRenderedRevision(
			$rev,
			$options,
			null,
			[ 'audience' => RevisionRecord::RAW ]
		);

		$this->assertTrue( $rr->isContentDeleted(), 'isContentDeleted' );

		$this->assertSame( $rev, $rr->getRevision() );
		$this->assertSame( $options, $rr->getOptions() );

		$html = $rr->getRevisionParserOutput()->getText();

		// Suppressed content should be visible in raw mode
		$this->assertContains( 'page:' . __CLASS__, $html );
		$this->assertContains( 'rev:11', $html );
		$this->assertContains( 'user:Frank', $html );
		$this->assertContains( 'time:20180101000003', $html );

		$this->assertSame( $html, $rr->getSlotParserOutput( 'main' )->getText() );
	}

	public function testGetRenderedRevision_multi() {
		$renderer = $this->newRevisionRenderer();
		$title = $this->getMockTitle( 7, 21 );

		$rev = new MutableRevisionRecord( $title );
		$rev->setUser( new UserIdentityValue( 9, 'Frank', 0 ) );
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
		$auxHtml = $auxOutput->getText();

		$this->assertContains( 'Kittens', $mainHtml );
		$this->assertContains( 'Goats', $auxHtml );
		$this->assertNotContains( 'Goats', $mainHtml );
		$this->assertNotContains( 'Kittens', $auxHtml );
		$this->assertContains( 'Kittens', $combinedHtml );
		$this->assertContains( 'Goats', $combinedHtml );
		$this->assertContains( '>aux<', $combinedHtml, 'slot header' );
		$this->assertNotContains( '<mw:slotheader', $combinedHtml, 'slot header placeholder' );

		// make sure output wrapping works right
		$this->assertContains( 'class="mw-parser-output"', $mainHtml );
		$this->assertContains( 'class="mw-parser-output"', $auxHtml );
		$this->assertContains( 'class="mw-parser-output"', $combinedHtml );

		// there should be only one wrapper div
		$this->assertSame( 1, preg_match_all( '#class="mw-parser-output"#', $combinedHtml ) );
		$this->assertNotContains( 'class="mw-parser-output"', $combinedOutput->getRawText() );

		$combinedLinks = $combinedOutput->getLinks();
		$mainLinks = $mainOutput->getLinks();
		$auxLinks = $auxOutput->getLinks();
		$this->assertTrue( isset( $combinedLinks[NS_MAIN]['Kittens'] ), 'links from main slot' );
		$this->assertTrue( isset( $combinedLinks[NS_MAIN]['Goats'] ), 'links from aux slot' );
		$this->assertFalse( isset( $mainLinks[NS_MAIN]['Goats'] ), 'no aux links in main' );
		$this->assertFalse( isset( $auxLinks[NS_MAIN]['Kittens'] ), 'no main links in aux' );
	}

	public function testGetRenderedRevision_noHtml() {
		/** @var MockObject|Content $mockContent */
		$mockContent = $this->getMockBuilder( WikitextContent::class )
			->setMethods( [ 'getParserOutput' ] )
			->setConstructorArgs( [ 'Whatever' ] )
			->getMock();
		$mockContent->method( 'getParserOutput' )
			->willReturnCallback( function ( Title $title, $revId = null,
				ParserOptions $options = null, $generateHtml = true
			) {
				if ( !$generateHtml ) {
					return new ParserOutput( null );
				} else {
					$this->fail( 'Should not be called with $generateHtml == true' );
					return null; // never happens, make analyzer happy
				}
			} );

		$renderer = $this->newRevisionRenderer();
		$title = $this->getMockTitle( 7, 21 );

		$rev = new MutableRevisionRecord( $title );
		$rev->setContent( SlotRecord::MAIN, $mockContent );
		$rev->setContent( 'aux', $mockContent );

		// NOTE: we are testing the private combineSlotOutput() callback here.
		$rr = $renderer->getRenderedRevision( $rev );

		$output = $rr->getSlotParserOutput( SlotRecord::MAIN, [ 'generate-html' => false ] );
		$this->assertFalse( $output->hasText(), 'hasText' );

		$output = $rr->getRevisionParserOutput( [ 'generate-html' => false ] );
		$this->assertFalse( $output->hasText(), 'hasText' );
	}

}
