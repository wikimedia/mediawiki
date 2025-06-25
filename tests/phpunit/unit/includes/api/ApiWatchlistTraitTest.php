<?php

namespace MediaWiki\Tests\Api;

use MediaWiki\Api\ApiWatchlistTrait;
use MediaWiki\Title\Title;
use MediaWiki\User\Options\UserOptionsLookup;
use MediaWiki\User\User;
use MediaWiki\Watchlist\WatchedItemStore;
use MediaWiki\Watchlist\WatchlistManager;
use MediaWikiUnitTestCase;
use Wikimedia\TestingAccessWrapper;

/**
 * @group API
 * @covers \MediaWiki\Api\ApiWatchlistTrait
 */
class ApiWatchlistTraitTest extends MediaWikiUnitTestCase {

	/**
	 * @dataProvider provideWatchlistValue
	 */
	public function testWatchlistValue( $watchlistValue, $setOption, $isBot, $isWatched, $expect ) {
		// TODO we don't currently test any of the logic that depends on if the title exists
		$user = $this->createMock( User::class );
		$user->method( 'isBot' )->willReturn( $isBot );

		$userOptionsLookup = $this->createMock( UserOptionsLookup::class );
		$userOptionsLookup->method( 'getBoolOption' )->willReturnCallback(
			static function ( $unused, $optionName ) use ( $setOption ) {
				if ( $optionName === 'watchdefault' ) {
					return (bool)$setOption;
				}
				// watchcreations is not currently tested, by default it is enabled
				return true;
			}
		);

		$watchedItemStore = $this->createMock( WatchedItemStore::class );

		$watchlistManager = $this->createMock( WatchlistManager::class );
		$watchlistManager->method( 'isWatchable' )->willReturn( true );
		$watchlistManager->method( 'isWatchedIgnoringRights' )->willReturn( $isWatched );

		$trait = TestingAccessWrapper::newFromObject( $this->getMockForTrait( ApiWatchlistTrait::class ) );
		$trait->watchlistManager = $watchlistManager;
		$trait->watchedItemStore = $watchedItemStore;
		$trait->userOptionsLookup = $userOptionsLookup;

		$title = $this->createMock( Title::class );
		$title->method( 'exists' )->willReturn( true );

		$watch = $trait->getWatchlistValue( $watchlistValue, $title, $user, 'watchdefault' );
		$this->assertEquals( $expect, $watch );
	}

	public static function provideWatchlistValue() {
		return [
			'watch option on unwatched page' => [ 'watch', null, false, false, true ],
			'watch option on watched page' => [ 'watch', null, false, true, true ],
			'unwatch option on unwatched page' => [ 'unwatch', null, false, false, false ],
			'unwatch option on watched page' => [ 'unwatch', null, false, true, false ],
			'preferences set to true on unwatched page' => [ 'preferences', true, false, false, true ],
			'preferences set to false on unwatched page' => [ 'preferences', false, false, false, false ],
			'preferences set to true on unwatched page (bot group)' => [ 'preferences', true, true, false, false ],
			'preferences set to true on watched page (bot group)' => [ 'preferences', true, true, true, true ],
			'preferences set to false on unwatched page (bot group)' => [ 'preferences', false, true, false, false ],
			'preferences set to false on watched page (bot group)' => [ 'preferences', false, true, true, true ],
			'nochange option on watched page' => [ 'nochange', null, false, true, true ],
			'nochange option on unwatched page' => [ 'nochange', null, false, false, false ],
		];
	}

}
