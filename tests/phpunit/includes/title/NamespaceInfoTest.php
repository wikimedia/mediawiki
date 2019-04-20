<?php
/**
 * @author Antoine Musso
 * @copyright Copyright © 2011, Antoine Musso
 * @file
 */

use MediaWiki\MediaWikiServices;

class NamespaceInfoTest extends MediaWikiTestCase {

	/** @var NamespaceInfo */
	private $obj;

	protected function setUp() {
		parent::setUp();

		$this->setMwGlobals( [
			'wgContentNamespaces' => [ NS_MAIN ],
			'wgNamespacesWithSubpages' => [
				NS_TALK => true,
				NS_USER => true,
				NS_USER_TALK => true,
			],
			'wgCapitalLinks' => true,
			'wgCapitalLinkOverrides' => [],
			'wgNonincludableNamespaces' => [],
		] );

		$this->obj = MediaWikiServices::getInstance()->getNamespaceInfo();
	}

	/**
	 * @todo Write more texts, handle $wgAllowImageMoving setting
	 * @covers NamespaceInfo::isMovable
	 */
	public function testIsMovable() {
		$this->assertFalse( $this->obj->isMovable( NS_SPECIAL ) );
	}

	private function assertIsSubject( $ns ) {
		$this->assertTrue( $this->obj->isSubject( $ns ) );
	}

	private function assertIsNotSubject( $ns ) {
		$this->assertFalse( $this->obj->isSubject( $ns ) );
	}

	/**
	 * Please make sure to change testIsTalk() if you change the assertions below
	 * @covers NamespaceInfo::isSubject
	 */
	public function testIsSubject() {
		// Special namespaces
		$this->assertIsSubject( NS_MEDIA );
		$this->assertIsSubject( NS_SPECIAL );

		// Subject pages
		$this->assertIsSubject( NS_MAIN );
		$this->assertIsSubject( NS_USER );
		$this->assertIsSubject( 100 ); # user defined

		// Talk pages
		$this->assertIsNotSubject( NS_TALK );
		$this->assertIsNotSubject( NS_USER_TALK );
		$this->assertIsNotSubject( 101 ); # user defined
	}

	private function assertIsTalk( $ns ) {
		$this->assertTrue( $this->obj->isTalk( $ns ) );
	}

	private function assertIsNotTalk( $ns ) {
		$this->assertFalse( $this->obj->isTalk( $ns ) );
	}

	/**
	 * Reverse of testIsSubject().
	 * Please update testIsSubject() if you change assertions below
	 * @covers NamespaceInfo::isTalk
	 */
	public function testIsTalk() {
		// Special namespaces
		$this->assertIsNotTalk( NS_MEDIA );
		$this->assertIsNotTalk( NS_SPECIAL );

		// Subject pages
		$this->assertIsNotTalk( NS_MAIN );
		$this->assertIsNotTalk( NS_USER );
		$this->assertIsNotTalk( 100 ); # user defined

		// Talk pages
		$this->assertIsTalk( NS_TALK );
		$this->assertIsTalk( NS_USER_TALK );
		$this->assertIsTalk( 101 ); # user defined
	}

	/**
	 * @covers NamespaceInfo::getSubject
	 */
	public function testGetSubject() {
		// Special namespaces are their own subjects
		$this->assertEquals( NS_MEDIA, $this->obj->getSubject( NS_MEDIA ) );
		$this->assertEquals( NS_SPECIAL, $this->obj->getSubject( NS_SPECIAL ) );

		$this->assertEquals( NS_MAIN, $this->obj->getSubject( NS_TALK ) );
		$this->assertEquals( NS_USER, $this->obj->getSubject( NS_USER_TALK ) );
	}

	/**
	 * Regular getTalk() calls
	 * Namespaces without a talk page (NS_MEDIA, NS_SPECIAL) are tested in
	 * the function testGetTalkExceptions()
	 * @covers NamespaceInfo::getTalk
	 */
	public function testGetTalk() {
		$this->assertEquals( NS_TALK, $this->obj->getTalk( NS_MAIN ) );
		$this->assertEquals( NS_TALK, $this->obj->getTalk( NS_TALK ) );
		$this->assertEquals( NS_USER_TALK, $this->obj->getTalk( NS_USER ) );
		$this->assertEquals( NS_USER_TALK, $this->obj->getTalk( NS_USER_TALK ) );
	}

