<?php

namespace MediaWiki\Tests\Revision;

use Content;
use InvalidArgumentException;
use LogicException;
use MediaWiki\MediaWikiServices;
use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Revision\MutableRevisionSlots;
use MediaWiki\Revision\RenderedRevision;
use MediaWiki\Revision\RevisionArchiveRecord;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Revision\RevisionStoreRecord;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Revision\SuppressedDataException;
use MediaWiki\User\UserIdentityValue;
use MediaWikiIntegrationTestCase;
use ParserOptions;
use ParserOutput;
use PHPUnit\Framework\MockObject\MockObject;
use Title;
use Wikimedia\TestingAccessWrapper;
use WikitextContent;

/**
 * @covers \MediaWiki\Revision\RenderedRevision
 */
class RenderedRevisionTest extends MediaWikiIntegrationTestCase {

	/** @var callable */
	private $combinerCallback;

	protected function setUp() : void {
		parent::setUp();

		$this->combinerCallback = function ( RenderedRevision $rr, array $hints = [] ) {
			return $this->combineOutput( $rr, $hints );
		};
	}

	private function combineOutput( RenderedRevision $rrev, array $hints = [] ) {
		// NOTE: the is a slightly simplified version of RevisionRenderer::combineSlotOutput

		$withHtml = $hints['generate-html'] ?? true;

		$revision = $rrev->getRevision();
		$slots = $revision->getSlots()->getSlots();

		$combinedOutput = new ParserOutput( null );
		$slotOutput = [];
		foreach ( $slots as $role => $slot ) {
			$out = $rrev->getSlotParserOutput( $role, $hints );
			$slotOutput[$role] = $out;

			$combinedOutput->mergeInternalMetaDataFrom( $out );
			$combinedOutput->mergeTrackingMetaDataFrom( $out );
		}

		if ( $withHtml ) {
			$html = '';
			/** @var ParserOutput $out */
			foreach ( $slotOutput as $role => $out ) {

				if ( $html !== '' ) {
					// skip header for the first slot
					$html .= "(($role))";
				}

				$html .= $out->getRawText();
				$combinedOutput->mergeHtmlMetaDataFrom( $out );
			}

			$combinedOutput->setText( $html );
		}

		return $combinedOutput;
	}

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
			->will( $this->returnValue( 'RenderTestPage' ) );
		$mock->expects( $this->any() )
			->method( 'getPrefixedText' )
			->will( $this->returnValue( 'RenderTestPage' ) );
		$mock->expects( $this->any() )
			->method( 'getDBkey' )
			->will( $this->returnValue( 'RenderTestPage' ) );
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
			->will( $this->returnValue(
				MediaWikiServices::getInstance()->getLanguageFactory()->getLanguage( 'en' ) ) );
		$mock->expects( $this->any() )
			->method( 'isContentPage' )
			->will( $this->returnValue( true ) );
		$mock->expects( $this->any() )
			->method( 'equals' )
			->willReturnCallback( function ( Title $other ) use ( $mock ) {
				return $mock->getPrefixedText() === $other->getPrefixedText();
			} );

