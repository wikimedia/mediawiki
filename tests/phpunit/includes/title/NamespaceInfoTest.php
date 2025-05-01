<?php
/**
 * @author Antoine Musso
 * @copyright Copyright © 2011, Antoine Musso
 * @file
 */

use MediaWiki\Config\ServiceOptions;
use MediaWiki\Exception\MWException;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\MainConfigNames;
use MediaWiki\Title\NamespaceInfo;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleValue;

class NamespaceInfoTest extends MediaWikiIntegrationTestCase {
	use TestAllServiceOptionsUsed;

	private const TEST_EXT_NAMESPACES = [ NS_MAIN => 'No effect', NS_TALK => 'No effect', 12345 => 'Extended' ];

	/**********************************************************************************************
	 * Shared code
	 * %{
	 */

	private const DEFAULT_OPTIONS = [
		MainConfigNames::CanonicalNamespaceNames => [
			NS_TALK => 'Talk',
			NS_USER => 'User',
			NS_USER_TALK => 'User_talk',
			NS_SPECIAL => 'Special',
			NS_MEDIA => 'Media',
		],
		MainConfigNames::CapitalLinkOverrides => [],
		MainConfigNames::CapitalLinks => true,
		MainConfigNames::ContentNamespaces => [ NS_MAIN ],
		MainConfigNames::ExtraNamespaces => [],
		MainConfigNames::ExtraSignatureNamespaces => [],
		MainConfigNames::NamespaceContentModels => [],
		MainConfigNames::NamespacesWithSubpages => [
			NS_TALK => true,
			NS_USER => true,
			NS_USER_TALK => true,
		],
		MainConfigNames::NonincludableNamespaces => [],
	];

	/**
	 * @return HookContainer
	 */
	private function getHookContainer() {
		return $this->getServiceContainer()->getHookContainer();
	}

	private function newObj( array $options = [], array $extensionNamespaces = [] ): NamespaceInfo {
		return new NamespaceInfo(
			new LoggedServiceOptions(
				self::$serviceOptionsAccessLog,
				NamespaceInfo::CONSTRUCTOR_OPTIONS,
				$options, self::DEFAULT_OPTIONS
			),
			$this->getHookContainer(),
			$extensionNamespaces,
			[]
		);
	}

	// %} End shared code

	/**********************************************************************************************
	 * Basic methods
	 * %{
	 */

	/**
	 * @covers \MediaWiki\Title\NamespaceInfo::__construct
	 * @dataProvider provideConstructor
	 * @param ServiceOptions $options
	 * @param string|null $expectedExceptionText
	 */
	public function testConstructor( ServiceOptions $options, $expectedExceptionText = null ) {
		if ( $expectedExceptionText !== null ) {
			$this->expectException( \Wikimedia\Assert\PreconditionException::class );
			$this->expectExceptionMessage( $expectedExceptionText );
		}
		new NamespaceInfo( $options, $this->getHookContainer(), [], [] );
		$this->assertTrue( true );
	}

	public static function provideConstructor() {
		return [
			[ new ServiceOptions( NamespaceInfo::CONSTRUCTOR_OPTIONS, self::DEFAULT_OPTIONS ) ],
			[ new ServiceOptions( [], [] ), 'Required options missing: ' ],
			[ new ServiceOptions(
				array_merge( NamespaceInfo::CONSTRUCTOR_OPTIONS, [ 'invalid' ] ),
				self::DEFAULT_OPTIONS,
				[ 'invalid' => '' ]
			), 'Unsupported options passed: invalid' ],
		];
	}

	/**
	 * @dataProvider provideIsMovable
	 * @covers \MediaWiki\Title\NamespaceInfo::isMovable
	 *
	 * @param bool $expected
	 * @param int $ns
	 */
	public function testIsMovable( $expected, $ns ) {
		$obj = $this->newObj();
		$this->assertSame( $expected, $obj->isMovable( $ns ) );
	}

	public static function provideIsMovable() {
		return [
			'Main' => [ true, NS_MAIN ],
			'Talk' => [ true, NS_TALK ],
			'Special' => [ false, NS_SPECIAL ],
			'Nonexistent even namespace' => [ true, 1234 ],
			'Nonexistent odd namespace' => [ true, 12345 ],

			'Media' => [ false, NS_MEDIA ],
			'File' => [ true, NS_FILE ],
		];
	}

	/**
	 * @param int $ns
	 * @param bool $expected
	 * @dataProvider provideIsSubject
	 * @covers \MediaWiki\Title\NamespaceInfo::isSubject
	 */
	public function testIsSubject( $ns, $expected ) {
		$this->assertSame( $expected, $this->newObj()->isSubject( $ns ) );
	}

	/**
	 * @param int $ns
	 * @param bool $expected
	 * @dataProvider provideIsSubject
	 * @covers \MediaWiki\Title\NamespaceInfo::isTalk
	 */
	public function testIsTalk( $ns, $expected ) {
		$this->assertSame( !$expected, $this->newObj()->isTalk( $ns ) );
	}

	public static function provideIsSubject() {
		return [
			// Special namespaces
			[ NS_MEDIA, true ],
			[ NS_SPECIAL, true ],

			// Subject pages
			[ NS_MAIN, true ],
			[ NS_USER, true ],
			[ 100, true ],

			// Talk pages
			[ NS_TALK, false ],
			[ NS_USER_TALK, false ],
			[ 101, false ],
		];
	}

	/**
	 * @covers \MediaWiki\Title\NamespaceInfo::exists
	 * @dataProvider provideExists
	 * @param int $ns
	 * @param bool $expected
	 */
	public function testExists( $ns, $expected ) {
		$this->assertSame( $expected, $this->newObj()->exists( $ns ) );
	}

