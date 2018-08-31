<?php

namespace MediaWiki\Tests\Revision;

use Content;
use Language;
use MediaWiki\Revision\RenderedRevision;
use MediaWiki\Storage\MutableRevisionRecord;
use MediaWiki\Storage\RevisionRecord;
use MediaWiki\Storage\SuppressedDataException;
use MediaWiki\User\UserIdentityValue;
use MediaWikiTestCase;
use ParserOptions;
use ParserOutput;
use PHPUnit\Framework\MockObject\MockObject;
use Title;
use User;
use WikitextContent;

/**
 * @covers \MediaWiki\Revision\RenderedRevision
 */
class RenderedRevisionTest extends MediaWikiTestCase {

	/** @var callable */
	private $combinerCallback;

	public function setUp() {
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
	 * @param $articleId
	 * @param $revisionId
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
			->willReturnCallback( function ( Title $other ) use ( $mock ) {
				return $mock->getArticleID() === $other->getArticleID();
			} );
		$mock->expects( $this->any() )
			->method( 'userCan' )
			->willReturnCallback( function ( $perm, User $user ) use ( $mock ) {
				return $user->isAllowed( $perm );
			} );

		return $mock;
	}

	public function testGetRevisionParserOutput_new() {
		$title = $this->getMockTitle( 7, 21 );

		$rev = new MutableRevisionRecord( $title );
		$rev->setUser( new UserIdentityValue( 9, 'Frank', 0 ) );
		$rev->setTimestamp( '20180101000003' );

		$text = "";
		$text .= "* page:{{PAGENAME}}\n";
		$text .= "* rev:{{REVISIONID}}\n";
		$text .= "* user:{{REVISIONUSER}}\n";
		$text .= "* time:{{REVISIONTIMESTAMP}}\n";
		$text .= "* [[Link It]]\n";

		$rev->setContent( 'main', new WikitextContent( $text ) );

		$options = ParserOptions::newCanonical( 'canonical' );
		$rr = new RenderedRevision( $title, $rev, $options, $this->combinerCallback );

		$this->assertFalse( $rr->isContentDeleted(), 'isContentDeleted' );

		$this->assertSame( $rev, $rr->getRevision() );
		$this->assertSame( $options, $rr->getOptions() );

		$html = $rr->getRevisionParserOutput()->getText();

		$this->assertContains( 'page:' . __CLASS__, $html );
		$this->assertContains( 'user:Frank', $html );
		$this->assertContains( 'time:20180101000003', $html );
	}

	public function testGetRevisionParserOutput_current() {
		$title = $this->getMockTitle( 7, 21 );

		$rev = new MutableRevisionRecord( $title );
		$rev->setId( 21 ); // current!
		$rev->setUser( new UserIdentityValue( 9, 'Frank', 0 ) );
		$rev->setTimestamp( '20180101000003' );

		$text = "";
		$text .= "* page:{{PAGENAME}}\n";
		$text .= "* rev:{{REVISIONID}}\n";
		$text .= "* user:{{REVISIONUSER}}\n";
		$text .= "* time:{{REVISIONTIMESTAMP}}\n";

		$rev->setContent( 'main', new WikitextContent( $text ) );

		$options = ParserOptions::newCanonical( 'canonical' );
		$rr = new RenderedRevision( $title, $rev, $options, $this->combinerCallback );

		$this->assertFalse( $rr->isContentDeleted(), 'isContentDeleted' );

		$this->assertSame( $rev, $rr->getRevision() );
		$this->assertSame( $options, $rr->getOptions() );

		$html = $rr->getRevisionParserOutput()->getText();

		$this->assertContains( 'page:' . __CLASS__, $html );
		$this->assertContains( 'rev:21', $html );
		$this->assertContains( 'user:Frank', $html );
		$this->assertContains( 'time:20180101000003', $html );

		$this->assertSame( $html, $rr->getSlotParserOutput( 'main' )->getText() );
	}

