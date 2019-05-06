<?php
/**
 * @author Antoine Musso
 * @copyright Copyright © 2011, Antoine Musso
 * @file
 */

use MediaWiki\Config\ServiceOptions;

class NamespaceInfoTest extends MediaWikiTestCase {
	private function newObj( array $options = [] ) : NamespaceInfo {
		$defaults = [
			'AllowImageMoving' => true,
			'CanonicalNamespaceNames' => [
				NS_TALK => 'Talk',
				NS_USER => 'User',
				NS_USER_TALK => 'User_talk',
				NS_SPECIAL => 'Special',
				NS_MEDIA => 'Media',
			],
			'CapitalLinkOverrides' => [],
			'CapitalLinks' => true,
			'ContentNamespaces' => [ NS_MAIN ],
			'ExtraNamespaces' => [],
			'ExtraSignatureNamespaces' => [],
			'NamespaceContentModels' => [],
			'NamespaceProtection' => [],
			'NamespacesWithSubpages' => [
				NS_TALK => true,
				NS_USER => true,
				NS_USER_TALK => true,
			],
			'NonincludableNamespaces' => [],
			'RestrictionLevels' => [ '', 'autoconfirmed', 'sysop' ],
		];
		return new NamespaceInfo(
			new ServiceOptions( NamespaceInfo::$constructorOptions, $options, $defaults ) );
	}

	/**
	 * @todo Write more tests, handle $wgAllowImageMoving setting
	 * @covers NamespaceInfo::isMovable
	 */
	public function testIsMovable() {
		$this->assertFalse( $this->newObj()->isMovable( NS_SPECIAL ) );
	}

	private function assertIsSubject( $ns ) {
		$this->assertTrue( $this->newObj()->isSubject( $ns ) );
	}

	private function assertIsNotSubject( $ns ) {
		$this->assertFalse(
			$this->newObj()->isSubject( $ns ) );
	}

	/**
	 * @param int $ns
	 * @param bool $expected
	 * @dataProvider provideIsSubject
	 * @covers NamespaceInfo::isSubject
	 */
	public function testIsSubject( $ns, $expected ) {
		$this->assertSame( $expected, $this->newObj()->isSubject( $ns ) );
	}

	/**
	 * @param int $ns
	 * @param bool $expected
	 * @dataProvider provideIsSubject
	 * @covers NamespaceInfo::isTalk
	 */
	public function testIsTalk( $ns, $expected ) {
		$this->assertSame( !$expected, $this->newObj()->isTalk( $ns ) );
	}

