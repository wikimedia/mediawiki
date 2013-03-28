<?php

/**
 * @group Skin
 */
class SkinTest extends MediaWikiTestCase {

	protected function setUp() {
		parent::setUp();
		self::setMwGlobals( 'wgDefaultSkin', 'vector' );
		self::setMwGlobals( 'wgValidSkinNames', array(
			'vector' => 'Vector',
			'monobook' => 'MonoBook',
			'curlytest' => '{SkinMonoBook}',
		) );
	}

	/**
	 * Test the CLASS_RE the Skin uses to validate class names
	 * @dataProvider provideClassNames
	 */
	public function testClassNameRegexp( $isValid, $className ) {
		$this->assertEquals( $isValid, preg_match( '/^' . Skin::CLASS_RE_PART . '$/', $className ) );
	}

	/**
	 * Provide valid and invalid class names for testClassNameRegexp
	 */
	public function provideClassNames() {
		return array(
			/* (0 = Invalid, 1 = Valid), Class name */
			array( 1, 'Foo' ),
			array( 1, 'FooBar' ),
			array( 1, 'Foo_' ),
			array( 1, '_Foo' ),
			array( 1, 'Foo5' ),
			array( 0, '5Foo' ),
			array( 0, ' Foo ' ),
			array( 0, 'Foo!' ),
			array( 0, 'Foo@' ),
			array( 0, 'Foo#' ),
			array( 0, 'Foo$' ),
			array( 0, 'Foo%' ),
			array( 0, 'Foo^' ),
			array( 0, 'Foo&' ),
			array( 0, 'Foo?' ),
			array( 0, 'Foo-' ),
			array( 0, 'Foo+' ),
			array( 0, 'Foo=' ),
			array( 0, 'Foo*' ),
			array( 0, 'Foo~' ),
			array( 0, 'Foo`' ),
			array( 0, 'Foo()' ),
			array( 0, 'Foo[]' ),
			array( 0, 'Foo{}' ),
			array( 0, "Foo'" ),
			array( 0, "Foo'" ),
			array( 0, 'Foo:' ),
			array( 0, 'Foo;' ),
			array( 0, 'Foo/Bar' ),
			array( 0, 'Foo<>' ),
			array( 1, 'Foo\\Bar' ),
			array( 1, 'Foo\\Bar\\Baz' ),
			array( 1, '\\Foo' ),
			array( 0, 'Foo\\5Bar' ),
		);
	}

	/**
	 * Test 
	 * @dataProvider provideSkinPairs
	 */
	public function testSkinPairs( $key, $className ) {
		$sk = Skin::newFromKey( $key );
		$this->assertInstanceOf( $className, $sk );
	}

	public function provideSkinPairs() {
		return array(
			// Default skin
			array( 'vector', 'SkinVector' ),
			// Make sure everything doesn't fall back to default
			array( 'monobook', 'SkinMonoBook' ),
			// setUp defines a 'curlytest' => "{SkinMonoBook}" we can test curly syntax with
			array( 'curlytest', 'SkinMonoBook' ),
			// empty string and default always falls back to the default skin (set to vector by setUp)
			array( '', 'SkinVector' ),
			array( 'default', 'SkinVector' ),
			// Nonexistent keys are normalized to the default skin
			array( 'thisskindoesnotexist', 'SkinVector' ),
		);
	}

}