	public static function provideExists() {
		return [
			'Main' => [ NS_MAIN, true ],
			'Talk' => [ NS_TALK, true ],
			'Media' => [ NS_MEDIA, true ],
			'Special' => [ NS_SPECIAL, true ],
			'Nonexistent' => [ 12345, false ],
			'Negative nonexistent' => [ -12345, false ],
		];
	}

	/**
	 * Note if we add a namespace registration system with keys like 'MAIN'
	 * we should add tests here for equivalence on things like 'MAIN' == 0
	 * and 'MAIN' == NS_MAIN.
	 * @covers \MediaWiki\Title\NamespaceInfo::equals
	 */
	public function testEquals() {
		$obj = $this->newObj();
		$this->assertTrue( $obj->equals( NS_MAIN, NS_MAIN ) );
		$this->assertTrue( $obj->equals( NS_MAIN, 0 ) ); // In case we make NS_MAIN 'MAIN'
		$this->assertTrue( $obj->equals( NS_USER, NS_USER ) );
		$this->assertTrue( $obj->equals( NS_USER, 2 ) );
		$this->assertTrue( $obj->equals( NS_USER_TALK, NS_USER_TALK ) );
		$this->assertTrue( $obj->equals( NS_SPECIAL, NS_SPECIAL ) );
		$this->assertFalse( $obj->equals( NS_MAIN, NS_TALK ) );
		$this->assertFalse( $obj->equals( NS_USER, NS_USER_TALK ) );
		$this->assertFalse( $obj->equals( NS_PROJECT, NS_TEMPLATE ) );
	}

	/**
	 * @param int $ns1
	 * @param int $ns2
	 * @param bool $expected
	 * @dataProvider provideSubjectEquals
	 * @covers \MediaWiki\Title\NamespaceInfo::subjectEquals
	 */
	public function testSubjectEquals( $ns1, $ns2, $expected ) {
		$this->assertSame( $expected, $this->newObj()->subjectEquals( $ns1, $ns2 ) );
	}

	public static function provideSubjectEquals() {
		return [
			[ NS_MAIN, NS_MAIN, true ],
			// In case we make NS_MAIN 'MAIN'
			[ NS_MAIN, 0, true ],
			[ NS_USER, NS_USER, true ],
			[ NS_USER, 2, true ],
			[ NS_USER_TALK, NS_USER_TALK, true ],
			[ NS_SPECIAL, NS_SPECIAL, true ],
			[ NS_MAIN, NS_TALK, true ],
			[ NS_USER, NS_USER_TALK, true ],

			[ NS_PROJECT, NS_TEMPLATE, false ],
			[ NS_SPECIAL, NS_MAIN, false ],
			[ NS_MEDIA, NS_SPECIAL, false ],
			[ NS_SPECIAL, NS_MEDIA, false ],
		];
	}

	/**
	 * @dataProvider provideHasTalkNamespace
	 * @covers \MediaWiki\Title\NamespaceInfo::hasTalkNamespace
	 *
	 * @param int $ns
	 * @param bool $expected
	 */
	public function testHasTalkNamespace( $ns, $expected ) {
		$this->assertSame( $expected, $this->newObj()->hasTalkNamespace( $ns ) );
	}

	public static function provideHasTalkNamespace() {
		return [
			[ NS_MEDIA, false ],
			[ NS_SPECIAL, false ],

			[ NS_MAIN, true ],
			[ NS_TALK, true ],
			[ NS_USER, true ],
			[ NS_USER_TALK, true ],

			[ 100, true ],
			[ 101, true ],
		];
	}

	/**
	 * @param int $ns
	 * @param bool $expected
	 * @param array $contentNamespaces
	 * @covers \MediaWiki\Title\NamespaceInfo::isContent
	 * @dataProvider provideIsContent
	 */
	public function testIsContent( $ns, $expected, $contentNamespaces = [ NS_MAIN ] ) {
		$obj = $this->newObj( [ MainConfigNames::ContentNamespaces => $contentNamespaces ] );
		$this->assertSame( $expected, $obj->isContent( $ns ) );
	}

	public static function provideIsContent() {
		return [
			[ NS_MAIN, true ],
			[ NS_MEDIA, false ],
			[ NS_SPECIAL, false ],
			[ NS_TALK, false ],
			[ NS_USER, false ],
			[ NS_CATEGORY, false ],
			[ 100, false ],
			[ 100, true, [ NS_MAIN, 100, 252 ] ],
			[ 252, true, [ NS_MAIN, 100, 252 ] ],
			[ NS_MAIN, true, [ NS_MAIN, 100, 252 ] ],
			// NS_MAIN is always content
			[ NS_MAIN, true, [] ],
		];
	}

	/**
	 * @dataProvider provideWantSignatures
	 * @covers \MediaWiki\Title\NamespaceInfo::wantSignatures
	 *
	 * @param int $index
	 * @param bool $expected
	 */
	public function testWantSignatures( $index, $expected ) {
		$this->assertSame( $expected, $this->newObj()->wantSignatures( $index ) );
	}

	public static function provideWantSignatures() {
		return [
			'Main' => [ NS_MAIN, false ],
			'Talk' => [ NS_TALK, true ],
			'User' => [ NS_USER, false ],
			'User talk' => [ NS_USER_TALK, true ],
			'Special' => [ NS_SPECIAL, false ],
			'Media' => [ NS_MEDIA, false ],
			'Nonexistent talk' => [ 12345, true ],
			'Nonexistent subject' => [ 123456, false ],
			'Nonexistent negative odd' => [ -12345, false ],
		];
	}

	/**
	 * @dataProvider provideWantSignatures_ExtraSignatureNamespaces
	 * @covers \MediaWiki\Title\NamespaceInfo::wantSignatures
	 *
	 * @param int $index
	 * @param int $expected
	 */
	public function testWantSignatures_ExtraSignatureNamespaces( $index, $expected ) {
		$obj = $this->newObj( [ MainConfigNames::ExtraSignatureNamespaces =>
			[ NS_MAIN, NS_USER, NS_SPECIAL, NS_MEDIA, 123456, -12345 ] ] );
		$this->assertSame( $expected, $obj->wantSignatures( $index ) );
	}

