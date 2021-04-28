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

use MediaWiki\Cache\CacheKeyHelper;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\Page\PageReference;
use MediaWiki\User\UserIdentity;
use PHPUnit\Framework\MockObject\MockObject;
use WatchedItem;
use WatchedItemStore;

/**
 * Trait to get helper services that can be used in unit tests
 *
 * @internal
 * @author DannyS712
 */
trait DummyServicesTrait {

	/**
	 * @var array
	 * Data for the watched item store, keys are result of getWatchedItemStoreKey()
	 * and the value is 'true' for indefinitely watched, or a string with an expiration;
	 * if there is no entry here than the page is not watched
	 */
	private $watchedItemStoreData = [];

	/**
	 * @param UserIdentity $user Should only be called with registered users
	 * @param LinkTarget|PageReference $page
	 * @return string
	 */
	private function getWatchedItemStoreKey( UserIdentity $user, $page ) : string {
		return 'u' . (string)$user->getId() . ':' . CacheKeyHelper::getKeyForPage( $page );
	}

	/**
	 * @return WatchedItemStore|MockObject
	 */
	private function getDummyWatchedItemStore() {
		// The WatchedItemStoreInterface has a lot of stuff, but most tests only depend
		// on the basic getWatchedItem/addWatch/removeWatch/isWatched/isTempWatched
		// We mock WatchedItemStore and support those 5 methods, and it even handles
		// keep track of different pages and users!
		// Note: we store no expiration as true, so we can use isset(), but its represented
		// by null elsewhere, so we need to convert
		$mock = $this->createNoOpMock(
			WatchedItemStore::class,
			[ 'getWatchedItem', 'addWatch', 'removeWatch', 'isWatched', 'isTempWatched' ]
		);
		$mock->method( 'getWatchedItem' )->willReturnCallback( function ( $user, $target ) {
			$dataKey = $this->getWatchedItemStoreKey( $user, $target );
			if ( isset( $this->watchedItemStoreData[ $dataKey ] ) ) {
				$expiry = $this->watchedItemStoreData[ $dataKey ];
				// We store no expiration as true, so we can use isset(), but its
				// represented by null elsewhere, including in WatchedItem
				$expiry = ( $expiry === true ? null : $expiry );
				return new WatchedItem(
					$user,
					$target,
					null,
					$expiry
				);
			}
			return false;
		} );
		$mock->method( 'addWatch' )->willReturnCallback( function ( $user, $target, $expiry ) {
			if ( !$user->isRegistered() ) {
				return false;
			}
			$dataKey = $this->getWatchedItemStoreKey( $user, $target );
			$this->watchedItemStoreData[ $dataKey ] = ( $expiry === null ? true : $expiry );
			return true;
		} );
		$mock->method( 'removeWatch' )->willReturnCallback( function ( $user, $target ) {
			if ( !$user->isRegistered() ) {
				return false;
			}
			$dataKey = $this->getWatchedItemStoreKey( $user, $target );
			if ( isset( $this->watchedItemStoreData[ $dataKey ] ) ) {
				unset( $this->watchedItemStoreData[ $dataKey ] );
				return true;
			}
			return false;
		} );
		$mock->method( 'isWatched' )->willReturnCallback( function ( $user, $target ) {
			$dataKey = $this->getWatchedItemStoreKey( $user, $target );
			return isset( $this->watchedItemStoreData[ $dataKey ] );
		} );
		$mock->method( 'isTempWatched' )->willreturnCallback( function ( $user, $target ) {
			$dataKey = $this->getWatchedItemStoreKey( $user, $target );
			return isset( $this->watchedItemStoreData[ $dataKey ] ) &&
				$this->watchedItemStoreData[ $dataKey ] !== true;
		} );
		return $mock;
	}
}
