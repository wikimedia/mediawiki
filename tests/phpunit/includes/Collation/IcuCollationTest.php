<?php

use MediaWiki\Collation\IcuCollation;

/**
 * @covers \MediaWiki\Collation\IcuCollation
 * @covers \MediaWiki\Collation\PinyinCollation
 */
class IcuCollationTest extends MediaWikiLangTestCase {

	/**
	 * @dataProvider firstLetterForCjkPunctuationProvider
	 */
	public function testGetFirstLetterForCjkPunctuation( string $collation, string $input, string $firstLetter ) {
		$col = $this->getServiceContainer()->getCollationFactory()->makeCollation( $collation );
		$this->assertSame( $firstLetter, $col->getFirstLetter( $input ) );
	}

	public static function firstLetterForCjkPunctuationProvider() {
		foreach ( [
			'uca-en',
			'uca-zh@collation=unihan',
			'uca-zh@collation=zhuyin',
			'uca-zh@collation=pinyin',
			'uca-zh@collation=stroke',
			'pinyin-zh-u-kr-latn-hani',
		] as $collation ) {
			foreach ( [
				'《测试》' => '《',
				'『测试』' => '『',
				'（测试）' => '(',
			] as $input => $firstLetter ) {
				yield "$collation: $input" => [ $collation, $input, $firstLetter ];
			}
		}
	}

	/**
	 * @dataProvider numericAttributeProvider
	 */
	public function testNumericAttribute( string $collation, int $expected ) {
		$col = $this->getServiceContainer()->getCollationFactory()->makeCollation( $collation );
		$reflection = new ReflectionProperty( IcuCollation::class, 'mainCollator' );
		$collator = $reflection->getValue( $col );
		$this->assertEquals( $expected, $collator->getAttribute( Collator::NUMERIC_COLLATION ) );
	}

	public static function numericAttributeProvider() {
		return [
			[ 'uca-default', Collator::OFF ],
			[ 'uca-default-u-kn', Collator::ON ],
			[ 'uca-en@colNumeric=yes', Collator::ON ],

			[ 'uca-zh@collation=unihan', Collator::OFF ],
			[ 'uca-zh@collation=unihan;colNumeric=yes', Collator::ON ],
			[ 'uca-zh@colNumeric=yes;collation=unihan', Collator::ON ],

			[ 'uca-zh-u-co-unihan', Collator::OFF ],
			[ 'uca-zh-u-co-unihan-kn', Collator::ON ],
			[ 'uca-zh-u-kn-co-unihan', Collator::ON ],
		];
	}
}