	public static function provideWantSignatures_ExtraSignatureNamespaces() {
		$ret = array_map(
			static function ( $arr ) {
				// We've added all these as extra signature namespaces, so expect true
				return [ $arr[0], true ];
			},
			self::provideWantSignatures()
		);

		// Add one more that's false
		$ret['Another nonexistent subject'] = [ 12345678, false ];
		return $ret;
	}

	/**
	 * @param int $ns
	 * @param bool $expected
	 * @covers \MediaWiki\Title\NamespaceInfo::isWatchable
	 * @dataProvider provideIsWatchable
	 */
	public function testIsWatchable( $ns, $expected ) {
		$this->assertSame( $expected, $this->newObj()->isWatchable( $ns ) );
	}

	public static function provideIsWatchable() {
		return [
			// Specials namespaces are not watchable
			[ NS_MEDIA, false ],
			[ NS_SPECIAL, false ],

			// Core defined namespaces are watchables
			[ NS_MAIN, true ],
			[ NS_TALK, true ],

			// Additional, user defined namespaces are watchables
			[ 100, true ],
			[ 101, true ],
		];
	}

	/**
	 * @param int $ns
	 * @param int $expected
	 * @param array|null $namespacesWithSubpages To pass to constructor
	 * @covers \MediaWiki\Title\NamespaceInfo::hasSubpages
	 * @dataProvider provideHasSubpages
	 */
	public function testHasSubpages( $ns, $expected, ?array $namespacesWithSubpages = null ) {
		$obj = $this->newObj( $namespacesWithSubpages
			? [ MainConfigNames::NamespacesWithSubpages => $namespacesWithSubpages ]
			: [] );
		$this->assertSame( $expected, $obj->hasSubpages( $ns ) );
	}

	public static function provideHasSubpages() {
		return [
			// Special namespaces:
			[ NS_MEDIA, false ],
			[ NS_SPECIAL, false ],

			// Namespaces without subpages
			[ NS_MAIN, false ],
			[ NS_MAIN, true, [ NS_MAIN => true ] ],
			[ NS_MAIN, false, [ NS_MAIN => false ] ],

			// Some namespaces with subpages
			[ NS_TALK, true ],
			[ NS_USER, true ],
			[ NS_USER_TALK, true ],
		];
	}

	/**
	 * @dataProvider provideGetContentNamespaces
	 * @covers \MediaWiki\Title\NamespaceInfo::getContentNamespaces
	 */
	public function testGetContentNamespaces( $contentNamespaces, array $expected ) {
		$obj = $this->newObj( [ MainConfigNames::ContentNamespaces => $contentNamespaces ] );
		$this->assertSame( $expected, $obj->getContentNamespaces() );
	}

	public static function provideGetContentNamespaces() {
		return [
			// Non-array
			[ '', [ NS_MAIN ] ],
			[ false, [ NS_MAIN ] ],
			[ null, [ NS_MAIN ] ],
			[ 5, [ NS_MAIN ] ],

			// Empty array
			[ [], [ NS_MAIN ] ],

			// NS_MAIN is forced to be content even if unwanted
			[ [ NS_USER, NS_CATEGORY ], [ NS_MAIN, NS_USER, NS_CATEGORY ] ],

			// In other cases, return as-is
			[ [ NS_MAIN ], [ NS_MAIN ] ],
			[ [ NS_MAIN, NS_USER, NS_CATEGORY ], [ NS_MAIN, NS_USER, NS_CATEGORY ] ],
		];
	}

	/**
	 * @covers \MediaWiki\Title\NamespaceInfo::getSubjectNamespaces
	 */
	public function testGetSubjectNamespaces() {
		$subjectsNS = $this->newObj()->getSubjectNamespaces();
		$this->assertContains( NS_MAIN, $subjectsNS,
			"Talk namespaces should have NS_MAIN" );
		$this->assertNotContains( NS_TALK, $subjectsNS,
			"Talk namespaces should have NS_TALK" );

		$this->assertNotContains( NS_MEDIA, $subjectsNS,
			"Talk namespaces should not have NS_MEDIA" );
		$this->assertNotContains( NS_SPECIAL, $subjectsNS,
			"Talk namespaces should not have NS_SPECIAL" );
	}

	/**
	 * @covers \MediaWiki\Title\NamespaceInfo::getTalkNamespaces
	 */
	public function testGetTalkNamespaces() {
		$talkNS = $this->newObj()->getTalkNamespaces();
		$this->assertContains( NS_TALK, $talkNS,
			"Subject namespaces should have NS_TALK" );
		$this->assertNotContains( NS_MAIN, $talkNS,
			"Subject namespaces should not have NS_MAIN" );

		$this->assertNotContains( NS_MEDIA, $talkNS,
			"Subject namespaces should not have NS_MEDIA" );
		$this->assertNotContains( NS_SPECIAL, $talkNS,
			"Subject namespaces should not have NS_SPECIAL" );
	}

	/**
	 * @param int $ns
	 * @param bool $expected
	 * @param bool $capitalLinks To pass to constructor
	 * @param array $capitalLinkOverrides To pass to constructor
	 * @dataProvider provideIsCapitalized
	 * @covers \MediaWiki\Title\NamespaceInfo::isCapitalized
	 */
	public function testIsCapitalized(
		$ns, $expected, $capitalLinks = true, array $capitalLinkOverrides = []
	) {
		$obj = $this->newObj( [
			MainConfigNames::CapitalLinks => $capitalLinks,
			MainConfigNames::CapitalLinkOverrides => $capitalLinkOverrides,
		] );
		$this->assertSame( $expected, $obj->isCapitalized( $ns ) );
	}

