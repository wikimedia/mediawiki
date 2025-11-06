<?php
namespace MediaWiki\Tests\Specials;

use MediaWiki\MainConfigNames;
use MediaWiki\Specials\SpecialWatchlist;
use MediaWiki\Specials\SpecialWatchlistLabels;

/**
 * @covers \MediaWiki\Specials\SpecialWatchlistLabels
 */
class SpecialWatchlistLabelsTest extends SpecialPageTestBase {
	protected function setUp(): void {
		parent::setUp();
	}

	/** @inheritDoc */
	protected function newSpecialPage() {
		$services = $this->getServiceContainer();
		return new SpecialWatchlistLabels();
	}

	/**
	 * The 'Wathlist labels' navigation tab should only be shown (on Watchlist or WatchlistLabels)
	 * when the feature flag is enabled.
	 *
	 * @dataProvider provideNavOnlyShownWhenEnabled
	 */
	public function testNavOnlyShownWhenEnabled( bool $enabled ) {
		$services = $this->getServiceContainer();
		$this->overrideConfigValues( [ MainConfigNames::EnableWatchlistLabels => $enabled ] );
		$watchlistLabels = new SpecialWatchlistLabels();
		$watchlist = new SpecialWatchlist(
			$services->getWatchedItemStore(),
			$services->getWatchlistManager(),
			$services->getUserOptionsLookup(),
			$services->getUserIdentityUtils(),
			$services->getTempUserConfig(),
			$services->getRecentChangeFactory(),
			$services->getChangesListQueryFactory(),
		);
		if ( $enabled ) {
			$this->assertContains( 'Special:WatchlistLabels', $watchlistLabels->getAssociatedNavigationLinks() );
			$this->assertContains( 'Special:WatchlistLabels', $watchlist->getAssociatedNavigationLinks() );
		} else {
			$this->assertNotContains( 'Special:WatchlistLabels', $watchlistLabels->getAssociatedNavigationLinks() );
			$this->assertNotContains( 'Special:WatchlistLabels', $watchlist->getAssociatedNavigationLinks() );
		}
	}

	public static function provideNavOnlyShownWhenEnabled(): array {
		return [
			[ 'enabled' => false ],
			[ 'enabled' => true ],
		];
	}
}