		return $mock;
	}

	/**
	 * @param string $class
	 * @param Title $title
	 * @param null|int $id
	 * @param int $visibility
	 * @return RevisionRecord
	 */
	private function getMockRevision(
		$class,
		$title,
		$id = null,
		$visibility = 0,
		array $content = null
	) {
		$frank = new UserIdentityValue( 9, 'Frank', 0 );

		if ( !$content ) {
			$text = "";
			$text .= "* page:{{PAGENAME}}!\n";
			$text .= "* rev:{{REVISIONID}}!\n";
			$text .= "* user:{{REVISIONUSER}}!\n";
			$text .= "* time:{{REVISIONTIMESTAMP}}!\n";
			$text .= "* [[Link It]]\n";

			$content = [ 'main' => new WikitextContent( $text ) ];
		}

		/** @var MockObject|RevisionRecord $mock */
		$mock = $this->getMockBuilder( $class )
			->disableOriginalConstructor()
			->setMethods( [
				'getId',
				'getPageId',
				'getPageAsLinkTarget',
				'getUser',
				'getVisibility',
				'getTimestamp',
			] )->getMock();

		$mock->method( 'getId' )->willReturn( $id );
		$mock->method( 'getPageId' )->willReturn( $title->getArticleID() );
		$mock->method( 'getPageAsLinkTarget' )->willReturn( $title );
		$mock->method( 'getUser' )->willReturn( $frank );
		$mock->method( 'getVisibility' )->willReturn( $visibility );
		$mock->method( 'getTimestamp' )->willReturn( '20180101000003' );

		/** @var object $mockAccess */
		$mockAccess = TestingAccessWrapper::newFromObject( $mock );
		$mockAccess->mSlots = new MutableRevisionSlots();

		foreach ( $content as $role => $cnt ) {
			$mockAccess->mSlots->setContent( $role, $cnt );
		}

		return $mock;
	}

	public function testConstructorInvalidArguments() {
		$title = $this->getMockTitle( 0, 21 );
		$rev = $this->getMockRevision( RevisionStoreRecord::class, $title );
		$options = ParserOptions::newCanonical( 'canonical' );

		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage(
			'User must be specified when setting audience to FOR_THIS_USER'
		);
		$rr = new RenderedRevision(
			$title,
			$rev,
			$options,
			$this->combinerCallback,
			RevisionRecord::FOR_THIS_USER
		);
	}

	public function testGetRevisionParserOutput_new() {
		$title = $this->getMockTitle( 0, 21 );
		$rev = $this->getMockRevision( RevisionStoreRecord::class, $title );

		$options = ParserOptions::newCanonical( 'canonical' );
		$rr = new RenderedRevision( $title, $rev, $options, $this->combinerCallback );

		$this->assertFalse( $rr->isContentDeleted(), 'isContentDeleted' );

		$this->assertSame( $rev, $rr->getRevision() );
		$this->assertSame( $options, $rr->getOptions() );

		$html = $rr->getRevisionParserOutput()->getText();

		$this->assertStringContainsString( 'page:RenderTestPage!', $html );
		$this->assertStringContainsString( 'user:Frank!', $html );
		$this->assertStringContainsString( 'time:20180101000003!', $html );
	}

	public function testGetRevisionParserOutput_previewWithSelfTransclusion() {
		$title = $this->getMockTitle( 0, 21 );
		$name = $title->getPrefixedText();

		$text = "(ONE)<includeonly>(TWO)</includeonly><noinclude>#{{:$name}}#</noinclude>";

		$content = [
			'main' => new WikitextContent( $text )
		];

		$rev = $this->getMockRevision( RevisionStoreRecord::class, $title, null, 0, $content );

		$options = ParserOptions::newCanonical( 'canonical' );
		$rr = new RenderedRevision( $title, $rev, $options, $this->combinerCallback );

		$html = $rr->getRevisionParserOutput()->getText();
		$this->assertStringContainsString( '(ONE)#(ONE)(TWO)#', $html );
	}

	public function testGetRevisionParserOutput_current() {
		$title = $this->getMockTitle( 7, 21 );
		$rev = $this->getMockRevision( RevisionStoreRecord::class, $title, 21 );

		$options = ParserOptions::newCanonical( 'canonical' );
		$rr = new RenderedRevision( $title, $rev, $options, $this->combinerCallback );

		$this->assertFalse( $rr->isContentDeleted(), 'isContentDeleted' );

		$this->assertSame( $rev, $rr->getRevision() );
		$this->assertSame( $options, $rr->getOptions() );

		$html = $rr->getRevisionParserOutput()->getText();

		$this->assertStringContainsString( 'page:RenderTestPage!', $html );
		$this->assertStringContainsString( 'rev:21!', $html );
		$this->assertStringContainsString( 'user:Frank!', $html );
		$this->assertStringContainsString( 'time:20180101000003!', $html );

		$this->assertSame( $html, $rr->getSlotParserOutput( SlotRecord::MAIN )->getText() );
	}

	public function testGetRevisionParserOutput_old() {
		$title = $this->getMockTitle( 7, 21 );
		$rev = $this->getMockRevision( RevisionStoreRecord::class, $title, 11 );

		$options = ParserOptions::newCanonical( 'canonical' );
		$rr = new RenderedRevision( $title, $rev, $options, $this->combinerCallback );

		$this->assertFalse( $rr->isContentDeleted(), 'isContentDeleted' );

		$this->assertSame( $rev, $rr->getRevision() );
		$this->assertSame( $options, $rr->getOptions() );

		$html = $rr->getRevisionParserOutput()->getText();

		$this->assertStringContainsString( 'page:RenderTestPage!', $html );
		$this->assertStringContainsString( 'rev:11!', $html );
		$this->assertStringContainsString( 'user:Frank!', $html );
		$this->assertStringContainsString( 'time:20180101000003!', $html );

		$this->assertSame( $html, $rr->getSlotParserOutput( SlotRecord::MAIN )->getText() );
	}

	public function testGetRevisionParserOutput_archive() {
		$title = $this->getMockTitle( 7, 21 );
		$rev = $this->getMockRevision( RevisionArchiveRecord::class, $title, 11 );

		$options = ParserOptions::newCanonical( 'canonical' );
		$rr = new RenderedRevision( $title, $rev, $options, $this->combinerCallback );

		$this->assertFalse( $rr->isContentDeleted(), 'isContentDeleted' );

		$this->assertSame( $rev, $rr->getRevision() );
		$this->assertSame( $options, $rr->getOptions() );

		$html = $rr->getRevisionParserOutput()->getText();

		$this->assertStringContainsString( 'page:RenderTestPage!', $html );
		$this->assertStringContainsString( 'rev:11!', $html );
		$this->assertStringContainsString( 'user:Frank!', $html );
		$this->assertStringContainsString( 'time:20180101000003!', $html );

		$this->assertSame( $html, $rr->getSlotParserOutput( SlotRecord::MAIN )->getText() );
	}

	public function testGetRevisionParserOutput_suppressed() {
		$title = $this->getMockTitle( 7, 21 );
		$rev = $this->getMockRevision(
			RevisionStoreRecord::class,
			$title,
			11,
			RevisionRecord::DELETED_TEXT
		);

		$options = ParserOptions::newCanonical( 'canonical' );
		$rr = new RenderedRevision( $title, $rev, $options, $this->combinerCallback );

		$this->expectException( SuppressedDataException::class );
		$rr->getRevisionParserOutput();
	}

	public function testGetRevisionParserOutput_privileged() {
		$title = $this->getMockTitle( 7, 21 );
		$rev = $this->getMockRevision(
			RevisionStoreRecord::class,
			$title,
			11,
			RevisionRecord::DELETED_TEXT
		);

		$options = ParserOptions::newCanonical( 'canonical' );
		$sysop = $this->getTestUser( [ 'sysop' ] )->getUser(); // privileged!
		$rr = new RenderedRevision(
			$title,
			$rev,
			$options,
			$this->combinerCallback,
			RevisionRecord::FOR_THIS_USER,
			$sysop
		);

		$this->assertTrue( $rr->isContentDeleted(), 'isContentDeleted' );

		$this->assertSame( $rev, $rr->getRevision() );
		$this->assertSame( $options, $rr->getOptions() );

		$html = $rr->getRevisionParserOutput()->getText();

		// Suppressed content should be visible for sysops
		$this->assertStringContainsString( 'page:RenderTestPage!', $html );
		$this->assertStringContainsString( 'rev:11!', $html );
		$this->assertStringContainsString( 'user:Frank!', $html );
		$this->assertStringContainsString( 'time:20180101000003!', $html );

		$this->assertSame( $html, $rr->getSlotParserOutput( SlotRecord::MAIN )->getText() );
	}

	public function testGetRevisionParserOutput_raw() {
		$title = $this->getMockTitle( 7, 21 );
		$rev = $this->getMockRevision(
			RevisionStoreRecord::class,
			$title,
			11,
			RevisionRecord::DELETED_TEXT
		);

		$options = ParserOptions::newCanonical( 'canonical' );
		$rr = new RenderedRevision(
			$title,
			$rev,
			$options,
			$this->combinerCallback,
			RevisionRecord::RAW
		);

		$this->assertTrue( $rr->isContentDeleted(), 'isContentDeleted' );

		$this->assertSame( $rev, $rr->getRevision() );
		$this->assertSame( $options, $rr->getOptions() );

		$html = $rr->getRevisionParserOutput()->getText();

		// Suppressed content should be visible for sysops
		$this->assertStringContainsString( 'page:RenderTestPage!', $html );
		$this->assertStringContainsString( 'rev:11!', $html );
		$this->assertStringContainsString( 'user:Frank!', $html );
		$this->assertStringContainsString( 'time:20180101000003!', $html );

		$this->assertSame( $html, $rr->getSlotParserOutput( SlotRecord::MAIN )->getText() );
	}

	public function testGetRevisionParserOutput_multi() {
		$content = [
			'main' => new WikitextContent( '[[Kittens]]' ),
			'aux' => new WikitextContent( '[[Goats]]' ),
		];

		$title = $this->getMockTitle( 7, 21 );
		$rev = $this->getMockRevision( RevisionStoreRecord::class, $title, 11, 0, $content );

		$options = ParserOptions::newCanonical( 'canonical' );
		$rr = new RenderedRevision( $title, $rev, $options, $this->combinerCallback );

		$combinedOutput = $rr->getRevisionParserOutput();
		$mainOutput = $rr->getSlotParserOutput( SlotRecord::MAIN );
		$auxOutput = $rr->getSlotParserOutput( 'aux' );

		$combinedHtml = $combinedOutput->getText();
		$mainHtml = $mainOutput->getText();
		$auxHtml = $auxOutput->getText();

		$this->assertStringContainsString( 'Kittens', $mainHtml );
		$this->assertStringContainsString( 'Goats', $auxHtml );
		$this->assertStringNotContainsString( 'Goats', $mainHtml );
		$this->assertStringNotContainsString( 'Kittens', $auxHtml );
		$this->assertStringContainsString( 'Kittens', $combinedHtml );
		$this->assertStringContainsString( 'Goats', $combinedHtml );
		$this->assertStringContainsString( 'aux', $combinedHtml, 'slot section header' );

		$combinedLinks = $combinedOutput->getLinks();
		$mainLinks = $mainOutput->getLinks();
		$auxLinks = $auxOutput->getLinks();
		$this->assertTrue( isset( $combinedLinks[NS_MAIN]['Kittens'] ), 'links from main slot' );
		$this->assertTrue( isset( $combinedLinks[NS_MAIN]['Goats'] ), 'links from aux slot' );
		$this->assertFalse( isset( $mainLinks[NS_MAIN]['Goats'] ), 'no aux links in main' );
		$this->assertFalse( isset( $auxLinks[NS_MAIN]['Kittens'] ), 'no main links in aux' );
	}

	public function testGetRevisionParserOutput_incompleteNoId() {
		$title = $this->getMockTitle( 7, 21 );

		$rev = new MutableRevisionRecord( $title );

		$text = "";
		$text .= "* page:{{PAGENAME}}!\n";
		$text .= "* rev:{{REVISIONID}}!\n";
		$text .= "* user:{{REVISIONUSER}}!\n";
		$text .= "* time:{{REVISIONTIMESTAMP}}!\n";

		$rev->setContent( SlotRecord::MAIN, new WikitextContent( $text ) );

		$options = ParserOptions::newCanonical( 'canonical' );
		$rr = new RenderedRevision( $title, $rev, $options, $this->combinerCallback );

		// MutableRevisionRecord without ID should be used by the parser.
		// USeful for fake
		$html = $rr->getRevisionParserOutput()->getText();

		$this->assertStringContainsString( 'page:RenderTestPage!', $html );
		$this->assertStringContainsString( 'rev:!', $html );
		$this->assertStringContainsString( 'user:!', $html );
		$this->assertStringContainsString( 'time:!', $html );
	}

	public function testGetRevisionParserOutput_incompleteWithId() {
		$title = $this->getMockTitle( 7, 21 );

		$rev = new MutableRevisionRecord( $title );
		$rev->setId( 21 );

		$text = "";
		$text .= "* page:{{PAGENAME}}!\n";
		$text .= "* rev:{{REVISIONID}}!\n";
		$text .= "* user:{{REVISIONUSER}}!\n";
		$text .= "* time:{{REVISIONTIMESTAMP}}!\n";

		$rev->setContent( SlotRecord::MAIN, new WikitextContent( $text ) );

		$actualRevision = $this->getMockRevision(
			RevisionStoreRecord::class,
			$title,
			21,
			RevisionRecord::DELETED_TEXT
		);

		$options = ParserOptions::newCanonical( 'canonical' );
		$rr = new RenderedRevision( $title, $rev, $options, $this->combinerCallback );

		// MutableRevisionRecord with ID should not be used by the parser,
		// revision should be loaded instead!
		$revisionStore = $this->getMockBuilder( RevisionStore::class )
			->disableOriginalConstructor()
			->getMock();

		$revisionStore->expects( $this->once() )
			->method( 'getKnownCurrentRevision' )
			->with( $title, 0 )
			->willReturn( $actualRevision );

		$this->setService( 'RevisionStore', $revisionStore );

		$html = $rr->getRevisionParserOutput()->getText();

		$this->assertStringContainsString( 'page:RenderTestPage!', $html );
		$this->assertStringContainsString( 'rev:21!', $html );
		$this->assertStringContainsString( 'user:Frank!', $html );
		$this->assertStringContainsString( 'time:20180101000003!', $html );
	}

	public function testSetRevisionParserOutput() {
		$title = $this->getMockTitle( 3, 21 );
		$rev = $this->getMockRevision( RevisionStoreRecord::class, $title );

		$options = ParserOptions::newCanonical( 'canonical' );
		$rr = new RenderedRevision( $title, $rev, $options, $this->combinerCallback );

		$output = new ParserOutput( 'Kittens' );
		$rr->setRevisionParserOutput( $output );

		$this->assertSame( $output, $rr->getRevisionParserOutput() );
		$this->assertSame( 'Kittens', $rr->getRevisionParserOutput()->getText() );

		$this->assertSame( $output, $rr->getSlotParserOutput( SlotRecord::MAIN ) );
		$this->assertSame( 'Kittens', $rr->getSlotParserOutput( SlotRecord::MAIN )->getText() );
	}

	public function testNoHtml() {
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

		$title = $this->getMockTitle( 7, 21 );

		$rev = new MutableRevisionRecord( $title );
		$rev->setContent( SlotRecord::MAIN, $mockContent );
		$rev->setContent( 'aux', $mockContent );

		$options = ParserOptions::newCanonical( 'canonical' );
		$rr = new RenderedRevision( $title, $rev, $options, $this->combinerCallback );

		$output = $rr->getSlotParserOutput( SlotRecord::MAIN, [ 'generate-html' => false ] );
		$this->assertFalse( $output->hasText(), 'hasText' );

		$output = $rr->getRevisionParserOutput( [ 'generate-html' => false ] );
		$this->assertFalse( $output->hasText(), 'hasText' );
	}

	public function testUpdateRevision() {
		$title = $this->getMockTitle( 7, 21 );

		$rev = new MutableRevisionRecord( $title );

		$text = "";
		$text .= "* page:{{PAGENAME}}!\n";
		$text .= "* rev:{{REVISIONID}}!\n";
		$text .= "* user:{{REVISIONUSER}}!\n";
		$text .= "* time:{{REVISIONTIMESTAMP}}!\n";

		$rev->setContent( SlotRecord::MAIN, new WikitextContent( $text ) );
		$rev->setContent( 'aux', new WikitextContent( '[[Goats]]' ) );

		$options = ParserOptions::newCanonical( 'canonical' );
		$rr = new RenderedRevision( $title, $rev, $options, $this->combinerCallback );

		$firstOutput = $rr->getRevisionParserOutput();
		$mainOutput = $rr->getSlotParserOutput( SlotRecord::MAIN );
		$auxOutput = $rr->getSlotParserOutput( 'aux' );

		// emulate a saved revision
		$savedRev = new MutableRevisionRecord( $title );
		$savedRev->setContent( SlotRecord::MAIN, new WikitextContent( $text ) );
		$savedRev->setContent( 'aux', new WikitextContent( '[[Goats]]' ) );
		$savedRev->setId( 23 ); // saved, new
		$savedRev->setUser( new UserIdentityValue( 9, 'Frank', 0 ) );
		$savedRev->setTimestamp( '20180101000003' );

		$rr->updateRevision( $savedRev );

		$this->assertNotSame( $mainOutput, $rr->getSlotParserOutput( SlotRecord::MAIN ), 'Reset main' );
		$this->assertSame( $auxOutput, $rr->getSlotParserOutput( 'aux' ), 'Keep aux' );

		$updatedOutput = $rr->getRevisionParserOutput();
		$html = $updatedOutput->getText();

		$this->assertNotSame( $firstOutput, $updatedOutput, 'Reset merged' );
		$this->assertStringContainsString( 'page:RenderTestPage!', $html );
		$this->assertStringContainsString( 'rev:23!', $html );
		$this->assertStringContainsString( 'user:Frank!', $html );
		$this->assertStringContainsString( 'time:20180101000003!', $html );
		$this->assertStringContainsString( 'Goats', $html );

		$rr->updateRevision( $savedRev ); // should do nothing
		$this->assertSame( $updatedOutput, $rr->getRevisionParserOutput(), 'no more reset needed' );
	}

	public function testUpdateRevision_revIdSet() {
		$title = $this->getMockTitle( 7, 21 );

		$rev = new MutableRevisionRecord( $title );
		$rev->setId( 123 );
		$rev->setContent( SlotRecord::MAIN, new WikitextContent( 'FooBar' ) );

		$options = ParserOptions::newCanonical( 'canonical' );
		$rr = new RenderedRevision( $title, $rev, $options, $this->combinerCallback );

		$newRev = new MutableRevisionRecord( $title );
		$newRev->setId( 321 ); // Different
		$newRev->setContent( SlotRecord::MAIN, new WikitextContent( 'FooBar' ) );

		$this->expectException( LogicException::class );
		$this->expectExceptionMessage(
			'RenderedRevision already has a revision with ID 123, ' .
			'can\'t update to revision with ID 321'
		);
		$rr->updateRevision( $newRev );
	}

}