	/**
	 * Exceptions with getTalk()
	 * NS_MEDIA does not have talk pages. MediaWiki raise an exception for them.
	 * @expectedException MWException
	 * @covers NamespaceInfo::getTalk
	 */
	public function testGetTalkExceptionsForNsMedia() {
		$this->assertNull( $this->obj->getTalk( NS_MEDIA ) );
	}

	/**
	 * Exceptions with getTalk()
	 * NS_SPECIAL does not have talk pages. MediaWiki raise an exception for them.
	 * @expectedException MWException
	 * @covers NamespaceInfo::getTalk
	 */
	public function testGetTalkExceptionsForNsSpecial() {
		$this->assertNull( $this->obj->getTalk( NS_SPECIAL ) );
	}

	/**
	 * Regular getAssociated() calls
	 * Namespaces without an associated page (NS_MEDIA, NS_SPECIAL) are tested in
	 * the function testGetAssociatedExceptions()
	 * @covers NamespaceInfo::getAssociated
	 */
	public function testGetAssociated() {
		$this->assertEquals( NS_TALK, $this->obj->getAssociated( NS_MAIN ) );
		$this->assertEquals( NS_MAIN, $this->obj->getAssociated( NS_TALK ) );
	}

	# ## Exceptions with getAssociated()
	# ## NS_MEDIA and NS_SPECIAL do not have talk pages. MediaWiki raises
	# ## an exception for them.
	/**
	 * @expectedException MWException
	 * @covers NamespaceInfo::getAssociated
	 */
	public function testGetAssociatedExceptionsForNsMedia() {
		$this->assertNull( $this->obj->getAssociated( NS_MEDIA ) );
	}

	/**
	 * @expectedException MWException
	 * @covers NamespaceInfo::getAssociated
	 */
	public function testGetAssociatedExceptionsForNsSpecial() {
		$this->assertNull( $this->obj->getAssociated( NS_SPECIAL ) );
	}

	/**
	 * Note if we add a namespace registration system with keys like 'MAIN'
	 * we should add tests here for equivilance on things like 'MAIN' == 0
	 * and 'MAIN' == NS_MAIN.
	 * @covers NamespaceInfo::equals
	 */
	public function testEquals() {
		$this->assertTrue( $this->obj->equals( NS_MAIN, NS_MAIN ) );
		$this->assertTrue( $this->obj->equals( NS_MAIN, 0 ) ); // In case we make NS_MAIN 'MAIN'
		$this->assertTrue( $this->obj->equals( NS_USER, NS_USER ) );
		$this->assertTrue( $this->obj->equals( NS_USER, 2 ) );
		$this->assertTrue( $this->obj->equals( NS_USER_TALK, NS_USER_TALK ) );
		$this->assertTrue( $this->obj->equals( NS_SPECIAL, NS_SPECIAL ) );
		$this->assertFalse( $this->obj->equals( NS_MAIN, NS_TALK ) );
		$this->assertFalse( $this->obj->equals( NS_USER, NS_USER_TALK ) );
		$this->assertFalse( $this->obj->equals( NS_PROJECT, NS_TEMPLATE ) );
	}

	/**
	 * @covers NamespaceInfo::subjectEquals
	 */
	public function testSubjectEquals() {
		$this->assertSameSubject( NS_MAIN, NS_MAIN );
		$this->assertSameSubject( NS_MAIN, 0 ); // In case we make NS_MAIN 'MAIN'
		$this->assertSameSubject( NS_USER, NS_USER );
		$this->assertSameSubject( NS_USER, 2 );
		$this->assertSameSubject( NS_USER_TALK, NS_USER_TALK );
		$this->assertSameSubject( NS_SPECIAL, NS_SPECIAL );
		$this->assertSameSubject( NS_MAIN, NS_TALK );
		$this->assertSameSubject( NS_USER, NS_USER_TALK );

		$this->assertDifferentSubject( NS_PROJECT, NS_TEMPLATE );
		$this->assertDifferentSubject( NS_SPECIAL, NS_MAIN );
	}

	/**
	 * @covers NamespaceInfo::subjectEquals
	 */
	public function testSpecialAndMediaAreDifferentSubjects() {
		$this->assertDifferentSubject(
			NS_MEDIA, NS_SPECIAL,
			"NS_MEDIA and NS_SPECIAL are different subject namespaces"
		);
		$this->assertDifferentSubject(
			NS_SPECIAL, NS_MEDIA,
			"NS_SPECIAL and NS_MEDIA are different subject namespaces"
		);
	}