	public static function provideIsCapitalized() {
		return [
			// Test default settings
			[ NS_PROJECT, true ],
			[ NS_PROJECT_TALK, true ],
			[ NS_MEDIA, true ],
			[ NS_FILE, true ],

			// Always capitalized no matter what
			[ NS_SPECIAL, true, false ],
			[ NS_USER, true, false ],
			[ NS_MEDIAWIKI, true, false ],

			// Even with an override too
			[ NS_SPECIAL, true, false, [ NS_SPECIAL => false ] ],
			[ NS_USER, true, false, [ NS_USER => false ] ],
			[ NS_MEDIAWIKI, true, false, [ NS_MEDIAWIKI => false ] ],

			// Overrides work for other namespaces
			[ NS_PROJECT, false, true, [ NS_PROJECT => false ] ],
			[ NS_PROJECT, true, false, [ NS_PROJECT => true ] ],

			// NS_MEDIA is treated like NS_FILE, and ignores NS_MEDIA overrides
			[ NS_MEDIA, false, true, [ NS_FILE => false, NS_MEDIA => true ] ],
			[ NS_MEDIA, true, false, [ NS_FILE => true, NS_MEDIA => false ] ],
			[ NS_FILE, false, true, [ NS_FILE => false, NS_MEDIA => true ] ],
			[ NS_FILE, true, false, [ NS_FILE => true, NS_MEDIA => false ] ],
		];
	}

	/**
	 * @covers \MediaWiki\Title\NamespaceInfo::hasGenderDistinction
	 */
	public function testHasGenderDistinction() {
		$obj = $this->newObj();

		// Namespaces with gender distinctions
		$this->assertTrue( $obj->hasGenderDistinction( NS_USER ) );
		$this->assertTrue( $obj->hasGenderDistinction( NS_USER_TALK ) );

		// Other ones, "genderless"
		$this->assertFalse( $obj->hasGenderDistinction( NS_MEDIA ) );
		$this->assertFalse( $obj->hasGenderDistinction( NS_SPECIAL ) );
		$this->assertFalse( $obj->hasGenderDistinction( NS_MAIN ) );
		$this->assertFalse( $obj->hasGenderDistinction( NS_TALK ) );
	}

	/**
	 * @covers \MediaWiki\Title\NamespaceInfo::isNonincludable
	 */
	public function testIsNonincludable() {
		$obj = $this->newObj( [ MainConfigNames::NonincludableNamespaces => [ NS_USER ] ] );
		$this->assertTrue( $obj->isNonincludable( NS_USER ) );
		$this->assertFalse( $obj->isNonincludable( NS_TEMPLATE ) );
	}

	/**
	 * @dataProvider provideGetNamespaceContentModel
	 * @covers \MediaWiki\Title\NamespaceInfo::getNamespaceContentModel
	 *
	 * @param int $ns
	 * @param string $expected
	 */
	public function testGetNamespaceContentModel( $ns, $expected ) {
		$obj = $this->newObj( [ MainConfigNames::NamespaceContentModels =>
			[ NS_USER => CONTENT_MODEL_WIKITEXT, 123 => CONTENT_MODEL_JSON, 1234 => 'abcdef' ],
		] );
		$this->assertSame( $expected, $obj->getNamespaceContentModel( $ns ) );
	}

	public static function provideGetNamespaceContentModel() {
		return [
			[ NS_MAIN, null ],
			[ NS_TALK, null ],
			[ NS_USER, CONTENT_MODEL_WIKITEXT ],
			[ NS_USER_TALK, null ],
			[ NS_SPECIAL, null ],
			[ 122, null ],
			[ 123, CONTENT_MODEL_JSON ],
			[ 1234, 'abcdef' ],
			[ 1235, null ],
		];
	}

	/**
	 * @dataProvider provideGetCategoryLinkType
	 * @covers \MediaWiki\Title\NamespaceInfo::getCategoryLinkType
	 *
	 * @param int $ns
	 * @param string $expected
	 */
	public function testGetCategoryLinkType( $ns, $expected ) {
		$this->assertSame( $expected, $this->newObj()->getCategoryLinkType( $ns ) );
	}

	public static function provideGetCategoryLinkType() {
		return [
			[ NS_MAIN, 'page' ],
			[ NS_TALK, 'page' ],
			[ NS_USER, 'page' ],
			[ NS_USER_TALK, 'page' ],

			[ NS_FILE, 'file' ],
			[ NS_FILE_TALK, 'page' ],

			[ NS_CATEGORY, 'subcat' ],
			[ NS_CATEGORY_TALK, 'page' ],

			[ 100, 'page' ],
			[ 101, 'page' ],
		];
	}

	// %} End basic methods

	/**********************************************************************************************
	 * getSubject/Talk/Associated
	 * %{
	 */

	/**
	 * @dataProvider provideSubjectTalk
	 * @covers \MediaWiki\Title\NamespaceInfo::getSubject
	 * @covers \MediaWiki\Title\NamespaceInfo::getSubjectPage
	 * @covers \MediaWiki\Title\NamespaceInfo::isMethodValidFor
	 * @covers \MediaWiki\Title\Title::getSubjectPage
	 *
	 * @param int $subject
	 * @param int $talk
	 */
	public function testGetSubject( $subject, $talk ) {
		$obj = $this->newObj();
		$this->assertSame( $subject, $obj->getSubject( $subject ) );
		$this->assertSame( $subject, $obj->getSubject( $talk ) );

		$subjectTitleVal = new TitleValue( $subject, 'A' );
		$talkTitleVal = new TitleValue( $talk, 'A' );
		// Object will be the same one passed in if it's a subject, different but equal object if
		// it's talk
		$this->assertSame( $subjectTitleVal, $obj->getSubjectPage( $subjectTitleVal ) );
		$this->assertEquals( $subjectTitleVal, $obj->getSubjectPage( $talkTitleVal ) );

		$subjectTitle = Title::makeTitle( $subject, 'A' );
		$talkTitle = Title::makeTitle( $talk, 'A' );
		$this->assertSame( $subjectTitle, $subjectTitle->getSubjectPage() );
		$this->assertEquals( $subjectTitle, $talkTitle->getSubjectPage() );
	}

