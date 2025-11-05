<?php
namespace MediaWiki\Tests\Specials;

use InvalidArgumentException;
use MediaWiki\MainConfigNames;
use MediaWiki\Specials\SpecialWatchlist;
use MediaWiki\Specials\SpecialWatchlistLabels;

/**
 * @group Database
 * @covers \MediaWiki\Specials\SpecialWatchlistLabels
 */
class SpecialWatchlistLabelsTest extends SpecialPageTestBase {
	protected function setUp(): void {
		parent::setUp();
		$this->overrideConfigValues( [ MainConfigNames::EnableWatchlistLabels => true ] );
	}

	/** @inheritDoc */
	protected function newSpecialPage() {
		$services = $this->getServiceContainer();
		return new SpecialWatchlistLabels( $services->getWatchlistLabelStore() );
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
		$watchlistLabels = new SpecialWatchlistLabels( $services->getWatchlistLabelStore() );
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

	/**
	 * Label names must not be longer than 255 bytes.
	 */
	public function testValidateName(): void {
		$sp = $this->newSpecialPage();
		$this->assertStatusGood( $sp->validateName( 'test', null, null ) );
		$this->assertStatusGood( $sp->validateName( str_repeat( 't', 255 ), null, null ) );
		$this->assertStatusNotGood( $sp->validateName( str_repeat( 't', 256 ), null, null ) );
	}

	/**
	 * Submitting form data without a 'name' property fails.
	 */
	public function testOnSubmitEmpty(): void {
		$sp = $this->newSpecialPage();
		$this->expectException( InvalidArgumentException::class );
		$sp->onSubmit( [] );
	}

	/**
	 * Submitting form data saves a watchlist label that is then shown on the page.
	 */
	public function testOnSubmit(): void {
		$sp = $this->newSpecialPage();
		$sp->getContext()->setUser( $this->getTestUser()->getUser() );
		$sp->onSubmit( [ 'name' => 'Lorem the label' ] );
		$sp->execute( null );
		$this->assertStringContainsString( 'Lorem the label', $sp->getOutput()->getHTML() );
	}
}
