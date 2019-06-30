<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

use MediaWiki\Preferences\IntvalFilter;
use MediaWiki\Preferences\MultiUsernameFilter;
use MediaWiki\Preferences\TimezoneFilter;

/**
 * @group Preferences
 */
class FiltersTest extends \MediaWikiUnitTestCase {
	/**
	 * @covers MediaWiki\Preferences\IntvalFilter::filterFromForm()
	 * @covers MediaWiki\Preferences\IntvalFilter::filterForForm()
	 */
	public function testIntvalFilter() {
		$filter = new IntvalFilter();
		self::assertSame( 0, $filter->filterFromForm( '0' ) );
		self::assertSame( 3, $filter->filterFromForm( '3' ) );
		self::assertSame( '123', $filter->filterForForm( '123' ) );
	}

	/**
	 * @covers       MediaWiki\Preferences\TimezoneFilter::filterFromForm()
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

	public function provideTimezoneFilter() {
		return [
			[ 'ZoneInfo', 'Offset|0' ],
			[ 'ZoneInfo|bogus', 'Offset|0' ],
			[ 'System', 'System' ],
			[ '2:30', 'Offset|150' ],
		];
	}

	/**
	 * @covers MediaWiki\Preferences\MultiUsernameFilter::filterFromForm()
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

	public function provideMultiUsernameFilterFrom() {
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
	 * @covers MediaWiki\Preferences\MultiUsernameFilter::filterForForm()
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

	public function provideMultiUsernameFilterFor() {
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
		$idLookup = self::getMockBuilder( CentralIdLookup::class )
			->disableOriginalConstructor()
			->setMethods( [ 'centralIdsFromNames', 'namesFromCentralIds' ] )
			->getMockForAbstractClass();

		$idLookup->method( 'centralIdsFromNames' )
			->will( self::returnCallback( function ( $names ) use ( $userMapping ) {
				$ids = [];
				foreach ( $names as $name ) {
					$ids[] = $userMapping[$name] ?? null;
				}
				return array_filter( $ids, 'is_numeric' );
			} ) );
		$idLookup->method( 'namesFromCentralIds' )
			->will( self::returnCallback( function ( $ids ) use ( $flipped ) {
				$names = [];
				foreach ( $ids as $id ) {
					$names[] = $flipped[$id] ?? null;
				}
				return array_filter( $names, 'is_string' );
			} ) );

		return new MultiUsernameFilter( $idLookup );
	}
}