	/**
	 * @dataProvider provideSpecialNamespaces
	 * @covers \MediaWiki\Title\NamespaceInfo::getSubject
	 * @covers \MediaWiki\Title\NamespaceInfo::getSubjectPage
	 *
	 * @param int $ns
	 */
	public function testGetSubject_special( $ns ) {
		$obj = $this->newObj();
		$this->assertSame( $ns, $obj->getSubject( $ns ) );

		$title = new TitleValue( $ns, 'A' );
		$this->assertSame( $title, $obj->getSubjectPage( $title ) );
	}

	/**
	 * @dataProvider provideSubjectTalk
	 * @covers \MediaWiki\Title\NamespaceInfo::getTalk
	 * @covers \MediaWiki\Title\NamespaceInfo::getTalkPage
	 * @covers \MediaWiki\Title\NamespaceInfo::isMethodValidFor
	 * @covers \MediaWiki\Title\Title::getTalkPage
	 *
	 * @param int $subject
	 * @param int $talk
	 */
	public function testGetTalk( $subject, $talk ) {
		$obj = $this->newObj();
		$this->assertSame( $talk, $obj->getTalk( $subject ) );
		$this->assertSame( $talk, $obj->getTalk( $talk ) );

		$subjectTitleVal = new TitleValue( $subject, 'A' );
		$talkTitleVal = new TitleValue( $talk, 'A' );
		// Object will be the same one passed in if it's a talk, different but equal object if it's
		// subject
		$this->assertEquals( $talkTitleVal, $obj->getTalkPage( $subjectTitleVal ) );
		$this->assertSame( $talkTitleVal, $obj->getTalkPage( $talkTitleVal ) );

		$subjectTitle = Title::makeTitle( $subject, 'A' );
		$talkTitle = Title::makeTitle( $talk, 'A' );
		$this->assertEquals( $talkTitle, $subjectTitle->getTalkPage() );
		$this->assertSame( $talkTitle, $talkTitle->getTalkPage() );
	}

	/**
	 * @dataProvider provideSpecialNamespaces
	 * @covers \MediaWiki\Title\NamespaceInfo::getTalk
	 * @covers \MediaWiki\Title\NamespaceInfo::isMethodValidFor
	 *
	 * @param int $ns
	 */
	public function testGetTalk_special( $ns ) {
		$this->expectException( MWException::class );
		$this->expectExceptionMessage(
			"NamespaceInfo::getTalk does not make any sense for given namespace $ns"
		);
		$this->newObj()->getTalk( $ns );
	}

	/**
	 * @dataProvider provideSpecialNamespaces
	 * @covers \MediaWiki\Title\NamespaceInfo::getAssociated
	 * @covers \MediaWiki\Title\NamespaceInfo::isMethodValidFor
	 *
	 * @param int $ns
	 */
	public function testGetAssociated_special( $ns ) {
		$this->expectException( MWException::class );
		$this->expectExceptionMessage(
			"NamespaceInfo::getAssociated does not make any sense for given namespace $ns"
		);
		$this->newObj()->getAssociated( $ns );
	}

	public static function provideCanHaveTalkPage() {
		return [
			[ new TitleValue( NS_MAIN, 'Test' ), true ],
			[ new TitleValue( NS_TALK, 'Test' ), true ],
			[ new TitleValue( NS_USER, 'Test' ), true ],
			[ new TitleValue( NS_SPECIAL, 'Test' ), false ],
			[ new TitleValue( NS_MEDIA, 'Test' ), false ],
			[ new TitleValue( NS_MAIN, '', 'Kittens' ), false ],
			[ new TitleValue( NS_MAIN, 'Kittens', '', 'acme' ), false ],
		];
	}

	/**
	 * @dataProvider provideCanHaveTalkPage
	 * @covers \MediaWiki\Title\NamespaceInfo::canHaveTalkPage
	 */
	public function testCanHaveTalkPage( LinkTarget $t, $expected ) {
		$actual = $this->newObj()->canHaveTalkPage( $t );
		$this->assertEquals( $expected, $actual, $t->getDBkey() );
	}

	public static function provideGetTalkPage_good() {
		return [
			[ new TitleValue( NS_MAIN, 'Test' ), new TitleValue( NS_TALK, 'Test' ) ],
			[ new TitleValue( NS_TALK, 'Test' ), new TitleValue( NS_TALK, 'Test' ) ],
			[ new TitleValue( NS_USER, 'Test' ), new TitleValue( NS_USER_TALK, 'Test' ) ],
		];
	}

	/**
	 * @dataProvider provideGetTalkPage_good
	 * @covers \MediaWiki\Title\NamespaceInfo::getTalk
	 * @covers \MediaWiki\Title\NamespaceInfo::getTalkPage
	 * @covers \MediaWiki\Title\NamespaceInfo::isMethodValidFor
	 */
	public function testGetTalkPage_good( LinkTarget $t, LinkTarget $expected ) {
		$actual = $this->newObj()->getTalkPage( $t );
		$this->assertEquals( $expected, $actual, $t->getDBkey() );
	}

	public static function provideGetTalkPage_bad() {
		return [
			[ new TitleValue( NS_SPECIAL, 'Test' ) ],
			[ new TitleValue( NS_MEDIA, 'Test' ) ],
			[ new TitleValue( NS_MAIN, '', 'Kittens' ) ],
			[ new TitleValue( NS_MAIN, 'Kittens', '', 'acme' ) ],
		];
	}

