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

namespace MediaWiki\Tests\Unit;

use MediaWiki\Interwiki\InterwikiLookup;
use MediaWiki\Page\PageReferenceValue;
use MediaWiki\User\UserIdentityValue;
use MediaWiki\Watchlist\WatchedItem;
use MediaWiki\Watchlist\WatchedItemStore;
use MediaWikiUnitTestCase;
use Wikimedia\Rdbms\ReadOnlyMode;

/**
 * Unit tests for DummyServicesTrait itself, which is a helper to be
 * used by other test classes
 *
 * @covers \MediaWiki\Tests\Unit\DummyServicesTrait
 * @author DannyS712
 */
class DummyServicesTraitTest extends MediaWikiUnitTestCase {
	use DummyServicesTrait;

	public function testDummyInterwikiLookup() {
		$lookup = $this->getDummyInterwikiLookup( [
			'just_a_string',
			[ 'iw_prefix' => 'in_an_array' ],
			[ 'iw_prefix' => 'with_overrides', 'iw_url' => 'https://www.example.com' ]
		] );
		$this->assertInstanceOf( InterwikiLookup::class, $lookup );
		$this->assertTrue( $lookup->isValidInterwiki( 'just_a_string' ) );
		$this->assertTrue( $lookup->isValidInterwiki( 'in_an_array' ) );
		$this->assertTrue( $lookup->isValidINterwiki( 'JUST_A_STRING' ), 'Case normalization' );
	}

	/**
	 * @dataProvider provideDummyReadOnlyMode
	 */
	public function testDummyReadOnlyMode( $param, $isReadOnly, $expectedReason ) {
		$readOnlyMode = $this->getDummyReadOnlyMode( $param );
		$this->assertInstanceOf( ReadOnlyMode::class, $readOnlyMode );
		$this->assertSame( $isReadOnly, $readOnlyMode->isReadOnly() );
		$this->assertSame( $expectedReason, $readOnlyMode->getReason() );
	}

	public static function provideDummyReadOnlyMode() {
		yield 'Disabled' => [ false, false, false ];
		yield 'Enabled, default reason' => [ true, true, 'Random reason' ];
		yield 'Enabled, custom reason' => [ 'My reason', true, 'My reason' ];
	}

	public function testDummyWatchedItemStore() {
		$store = $this->getDummyWatchedItemStore();
		$this->assertInstanceOf( WatchedItemStore::class, $store );

		$user1 = new UserIdentityValue( 123, 'First user' );
		$user2 = new UserIdentityValue( 456, 'Second user' );
		$page1 = new PageReferenceValue( NS_MAIN, 'Example', PageReferenceValue::LOCAL );
		$page2 = new PageReferenceValue( NS_TALK, 'Other', PageReferenceValue::LOCAL );

		$this->assertFalse(
			$store->isWatched( $user1, $page1 ),
			'Nothing starts watched'
		);
		$store->addWatch( $user1, $page1 );
		$this->assertTrue(
			$store->isWatched( $user1, $page1 ),
			'addWatch() works'
		);
		$this->assertFalse(
			$store->isWatched( $user2, $page1 ),
			'Can tell different users apart'
		);
		$this->assertFalse(
			$store->isWatched( $user1, $page2 ),
			'Can tell different pages apart'
		);
		$this->assertFalse(
			$store->isTempWatched( $user1, $page1 ),
			'Store can handle expirations'
		);
		$store->addWatch( $user1, $page1, '20210101120000' );
		$this->assertTrue(
			$store->isTempWatched( $user1, $page1 ),
			'Store can handle expirations'
		);
		$watchedItem = $store->getWatchedItem( $user1, $page1 );
		$this->assertInstanceOf(
			WatchedItem::class,
			$watchedItem,
			'Get a WatchedItem if watched'
		);
		$this->assertSame(
			'20210101120000',
			$watchedItem->getExpiry(),
			'Expiration is stored'
		);
		$store->removeWatch( $user1, $page1 );
		$this->assertFalse(
			$store->isWatched( $user1, $page1 ),
			'removeWatch() works'
		);
	}

}
