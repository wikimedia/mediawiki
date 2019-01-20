<?php

use MediaWiki\MainConfigNames;
use MediaWiki\Permissions\SimpleAuthority;
use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Title\Title;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers DifferenceEngine
 *
 * @todo tests for the rest of DifferenceEngine!
 *
 * @group Database
 * @group Diff
 *
 * @author Katie Filbert < aude.wiki@gmail.com >
 */
class DifferenceEngineTest extends MediaWikiIntegrationTestCase {
	use MockTitleTrait;

	protected $context;

	private static $revisions;

	protected function setUp(): void {
		parent::setUp();

		$title = $this->getTitle();

		$this->context = new RequestContext();
		$this->context->setTitle( $title );

		$this->overrideConfigValue( MainConfigNames::DiffEngine, 'php' );

		$slotRoleRegistry = $this->getServiceContainer()->getSlotRoleRegistry();

		if ( !$slotRoleRegistry->isDefinedRole( 'derivedslot' ) ) {
			$slotRoleRegistry->defineRoleWithModel(
				'derivedslot',
				CONTENT_MODEL_WIKITEXT,
				[],
				true
			);
		}
	}

	public function addDBDataOnce() {
		self::$revisions = $this->doEdits();
	}

	/**
	 * @return Title
	 */
	protected function getTitle() {
		$namespace = $this->getDefaultWikitextNS();
		return Title::makeTitle( $namespace, 'Kitten' );
	}

	/**
	 * @return int[] Revision ids
	 */
	protected function doEdits() {
		$title = $this->getTitle();

		$strings = [ "it is a kitten", "two kittens", "three kittens", "four kittens" ];
		$revisions = [];

		$user = $this->getTestSysop()->getAuthority();
		foreach ( $strings as $string ) {
			$status = $this->editPage(
				$title,
				$string,
				'edit page',
				NS_MAIN,
				$user
			);
			$revisions[] = $status->getNewRevision()->getId();
		}

		return $revisions;
	}

	private function expandData( $data ) {
		if ( is_array( $data ) ) {
			foreach ( $data as &$value ) {
				$value = $this->expandData( $value );
			}
		} elseif ( is_string( $data ) ) {
			$data = preg_replace_callback(
				'/rev\[([0-9]+)]/',
				static function ( $m ) {
					return self::$revisions[(int)$m[1]];
				},
				$data
			);
		}
		return $data;
	}

	private function expandTestArgs( $args ) {
		foreach ( $args as &$arg ) {
			$arg = $this->expandData( $arg );
		}
	}

	/**
	 * @dataProvider provideMapDiffPrevNext
	 */
	public function testMapDiffPrevNext( $expected, $old, $new, $message ) {
		$this->expandTestArgs( [ &$expected, &$old, &$new, &$message ] );
		$diffEngine = new DifferenceEngine( $this->context, $old, $new, 2, true, false );
		$diffMap = $diffEngine->mapDiffPrevNext( $old, $new );
		$this->assertEquals( $expected, $diffMap, $message );
	}

	public static function provideMapDiffPrevNext() {
		return [
			[ [ 'rev[1]', 'rev[2]' ], 'rev[2]', 'prev', 'diff=prev' ],
			[ [ 'rev[2]', 'rev[3]' ], 'rev[2]', 'next', 'diff=next' ],
			[ [ 'rev[1]', 'rev[3]' ], 'rev[1]', 'rev[3]', 'diff=rev3' ]
		];
	}

	/**
	 * @dataProvider provideLoadRevision
	 */
	public function testLoadRevisionData( $expectedOld, $expectedNew, $expectedRet, $old, $new ) {
		$this->expandTestArgs( [ &$expectedOld, &$expectedNew, &$expectedRet, &$old, &$new ] );
		$diffEngine = new DifferenceEngine( $this->context, $old, $new, 2, true, false );
		$ret = $diffEngine->loadRevisionData();
		$ret2 = $diffEngine->loadRevisionData();

		$this->assertEquals( $expectedOld, $diffEngine->getOldid() );
		$this->assertEquals( $expectedNew, $diffEngine->getNewid() );
		$this->assertEquals( $expectedRet, $ret );
		$this->assertEquals( $expectedRet, $ret2 );
	}

