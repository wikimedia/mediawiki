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

		// Deprecated
		$this->setTemporaryHook(
			'ChangesListSpecialPageFilters',
			null
		);

		# setup the ChangesListSpecialPage (or subclass) object
		$this->changesListSpecialPage = $this->getPage();
		$context = $this->changesListSpecialPage->getContext();
		$context = new DerivativeContext( $context );
		$context->setUser( $this->getTestUser( [ 'patrollers' ] )->getUser() );
		$this->changesListSpecialPage->setContext( $context );
		$this->changesListSpecialPage->registerFilters();
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

	/**
	 * @dataProvider validateOptionsProvider
	 */
	public function testValidateOptions( $optionsToSet, $expectedRedirect, $expectedRedirectOptions ) {
		$redirectQuery = [];
		$redirected = false;
		$output = $this->getMockBuilder( OutputPage::class )
			->disableProxyingToOriginalMethods()
			->disableOriginalConstructor()
			->getMock();
		$output->method( 'redirect' )->willReturnCallback(
			function ( $url ) use ( &$redirectQuery, &$redirected ) {
				$urlParts = wfParseUrl( $url );
				$query = isset( $urlParts[ 'query' ] ) ? $urlParts[ 'query' ] : '';
				parse_str( $query, $redirectQuery );
				$redirected = true;
			}
		);
		$ctx = new RequestContext();

		// Give users patrol permissions so we can test that.
		$user = $this->getTestSysop()->getUser();
		$ctx->setUser( $user );

		// Disable this hook or it could break changeType
		// depending on which other extensions are running.
		$this->setTemporaryHook(
			'ChangesListSpecialPageStructuredFilters',
			null
		);

		$ctx->setOutput( $output );
		$clsp = $this->changesListSpecialPage;
		$clsp->setContext( $ctx );
		$opts = $clsp->getDefaultOptions();

		foreach ( $optionsToSet as $option => $value ) {
			$opts->setValue( $option, $value );
		}

		$clsp->validateOptions( $opts );

		$this->assertEquals( $expectedRedirect, $redirected, 'redirection' );

		if ( $expectedRedirect ) {
			if ( count( $expectedRedirectOptions ) > 0 ) {
				$expectedRedirectOptions += [
					'title' => $clsp->getPageTitle()->getPrefixedText(),
				];
			}

			$this->assertArrayEquals(
				$expectedRedirectOptions,
				$redirectQuery,
				/* $ordered= */ false,
				/* $named= */ true,
				'redirection query'
			);
		}
	}
}
