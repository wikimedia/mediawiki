<?php
class SpecialBooksourcesTest extends MediaWikiTestCase {
	public static function provideISBNs() {
		return array(
			array( '978-0-300-14424-6', true ),
			array( '0-14-020652-3', true ),
			array( '020652-3', false ),
			array( '9781234567897', true ),
			array( '1-4133-0454-0', true ),
			array( '978-1413304541', true ),
			array( '0136091814', true ),
			array( '0136091812', false ),
			array( '9780136091813', true ),
			array( '9780136091817', false ),
			array( '123456789X', true ),

			// Bug 67021
			array( '1413304541', false ),
			array( '141330454X', false ),
			array( '1413304540', true ),
			array( '14133X4540', false ),
			array( '97814133X4541', false ),
			array( '978035642615X', false ),
			array( '9781413304541', true ),
			array( '9780356426150', true ),
		);
	}

	/**
	 * @covers SpecialBookSources::isValidISBN
	 * @dataProvider provideISBNs
	 */
	public function testIsValidISBN( $isbn, $isValid ) {
		$this->assertSame( $isValid, SpecialBookSources::isValidISBN( $isbn ) );
	}
}
