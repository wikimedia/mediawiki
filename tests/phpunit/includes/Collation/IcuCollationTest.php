<?php

use MediaWiki\Collation\IcuCollation;

/**
 * @covers \MediaWiki\Collation\IcuCollation
 */
class IcuCollationTest extends MediaWikiLangTestCase {

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
		];
	}
}