	public function testGetRevisionParserOutput_old() {
		$title = $this->getMockTitle( 7, 21 );

		$rev = new MutableRevisionRecord( $title );
		$rev->setId( 11 ); // old!
		$rev->setUser( new UserIdentityValue( 9, 'Frank', 0 ) );
		$rev->setTimestamp( '20180101000003' );

		$text = "";
		$text .= "* page:{{PAGENAME}}\n";
		$text .= "* rev:{{REVISIONID}}\n";
		$text .= "* user:{{REVISIONUSER}}\n";
		$text .= "* time:{{REVISIONTIMESTAMP}}\n";

		$rev->setContent( 'main', new WikitextContent( $text ) );

		$options = ParserOptions::newCanonical( 'canonical' );
		$rr = new RenderedRevision( $title, $rev, $options, $this->combinerCallback );

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

	public function testGetRevisionParserOutput_suppressed() {
		$title = $this->getMockTitle( 7, 21 );

		$rev = new MutableRevisionRecord( $title );
		$rev->setId( 11 ); // old!
		$rev->setVisibility( RevisionRecord::DELETED_TEXT ); // suppressed!
		$rev->setUser( new UserIdentityValue( 9, 'Frank', 0 ) );
		$rev->setTimestamp( '20180101000003' );

		$text = "";
		$text .= "* page:{{PAGENAME}}\n";
		$text .= "* rev:{{REVISIONID}}\n";
		$text .= "* user:{{REVISIONUSER}}\n";
		$text .= "* time:{{REVISIONTIMESTAMP}}\n";

		$rev->setContent( 'main', new WikitextContent( $text ) );

		$options = ParserOptions::newCanonical( 'canonical' );
		$rr = new RenderedRevision( $title, $rev, $options, $this->combinerCallback );

		$this->setExpectedException( SuppressedDataException::class );
		$rr->getRevisionParserOutput();
	}

	public function testGetRevisionParserOutput_privileged() {
		$title = $this->getMockTitle( 7, 21 );

		$rev = new MutableRevisionRecord( $title );
		$rev->setId( 11 ); // old!
		$rev->setVisibility( RevisionRecord::DELETED_TEXT ); // suppressed!
		$rev->setUser( new UserIdentityValue( 9, 'Frank', 0 ) );
		$rev->setTimestamp( '20180101000003' );

		$text = "";
		$text .= "* page:{{PAGENAME}}\n";
		$text .= "* rev:{{REVISIONID}}\n";
		$text .= "* user:{{REVISIONUSER}}\n";
		$text .= "* time:{{REVISIONTIMESTAMP}}\n";

		$rev->setContent( 'main', new WikitextContent( $text ) );

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
		$this->assertContains( 'page:' . __CLASS__, $html );
		$this->assertContains( 'rev:11', $html );
		$this->assertContains( 'user:Frank', $html );
		$this->assertContains( 'time:20180101000003', $html );

		$this->assertSame( $html, $rr->getSlotParserOutput( 'main' )->getText() );
	}

	public function testGetRevisionParserOutput_raw() {
		$title = $this->getMockTitle( 7, 21 );

		$rev = new MutableRevisionRecord( $title );
		$rev->setId( 11 ); // old!
		$rev->setVisibility( RevisionRecord::DELETED_TEXT ); // suppressed!
		$rev->setUser( new UserIdentityValue( 9, 'Frank', 0 ) );
		$rev->setTimestamp( '20180101000003' );

		$text = "";
		$text .= "* page:{{PAGENAME}}\n";
		$text .= "* rev:{{REVISIONID}}\n";
		$text .= "* user:{{REVISIONUSER}}\n";
		$text .= "* time:{{REVISIONTIMESTAMP}}\n";

		$rev->setContent( 'main', new WikitextContent( $text ) );

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
		$this->assertContains( 'page:' . __CLASS__, $html );
		$this->assertContains( 'rev:11', $html );
		$this->assertContains( 'user:Frank', $html );
		$this->assertContains( 'time:20180101000003', $html );

		$this->assertSame( $html, $rr->getSlotParserOutput( 'main' )->getText() );
	}

	public function testGetRevisionParserOutput_multi() {
		$title = $this->getMockTitle( 7, 21 );

		$rev = new MutableRevisionRecord( $title );
		$rev->setUser( new UserIdentityValue( 9, 'Frank', 0 ) );
		$rev->setTimestamp( '20180101000003' );

		$rev->setContent( 'main', new WikitextContent( '[[Kittens]]' ) );
		$rev->setContent( 'aux', new WikitextContent( '[[Goats]]' ) );

		$options = ParserOptions::newCanonical( 'canonical' );
		$rr = new RenderedRevision( $title, $rev, $options, $this->combinerCallback );

		$combinedOutput = $rr->getRevisionParserOutput();
		$mainOutput = $rr->getSlotParserOutput( 'main' );
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
		$this->assertContains( 'aux', $combinedHtml, 'slot section header' );

		$combinedLinks = $combinedOutput->getLinks();
		$mainLinks = $mainOutput->getLinks();
		$auxLinks = $auxOutput->getLinks();
		$this->assertTrue( isset( $combinedLinks[NS_MAIN]['Kittens'] ), 'links from main slot' );
		$this->assertTrue( isset( $combinedLinks[NS_MAIN]['Goats'] ), 'links from aux slot' );
		$this->assertFalse( isset( $mainLinks[NS_MAIN]['Goats'] ), 'no aux links in main' );
		$this->assertFalse( isset( $auxLinks[NS_MAIN]['Kittens'] ), 'no main links in aux' );
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
		$rev->setContent( 'main', $mockContent );
		$rev->setContent( 'aux', $mockContent );

		$options = ParserOptions::newCanonical( 'canonical' );
		$rr = new RenderedRevision( $title, $rev, $options, $this->combinerCallback );

		$output = $rr->getSlotParserOutput( 'main', [ 'generate-html' => false ] );
		$this->assertFalse( $output->hasText(), 'hasText' );

		$output = $rr->getRevisionParserOutput( [ 'generate-html' => false ] );
		$this->assertFalse( $output->hasText(), 'hasText' );
	}

	public function testUpdateRevision() {
		$title = $this->getMockTitle( 7, 21 );

		$rev = new MutableRevisionRecord( $title );

		$text = "";
		$text .= "* page:{{PAGENAME}}\n";
		$text .= "* rev:{{REVISIONID}}\n";
		$text .= "* user:{{REVISIONUSER}}\n";
		$text .= "* time:{{REVISIONTIMESTAMP}}\n";

		$rev->setContent( 'main', new WikitextContent( $text ) );
		$rev->setContent( 'aux', new WikitextContent( '[[Goats]]' ) );

		$options = ParserOptions::newCanonical( 'canonical' );
		$rr = new RenderedRevision( $title, $rev, $options, $this->combinerCallback );

		$firstOutput = $rr->getRevisionParserOutput();
		$mainOutput = $rr->getSlotParserOutput( 'main' );
		$auxOutput = $rr->getSlotParserOutput( 'aux' );

		// emulate a saved revision
		$savedRev = new MutableRevisionRecord( $title );
		$savedRev->setContent( 'main', new WikitextContent( $text ) );
		$savedRev->setContent( 'aux', new WikitextContent( '[[Goats]]' ) );
		$savedRev->setId( 23 ); // saved, new
		$savedRev->setUser( new UserIdentityValue( 9, 'Frank', 0 ) );
		$savedRev->setTimestamp( '20180101000003' );

		$rr->updateRevision( $savedRev );

		$this->assertNotSame( $mainOutput, $rr->getSlotParserOutput( 'main' ), 'Reset main' );
		$this->assertSame( $auxOutput, $rr->getSlotParserOutput( 'aux' ), 'Keep aux' );

		$updatedOutput = $rr->getRevisionParserOutput();
		$html = $updatedOutput->getText();

		$this->assertNotSame( $firstOutput, $updatedOutput, 'Reset merged' );
		$this->assertContains( 'page:' . __CLASS__, $html );
		$this->assertContains( 'rev:23', $html );
		$this->assertContains( 'user:Frank', $html );
		$this->assertContains( 'time:20180101000003', $html );
		$this->assertContains( 'Goats', $html );

		$rr->updateRevision( $savedRev ); // should do nothing
		$this->assertSame( $updatedOutput, $rr->getRevisionParserOutput(), 'no more reset needed' );
	}

}