	public static function provideLoadRevision() {
		return [
			'diff=prev' => [ 'rev[2]', 'rev[3]', true, 'rev[3]', 'prev' ],
			'diff=next' => [ 'rev[2]', 'rev[3]', true, 'rev[2]', 'next' ],
			'diff=' . 'rev[3]' => [ 'rev[1]', 'rev[3]', true, 'rev[1]', 'rev[3]' ],
			'diff=0' => [ 'rev[1]', 'rev[3]', true, 'rev[1]', 0 ],
			'diff=prev&oldid=<first>' => [ false, 'rev[0]', true, 'rev[0]', 'prev' ],
			'invalid' => [ 123456789, 'rev[1]', false, 123456789, 'rev[1]' ],
		];
	}

	public function testGetOldid() {
		$revs = self::$revisions;

		$diffEngine = new DifferenceEngine( $this->context, $revs[1], $revs[2], 2, true, false );
		$this->assertEquals( $revs[1], $diffEngine->getOldid(), 'diff get old id' );
	}

	public function testGetNewid() {
		$revs = self::$revisions;

		$diffEngine = new DifferenceEngine( $this->context, $revs[1], $revs[2], 2, true, false );
		$this->assertEquals( $revs[2], $diffEngine->getNewid(), 'diff get new id' );
	}

	public static function provideLocaliseTitleTooltipsTestData() {
		return [
			'moved paragraph left shoud get new location title' => [
				'<a class="mw-diff-movedpara-left">⚫</a>',
				'<a class="mw-diff-movedpara-left" title="(diff-paragraph-moved-tonew)">⚫</a>',
			],
			'moved paragraph right shoud get old location title' => [
				'<a class="mw-diff-movedpara-right">⚫</a>',
				'<a class="mw-diff-movedpara-right" title="(diff-paragraph-moved-toold)">⚫</a>',
			],
			'nothing changed when key not hit' => [
				'<a class="mw-diff-movedpara-rightis">⚫</a>',
				'<a class="mw-diff-movedpara-rightis">⚫</a>',
			],
		];
	}

	/**
	 * @dataProvider provideLocaliseTitleTooltipsTestData
	 */
	public function testAddLocalisedTitleTooltips( $input, $expected ) {
		$this->setContentLang( 'qqx' );
		/** @var DifferenceEngine $diffEngine */
		$diffEngine = TestingAccessWrapper::newFromObject( new DifferenceEngine() );
		$this->assertEquals( $expected, $diffEngine->addLocalisedTitleTooltips( $input ) );
	}

	/**
	 * @dataProvider provideGenerateContentDiffBody
	 */
	public function testGenerateContentDiffBody(
		array $oldContentArgs, array $newContentArgs, $expectedDiff
	) {
		$this->mergeMwGlobalArrayValue( 'wgContentHandlers', [
			'testing-nontext' => DummyNonTextContentHandler::class,
		] );
		$oldContent = ContentHandler::makeContent( ...$oldContentArgs );
		$newContent = ContentHandler::makeContent( ...$newContentArgs );

		$differenceEngine = new DifferenceEngine();
		$diff = $differenceEngine->generateContentDiffBody( $oldContent, $newContent );
		$this->assertSame( $expectedDiff, $this->getPlainDiff( $diff ) );
	}

	public static function provideGenerateContentDiffBody() {
		$content1 = [ 'xxx', null, CONTENT_MODEL_TEXT ];
		$content2 = [ 'yyy', null, CONTENT_MODEL_TEXT ];

		return [
			'self-diff' => [ $content1, $content1, '' ],
			'text diff' => [ $content1, $content2, '-xxx+yyy' ],
		];
	}

	public function testGenerateTextDiffBody() {
		$oldText = "aaa\nbbb\nccc";
		$newText = "aaa\nxxx\nccc";
		$expectedDiff = " aaa aaa\n-bbb+xxx\n ccc ccc";

		$differenceEngine = new DifferenceEngine();
		$diff = $differenceEngine->generateTextDiffBody( $oldText, $newText );
		$this->assertSame( $expectedDiff, $this->getPlainDiff( $diff ) );
	}

