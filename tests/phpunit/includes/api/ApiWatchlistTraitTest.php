<?php

use MediaWiki\MediaWikiServices;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers ApiWatchlistTrait
 */
class ApiWatchlistTraitTest extends MediaWikiIntegrationTestCase {

	/**
	 * @dataProvider provideWatchlistValue
	 */
	public function testWatchlistValue( $watchlistValue, $setOption, $setWatch, $expect ) {
		$mock = $this->getMockForTrait( ApiWatchlistTrait::class );

		$user = $this->getTestSysop()->getUser();
		$title = Title::newFromText( 'Help:' . ucfirst( __FUNCTION__ ) );

		if ( $setOption !== null ) {
			MediaWikiServices::getInstance()
				->getUserOptionsManager()
				->setOption( $user, 'watchdefault', $setOption );
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
			'watch option on unwatched page' => [ 'watch', null, false, true ],
			'watch option on watched page' => [ 'watch', null, true, true ],
			'unwatch option on unwatched page' => [ 'unwatch', null, false, false ],
			'unwatch option on watched page' => [ 'unwatch', null, true, false ],
			'preferences set to true on unwatched page' => [ 'preferences', true, false, true ],
			'preferences set to false on unwatched page' => [ 'preferences', false, false, false ],
			'nochange option on watched page' => [ 'nochange', null, true, true ],
			'nochange option on unwatched page' => [ 'nochange', null, false, false ],
		];
	}

}