	public function provideIsSubject() {
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
	 * @covers NamespaceInfo::getSubject
	 */
	public function testGetSubject() {
		// Special namespaces are their own subjects
		$obj = $this->newObj();
		$this->assertEquals( NS_MEDIA, $obj->getSubject( NS_MEDIA ) );
		$this->assertEquals( NS_SPECIAL, $obj->getSubject( NS_SPECIAL ) );

		$this->assertEquals( NS_MAIN, $obj->getSubject( NS_TALK ) );
		$this->assertEquals( NS_USER, $obj->getSubject( NS_USER_TALK ) );
	}

	/**
	 * Regular getTalk() calls
	 * Namespaces without a talk page (NS_MEDIA, NS_SPECIAL) are tested in
	 * the function testGetTalkExceptions()
	 * @covers NamespaceInfo::getTalk
	 */
	public function testGetTalk() {
		$obj = $this->newObj();
		$this->assertEquals( NS_TALK, $obj->getTalk( NS_MAIN ) );
		$this->assertEquals( NS_TALK, $obj->getTalk( NS_TALK ) );
		$this->assertEquals( NS_USER_TALK, $obj->getTalk( NS_USER ) );
		$this->assertEquals( NS_USER_TALK, $obj->getTalk( NS_USER_TALK ) );
	}

	/**
	 * Exceptions with getTalk()
	 * NS_MEDIA does not have talk pages. MediaWiki raise an exception for them.
	 * @expectedException MWException
	 * @covers NamespaceInfo::getTalk
	 */
	public function testGetTalkExceptionsForNsMedia() {
		$this->assertNull( $this->newObj()->getTalk( NS_MEDIA ) );
	}

	/**
	 * Exceptions with getTalk()
	 * NS_SPECIAL does not have talk pages. MediaWiki raise an exception for them.
	 * @expectedException MWException
	 * @covers NamespaceInfo::getTalk
	 */
	public function testGetTalkExceptionsForNsSpecial() {
		$this->assertNull( $this->newObj()->getTalk( NS_SPECIAL ) );
	}

	/**
	 * Regular getAssociated() calls
	 * Namespaces without an associated page (NS_MEDIA, NS_SPECIAL) are tested in
	 * the function testGetAssociatedExceptions()
	 * @covers NamespaceInfo::getAssociated
	 */
	public function testGetAssociated() {
		$this->assertEquals( NS_TALK, $this->newObj()->getAssociated( NS_MAIN ) );
		$this->assertEquals( NS_MAIN, $this->newObj()->getAssociated( NS_TALK ) );
	}

	# ## Exceptions with getAssociated()
	# ## NS_MEDIA and NS_SPECIAL do not have talk pages. MediaWiki raises
	# ## an exception for them.
	/**
	 * @expectedException MWException
	 * @covers NamespaceInfo::getAssociated
	 */
	public function testGetAssociatedExceptionsForNsMedia() {
		$this->assertNull( $this->newObj()->getAssociated( NS_MEDIA ) );
	}

	/**
	 * @expectedException MWException
	 * @covers NamespaceInfo::getAssociated
	 */
	public function testGetAssociatedExceptionsForNsSpecial() {
		$this->assertNull( $this->newObj()->getAssociated( NS_SPECIAL ) );
	}

	/**
	 * Note if we add a namespace registration system with keys like 'MAIN'
	 * we should add tests here for equivilance on things like 'MAIN' == 0
	 * and 'MAIN' == NS_MAIN.
	 * @covers NamespaceInfo::equals
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
	 * @covers NamespaceInfo::subjectEquals
	 */
	public function testSubjectEquals( $ns1, $ns2, $expected ) {
		$this->assertSame( $expected, $this->newObj()->subjectEquals( $ns1, $ns2 ) );
	}

	public function provideSubjectEquals() {
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
	 * @covers NamespaceInfo::hasTalkNamespace
	 *
	 * @param int $ns
	 * @param bool $expected
	 */
	public function testHasTalkNamespace( $ns, $expected ) {
		$this->assertSame( $expected, $this->newObj()->hasTalkNamespace( $ns ) );
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
	 * @param int $ns
	 * @param bool $expected
	 * @param array $contentNamespaces
	 * @covers NamespaceInfo::isContent
	 * @dataProvider provideIsContent
	 */
	public function testIsContent( $ns, $expected, $contentNamespaces = [ NS_MAIN ] ) {
		$obj = $this->newObj( [ 'ContentNamespaces' => $contentNamespaces ] );
		$this->assertSame( $expected, $obj->isContent( $ns ) );
	}

	public function provideIsContent() {
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
	 * @param int $ns
	 * @param bool $expected
	 * @covers NamespaceInfo::isWatchable
	 * @dataProvider provideIsWatchable
	 */
	public function testIsWatchable( $ns, $expected ) {
		$this->assertSame( $expected, $this->newObj()->isWatchable( $ns ) );
	}

	public function provideIsWatchable() {
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
	 * @covers NamespaceInfo::hasSubpages
	 * @dataProvider provideHasSubpages
	 */
	public function testHasSubpages( $ns, $expected, array $namespacesWithSubpages = null ) {
		$obj = $this->newObj( $namespacesWithSubpages
			? [ 'NamespacesWithSubpages' => $namespacesWithSubpages ]
			: [] );
		$this->assertSame( $expected, $obj->hasSubpages( $ns ) );
	}

	public function provideHasSubpages() {
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
	 * @param $contentNamespaces To pass to constructor
	 * @param array $expected
	 * @dataProvider provideGetContentNamespaces
	 * @covers NamespaceInfo::getContentNamespaces
	 */
	public function testGetContentNamespaces( $contentNamespaces, array $expected ) {
		$obj = $this->newObj( [ 'ContentNamespaces' => $contentNamespaces ] );
		$this->assertSame( $expected, $obj->getContentNamespaces() );
	}

	public function provideGetContentNamespaces() {
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
	 * @covers NamespaceInfo::getSubjectNamespaces
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
	 * @covers NamespaceInfo::getTalkNamespaces
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
	 * @covers NamespaceInfo::isCapitalized
	 */
	public function testIsCapitalized(
		$ns, $expected, $capitalLinks = true, array $capitalLinkOverrides = []
	) {
		$obj = $this->newObj( [
			'CapitalLinks' => $capitalLinks,
			'CapitalLinkOverrides' => $capitalLinkOverrides,
		] );
		$this->assertSame( $expected, $obj->isCapitalized( $ns ) );
	}

	public function provideIsCapitalized() {
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
	 * @covers NamespaceInfo::hasGenderDistinction
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
	 * @covers NamespaceInfo::isNonincludable
	 */
	public function testIsNonincludable() {
		$obj = $this->newObj( [ 'NonincludableNamespaces' => [ NS_USER ] ] );
		$this->assertTrue( $obj->isNonincludable( NS_USER ) );
		$this->assertFalse( $obj->isNonincludable( NS_TEMPLATE ) );
	}

	/**
	 * @dataProvider provideGetCategoryLinkType
	 * @covers NamespaceInfo::getCategoryLinkType
	 *
	 * @param int $ns
	 * @param string $expected
	 */
	public function testGetCategoryLinkType( $ns, $expected ) {
		$this->assertSame( $expected, $this->newObj()->getCategoryLinkType( $ns ) );
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
}
