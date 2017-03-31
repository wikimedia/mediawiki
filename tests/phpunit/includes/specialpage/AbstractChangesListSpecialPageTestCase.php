<?php

/**
 * Abstract base class for shared logic when testing ChangesListSpecialPage
 * and subclasses
 *
 * @group Database
 */
abstract class AbstractChangesListSpecialPageTestCase extends MediaWikiTestCase {
	// Must be initialized by subclass
	/**
	 * @var ChangesListSpecialPage
	 */
	protected $changesListSpecialPage;

	protected $oldPatrollersGroup;

	protected function setUp() {
		global $wgGroupPermissions;

		parent::setUp();
		$this->setMwGlobals( 'wgRCWatchCategoryMembership', true );

		if ( isset( $wgGroupPermissions['patrollers'] ) ) {
			$this->oldPatrollersGroup = $wgGroupPermissions['patrollers'];
		}

		$wgGroupPermissions['patrollers'] = [
			'patrol' => true,
		];
	}

	protected function tearDown() {
		global $wgGroupPermissions;

		parent::tearDown();

		if ( $this->oldPatrollersGroup !== null ) {
			$wgGroupPermissions['patrollers'] = $this->oldPatrollersGroup;
		}
	}

	/**
	 * @dataProvider provideParseParameters
	 */
	public function testParseParameters( $params, $expected ) {
		$context = $this->changesListSpecialPage->getContext();
		$context = new DerivativeContext( $context );
		$context->setUser( $this->getTestUser( [ 'patrollers' ] )->getUser() );
		$this->changesListSpecialPage->setContext( $context );

		$this->changesListSpecialPage->registerFilters();

		$opts = new FormOptions();
		foreach ( $expected as $key => $value ) {
			// Register it as null so sets aren't rejected.
			$opts->add(
				$key,
				null,
				FormOptions::guessType( $expected )
			);
		}

		$this->changesListSpecialPage->parseParameters(
			$params,
			$opts
		);

		$this->assertArrayEquals(
			$expected,
			$opts->getAllValues(),
			/** ordered= */ false,
			/** named= */ true
		);
	}
}
