<?php

namespace MediaWiki\Tests\Revision;

use Content;
use InvalidArgumentException;
use LogicException;
use MediaWiki\Content\Renderer\ContentRenderer;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Page\PageReference;
use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Revision\MutableRevisionSlots;
use MediaWiki\Revision\RenderedRevision;
use MediaWiki\Revision\RevisionArchiveRecord;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Revision\RevisionStoreRecord;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Revision\SuppressedDataException;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use MediaWiki\User\UserIdentityValue;
use MediaWikiIntegrationTestCase;
use ParserOptions;
use ParserOutput;
use PHPUnit\Framework\MockObject\MockObject;
use TitleValue;
use Wikimedia\TestingAccessWrapper;
use WikitextContent;

/**
 * @covers \MediaWiki\Revision\RenderedRevision
 */
class RenderedRevisionTest extends MediaWikiIntegrationTestCase {
	use MockAuthorityTrait;

	/** @var callable */
	private $combinerCallback;

	/** @var ContentRenderer */
	private $contentRenderer;

	protected function setUp(): void {
		parent::setUp();

		$this->combinerCallback = function ( RenderedRevision $rr, array $hints = [] ) {
			return $this->combineOutput( $rr, $hints );
		};

		$this->contentRenderer = $this->getServiceContainer()->getContentRenderer();
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
	 * @param string $class
	 * @param PageIdentity $page
	 * @param null|int $id
	 * @param int $visibility
	 * @param Content[]|null $content
	 * @return RevisionRecord
	 */
	private function getMockRevision(
		$class,
		$page,
		$id = null,
		$visibility = 0,
		array $content = null
	) {
		$frank = new UserIdentityValue( 9, 'Frank' );

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
			->onlyMethods( [
				'getId',
				'getPageId',
				'getPageAsLinkTarget',
				'getPage',
				'getUser',
				'getVisibility',
				'getTimestamp',
			] )->getMock();

		$mock->method( 'getId' )->willReturn( $id );
		$mock->method( 'getPageId' )->willReturn( $page->getId() );
		$mock->method( 'getPageAsLinkTarget' )->willReturn( TitleValue::castPageToLinkTarget( $page ) );
		$mock->method( 'getPage' )->willReturn( $page );
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
		$rev = $this->getMockRevision(
			RevisionStoreRecord::class,
			PageIdentityValue::localIdentity( 0, NS_MAIN, __METHOD__ )
		);
		$options = ParserOptions::newFromAnon();

		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage(
			'User must be specified when setting audience to FOR_THIS_USER'
		);
		$rr = new RenderedRevision(
			$rev,
			$options,
			$this->contentRenderer,
			$this->combinerCallback,
			RevisionRecord::FOR_THIS_USER
		);
	}

	public function testGetRevisionParserOutput_new() {
		$rev = $this->getMockRevision(
			RevisionStoreRecord::class,
			PageIdentityValue::localIdentity( 0, NS_MAIN, 'RenderTestPage' )
		);

		$options = ParserOptions::newFromAnon();
		$rr = new RenderedRevision(
			$rev,
			$options,
			$this->contentRenderer,
			$this->combinerCallback
		);

		$this->assertFalse( $rr->isContentDeleted(), 'isContentDeleted' );

		$this->assertSame( $rev, $rr->getRevision() );
		$this->assertSame( $options, $rr->getOptions() );

		$html = $rr->getRevisionParserOutput()->getText();

		$this->assertStringContainsString( 'page:RenderTestPage!', $html );
		$this->assertStringContainsString( 'user:Frank!', $html );
		$this->assertStringContainsString( 'time:20180101000003!', $html );
	}

	public function testGetRevisionParserOutput_previewWithSelfTransclusion() {
		$title = PageIdentityValue::localIdentity( 0, NS_MAIN, __METHOD__ );
		$name = $this->getServiceContainer()->getTitleFormatter()->getPrefixedText( $title );

		$text = "(ONE)<includeonly>(TWO)</includeonly><noinclude>#{{:$name}}#</noinclude>";

		$content = [
			'main' => new WikitextContent( $text )
		];

		$rev = $this->getMockRevision( RevisionStoreRecord::class, $title, null, 0, $content );

		$options = ParserOptions::newFromAnon();
		$rr = new RenderedRevision(
			$rev,
			$options,
			$this->contentRenderer,
			$this->combinerCallback
		);

		$html = $rr->getRevisionParserOutput()->getText();
		$this->assertStringContainsString( '(ONE)#(ONE)(TWO)#', $html );
	}

	public function testGetRevisionParserOutput_current() {
		$rev = $this->getMockRevision(
			RevisionStoreRecord::class,
			PageIdentityValue::localIdentity( 0, NS_MAIN, 'RenderTestPage' ),
			21
		);

		$options = ParserOptions::newFromAnon();
		$rr = new RenderedRevision(
			$rev,
			$options,
			$this->contentRenderer,
			$this->combinerCallback
		);

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
		$rev = $this->getMockRevision(
			RevisionStoreRecord::class,
			PageIdentityValue::localIdentity( 7, NS_MAIN, 'RenderTestPage' ),
			11
		);

		$options = ParserOptions::newFromAnon();
		$rr = new RenderedRevision(
			$rev,
			$options,
			$this->contentRenderer,
			$this->combinerCallback
		);

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
		$rev = $this->getMockRevision(
			RevisionArchiveRecord::class,
			PageIdentityValue::localIdentity( 7, NS_MAIN, 'RenderTestPage' ),
			11
		);

		$options = ParserOptions::newFromAnon();
		$rr = new RenderedRevision(
			$rev,
			$options,
			$this->contentRenderer,
			$this->combinerCallback
		);

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
		$rev = $this->getMockRevision(
			RevisionStoreRecord::class,
			PageIdentityValue::localIdentity( 7, NS_MAIN, 'RenderTestPage' ),
			11,
			RevisionRecord::DELETED_TEXT
		);

		$options = ParserOptions::newFromAnon();
		$rr = new RenderedRevision(
			$rev,
			$options,
			$this->contentRenderer,
			$this->combinerCallback
		);

		$this->expectException( SuppressedDataException::class );
		$rr->getRevisionParserOutput();
	}

	public function testGetRevisionParserOutput_privileged() {
		$rev = $this->getMockRevision(
			RevisionStoreRecord::class,
			PageIdentityValue::localIdentity( 7, NS_MAIN, 'RenderTestPage' ),
			11,
			RevisionRecord::DELETED_TEXT
		);

		$options = ParserOptions::newFromAnon();
		$rr = new RenderedRevision(
			$rev,
			$options,
			$this->contentRenderer,
			$this->combinerCallback,
			RevisionRecord::FOR_THIS_USER,
			$this->mockRegisteredUltimateAuthority()
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
		$rev = $this->getMockRevision(
			RevisionStoreRecord::class,
			PageIdentityValue::localIdentity( 7, NS_MAIN, 'RenderTestPage' ),
			11,
			RevisionRecord::DELETED_TEXT
		);

		$options = ParserOptions::newFromAnon();
		$rr = new RenderedRevision(
			$rev,
			$options,
			$this->contentRenderer,
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

		$rev = $this->getMockRevision(
			RevisionStoreRecord::class,
			PageIdentityValue::localIdentity( 7, NS_MAIN, 'RenderTestPage' ),
			11,
			0,
			$content );

		$options = ParserOptions::newFromAnon();
		$rr = new RenderedRevision(
			$rev,
			$options,
			$this->contentRenderer,
			$this->combinerCallback
		);

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
		$rev = new MutableRevisionRecord(
			PageIdentityValue::localIdentity( 7, NS_MAIN, 'RenderTestPage' )
		);

		$text = "";
		$text .= "* page:{{PAGENAME}}!\n";
		$text .= "* rev:{{REVISIONID}}!\n";
		$text .= "* user:{{REVISIONUSER}}!\n";
		$text .= "* time:{{REVISIONTIMESTAMP}}!\n";

		$rev->setContent( SlotRecord::MAIN, new WikitextContent( $text ) );

		$options = ParserOptions::newFromAnon();
		$rr = new RenderedRevision(
			$rev,
			$options,
			$this->contentRenderer,
			$this->combinerCallback
		);

		// MutableRevisionRecord without ID should be used by the parser.
		// USeful for fake
		$html = $rr->getRevisionParserOutput()->getText();

		$this->assertStringContainsString( 'page:RenderTestPage!', $html );
		$this->assertStringContainsString( 'rev:!', $html );
		$this->assertStringContainsString( 'user:!', $html );
		// Per parser docs, if revision object does not contain a timestamp
		// then parser uses current time. Hence don't expect time to be
		// empty or a specific time.
		$this->assertStringContainsString( 'time:2', $html );
	}

	public function testGetRevisionParserOutput_incompleteWithId() {
		$page = PageIdentityValue::localIdentity( 7, NS_MAIN, 'RenderTestPage' );
		$rev = new MutableRevisionRecord( $page );
		$rev->setId( 21 );

		$text = "";
		$text .= "* page:{{PAGENAME}}!\n";
		$text .= "* rev:{{REVISIONID}}!\n";
		$text .= "* user:{{REVISIONUSER}}!\n";
		$text .= "* time:{{REVISIONTIMESTAMP}}!\n";

		$rev->setContent( SlotRecord::MAIN, new WikitextContent( $text ) );

		$actualRevision = $this->getMockRevision(
			RevisionStoreRecord::class,
			$page,
			21,
			RevisionRecord::DELETED_TEXT
		);

		$options = ParserOptions::newFromAnon();
		$rr = new RenderedRevision(
			$rev,
			$options,
			$this->contentRenderer,
			$this->combinerCallback
		);

		// MutableRevisionRecord with ID should not be used by the parser,
		// revision should be loaded instead!
		$revisionStore = $this->createMock( RevisionStore::class );

		$revisionStore->expects( $this->once() )
			->method( 'getKnownCurrentRevision' )
			->willReturn( $actualRevision );

		$this->setService( 'RevisionStore', $revisionStore );

		$html = $rr->getRevisionParserOutput()->getText();

		$this->assertStringContainsString( 'page:RenderTestPage!', $html );
		$this->assertStringContainsString( 'rev:21!', $html );
		$this->assertStringContainsString( 'user:Frank!', $html );
		$this->assertStringContainsString( 'time:20180101000003!', $html );
	}

	public function testSetRevisionParserOutput() {
		$rev = $this->getMockRevision(
			RevisionStoreRecord::class,
			PageIdentityValue::localIdentity( 3, NS_MAIN, 'RenderTestPage' )
		);

		$options = ParserOptions::newFromAnon();
		$rr = new RenderedRevision(
			$rev,
			$options,
			$this->contentRenderer,
			$this->combinerCallback
		);

		$output = new ParserOutput( 'Kittens' );
		$rr->setRevisionParserOutput( $output );

		$this->assertSame( $output, $rr->getRevisionParserOutput() );
		$this->assertSame( 'Kittens', $rr->getRevisionParserOutput()->getText() );

		$this->assertSame( $output, $rr->getSlotParserOutput( SlotRecord::MAIN ) );
		$this->assertSame( 'Kittens', $rr->getSlotParserOutput( SlotRecord::MAIN )->getText() );
	}

	public function testNoHtml() {
		$content = new WikitextContent( 'whatever' );

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

		$rev = new MutableRevisionRecord(
			PageIdentityValue::localIdentity( 7, NS_MAIN, 'RenderTestPage' )
		);
		$rev->setContent( SlotRecord::MAIN, $content );
		$rev->setContent( 'aux', $content );

		$options = ParserOptions::newFromAnon();
		$rr = new RenderedRevision(
			$rev,
			$options,
			$mockContentRenderer,
			$this->combinerCallback
		);

		$output = $rr->getSlotParserOutput( SlotRecord::MAIN, [ 'generate-html' => false ] );
		$this->assertFalse( $output->hasText(), 'hasText' );

		$output = $rr->getRevisionParserOutput( [ 'generate-html' => false ] );
		$this->assertFalse( $output->hasText(), 'hasText' );
	}

	public function testUpdateRevision() {
		$page = PageIdentityValue::localIdentity( 7, NS_MAIN, 'RenderTestPage' );
		$rev = new MutableRevisionRecord( $page );

		$text = "";
		$text .= "* page:{{PAGENAME}}!\n";
		$text .= "* rev:{{REVISIONID}}!\n";
		$text .= "* user:{{REVISIONUSER}}!\n";
		$text .= "* time:{{REVISIONTIMESTAMP}}!\n";

		$rev->setContent( SlotRecord::MAIN, new WikitextContent( $text ) );
		$rev->setContent( 'aux', new WikitextContent( '[[Goats]]' ) );

		$options = ParserOptions::newFromAnon();
		$rr = new RenderedRevision(
			$rev,
			$options,
			$this->contentRenderer,
			$this->combinerCallback
		);

		$firstOutput = $rr->getRevisionParserOutput();
		$mainOutput = $rr->getSlotParserOutput( SlotRecord::MAIN );
		$auxOutput = $rr->getSlotParserOutput( 'aux' );

		// emulate a saved revision
		$savedRev = new MutableRevisionRecord( $page );
		$savedRev->setContent( SlotRecord::MAIN, new WikitextContent( $text ) );
		$savedRev->setContent( 'aux', new WikitextContent( '[[Goats]]' ) );
		$savedRev->setId( 23 ); // saved, new
		$savedRev->setUser( new UserIdentityValue( 9, 'Frank' ) );
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
		$page = PageIdentityValue::localIdentity( 7, NS_MAIN, 'RenderTestPage' );
		$rev = new MutableRevisionRecord( $page );
		$rev->setId( 123 );
		$rev->setContent( SlotRecord::MAIN, new WikitextContent( 'FooBar' ) );

		$options = ParserOptions::newFromAnon();
		$rr = new RenderedRevision(
			$rev,
			$options,
			$this->contentRenderer,
			$this->combinerCallback
		);

		$newRev = new MutableRevisionRecord( $page );
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
