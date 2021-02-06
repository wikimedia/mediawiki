<?php

use MediaWiki\MediaWikiServices;
use Wikimedia\TestingAccessWrapper;

/**
 * @group API
 * @group Database
 * @covers ApiWatchlistTrait
 */
class ApiWatchlistTraitTest extends MediaWikiIntegrationTestCase {

	protected function setUp(): void {
		parent::setUp();

		$this->tablesUsed = array_merge(
			$this->tablesUsed,
			[ 'watchlist', 'watchlist_expiry' ]
		);
	}

	/**
	 * @dataProvider provideWatchlistValue
	 */
	public function testWatchlistValue( $watchlistValue, $setOption, $setGroup, $setWatch, $expect ) {
		$mock = $this->getMockForTrait( ApiWatchlistTrait::class );

		$user = $this->getTestUser( $setGroup ?? [] )->getUser();
		$title = Title::newFromText( 'Help:' . ucfirst( __FUNCTION__ ) );

		if ( $setOption !== null ) {
			MediaWikiServices::getInstance()
				->getUserOptionsManager()
				->setOption( $user, 'watchdefault', $setOption );
		}

		$resetPermission = null;
		if ( $setGroup !== null ) {
			// User::isBot needs the bot permission along with the bot group
			$resetPermission = MediaWikiServices::getInstance()
				->getPermissionManager()
				->addTemporaryUserRights( $user, $setGroup );
		}

		$store = MediaWikiServices::getInstance()->getWatchedItemStore();
		if ( $setWatch ) {
			$store->addWatch( $user, $title );
		} else {
			$store->removeWatch( $user, $title );
		}

		$watch = TestingAccessWrapper::newFromObject( $mock )
			->getWatchlistValue( $watchlistValue, $title, $user, 'watchdefault' );
		$this->assertEquals( $expect, $watch );
	}

	public function provideWatchlistValue() {
		return [
			'watch option on unwatched page' => [ 'watch', null, null, false, true ],
			'watch option on watched page' => [ 'watch', null, null, true, true ],
			'unwatch option on unwatched page' => [ 'unwatch', null, null, false, false ],
			'unwatch option on watched page' => [ 'unwatch', null, null, true, false ],
			'preferences set to true on unwatched page' => [ 'preferences', true, null, false, true ],
			'preferences set to false on unwatched page' => [ 'preferences', false, null, false, false ],
			'preferences set to true on unwatched page (bot group)' => [ 'preferences', true, 'bot', false, false ],
			'preferences set to true on watched page (bot group)' => [ 'preferences', true, 'bot', true, true ],
			'preferences set to false on unwatched page (bot group)' => [ 'preferences', false, 'bot', false, false ],
			'preferences set to false on watched page (bot group)' => [ 'preferences', false, 'bot', true, true ],
			'nochange option on watched page' => [ 'nochange', null, null, true, true ],
			'nochange option on unwatched page' => [ 'nochange', null, null, false, false ],
		];
	}

}