	public function provideHasTalkNamespace() {
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
	 * @dataProvider provideHasTalkNamespace
	 * @covers NamespaceInfo::hasTalkNamespace
	 *
	 * @param int $index
	 * @param bool $expected
	 */
	public function testHasTalkNamespace( $index, $expected ) {
		$actual = $this->obj->hasTalkNamespace( $index );
		$this->assertSame( $actual, $expected, "NS $index" );
	}

	private function assertIsContent( $ns ) {
		$this->assertTrue( $this->obj->isContent( $ns ) );
	}

	private function assertIsNotContent( $ns ) {
		$this->assertFalse( $this->obj->isContent( $ns ) );
	}

	/**
	 * @covers NamespaceInfo::isContent
	 */
	public function testIsContent() {
		// NS_MAIN is a content namespace per DefaultSettings.php
		// and per function definition.

		$this->assertIsContent( NS_MAIN );

		// Other namespaces which are not expected to be content

		$this->assertIsNotContent( NS_MEDIA );
		$this->assertIsNotContent( NS_SPECIAL );
		$this->assertIsNotContent( NS_TALK );
		$this->assertIsNotContent( NS_USER );
		$this->assertIsNotContent( NS_CATEGORY );
		$this->assertIsNotContent( 100 );
	}

	/**
	 * Similar to testIsContent() but alters the $wgContentNamespaces
	 * global variable.
	 * @covers NamespaceInfo::isContent
	 */
	public function testIsContentAdvanced() {
		global $wgContentNamespaces;

		// Test that user defined namespace #252 is not content
		$this->assertIsNotContent( 252 );

		// Bless namespace # 252 as a content namespace
		$wgContentNamespaces[] = 252;

		$this->assertIsContent( 252 );

		// Makes sure NS_MAIN was not impacted
		$this->assertIsContent( NS_MAIN );
	}

	private function assertIsWatchable( $ns ) {
		$this->assertTrue( $this->obj->isWatchable( $ns ) );
	}

	private function assertIsNotWatchable( $ns ) {
		$this->assertFalse( $this->obj->isWatchable( $ns ) );
	}

	/**
	 * @covers NamespaceInfo::isWatchable
	 */
	public function testIsWatchable() {
		// Specials namespaces are not watchable
		$this->assertIsNotWatchable( NS_MEDIA );
		$this->assertIsNotWatchable( NS_SPECIAL );

		// Core defined namespaces are watchables
		$this->assertIsWatchable( NS_MAIN );
		$this->assertIsWatchable( NS_TALK );

		// Additional, user defined namespaces are watchables
		$this->assertIsWatchable( 100 );
		$this->assertIsWatchable( 101 );
	}

	private function assertHasSubpages( $ns ) {
		$this->assertTrue( $this->obj->hasSubpages( $ns ) );
	}

	private function assertHasNotSubpages( $ns ) {
		$this->assertFalse( $this->obj->hasSubpages( $ns ) );
	}

	/**
	 * @covers NamespaceInfo::hasSubpages
	 */
	public function testHasSubpages() {
		global $wgNamespacesWithSubpages;

		// Special namespaces:
		$this->assertHasNotSubpages( NS_MEDIA );
		$this->assertHasNotSubpages( NS_SPECIAL );

		// Namespaces without subpages
		$this->assertHasNotSubpages( NS_MAIN );

		$wgNamespacesWithSubpages[NS_MAIN] = true;
		$this->assertHasSubpages( NS_MAIN );

		$wgNamespacesWithSubpages[NS_MAIN] = false;
		$this->assertHasNotSubpages( NS_MAIN );

		// Some namespaces with subpages
		$this->assertHasSubpages( NS_TALK );
		$this->assertHasSubpages( NS_USER );
		$this->assertHasSubpages( NS_USER_TALK );
	}

	/**
	 * @covers NamespaceInfo::getContentNamespaces
	 */
	public function testGetContentNamespaces() {
		global $wgContentNamespaces;

		$this->assertEquals(
			[ NS_MAIN ],
			$this->obj->getContentNamespaces(),
			'$wgContentNamespaces is an array with only NS_MAIN by default'
		);

		# test !is_array( $wgcontentNamespaces )
		$wgContentNamespaces = '';
		$this->assertEquals( [ NS_MAIN ], $this->obj->getContentNamespaces() );

		$wgContentNamespaces = false;
		$this->assertEquals( [ NS_MAIN ], $this->obj->getContentNamespaces() );

		$wgContentNamespaces = null;
		$this->assertEquals( [ NS_MAIN ], $this->obj->getContentNamespaces() );

		$wgContentNamespaces = 5;
		$this->assertEquals( [ NS_MAIN ], $this->obj->getContentNamespaces() );

		# test $wgContentNamespaces === []
		$wgContentNamespaces = [];
		$this->assertEquals( [ NS_MAIN ], $this->obj->getContentNamespaces() );

		# test !in_array( NS_MAIN, $wgContentNamespaces )
		$wgContentNamespaces = [ NS_USER, NS_CATEGORY ];
		$this->assertEquals(
			[ NS_MAIN, NS_USER, NS_CATEGORY ],
			$this->obj->getContentNamespaces(),
			'NS_MAIN is forced in $wgContentNamespaces even if unwanted'
		);

		# test other cases, return $wgcontentNamespaces as is
		$wgContentNamespaces = [ NS_MAIN ];
		$this->assertEquals(
			[ NS_MAIN ],
			$this->obj->getContentNamespaces()
		);

		$wgContentNamespaces = [ NS_MAIN, NS_USER, NS_CATEGORY ];
		$this->assertEquals(
			[ NS_MAIN, NS_USER, NS_CATEGORY ],
			$this->obj->getContentNamespaces()
		);
	}

	/**
	 * @covers NamespaceInfo::getSubjectNamespaces
	 */
	public function testGetSubjectNamespaces() {
		$subjectsNS = $this->obj->getSubjectNamespaces();
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
	 * @covers NamespaceInfo::getTalkNamespaces
	 */
	public function testGetTalkNamespaces() {
		$talkNS = $this->obj->getTalkNamespaces();
		$this->assertContains( NS_TALK, $talkNS,
			"Subject namespaces should have NS_TALK" );
		$this->assertNotContains( NS_MAIN, $talkNS,
			"Subject namespaces should not have NS_MAIN" );

		$this->assertNotContains( NS_MEDIA, $talkNS,
			"Subject namespaces should not have NS_MEDIA" );
		$this->assertNotContains( NS_SPECIAL, $talkNS,
			"Subject namespaces should not have NS_SPECIAL" );
	}

	private function assertIsCapitalized( $ns ) {
		$this->assertTrue( $this->obj->isCapitalized( $ns ) );
	}

	private function assertIsNotCapitalized( $ns ) {
		$this->assertFalse( $this->obj->isCapitalized( $ns ) );
	}

	/**
	 * Some namespaces are always capitalized per code definition
	 * in NamespaceInfo::$alwaysCapitalizedNamespaces
	 * @covers NamespaceInfo::isCapitalized
	 */
	public function testIsCapitalizedHardcodedAssertions() {
		// NS_MEDIA and NS_FILE are treated the same
		$this->assertEquals(
			$this->obj->isCapitalized( NS_MEDIA ),
			$this->obj->isCapitalized( NS_FILE ),
			'NS_MEDIA and NS_FILE have same capitalization rendering'
		);

		// Boths are capitalized by default
		$this->assertIsCapitalized( NS_MEDIA );
		$this->assertIsCapitalized( NS_FILE );

		// Always capitalized namespaces
		// @see NamespaceInfo::$alwaysCapitalizedNamespaces
		$this->assertIsCapitalized( NS_SPECIAL );
		$this->assertIsCapitalized( NS_USER );
		$this->assertIsCapitalized( NS_MEDIAWIKI );
	}

	/**
	 * Follows up for testIsCapitalizedHardcodedAssertions() but alter the
	 * global $wgCapitalLink setting to have extended coverage.
	 *
	 * NamespaceInfo::isCapitalized() rely on two global settings:
	 *   $wgCapitalLinkOverrides = []; by default
	 *   $wgCapitalLinks = true; by default
	 * This function test $wgCapitalLinks
	 *
	 * Global setting correctness is tested against the NS_PROJECT and
	 * NS_PROJECT_TALK namespaces since they are not hardcoded nor specials
	 * @covers NamespaceInfo::isCapitalized
	 */
	public function testIsCapitalizedWithWgCapitalLinks() {
		$this->assertIsCapitalized( NS_PROJECT );
		$this->assertIsCapitalized( NS_PROJECT_TALK );

		$this->setMwGlobals( 'wgCapitalLinks', false );

		// hardcoded namespaces (see above function) are still capitalized:
		$this->assertIsCapitalized( NS_SPECIAL );
		$this->assertIsCapitalized( NS_USER );
		$this->assertIsCapitalized( NS_MEDIAWIKI );

		// setting is correctly applied
		$this->assertIsNotCapitalized( NS_PROJECT );
		$this->assertIsNotCapitalized( NS_PROJECT_TALK );
	}

	/**
	 * Counter part for NamespaceInfo::testIsCapitalizedWithWgCapitalLinks() now
	 * testing the $wgCapitalLinkOverrides global.
	 *
	 * @todo split groups of assertions in autonomous testing functions
	 * @covers NamespaceInfo::isCapitalized
	 */
	public function testIsCapitalizedWithWgCapitalLinkOverrides() {
		global $wgCapitalLinkOverrides;

		// Test default settings
		$this->assertIsCapitalized( NS_PROJECT );
		$this->assertIsCapitalized( NS_PROJECT_TALK );

		// hardcoded namespaces (see above function) are capitalized:
		$this->assertIsCapitalized( NS_SPECIAL );
		$this->assertIsCapitalized( NS_USER );
		$this->assertIsCapitalized( NS_MEDIAWIKI );

		// Hardcoded namespaces remains capitalized
		$wgCapitalLinkOverrides[NS_SPECIAL] = false;
		$wgCapitalLinkOverrides[NS_USER] = false;
		$wgCapitalLinkOverrides[NS_MEDIAWIKI] = false;

		$this->assertIsCapitalized( NS_SPECIAL );
		$this->assertIsCapitalized( NS_USER );
		$this->assertIsCapitalized( NS_MEDIAWIKI );

		$wgCapitalLinkOverrides[NS_PROJECT] = false;
		$this->assertIsNotCapitalized( NS_PROJECT );

		$wgCapitalLinkOverrides[NS_PROJECT] = true;
		$this->assertIsCapitalized( NS_PROJECT );

		unset( $wgCapitalLinkOverrides[NS_PROJECT] );
		$this->assertIsCapitalized( NS_PROJECT );
	}

	/**
	 * @covers NamespaceInfo::hasGenderDistinction
	 */
	public function testHasGenderDistinction() {
		// Namespaces with gender distinctions
		$this->assertTrue( $this->obj->hasGenderDistinction( NS_USER ) );
		$this->assertTrue( $this->obj->hasGenderDistinction( NS_USER_TALK ) );

		// Other ones, "genderless"
		$this->assertFalse( $this->obj->hasGenderDistinction( NS_MEDIA ) );
		$this->assertFalse( $this->obj->hasGenderDistinction( NS_SPECIAL ) );
		$this->assertFalse( $this->obj->hasGenderDistinction( NS_MAIN ) );
		$this->assertFalse( $this->obj->hasGenderDistinction( NS_TALK ) );
	}

	/**
	 * @covers NamespaceInfo::isNonincludable
	 */
	public function testIsNonincludable() {
		global $wgNonincludableNamespaces;

		$wgNonincludableNamespaces = [ NS_USER ];

		$this->assertTrue( $this->obj->isNonincludable( NS_USER ) );
		$this->assertFalse( $this->obj->isNonincludable( NS_TEMPLATE ) );
	}

	private function assertSameSubject( $ns1, $ns2, $msg = '' ) {
		$this->assertTrue( $this->obj->subjectEquals( $ns1, $ns2 ), $msg );
	}

	private function assertDifferentSubject( $ns1, $ns2, $msg = '' ) {
		$this->assertFalse( $this->obj->subjectEquals( $ns1, $ns2 ), $msg );
	}

	public function provideGetCategoryLinkType() {
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

	/**
	 * @dataProvider provideGetCategoryLinkType
	 * @covers NamespaceInfo::getCategoryLinkType
	 *
	 * @param int $index
	 * @param string $expected
	 */
	public function testGetCategoryLinkType( $index, $expected ) {
		$actual = $this->obj->getCategoryLinkType( $index );
		$this->assertSame( $expected, $actual, "NS $index" );
	}
}
