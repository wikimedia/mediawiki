<?php

use Wikimedia\TestingAccessWrapper;

/**
 * Checks that all API modules, core and extensions, conform to the conventions:
 * - have documentation i18n messages (the test won't catch everything since
 *   i18n messages can vary based on the wiki configuration, but it should
 *   catch many cases for forgotten i18n)
 * - do not have inconsistencies in the parameter definitions
 *
 * @group API
 */
class ApiStructureTest extends MediaWikiIntegrationTestCase {

	/** @var ApiMain */
	private static $main;

	/** @var array Sets of globals to test. Each array element is input to HashConfig */
	private static $testGlobals = [
		[
			'MiserMode' => false,
		],
		[
			'MiserMode' => true,
		],
	];

	/**
	 * Initialize/fetch the ApiMain instance for testing
	 * @return ApiMain
	 */
	private static function getMain() {
		if ( !self::$main ) {
			self::$main = new ApiMain( RequestContext::getMain() );
			self::$main->getContext()->setLanguage( 'en' );
			self::$main->getContext()->setTitle(
				Title::makeTitle( NS_SPECIAL, 'Badtitle/dummy title for ApiStructureTest' )
			);

			// Inject ApiDisabled and ApiQueryDisabled so they can be tested too
			self::$main->getModuleManager()->addModule( 'disabled', 'action', ApiDisabled::class );
			self::$main->getModuleFromPath( 'query' )
				->getModuleManager()->addModule( 'query-disabled', 'meta', ApiQueryDisabled::class );
		}
		return self::$main;
	}

	/**
	 * Test a message
	 * @param Message $msg
	 * @param string $what Which message is being checked
	 */
	private function checkMessage( $msg, $what ) {
		$msg = ApiBase::makeMessage( $msg, self::getMain()->getContext() );
		$this->assertInstanceOf( Message::class, $msg, "$what message" );
		$this->assertTrue( $msg->exists(), "$what message {$msg->getKey()} exists" );
	}

	/**
	 * @dataProvider provideDocumentationExists
	 * @param string $path Module path
	 * @param array $globals Globals to set
	 */
	public function testDocumentationExists( $path, array $globals ) {
		$main = self::getMain();

		// Set configuration variables
		$main->getContext()->setConfig( new MultiConfig( [
			new HashConfig( $globals ),
			RequestContext::getMain()->getConfig(),
		] ) );
		foreach ( $globals as $k => $v ) {
			$this->setMwGlobals( "wg$k", $v );
		}

		// Fetch module.
		$module = TestingAccessWrapper::newFromObject( $main->getModuleFromPath( $path ) );

		// Test messages for flags.
		foreach ( $module->getHelpFlags() as $flag ) {
			$this->checkMessage( "api-help-flag-$flag", "Flag $flag" );
		}

		// Module description messages.
		$this->checkMessage( $module->getSummaryMessage(), 'Module summary' );
		$this->checkMessage( $module->getExtendedDescription(), 'Module help top text' );

		// Messages for examples.
		foreach ( $module->getExamplesMessages() as $qs => $msg ) {
			$this->assertStringStartsNotWith( 'api.php?', $qs,
				"Query string must not begin with 'api.php?'" );
			$this->checkMessage( $msg, "Example $qs" );
		}
	}

	public static function provideDocumentationExists() {
		$main = self::getMain();
		$paths = self::getSubModulePaths( $main->getModuleManager() );
		array_unshift( $paths, $main->getModulePath() );

		$ret = [];
		foreach ( $paths as $path ) {
			foreach ( self::$testGlobals as $globals ) {
				$g = [];
				foreach ( $globals as $k => $v ) {
					$g[] = "$k=" . var_export( $v, 1 );
				}
				$k = "Module $path with " . implode( ', ', $g );
				$ret[$k] = [ $path, $globals ];
			}
		}
		return $ret;
	}

	/**
	 * @dataProvider provideParameters
	 * @param string $path
	 * @param array $params
	 * @param string $name
	 */
	public function testParameters( string $path, array $params, string $name ) : void {
		$main = self::getMain();

		$dataName = $this->dataName();
		$this->assertNotSame( '', $name, "$dataName: Name cannot be empty" );
		$this->assertArrayHasKey( $name, $params, "$dataName: Sanity check" );

		$ret = $main->getParamValidator()->checkSettings(
			$main->getModuleFromPath( $path ), $params, $name, []
		);

		// Warn about unknown keys. Don't fail, they might be for forward- or back-compat.
		if ( is_array( $params[$name] ) ) {
			$keys = array_diff(
				array_keys( $params[$name] ),
				$ret['allowedKeys']
			);
			if ( $keys ) {
				// Don't fail for this, for back-compat
				$this->addWarning(
					"$dataName: Unrecognized settings keys were used: " . implode( ', ', $keys )
				);
			}
		}

		if ( count( $ret['issues'] ) === 1 ) {
			$this->fail( "$dataName: Validation failed: " . reset( $ret['issues'] ) );
		} elseif ( $ret['issues'] ) {
			$this->fail( "$dataName: Validation failed:\n* " . implode( "\n* ", $ret['issues'] ) );
		}

		// Check message existence
		$done = [];
		foreach ( $ret['messages'] as $msg ) {
			// We don't really care about the parameters, so do it simply
			$key = $msg->getKey();
			if ( !isset( $done[$key] ) ) {
				$done[$key] = true;
				$this->checkMessage( $key, "$dataName: Parameter" );
			}
		}
	}

	public static function provideParameters() : Iterator {
		$main = self::getMain();
		$paths = self::getSubModulePaths( $main->getModuleManager() );
		array_unshift( $paths, $main->getModulePath() );
		$argsets = [
			'plain' => [],
			'for help' => [ ApiBase::GET_VALUES_FOR_HELP ],
		];

		foreach ( $paths as $path ) {
			$module = $main->getModuleFromPath( $path );
			foreach ( $argsets as $argset => $args ) {
				$params = $module->getFinalParams( ...$args );
				foreach ( $params as $param => $dummy ) {
					yield "Module $path, $argset, parameter $param" => [ $path, $params, $param ];
				}
			}
		}
	}

	/**
	 * Return paths of all submodules in an ApiModuleManager, recursively
	 * @param ApiModuleManager $manager
	 * @return string[]
	 */
	protected static function getSubModulePaths( ApiModuleManager $manager ) {
		$paths = [];
		foreach ( $manager->getNames() as $name ) {
			$module = $manager->getModule( $name );
			$paths[] = $module->getModulePath();
			$subManager = $module->getModuleManager();
			if ( $subManager ) {
				$paths = array_merge( $paths, self::getSubModulePaths( $subManager ) );
			}
		}
		return $paths;
	}
}