	public function testSetContent() {
		$oldContent = ContentHandler::makeContent( 'xxx', null, CONTENT_MODEL_TEXT );
		$newContent = ContentHandler::makeContent( 'yyy', null, CONTENT_MODEL_TEXT );

		$differenceEngine = new DifferenceEngine();
		$differenceEngine->setContent( $oldContent, $newContent );
		$diff = $differenceEngine->getDiffBody();
		$this->assertSame( "Line 1:\nLine 1:\n-xxx+yyy", $this->getPlainDiff( $diff ) );
	}

	public function testSetRevisions() {
		$rev1 = $this->getRevisionRecord( [ SlotRecord::MAIN => 'xxx' ] );
		$rev2 = $this->getRevisionRecord( [ SlotRecord::MAIN => 'yyy' ] );

		$differenceEngine = new DifferenceEngine();
		$differenceEngine->setRevisions( $rev1, $rev2 );
		$this->assertSame( $rev1, $differenceEngine->getOldRevision() );
		$this->assertSame( $rev2, $differenceEngine->getNewRevision() );
		$this->assertSame( true, $differenceEngine->loadRevisionData() );
		$this->assertSame( true, $differenceEngine->loadText() );

		$differenceEngine->setRevisions( null, $rev2 );
		$this->assertSame( null, $differenceEngine->getOldRevision() );
	}

	/**
	 * @dataProvider provideGetDiffBody
	 */
	public function testGetDiffBody(
		?array $oldSlots, ?array $newSlots, $expectedDiff
	) {
		$oldRevision = $this->getRevisionRecord( $oldSlots );
		$newRevision = $this->getRevisionRecord( $newSlots );
		if ( $expectedDiff instanceof Exception ) {
			$this->expectException( get_class( $expectedDiff ) );
			$this->expectExceptionMessage( $expectedDiff->getMessage() );
		}
		$differenceEngine = new DifferenceEngine();
		$differenceEngine->setRevisions( $oldRevision, $newRevision );
		if ( $expectedDiff instanceof Exception ) {
			return;
		}

		$diff = $differenceEngine->getDiffBody();
		$this->assertSame( $expectedDiff, $this->getPlainDiff( $diff ) );
	}

	public static function provideGetDiffBody() {
		$main1 = [ SlotRecord::MAIN => 'xxx' ];
		$main2 = [ SlotRecord::MAIN => 'yyy' ];
		$slot1 = [ 'slot' => 'aaa' ];
		$slot2 = [ 'slot' => 'bbb' ];
		$slot3 = [ 'derivedslot' => [ 'text' => 'aaa', 'derived' => true ] ];
		$slot4 = [ 'derivedslot' => [ 'text' => 'bbb', 'derived' => true ] ];
		$slot5 = [ 'slot' => [ 'model' => 'testing' ] ];

		return [
			'revision vs. null' => [
				null,
				$main1 + $slot1,
				'',
			],
			'revision vs. itself' => [
				$main1 + $slot1,
				$main1 + $slot1,
				'',
			],
			'different text in one slot' => [
				$main1 + $slot1,
				$main1 + $slot2,
				"slotLine 1:\nLine 1:\n-aaa+bbb",
			],
			'different text in two slots' => [
				$main1 + $slot1,
				$main2 + $slot2,
				"Line 1:\nLine 1:\n-xxx+yyy\nslotLine 1:\nLine 1:\n-aaa+bbb",
			],
			'new slot' => [
				$main1,
				$main1 + $slot1,
				"slotLine 1:\nLine 1:\n- +aaa",
			],
			'ignored difference in derived slot' => [
				$main1 + $slot3,
				$main1 + $slot4,
				'',
			],
			'incompatible slot' => [
				$main1 + $slot5,
				$main2 + $slot1,
				"Line 1:\nLine 1:\n-xxx+yyy\nslotCannot compare content models \"testing\" and \"plain text\"",
			],
		];
	}

