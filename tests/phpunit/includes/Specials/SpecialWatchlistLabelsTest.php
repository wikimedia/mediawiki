<?php
namespace MediaWiki\Tests\Specials;

use InvalidArgumentException;
use MediaWiki\MainConfigNames;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Specials\SpecialWatchlist;
use MediaWiki\Specials\SpecialWatchlistLabels;
use MediaWiki\Title\Title;
use MediaWiki\Watchlist\WatchlistLabel;
use Wikimedia\Parsoid\Utils\DOMCompat;
use Wikimedia\Parsoid\Utils\DOMUtils;

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
			$services->getWatchlistLabelStore(),
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
	public function testOnEditFormSubmitEmpty(): void {
		$sp = $this->newSpecialPage();
		$this->expectException( InvalidArgumentException::class );
		$sp->onEditFormSubmit( [] );
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

		// Save the same label ID with a new name.
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

		// Request to delete the label.
		[ $html, ] = $this->executeSpecialPage(
			'delete',
			new FauxRequest( [ 'wll_ids' => [ '1' ] ] ),
			null,
			$this->getTestUser()->getUser()
		);
		$this->assertStringContainsString( 'Lorem updated label', $html );
		$this->assertStringContainsString( '(watchlistlabels-delete-warning: 1, 1)', $html );

		// Actually delete the label.
		[ $html, ] = $this->executeSpecialPage(
			'delete',
			new FauxRequest( [ 'wll_ids' => [ '1' ] ], true ),
			null,
			$this->getTestUser()->getUser()
		);
		$this->assertStringNotContainsString( 'Lorem updated label', $html );
	}

	/**
	 * Labels can be sorted by name or count.
	 * @dataProvider provideSorting
	 */
	public function testSorting( array $request, string $selector, array $expected ): void {
		// Create some test labels.
		$labelStore = $this->getServiceContainer()->getWatchlistLabelStore();
		$user = $this->getTestUser()->getUser();
		for ( $i = 1; $i <= 5; $i++ ) {
			$labelStore->save( new WatchlistLabel( $user, "Test label $i" ) );
		}
		// Watch some pages.
		$watchedItemStore = $this->getServiceContainer()->getWatchedItemStore();
		$testPage1 = Title::newFromText( 'Test 1' );
		$testPage2 = Title::newFromText( 'Test 2' );
		$watchedItemStore->addWatchBatchForUser( $user, [ $testPage1, $testPage2 ] );
		// Add label 2 to 2 pages, and label 3 to 1 page.
		$watchedItemStore->addLabels( $user, [ $testPage1, $testPage2 ], [ 2 ] );
		$watchedItemStore->addLabels( $user, [ $testPage2 ], [ 3 ] );
		// Run the test.
		[ $html, ] = $this->executeSpecialPage( null, new FauxRequest( $request ), null, $user );
		$cells = DOMCompat::querySelectorAll( DOMUtils::parseHTML( $html ), $selector );
		$values = array_map( static fn ( $node ) => $node->textContent, $cells );
		$this->assertArrayEquals( $expected, $values, true );
	}

	private function provideSorting(): array {
		return [
			[
				// Default is by descending count:
				'request' => [],
				'selector' => 'tbody > tr > td:nth-child(3)',
				'expected' => [
					'2',
					'1',
					'0',
					'0',
					'0',
				],
			],
			[
				// Ascending by name:
				'request' => [ 'sort' => 'name', 'asc' => '1' ],
				'selector' => 'tbody > tr > td:nth-child(2)',
				'expected' => [
					'Test label 1',
					'Test label 2',
					'Test label 3',
					'Test label 4',
					'Test label 5',
				],
			],
			[
				// Descending by name:
				'request' => [ 'sort' => 'name', 'desc' => '1' ],
				'selector' => 'tbody > tr > td:nth-child(2)',
				'expected' => [
					'Test label 5',
					'Test label 4',
					'Test label 3',
					'Test label 2',
					'Test label 1',
				],
			],
		];
	}
}
