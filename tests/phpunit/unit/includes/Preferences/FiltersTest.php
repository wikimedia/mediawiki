<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

use MediaWiki\Preferences\IntvalFilter;
use MediaWiki\Preferences\MultiUsernameFilter;
use MediaWiki\Preferences\TimezoneFilter;
use MediaWiki\User\CentralId\CentralIdLookup;

/**
 * @group Preferences
 */
class FiltersTest extends \MediaWikiUnitTestCase {
	/**
	 * @covers \MediaWiki\Preferences\IntvalFilter::filterFromForm()
	 * @covers \MediaWiki\Preferences\IntvalFilter::filterForForm()
	 */
	public function testIntvalFilter() {
		$filter = new IntvalFilter();
		self::assertSame( 0, $filter->filterFromForm( '0' ) );
		self::assertSame( 3, $filter->filterFromForm( '3' ) );
		self::assertSame( '123', $filter->filterForForm( '123' ) );
	}

	/**
	 * @covers \MediaWiki\Preferences\TimezoneFilter::filterFromForm()
	 * @dataProvider provideTimezoneFilter
	 *
	 * @param string $input
	 * @param string $expected
	 */
	public function testTimezoneFilter( $input, $expected ) {
		$filter = new TimezoneFilter();
		$result = $filter->filterFromForm( $input );
		self::assertEquals( $expected, $result );
	}

	public static function provideTimezoneFilter() {
		return [
			[ 'ZoneInfo', 'Offset|0' ],
			[ 'ZoneInfo|bogus', 'Offset|0' ],
			[ 'System', 'System|0' ],
			[ 'System|120', 'System|0' ],
			[ '2:30', 'Offset|150' ],
		];
	}

	/**
	 * @covers \MediaWiki\Preferences\MultiUsernameFilter::filterFromForm()
	 * @dataProvider provideMultiUsernameFilterFrom
	 *
	 * @param string $input
	 * @param string|null $expected
	 */
	public function testMultiUsernameFilterFrom( $input, $expected ) {
		$filter = $this->makeMultiUsernameFilter();
		$result = $filter->filterFromForm( $input );
		self::assertSame( $expected, $result );
	}

	public static function provideMultiUsernameFilterFrom() {
		return [
			[ '', null ],
			[ "\n\n\n", null ],
			[ 'Foo', '1' ],
			[ "\n\n\nFoo\nBar\n", "1\n2" ],
			[ "Baz\nInvalid\nFoo", "3\n1" ],
			[ "Invalid", null ],
			[ "Invalid\n\n\nInvalid\n", null ],
		];
	}

	/**
	 * @covers \MediaWiki\Preferences\MultiUsernameFilter::filterForForm()
	 * @dataProvider provideMultiUsernameFilterFor
	 *
	 * @param string $input
	 * @param string $expected
	 */
	public function testMultiUsernameFilterFor( $input, $expected ) {
		$filter = $this->makeMultiUsernameFilter();
		$result = $filter->filterForForm( $input );
		self::assertSame( $expected, $result );
	}

	public static function provideMultiUsernameFilterFor() {
		return [
			[ '', '' ],
			[ "\n", '' ],
			[ '1', 'Foo' ],
			[ "\n1\n\n2\377\n", "Foo\nBar" ],
			[ "666\n667", '' ],
		];
	}

	private function makeMultiUsernameFilter() {
		$userMapping = [
			'Foo' => 1,
			'Bar' => 2,
			'Baz' => 3,
		];
		$flipped = array_flip( $userMapping );
		$idLookup = $this->getMockBuilder( CentralIdLookup::class )
			->disableOriginalConstructor()
			->onlyMethods( [ 'centralIdsFromNames', 'namesFromCentralIds' ] )
			->getMockForAbstractClass();

		$idLookup->method( 'centralIdsFromNames' )
			->will( self::returnCallback( static function ( $names ) use ( $userMapping ) {
				$ids = [];
				foreach ( $names as $name ) {
					$ids[] = $userMapping[$name] ?? null;
				}
				return array_filter( $ids, 'is_numeric' );
			} ) );
		$idLookup->method( 'namesFromCentralIds' )
			->will( self::returnCallback( static function ( $ids ) use ( $flipped ) {
				$names = [];
				foreach ( $ids as $id ) {
					$names[] = $flipped[$id] ?? null;
				}
				return array_filter( $names, 'is_string' );
			} ) );

		return new MultiUsernameFilter( $idLookup );
	}
}
