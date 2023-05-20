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
 * @author Daniel Kinzler
 */

namespace MediaWiki\Tests\Unit\Page;

use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Page\PageRecord;
use MediaWiki\Page\PageReference;
use MediaWiki\Page\PageStoreRecord;
use MediaWikiUnitTestCase;
use RuntimeException;
use Wikimedia\Assert\ParameterAssertionException;

/**
 * @covers \MediaWiki\Page\PageStoreRecord
 *
 * @group Title
 */
class PageStoreRecordTest extends MediaWikiUnitTestCase {

	public static function goodConstructorProvider() {
		return [
			'local' => [
				(object)[
					'page_id' => 7,
					'page_namespace' => NS_MAIN,
					'page_title' => 'Test',
					'page_touched' => '20200909001122',
					'page_latest' => 1717,
					'page_is_new' => true,
					'page_is_redirect' => true,
					'page_lang' => 'it',
				],
				PageIdentity::LOCAL
			],
			'non-local' => [
				(object)[
					'page_id' => 3,
					'page_namespace' => NS_USER,
					'page_title' => 'Test',
					'page_touched' => '20200909001122',
					'page_latest' => 1717,
					'page_is_new' => false,
					'page_is_redirect' => false,
					'page_lang' => 'und',
				],
				'h2g2'
			],
			'no language' => [
				(object)[
					'page_id' => 3,
					'page_namespace' => NS_USER,
					'page_title' => 'Test',
					'page_touched' => '20200909001122',
					'page_latest' => 1717,
					'page_is_new' => false,
					'page_is_redirect' => false,
				],
				PageIdentity::LOCAL
			]
		];
	}

	/**
	 * @dataProvider goodConstructorProvider
	 */
	public function testConstruction( $row, $wikiId ) {
		$pageRecord = new PageStoreRecord( $row, $wikiId );

		$this->assertSame( $row->page_id, $pageRecord->getId( $wikiId ) );
		$this->assertSame( $row->page_id > 0, $pageRecord->exists() );
		$this->assertSame( $row->page_namespace, $pageRecord->getNamespace() );
		$this->assertSame( $row->page_title, $pageRecord->getDBkey() );

		$this->assertTrue( $pageRecord->canExist() );

		$this->assertSame( $wikiId, $pageRecord->getWikiId() );
		$this->assertSame( $row->page_touched, $pageRecord->getTouched() );
		$this->assertSame( $row->page_latest, $pageRecord->getLatest( $wikiId ) );
		$this->assertSame( $row->page_is_new, $pageRecord->isNew() );
		$this->assertSame( $row->page_is_redirect, $pageRecord->isRedirect() );

		$this->assertSame( $row->page_lang ?? null, $pageRecord->getLanguage() );

		foreach ( $row as $name => $value ) {
			$this->assertEquals( $value, $pageRecord->getField( $name ) );
		}
	}

	public static function badConstructorProvider() {
		$row = [
			'page_id' => 1,
			'page_namespace' => NS_MAIN,
			'page_title' => 'Test',
			'page_touched' => '20200909001122',
			'page_latest' => 1717,
			'page_is_new' => true,
			'page_is_redirect' => true,
			'page_lang' => 'fi',
		];
		return [
			'nonexisting page' => [ (object)( [ 'page_id' => 0 ] + $row ) ],
			'negative id' => [ (object)( [ 'page_id' => -1 ] + $row ) ],
			'special page' => [ (object)( [ 'page_namespace' => NS_SPECIAL ] + $row ) ],
			'empty title' => [ (object)( [ 'page_title' => '' ] + $row ) ],

			// missing data
			'missing touched' => [ (object)array_diff_key( $row, [ 'page_touched' => 'foo' ] ) ],
			'missing latest' => [ (object)array_diff_key( $row, [ 'page_latest' => 'foo' ] ) ],
			'missing is_new' => [ (object)array_diff_key( $row, [ 'page_is_new' => 'foo' ] ) ],
			'missing is_redirect'
				=> [ (object)array_diff_key( $row, [ 'page_is_redirect' => 'foo' ] ) ],
		];
	}

	/**
	 * @dataProvider badConstructorProvider
	 */
	public function testConstructionErrors( $row ) {
		$this->expectException( ParameterAssertionException::class );
		new PageStoreRecord( $row, PageStoreRecord::LOCAL );
	}

	public function testGetLatestRequiresForeignWikiId() {
		$row = (object)[
			'page_id' => 7,
			'page_namespace' => NS_MAIN,
			'page_title' => 'Test',
			'page_touched' => '20200909001122',
			'page_latest' => 1717,
			'page_is_new' => true,
			'page_is_redirect' => true,
			'page_lang' => 'it',
		];
		$pageRecord = new PageStoreRecord( $row, 'acme' );

		$this->expectException( RuntimeException::class );
		$pageRecord->getLatest( 'xyzzy' );
	}

	public static function provideToString() {
		$row = [
			'page_id' => 7,
			'page_namespace' => NS_MAIN,
			'page_title' => 'Test',
			'page_touched' => '20200909001122',
			'page_latest' => 1717,
			'page_is_new' => true,
			'page_is_redirect' => true,
			'page_lang' => 'it',
		];

		yield [
			new PageStoreRecord( (object)$row, PageIdentity::LOCAL ),
			'[0:Test]'
		];
		yield [
			new PageStoreRecord( (object)( [ 'page_namespace' => 200 ] + $row ), 'codewiki' ),
			'[200:Test]@codewiki'
		];
	}

	/**
	 * @dataProvider provideToString
	 */
	public function testToString( PageStoreRecord $value, $expected ) {
		$this->assertSame(
			$expected,
			$value->__toString()
		);
	}

	public static function provideIsSamePageAs() {
		$row = [
			'page_id' => 7,
			'page_namespace' => NS_MAIN,
			'page_title' => 'Test',
			'page_touched' => '20200909001122',
			'page_latest' => 1717,
			'page_is_new' => true,
			'page_is_redirect' => true,
			'page_lang' => 'it',
		];

		yield [
			new PageStoreRecord( (object)$row, PageRecord::LOCAL ),
			new PageStoreRecord( (object)$row, PageRecord::LOCAL ),
			true
		];
		yield [
			new PageStoreRecord( (object)$row, PageRecord::LOCAL ),
			new PageStoreRecord( (object)$row, 'acme' ),
			false
		];
		yield [
			new PageStoreRecord( (object)$row, 'acme' ),
			new PageStoreRecord( (object)$row, 'acme' ),
			true
		];
		yield [
			new PageStoreRecord( (object)$row, 'acme' ),
			new PageIdentityValue( 7, NS_MAIN, 'Test', 'acme' ),
			true
		];
	}

	/**
	 * @dataProvider provideIsSamePageAs
	 */
	public function testIsSamePageAs( PageReference $a, PageReference $b, $expected ) {
		$this->assertSame( $expected, $a->isSamePageAs( $b ) );
		$this->assertSame( $expected, $b->isSamePageAs( $a ) );
	}

}
