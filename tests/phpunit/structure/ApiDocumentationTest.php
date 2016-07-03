<?php

/**
 * Checks that all API modules, core and extensions, have documentation i18n messages
 *
 * It won't catch everything since i18n messages can vary based on the wiki
 * configuration, but it should catch many cases for forgotten i18n.
 *
 * @group API
 */
class ApiDocumentationTest extends MediaWikiTestCase {

	/** @var ApiMain */
	private static $main;

	/** @var array Sets of globals to test. Each array element is input to HashConfig */
	private static $testGlobals = [
		[
			'MiserMode' => false,
			'AllowCategorizedRecentChanges' => false,
		],
		[
			'MiserMode' => true,
			'AllowCategorizedRecentChanges' => true,
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
				Title::makeTitle( NS_SPECIAL, 'Badtitle/dummy title for ApiDocumentationTest' )
			);
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
		$this->assertInstanceOf( 'Message', $msg, "$what message" );
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
		$this->checkMessage( $module->getDescriptionMessage(), 'Module description' );

		// Parameters. Lots of messages in here.
		$params = $module->getFinalParams( ApiBase::GET_VALUES_FOR_HELP );
		$tags = [];
		foreach ( $params as $name => $settings ) {
			if ( !is_array( $settings ) ) {
				$settings = [];
			}

			// Basic description message
			if ( isset( $settings[ApiBase::PARAM_HELP_MSG] ) ) {
				$msg = $settings[ApiBase::PARAM_HELP_MSG];
			} else {
				$msg = "apihelp-{$path}-param-{$name}";
			}
			$this->checkMessage( $msg, "Parameter $name description" );

			// If param-per-value is in use, each value's message
			if ( isset( $settings[ApiBase::PARAM_HELP_MSG_PER_VALUE] ) ) {
				$this->assertInternalType( 'array', $settings[ApiBase::PARAM_HELP_MSG_PER_VALUE],
					"Parameter $name PARAM_HELP_MSG_PER_VALUE is array" );
				$this->assertInternalType( 'array', $settings[ApiBase::PARAM_TYPE],
					"Parameter $name PARAM_TYPE is array for msg-per-value mode" );
				$valueMsgs = $settings[ApiBase::PARAM_HELP_MSG_PER_VALUE];
				foreach ( $settings[ApiBase::PARAM_TYPE] as $value ) {
					if ( isset( $valueMsgs[$value] ) ) {
						$msg = $valueMsgs[$value];
					} else {
						$msg = "apihelp-{$path}-paramvalue-{$name}-{$value}";
					}
					$this->checkMessage( $msg, "Parameter $name value $value" );
				}
			}

			// Appended messages (e.g. "disabled in miser mode")
			if ( isset( $settings[ApiBase::PARAM_HELP_MSG_APPEND] ) ) {
				$this->assertInternalType( 'array', $settings[ApiBase::PARAM_HELP_MSG_APPEND],
					"Parameter $name PARAM_HELP_MSG_APPEND is array" );
				foreach ( $settings[ApiBase::PARAM_HELP_MSG_APPEND] as $i => $msg ) {
					$this->checkMessage( $msg, "Parameter $name HELP_MSG_APPEND #$i" );
				}
			}

			// Info tags (e.g. "only usable in mode 1") are typically shared by
			// several parameters, so accumulate them and test them later.
			if ( !empty( $settings[ApiBase::PARAM_HELP_MSG_INFO] ) ) {
				foreach ( $settings[ApiBase::PARAM_HELP_MSG_INFO] as $i ) {
					$tags[array_shift( $i )] = 1;
				}
			}
		}

		// Info tags (e.g. "only usable in mode 1") accumulated above
		foreach ( $tags as $tag => $dummy ) {
			$this->checkMessage( "apihelp-{$path}-paraminfo-{$tag}", "HELP_MSG_INFO tag $tag" );
		}

		// Messages for examples.
		foreach ( $module->getExamplesMessages() as $qs => $msg ) {
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