	public function testRecursion() {
		// Set up a ContentHandler which will return a wrapped DifferenceEngine as
		// SlotDiffRenderer, then pass it a content which uses the same ContentHandler.
		// This tests the anti-recursion logic in DifferenceEngine::generateContentDiffBody.

		$customDifferenceEngine = $this->getMockBuilder( DifferenceEngine::class )
			->enableProxyingToOriginalMethods()
			->getMock();
		$customContentHandler = $this->getMockBuilder( ContentHandler::class )
			->setConstructorArgs( [ 'foo', [] ] )
			->onlyMethods( [ 'createDifferenceEngine' ] )
			->getMockForAbstractClass();
		$customContentHandler->method( 'createDifferenceEngine' )
			->willReturn( $customDifferenceEngine );
		/** @var ContentHandler $customContentHandler */
		$customContent = $this->getMockBuilder( Content::class )
			->onlyMethods( [ 'getContentHandler' ] )
			->getMockForAbstractClass();
		$customContent->method( 'getContentHandler' )
			->willReturn( $customContentHandler );
		/** @var Content $customContent */
		$customContent2 = clone $customContent;

		$slotDiffRenderer = $customContentHandler->getSlotDiffRenderer( RequestContext::getMain() );
		$this->expectException( Exception::class );
		$this->expectExceptionMessage(
			': could not maintain backwards compatibility. Please use a SlotDiffRenderer.'
		);
		$slotDiffRenderer->getDiff( $customContent, $customContent2 );
	}

	/**
	 * @dataProvider provideMarkPatrolledLink
	 */
	public function testMarkPatrolledLink( $group, $config, $expectedResult ) {
		$this->setUserLang( 'qqx' );
		$user = $this->getTestUser( $group )->getUser();
		$this->context->setUser( $user );
		if ( $config ) {
			$this->context->setConfig( new HashConfig( $config ) );
		}

		$page = $this->getNonexistingTestPage( 'Page1' );
		$this->assertTrue( $this->editPage( $page, 'Edit1' )->isGood(), 'edited a page' );
		$rev1 = $page->getRevisionRecord();
		$this->assertTrue( $this->editPage( $page, 'Edit2' )->isGood(), 'edited a page' );
		$rev2 = $page->getRevisionRecord();

		$diffEngine = new DifferenceEngine( $this->context );
		$diffEngine->setRevisions( $rev1, $rev2 );

		$html = $diffEngine->markPatrolledLink();
		$this->assertStringContainsString( $expectedResult, $html );
	}

	public static function provideMarkPatrolledLink() {
		yield 'PatrollingEnabledUserAllowed' => [
			'sysop',
			[ MainConfigNames::UseRCPatrol => true, MainConfigNames::LanguageCode => 'qxx' ],
			'Mark as patrolled'
		];

		yield 'PatrollingEnabledUserNotAllowed' => [
			null,
			[ MainConfigNames::UseRCPatrol => true, MainConfigNames::LanguageCode => 'qxx' ],
			''
		];

		yield 'PatrollingDisabledUserAllowed' => [
			'sysop',
			null,
			''
		];

		yield 'PatrollingDisabledUserNotAllowed' => [
			null,
			null,
			''
		];
	}

	/**
	 * Convert a HTML diff to a human-readable format and hopefully make the test less fragile.
	 * @param string $diff
	 * @return string
	 */
	private function getPlainDiff( $diff ) {
		$replacements = [
			html_entity_decode( '&nbsp;' ) => ' ',
			html_entity_decode( '&minus;' ) => '-',
		];
		// Preserve markers when stripping tags
		$diff = str_replace( '<td class="diff-marker"></td>', ' ', $diff );
		$diff = str_replace( '<td colspan="2"></td>', ' ', $diff );
		$diff = preg_replace( '/data-marker="([^"]*)">/', '>$1', $diff );
		return str_replace( array_keys( $replacements ), array_values( $replacements ),
			trim( strip_tags( $diff ), "\n" ) );
	}