	/**
	 * @dataProvider provideGetTalkPage_bad
	 * @covers \MediaWiki\Title\NamespaceInfo::getTalk
	 * @covers \MediaWiki\Title\NamespaceInfo::getTalkPage
	 * @covers \MediaWiki\Title\NamespaceInfo::isMethodValidFor
	 */
	public function testGetTalkPage_bad( LinkTarget $t ) {
		$this->expectException( MWException::class );
		$this->newObj()->getTalkPage( $t );
	}

	/**
	 * @dataProvider provideGetTalkPage_bad
	 * @covers \MediaWiki\Title\NamespaceInfo::getAssociated
	 * @covers \MediaWiki\Title\NamespaceInfo::getAssociatedPage
	 * @covers \MediaWiki\Title\NamespaceInfo::isMethodValidFor
	 */
	public function testGetAssociatedPage_bad( LinkTarget $t ) {
		$this->expectException( MWException::class );
		$this->newObj()->getAssociatedPage( $t );
	}

	/**
	 * @dataProvider provideSubjectTalk
	 * @covers \MediaWiki\Title\NamespaceInfo::getAssociated
	 * @covers \MediaWiki\Title\NamespaceInfo::getAssociatedPage
	 * @covers \MediaWiki\Title\Title::getOtherPage
	 *
	 * @param int $subject
	 * @param int $talk
	 */
	public function testGetAssociated( $subject, $talk ) {
		$obj = $this->newObj();
		$this->assertSame( $talk, $obj->getAssociated( $subject ) );
		$this->assertSame( $subject, $obj->getAssociated( $talk ) );

		$subjectTitle = new TitleValue( $subject, 'A' );
		$talkTitle = new TitleValue( $talk, 'A' );
		// Object will not be the same
		$this->assertEquals( $talkTitle, $obj->getAssociatedPage( $subjectTitle ) );
		$this->assertEquals( $subjectTitle, $obj->getAssociatedPage( $talkTitle ) );

		$subjectTitle = Title::makeTitle( $subject, 'A' );
		$talkTitle = Title::makeTitle( $talk, 'A' );
		$this->assertEquals( $talkTitle, $subjectTitle->getOtherPage() );
		$this->assertEquals( $subjectTitle, $talkTitle->getOtherPage() );
	}

	public static function provideSubjectTalk() {
		return [
			// Format: [ subject, talk ]
			'Main/talk' => [ NS_MAIN, NS_TALK ],
			'User/user talk' => [ NS_USER, NS_USER_TALK ],
			'Unknown namespaces also supported' => [ 106, 107 ],
		];
	}

	public static function provideSpecialNamespaces() {
		return [
			'Special' => [ NS_SPECIAL ],
			'Media' => [ NS_MEDIA ],
			'Unknown negative index' => [ -613 ],
		];
	}

	// %} End getSubject/Talk/Associated

	/**********************************************************************************************
	 * Canonical namespaces
	 * %{
	 */

	// Default canonical namespaces
	// %{
	private function getDefaultNamespaces() {
		return [ NS_MAIN => '' ] + self::DEFAULT_OPTIONS['CanonicalNamespaceNames'];
	}

	/**
	 * @covers \MediaWiki\Title\NamespaceInfo::getCanonicalNamespaces
	 */
	public function testGetCanonicalNamespaces() {
		$this->assertSame(
			$this->getDefaultNamespaces(),
			$this->newObj()->getCanonicalNamespaces()
		);
	}

	/**
	 * @dataProvider provideGetCanonicalName
	 * @covers \MediaWiki\Title\NamespaceInfo::getCanonicalName
	 *
	 * @param int $index
	 * @param string|bool $expected
	 */
	public function testGetCanonicalName( $index, $expected ) {
		$this->assertSame( $expected, $this->newObj()->getCanonicalName( $index ) );
	}

	public static function provideGetCanonicalName() {
		return [
			'Main' => [ NS_MAIN, '' ],
			'Talk' => [ NS_TALK, 'Talk' ],
			'With underscore not space' => [ NS_USER_TALK, 'User_talk' ],
			'Special' => [ NS_SPECIAL, 'Special' ],
			'Nonexistent' => [ 12345, false ],
			'Nonexistent negative' => [ -12345, false ],
		];
	}

	/**
	 * @dataProvider provideGetCanonicalIndex
	 * @covers \MediaWiki\Title\NamespaceInfo::getCanonicalIndex
	 *
	 * @param string $name
	 * @param int|null $expected
	 */
	public function testGetCanonicalIndex( $name, $expected ) {
		$this->assertSame( $expected, $this->newObj()->getCanonicalIndex( $name ) );
	}

	public static function provideGetCanonicalIndex() {
		return [
			'Main' => [ '', NS_MAIN ],
			'Talk' => [ 'talk', NS_TALK ],
			'Not lowercase' => [ 'Talk', null ],
			'With underscore' => [ 'user_talk', NS_USER_TALK ],
			'Space is not recognized for underscore' => [ 'user talk', null ],
			'0' => [ '0', null ],
		];
	}

	/**
	 * @covers \MediaWiki\Title\NamespaceInfo::getValidNamespaces
	 */
	public function testGetValidNamespaces() {
		$this->assertSame(
			[ NS_MAIN, NS_TALK, NS_USER, NS_USER_TALK ],
			$this->newObj()->getValidNamespaces()
		);
	}

	// %} End default canonical namespaces

	// No canonical namespace names
	// %{

	/**
	 * @covers \MediaWiki\Title\NamespaceInfo::getCanonicalNamespaces
	 */
	public function testGetCanonicalNamespaces_NoCanonicalNamespaceNames() {
		$obj = $this->newObj( [ MainConfigNames::CanonicalNamespaceNames => [] ] );

		$this->assertSame( [ NS_MAIN => '' ], $obj->getCanonicalNamespaces() );
	}

