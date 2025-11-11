<?php
namespace MediaWiki\Tests\Specials;

use InvalidArgumentException;
use MediaWiki\MainConfigNames;
use MediaWiki\Request\FauxRequest;
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
		$this->assertStatusNotGood( $sp->validateName( '', null, null ) );
		$this->assertStatusNotGood( $sp->validateName( '  ', null, null ) );
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
	 * Test the read, create, and edit functions of the special page.
	 */
	public function testExecute(): void {
		// Empty label list.
		[ $html, ] = $this->executeSpecialPage(
			null,
			null,
			null,
			$this->getTestUser()->getUser()
		);
		$this->assertStringContainsString( 'There is no data available', $html );

		// New form with no text.
		[ $html, ] = $this->executeSpecialPage(
			'edit',
			null,
			null,
			$this->getTestUser()->getUser()
		);
		$this->assertStringContainsString( '<input id="mw-input-wll_name" name="wll_name" size="45" class="cdx-text-input__input" required="">', $html );

		// Open the form for editing.
		[ $html, ] = $this->executeSpecialPage(
			'edit',
			new FauxRequest( [ 'wll_name' => 'Lorem the label' ] ),
			null,
			$this->getTestUser()->getUser()
		);
		$this->assertStringContainsString( 'value="Lorem the label"', $html );

		// Save the form and be redirected back to the table, where the new label is listed.
		[ , $res ] = $this->executeSpecialPage(
			'edit',
			new FauxRequest( [ 'wll_name' => 'Lorem the label' ], true ),
			null,
			$this->getTestUser()->getUser()
		);
		$this->assertStringEndsWith( 'Special:WatchlistLabels', $res->getHeader( 'location' ) );
		[ $html, ] = $this->executeSpecialPage(
			null,
			null,
			null,
			$this->getTestUser()->getUser()
		);
		$this->assertStringContainsString( 'Lorem the label', $html );

		// Save the same label ID.
		[ , $res ] = $this->executeSpecialPage(
			'edit',
			new FauxRequest( [ 'wll_name' => 'Lorem updated label', 'wll_id' => '1' ], true ),
			null,
			$this->getTestUser()->getUser()
		);
		$this->assertStringEndsWith( 'Special:WatchlistLabels', $res->getHeader( 'location' ) );
		[ $html, ] = $this->executeSpecialPage(
			null,
			null,
			null,
			$this->getTestUser()->getUser()
		);
		$this->assertStringNotContainsString( 'Lorem the label', $html );
		$this->assertStringContainsString( 'Lorem updated label', $html );
	}
}