	/**
	 * @param string[]|array[]|null $slots Array mapping slot role to content.
	 *   If the content is a string, a normal text slot will be created. If the
	 *   content is an associative array, it can have the following keys:
	 *    - derived: If present and true, the slot is a derived slot
	 *    - text: The serialized content
	 *    - model: The content model ID
	 * @return MutableRevisionRecord|null
	 */
	private function getRevisionRecord( $slots ) {
		if ( $slots === null ) {
			return null;
		}

		$contentHandlerFactory = $this->getServiceContainer()->getContentHandlerFactory();
		$title = $this->makeMockTitle( __CLASS__ );
		$revision = new MutableRevisionRecord( $title );
		foreach ( $slots as $role => $info ) {
			if ( is_string( $info ) ) {
				$info = [
					'text' => $info,
				];
			}
			$info += [ 'text' => '', 'model' => CONTENT_MODEL_TEXT ];

			if ( !$contentHandlerFactory->isDefinedModel( $info['model'] ) ) {
				$contentHandlerFactory->defineContentHandler(
					$info['model'], DummyContentHandlerForTesting::class );
			}

			$content = ContentHandler::makeContent( $info['text'], null, $info['model'] );
			if ( $info['derived'] ?? false ) {
				$slotRecord = SlotRecord::newDerived( $role, $content );
			} else {
				$slotRecord = SlotRecord::newUnsaved( $role, $content );
			}
			$revision->setSlot( $slotRecord );
		}
		return $revision;
	}

	/**
	 * @dataProvider provideRevisionHeader
	 */
	public function testRevisionHeader( $deletedFlag, $allowedAction ) {
		$revs = self::$revisions;

		if ( $deletedFlag !== 'none' ) {
			$this->revisionDelete(
				$revs[1],
				[
					RevisionRecord::DELETED_TEXT => 1,
					RevisionRecord::DELETED_RESTRICTED => $deletedFlag === 'suppressed' ? 1 : 0,
				],
				'Testing'
			);
		}

		$context = new DerivativeContext( $this->context );
		$context->setLanguage( 'qqx' );
		$permissionSet = [];
		if ( $allowedAction !== 'none' ) {
			if ( $allowedAction === 'edit' ) {
				$permissionSet[] = 'edit';
			}
			if ( $deletedFlag === 'suppressed' ) {
				$permissionSet[] = 'suppressrevision';
			} else {
				$permissionSet[] = 'deletedtext';
			}
		}
		$context->setAuthority(
			new SimpleAuthority( $this->getTestUser()->getUser(), $permissionSet )
		);

		$diffEngine = new DifferenceEngine( $context, $revs[1], $revs[2], 2, true, true );
		$this->assertTrue( $diffEngine->loadRevisionData() );
		$revisionHeaderHtml = $diffEngine->getRevisionHeader( $diffEngine->getOldRevision(), 'complete' );

		// Always show the timestamp
		$this->assertStringContainsString( '(revisionasof:', $revisionHeaderHtml );

		if ( $allowedAction === 'none' ) {
			$this->assertStringNotContainsString( 'oldid=' . $revs[1], $revisionHeaderHtml );
		} else {
			$this->assertStringContainsString( 'oldid=' . $revs[1], $revisionHeaderHtml );
		}
		if ( $allowedAction === 'edit' ) {
			$this->assertStringContainsString( '(editold)', $revisionHeaderHtml );
		} else {
			$this->assertStringNotContainsString( '(editold)', $revisionHeaderHtml );
		}
		if ( $allowedAction === 'view' ) {
			$this->assertStringContainsString( '(viewsourceold)', $revisionHeaderHtml );
		} else {
			$this->assertStringNotContainsString( '(viewsourceold)', $revisionHeaderHtml );
		}

		if ( $deletedFlag === 'none' ) {
			$this->assertStringNotContainsString( 'history-deleted', $revisionHeaderHtml );
		} else {
			$this->assertStringContainsString( 'history-deleted', $revisionHeaderHtml );
		}
		if ( $deletedFlag === 'suppressed' ) {
			$this->assertStringContainsString( 'mw-history-suppressed', $revisionHeaderHtml );
		} else {
			$this->assertStringNotContainsString( 'mw-history-suppressed', $revisionHeaderHtml );
		}
	}

	public static function provideRevisionHeader() {
		return [
			[ 'none', 'view' ],
			[ 'none', 'edit' ],
			[ 'deleted', 'none' ],
			[ 'deleted', 'view' ],
			[ 'deleted', 'edit' ],
			[ 'suppressed', 'none' ],
			[ 'suppressed', 'view' ],
			[ 'suppressed', 'edit' ],
		];
	}
}
