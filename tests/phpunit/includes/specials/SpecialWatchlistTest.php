<?php

use Wikimedia\TestingAccessWrapper;

/**
 * @author Addshore
 *
 * @group Database
 *
 * @covers SpecialWatchlist
 */
class SpecialWatchlistTest extends SpecialPageTestBase {
	public function setUp() {
		parent::setUp();
		$this->tablesUsed = [ 'watchlist' ];
		$this->setTemporaryHook(
			'ChangesListSpecialPageQuery',
			null
		);

		$this->setMwGlobals(
			'wgDefaultUserOptions',
			[
				'extendwatchlist' => 1,
				'watchlistdays' => 3.0,
				'watchlisthideanons' => 0,
				'watchlisthidebots' => 0,
				'watchlisthideliu' => 0,
				'watchlisthideminor' => 0,
				'watchlisthideown' => 0,
				'watchlisthidepatrolled' => 0,
				'watchlisthidecategorization' => 1,
				'watchlistreloadautomatically' => 0,
				'watchlistunwatchlinks' => 0,
			]
		);
	}

	/**
	 * Returns a new instance of the special page under test.
	 *
	 * @return SpecialPage
	 */
	protected function newSpecialPage() {
		return new SpecialWatchlist();
	}

	public function testNotLoggedIn_throwsException() {
		$this->setExpectedException( UserNotLoggedIn::class );
		$this->executeSpecialPage();
	}

	public function testUserWithNoWatchedItems_displaysNoWatchlistMessage() {
		$user = new TestUser( __METHOD__ );
		list( $html, ) = $this->executeSpecialPage( '', null, 'qqx', $user->getUser() );
		$this->assertContains( '(nowatchlist)', $html );
	}

	/**
	 * @dataProvider provideFetchOptionsFromRequest
	 */
	public function testFetchOptionsFromRequest(
		$expectedValuesDefaults, $expectedValues, $preferences, $inputParams
	) {
		// $defaults and $allFalse are just to make the expected values below
		// shorter by hiding the background.

		$page = TestingAccessWrapper::newFromObject(
			$this->newSpecialPage()
		);

		$page->registerFilters();

		// Does not consider $preferences, just wiki's defaults
		$wikiDefaults = $page->getDefaultOptions()->getAllValues();

		switch ( $expectedValuesDefaults ) {
		case 'allFalse':
			$allFalse = $wikiDefaults;

			foreach ( $allFalse as $key => $value ) {
				if ( $value === true ) {
					$allFalse[$key] = false;
				}
			}

			// This is not exposed on the form (only in preferences) so it
			// respects the preference.
			$allFalse['extended'] = true;

			$expectedValues += $allFalse;
			break;
		case 'wikiDefaults':
			$expectedValues += $wikiDefaults;
			break;
		default:
			$this->fail( "Unknown \$expectedValuesDefaults: $expectedValuesDefaults" );
		}

		$page = TestingAccessWrapper::newFromObject(
			$this->newSpecialPage()
		);

		$context = new DerivativeContext( $page->getContext() );

		$fauxRequest = new FauxRequest( $inputParams, /* $wasPosted= */ false );
		$user = $this->getTestUser()->getUser();

		foreach ( $preferences as $key => $value ) {
			$user->setOption( $key, $value );
		}

		$context->setRequest( $fauxRequest );
		$context->setUser( $user );
		$page->setContext( $context );

		$page->registerFilters();
		$formOptions = $page->getDefaultOptions();
		$page->fetchOptionsFromRequest( $formOptions );

		$this->assertArrayEquals(
			$expectedValues,
			$formOptions->getAllValues(),
			/* $ordered= */ false,
			/* $named= */ true
		);
	}

	public function provideFetchOptionsFromRequest() {
		return [
			'ignores casing' => [
				'expectedValuesDefaults' => 'wikiDefaults',
				'expectedValues' => [
					'hideminor' => true,
				],
				'preferences' => [],
				'inputParams' => [
					'hideMinor' => 1,
				],
			],

			'first two same as prefs, second two overriden' => [
				'expectedValuesDefaults' => 'wikiDefaults',
				'expectedValues' => [
					// First two same as prefs
					'hideminor' => true,
					'hidebots' => false,

					// Second two overriden
					'hideanons' => false,
					'hideliu' => true,
					'userExpLevel' => 'registered'
				],
				'preferences' => [
					'watchlisthideminor' => 1,
					'watchlisthidebots' => 0,

					'watchlisthideanons' => 1,
					'watchlisthideliu' => 0,
				],
				'inputParams' => [
					'hideanons' => 0,
					'hideliu' => 1,
				],
			],

			'Defaults/preferences for form elements are entirely ignored for '
			. 'action=submit and omitted elements become false' => [
				'expectedValuesDefaults' => 'allFalse',
				'expectedValues' => [
					'hideminor' => false,
					'hidebots' => true,
					'hideanons' => false,
					'hideliu' => true,
					'userExpLevel' => 'unregistered'
				],
				'preferences' => [
					'watchlisthideminor' => 0,
					'watchlisthidebots' => 1,

					'watchlisthideanons' => 0,
					'watchlisthideliu' => 1,
				],
				'inputParams' => [
					'hidebots' => 1,
					'hideliu' => 1,
					'action' => 'submit',
				],
			],
		];
	}
}
