<?php

/**
 * @covers \Collation
 * @covers \IcuCollation
 * @covers \IdentityCollation
 * @covers \UppercaseCollation
 */
class CollationTest extends MediaWikiLangTestCase {

	/**
	 * Test to make sure, that if you
	 * have "X" and "XY", the binary
	 * sortkey also has "X" being a
	 * prefix of "XY". Our collation
	 * code makes this assumption.
	 *
	 * @param string $lang Language code for collator
	 * @param string $base
	 * @param string $extended String containing base as a prefix.
	 *
	 * @covers \Collation::getSortKey()
	 * @covers \IcuCollation::getSortKey()
	 * @covers \IdentityCollation::getSortKey()
	 * @covers \UppercaseCollation::getSortKey()
	 * @dataProvider prefixDataProvider
	 */
	public function testIsPrefix( $lang, $base, $extended ) {
		$cp = Collator::create( $lang );
		$cp->setStrength( Collator::PRIMARY );
		$baseBin = $cp->getSortKey( $base );
		$extendedBin = $cp->getSortKey( $extended );
		$this->assertStringStartsWith( $baseBin, $extendedBin, "$base is not a prefix of $extended" );
	}

	public static function prefixDataProvider() {
		return [
			[ 'en', 'A', 'AA' ],
			[ 'en', 'A', 'AAA' ],
			[ 'en', 'Д', 'ДЂ' ],
			[ 'en', 'Д', 'ДA' ],
			// 'Ʒ' should expand to 'Z ' (note space).
			[ 'fi', 'Z', 'Ʒ' ],
			// 'Þ' should expand to 'th'
			[ 'sv', 't', 'Þ' ],
			// Javanese is a limited use alphabet, so should have 3 bytes
			// per character, so do some tests with it.
			[ 'en', 'ꦲ', 'ꦲꦤ' ],
			[ 'en', 'ꦲ', 'ꦲД' ],
			[ 'en', 'A', 'Aꦲ' ],
		];
	}

	/**
	 * Opposite of testIsPrefix
	 *
	 * @covers \Collation::getSortKey()
	 * @covers \IcuCollation::getSortKey()
	 * @covers \IdentityCollation::getSortKey()
	 * @covers \UppercaseCollation::getSortKey()
	 * @dataProvider notPrefixDataProvider
	 */
	public function testNotIsPrefix( $lang, $base, $extended ) {
		$cp = Collator::create( $lang );
		$cp->setStrength( Collator::PRIMARY );
		$baseBin = $cp->getSortKey( $base );
		$extendedBin = $cp->getSortKey( $extended );
		$this->assertStringStartsNotWith( $baseBin, $extendedBin, "$base is a prefix of $extended" );
	}

	public static function notPrefixDataProvider() {
		return [
			[ 'en', 'A', 'B' ],
			[ 'en', 'AC', 'ABC' ],
			[ 'en', 'Z', 'Ʒ' ],
			[ 'en', 'A', 'ꦲ' ],
		];
	}

	/**
	 * Test correct first letter is fetched.
	 *
	 * @param string $collation Collation name (aka uca-en)
	 * @param string $string String to get first letter of
	 * @param string $firstLetter Expected first letter.
	 *
	 * @covers \Collation::getFirstLetter()
	 * @covers \IcuCollation::getFirstLetter()
	 * @covers \IdentityCollation::getFirstLetter()
	 * @covers \UppercaseCollation::getFirstLetter()
	 * @dataProvider firstLetterProvider
	 */
	public function testGetFirstLetter( $collation, $string, $firstLetter ) {
		$col = $this->getServiceContainer()->getCollationFactory()->makeCollation( $collation );
		$this->assertEquals( $firstLetter, $col->getFirstLetter( $string ) );
	}

	public static function firstLetterProvider() {
		return [
			[ 'uppercase', 'Abc', 'A' ],
			[ 'uppercase', 'abc', 'A' ],
			[ 'identity', 'abc', 'a' ],
			[ 'uca-en', 'abc', 'A' ],
			[ 'uca-en', ' ', ' ' ],
			[ 'uca-en', 'Êveryone', 'E' ],
			[ 'uca-vi', 'Êveryone', 'Ê' ],
			// Make sure thorn is not a first letter.
			[ 'uca-sv', 'The', 'T' ],
			[ 'uca-sv', 'Å', 'Å' ],
			[ 'uca-hu', 'dzsdo', 'Dzs' ],
			[ 'uca-hu', 'dzdso', 'Dz' ],
			[ 'uca-hu', 'CSD', 'Cs' ],
			[ 'uca-root', 'CSD', 'C' ],
			[ 'uca-fi', 'Ǥ', 'G' ],
			[ 'uca-fi', 'Ŧ', 'T' ],
			[ 'uca-fi', 'Ʒ', 'Z' ],
			[ 'uca-fi', 'Ŋ', 'N' ],
			[ 'uppercase-ba', 'в', 'В' ],
			[ 'uppercase-smn', 'đ', 'Đ' ],

			[ 'uca-zh@collation=unihan', '测试', '⽔' ],
			[ 'uca-zh@collation=unihan', '瀑布', '⽔' ],
			[ 'uca-zh@collation=unihan', '安全', '⼧' ],
			[ 'uca-zh@collation=unihan', '首页', '⾸' ],
			[ 'uca-zh@collation=unihan', '馗龙', '⾸' ],
			[ 'uca-zh@collation=unihan', 'Test', 'T' ],

			[ 'uca-zh@collation=zhuyin', '测试', 'ㄘ' ],
			[ 'uca-zh@collation=zhuyin', '重要', 'ㄓ' ],
			[ 'uca-zh@collation=zhuyin', '重庆', 'ㄓ' ], // Should be `ㄔ`, incorrect due to the ICU implementation.
			[ 'uca-zh@collation=zhuyin', 'Test', 'T' ],

			[ 'uca-zh@collation=pinyin', '测试', 'C' ],
			[ 'uca-zh@collation=pinyin', '重要', 'Z' ],
			[ 'uca-zh@collation=pinyin', '重阳', 'Z' ], // Should be `C`, incorrect due to the ICU implementation.
			[ 'uca-zh@collation=pinyin', '重庆', 'C' ], // With a special handling ("&虫<重庆/庆") in the ICU implementation.
			[ 'uca-zh@collation=pinyin', 'Test', 'T' ],

			[ 'uca-zh@collation=stroke', '测试', '⠉' ],
			[ 'uca-zh@collation=stroke', '重要', '⠉' ],
			[ 'uca-zh@collation=stroke', '安全', '⠆' ],
			[ 'uca-zh@collation=stroke', '馗龙', '⠋' ],
			[ 'uca-zh@collation=stroke', 'Test', 'T' ],
		];
	}
}
