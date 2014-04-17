<?php

/**
 * Class CollationTest
 * @covers Collation
 * @covers IcuCollation
 * @covers IdentityCollation
 * @covers UppercaseCollation
 */
class CollationTest extends MediaWikiLangTestCase {
	protected function setUp() {
		parent::setUp();
		$this->checkPHPExtension( 'intl' );
	}

	/**
	 * Test to make sure, that if you
	 * have "X" and "XY", the binary
	 * sortkey also has "X" being a
	 * prefix of "XY". Our collation
	 * code makes this assumption.
	 *
	 * @param string $lang Language code for collator
	 * @param string $base Base string
	 * @param string $extended String containing base as a prefix.
	 *
	 * @dataProvider prefixDataProvider
	 */
	public function testIsPrefix( $lang, $base, $extended ) {
		$cp = Collator::create( $lang );
		$cp->setStrength( Collator::PRIMARY );
		$baseBin = $cp->getSortKey( $base );
		// Remove sortkey terminator
		$baseBin = rtrim( $baseBin, "\0" );
		$extendedBin = $cp->getSortKey( $extended );
		$this->assertStringStartsWith( $baseBin, $extendedBin, "$base is not a prefix of $extended" );
	}

	public static function prefixDataProvider() {
		return array(
			array( 'en', 'A', 'AA' ),
			array( 'en', 'A', 'AAA' ),
			array( 'en', 'Д', 'ДЂ' ),
			array( 'en', 'Д', 'ДA' ),
			// 'Ʒ' should expand to 'Z ' (note space).
			array( 'fi', 'Z', 'Ʒ' ),
			// 'Þ' should expand to 'th'
			array( 'sv', 't', 'Þ' ),
			// Javanese is a limited use alphabet, so should have 3 bytes
			// per character, so do some tests with it.
			array( 'en', 'ꦲ', 'ꦲꦤ' ),
			array( 'en', 'ꦲ', 'ꦲД' ),
			array( 'en', 'A', 'Aꦲ' ),
		);
	}

	/**
	 * Opposite of testIsPrefix
	 *
	 * @dataProvider notPrefixDataProvider
	 */
	public function testNotIsPrefix( $lang, $base, $extended ) {
		$cp = Collator::create( $lang );
		$cp->setStrength( Collator::PRIMARY );
		$baseBin = $cp->getSortKey( $base );
		// Remove sortkey terminator
		$baseBin = rtrim( $baseBin, "\0" );
		$extendedBin = $cp->getSortKey( $extended );
		$this->assertStringStartsNotWith( $baseBin, $extendedBin, "$base is a prefix of $extended" );
	}

	public static function notPrefixDataProvider() {
		return array(
			array( 'en', 'A', 'B' ),
			array( 'en', 'AC', 'ABC' ),
			array( 'en', 'Z', 'Ʒ' ),
			array( 'en', 'A', 'ꦲ' ),
		);
	}

	/**
	 * Test correct first letter is fetched.
	 *
	 * @param string $collation Collation name (aka uca-en)
	 * @param string $string String to get first letter of
	 * @param string $firstLetter Expected first letter.
	 *
	 * @dataProvider firstLetterProvider
	 */
	public function testGetFirstLetter( $collation, $string, $firstLetter ) {
		$col = Collation::factory( $collation );
		$this->assertEquals( $firstLetter, $col->getFirstLetter( $string ) );
	}

	function firstLetterProvider() {
		return array(
			array( 'uppercase', 'Abc', 'A' ),
			array( 'uppercase', 'abc', 'A' ),
			array( 'identity', 'abc', 'a' ),
			array( 'uca-en', 'abc', 'A' ),
			array( 'uca-en', ' ', ' ' ),
			array( 'uca-en', 'Êveryone', 'E' ),
			array( 'uca-vi', 'Êveryone', 'Ê' ),
			// Make sure thorn is not a first letter.
			array( 'uca-sv', 'The', 'T' ),
			array( 'uca-sv', 'Å', 'Å' ),
			array( 'uca-hu', 'dzsdo', 'Dzs' ),
			array( 'uca-hu', 'dzdso', 'Dz' ),
			array( 'uca-hu', 'CSD', 'Cs' ),
			array( 'uca-root', 'CSD', 'C' ),
			array( 'uca-fi', 'Ǥ', 'G' ),
			array( 'uca-fi', 'Ŧ', 'T' ),
			array( 'uca-fi', 'Ʒ', 'Z' ),
			array( 'uca-fi', 'Ŋ', 'N' ),
		);
	}
}