	/**
	 * @covers \MediaWiki\Title\NamespaceInfo::getCanonicalName
	 */
	public function testGetCanonicalName_NoCanonicalNamespaceNames() {
		$obj = $this->newObj( [ MainConfigNames::CanonicalNamespaceNames => [] ] );

		$this->assertSame( '', $obj->getCanonicalName( NS_MAIN ) );
		$this->assertFalse( $obj->getCanonicalName( NS_TALK ) );
	}

	/**
	 * @covers \MediaWiki\Title\NamespaceInfo::getCanonicalIndex
	 */
	public function testGetCanonicalIndex_NoCanonicalNamespaceNames() {
		$obj = $this->newObj( [ MainConfigNames::CanonicalNamespaceNames => [] ] );

		$this->assertSame( NS_MAIN, $obj->getCanonicalIndex( '' ) );
		$this->assertNull( $obj->getCanonicalIndex( 'talk' ) );
	}

	/**
	 * @covers \MediaWiki\Title\NamespaceInfo::getValidNamespaces
	 */
	public function testGetValidNamespaces_NoCanonicalNamespaceNames() {
		$obj = $this->newObj( [ MainConfigNames::CanonicalNamespaceNames => [] ] );

		$this->assertSame( [ NS_MAIN ], $obj->getValidNamespaces() );
	}

	// %} End no canonical namespace names

	// Test extension namespaces
	// %{

	/**
	 * @covers \MediaWiki\Title\NamespaceInfo::getCanonicalNamespaces
	 */
	public function testGetCanonicalNamespaces_ExtensionNamespaces() {
		$this->assertSame(
			$this->getDefaultNamespaces() + [ 12345 => 'Extended' ],
			$this->newObj( [], self::TEST_EXT_NAMESPACES )->getCanonicalNamespaces()
		);
	}

	/**
	 * @covers \MediaWiki\Title\NamespaceInfo::getCanonicalName
	 */
	public function testGetCanonicalName_ExtensionNamespaces() {
		$obj = $this->newObj( [], self::TEST_EXT_NAMESPACES );

		$this->assertSame( '', $obj->getCanonicalName( NS_MAIN ) );
		$this->assertSame( 'Talk', $obj->getCanonicalName( NS_TALK ) );
		$this->assertSame( 'Extended', $obj->getCanonicalName( 12345 ) );
	}

	/**
	 * @covers \MediaWiki\Title\NamespaceInfo::getCanonicalIndex
	 */
	public function testGetCanonicalIndex_ExtensionNamespaces() {
		$obj = $this->newObj( [], self::TEST_EXT_NAMESPACES );

		$this->assertSame( NS_MAIN, $obj->getCanonicalIndex( '' ) );
		$this->assertSame( NS_TALK, $obj->getCanonicalIndex( 'talk' ) );
		$this->assertSame( 12345, $obj->getCanonicalIndex( 'extended' ) );
	}

	/**
	 * @covers \MediaWiki\Title\NamespaceInfo::getValidNamespaces
	 */
	public function testGetValidNamespaces_ExtensionNamespaces() {
		$this->assertSame(
			[ NS_MAIN, NS_TALK, NS_USER, NS_USER_TALK, 12345 ],
			$this->newObj( [], self::TEST_EXT_NAMESPACES )->getValidNamespaces()
		);
	}

	// %} End extension namespaces

	// Hook namespaces
	// %{

	/**
	 * @return array Expected canonical namespaces
	 */
	private function setupHookNamespaces() {
		$callback =
			static function ( &$canonicalNamespaces ) {
				$canonicalNamespaces[NS_MAIN] = 'Main';
				unset( $canonicalNamespaces[NS_MEDIA] );
				$canonicalNamespaces[123456] = 'Hooked';
			};
		$this->setTemporaryHook( 'CanonicalNamespaces', $callback );
		$expected = $this->getDefaultNamespaces();
		( $callback )( $expected );
		return $expected;
	}

	/**
	 * @covers \MediaWiki\Title\NamespaceInfo::getCanonicalNamespaces
	 */
	public function testGetCanonicalNamespaces_HookNamespaces() {
		$expected = $this->setupHookNamespaces();

		$this->assertSame( $expected, $this->newObj()->getCanonicalNamespaces() );
	}

	/**
	 * @covers \MediaWiki\Title\NamespaceInfo::getCanonicalName
	 */
	public function testGetCanonicalName_HookNamespaces() {
		$this->setupHookNamespaces();
		$obj = $this->newObj();

		$this->assertSame( 'Main', $obj->getCanonicalName( NS_MAIN ) );
		$this->assertFalse( $obj->getCanonicalName( NS_MEDIA ) );
		$this->assertSame( 'Hooked', $obj->getCanonicalName( 123456 ) );
	}

	/**
	 * @covers \MediaWiki\Title\NamespaceInfo::getCanonicalIndex
	 */
	public function testGetCanonicalIndex_HookNamespaces() {
		$this->setupHookNamespaces();
		$obj = $this->newObj();

		$this->assertSame( NS_MAIN, $obj->getCanonicalIndex( 'main' ) );
		$this->assertNull( $obj->getCanonicalIndex( 'media' ) );
		$this->assertSame( 123456, $obj->getCanonicalIndex( 'hooked' ) );
	}

	/**
	 * @covers \MediaWiki\Title\NamespaceInfo::getValidNamespaces
	 */
	public function testGetValidNamespaces_HookNamespaces() {
		$this->setupHookNamespaces();

		$this->assertSame(
			[ NS_MAIN, NS_TALK, NS_USER, NS_USER_TALK, 123456 ],
			$this->newObj()->getValidNamespaces()
		);
	}

	// %} End hook namespaces

	// Extra namespaces
	// %{

	/**
	 * @return NamespaceInfo
	 */
	private function setupExtraNamespaces() {
		return $this->newObj( [ MainConfigNames::ExtraNamespaces =>
			[ NS_MAIN => 'No effect', NS_TALK => 'No effect', 1234567 => 'Extra' ]
		] );
	}

	/**
	 * @covers \MediaWiki\Title\NamespaceInfo::getCanonicalNamespaces
	 */
	public function testGetCanonicalNamespaces_ExtraNamespaces() {
		$this->assertSame(
			$this->getDefaultNamespaces() + [ 1234567 => 'Extra' ],
			$this->setupExtraNamespaces()->getCanonicalNamespaces()
		);
	}

	/**
	 * @covers \MediaWiki\Title\NamespaceInfo::getCanonicalName
	 */
	public function testGetCanonicalName_ExtraNamespaces() {
		$obj = $this->setupExtraNamespaces();

		$this->assertSame( '', $obj->getCanonicalName( NS_MAIN ) );
		$this->assertSame( 'Talk', $obj->getCanonicalName( NS_TALK ) );
		$this->assertSame( 'Extra', $obj->getCanonicalName( 1234567 ) );
	}

	/**
	 * @covers \MediaWiki\Title\NamespaceInfo::getCanonicalIndex
	 */
	public function testGetCanonicalIndex_ExtraNamespaces() {
		$obj = $this->setupExtraNamespaces();

		$this->assertNull( $obj->getCanonicalIndex( 'no effect' ) );
		$this->assertNull( $obj->getCanonicalIndex( 'no_effect' ) );
		$this->assertSame( 1234567, $obj->getCanonicalIndex( 'extra' ) );
	}

	/**
	 * @covers \MediaWiki\Title\NamespaceInfo::getValidNamespaces
	 */
	public function testGetValidNamespaces_ExtraNamespaces() {
		$this->assertSame(
			[ NS_MAIN, NS_TALK, NS_USER, NS_USER_TALK, 1234567 ],
			$this->setupExtraNamespaces()->getValidNamespaces()
		);
	}

	// %} End extra namespaces

	// Canonical namespace caching
	// %{

	/**
	 * @covers \MediaWiki\Title\NamespaceInfo::getCanonicalNamespaces
	 */
	public function testGetCanonicalNamespaces_caching() {
		$obj = $this->newObj();

		// This should cache the values
		$obj->getCanonicalNamespaces();

		// Now try to alter them through nefarious means
		$this->setupHookNamespaces();

		// Should have no effect
		$this->assertSame( $this->getDefaultNamespaces(), $obj->getCanonicalNamespaces() );
	}

	/**
	 * @covers \MediaWiki\Title\NamespaceInfo::getCanonicalName
	 */
	public function testGetCanonicalName_caching() {
		$obj = $this->newObj();

		// This should cache the values
		$obj->getCanonicalName( NS_MAIN );

		// Now try to alter them through nefarious means
		$this->setupHookNamespaces();

		// Should have no effect
		$this->assertSame( '', $obj->getCanonicalName( NS_MAIN ) );
		$this->assertSame( 'Media', $obj->getCanonicalName( NS_MEDIA ) );
		$this->assertFalse( $obj->getCanonicalName( 12345 ) );
		$this->assertFalse( $obj->getCanonicalName( 123456 ) );
	}

	/**
	 * @covers \MediaWiki\Title\NamespaceInfo::getCanonicalIndex
	 */
	public function testGetCanonicalIndex_caching() {
		$obj = $this->newObj();

		// This should cache the values
		$obj->getCanonicalIndex( '' );

		// Now try to alter them through nefarious means
		$this->setupHookNamespaces();

		// Should have no effect
		$this->assertSame( NS_MAIN, $obj->getCanonicalIndex( '' ) );
		$this->assertSame( NS_MEDIA, $obj->getCanonicalIndex( 'media' ) );
		$this->assertNull( $obj->getCanonicalIndex( 'extended' ) );
		$this->assertNull( $obj->getCanonicalIndex( 'hooked' ) );
	}

	/**
	 * @covers \MediaWiki\Title\NamespaceInfo::getValidNamespaces
	 */
	public function testGetValidNamespaces_caching() {
		$obj = $this->newObj();

		// This should cache the values
		$obj->getValidNamespaces();

		// Now try to alter through nefarious means
		$this->setupHookNamespaces();

		// Should have no effect
		$this->assertSame(
			[ NS_MAIN, NS_TALK, NS_USER, NS_USER_TALK ],
			$obj->getValidNamespaces()
		);
	}

	// %} End canonical namespace caching

	// Miscellaneous
	// %{

	/**
	 * @dataProvider provideGetValidNamespaces_misc
	 * @covers \MediaWiki\Title\NamespaceInfo::getValidNamespaces
	 *
	 * @param array $namespaces List of namespace indices to return from getCanonicalNamespaces()
	 *   (list is overwritten by a hook, so NS_MAIN doesn't have to be present)
	 * @param array $expected
	 */
	public function testGetValidNamespaces_misc( array $namespaces, array $expected ) {
		// Each namespace's name is just its index
		$this->setTemporaryHook( 'CanonicalNamespaces',
			static function ( &$canonicalNamespaces ) use ( $namespaces ) {
				$canonicalNamespaces = array_combine( $namespaces, $namespaces );
			}
		);
		$this->assertSame( $expected, $this->newObj()->getValidNamespaces() );
	}

	public static function provideGetValidNamespaces_misc() {
		return [
			'Out of order (T109137)' => [ [ 1, 0 ], [ 0, 1 ] ],
			'Alphabetical order' => [ [ 10, 2 ], [ 2, 10 ] ],
			'Negative' => [ [ -1000, -500, -2, 0 ], [ 0 ] ],
		];
	}

	// %} End miscellaneous
	// %} End canonical namespaces

	/**
	 * @coversNothing
	 */
	public function testAllServiceOptionsUsed() {
		$this->assertAllServiceOptionsUsed();
	}
}

/**
 * For really cool vim folding this needs to be at the end:
 * vim: foldmarker=%{,%} foldmethod=marker
 */
