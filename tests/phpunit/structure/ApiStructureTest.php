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
class ApiStructureTest extends MediaWikiTestCase {

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
	 * Values are an array, where each array value is a permitted type.  A type
	 * can be a string, which is the name of an internal type or a
	 * class/interface.  Or it can be an array, in which case the value must be
	 * an array whose elements are the types given in the array (e.g., [
	 * 'string', integer' ] means an array whose entries are strings and/or
	 * integers).
	 */
	private static $paramTypes = [
		// ApiBase::PARAM_DFLT => as appropriate for PARAM_TYPE
		ApiBase::PARAM_ISMULTI => [ 'boolean' ],
		ApiBase::PARAM_TYPE => [ 'string', [ 'string' ] ],
		ApiBase::PARAM_MAX => [ 'integer' ],
		ApiBase::PARAM_MAX2 => [ 'integer' ],
		ApiBase::PARAM_MIN => [ 'integer' ],
		ApiBase::PARAM_ALLOW_DUPLICATES => [ 'boolean' ],
		ApiBase::PARAM_DEPRECATED => [ 'boolean' ],
		ApiBase::PARAM_REQUIRED => [ 'boolean' ],
		ApiBase::PARAM_RANGE_ENFORCE => [ 'boolean' ],
		ApiBase::PARAM_HELP_MSG => [ 'string', 'array', Message::class ],
		ApiBase::PARAM_HELP_MSG_APPEND => [ [ 'string', 'array', Message::class ] ],
		ApiBase::PARAM_HELP_MSG_INFO => [ [ 'array' ] ],
		ApiBase::PARAM_VALUE_LINKS => [ [ 'string' ] ],
		ApiBase::PARAM_HELP_MSG_PER_VALUE => [ [ 'string', 'array', Message::class ] ],
		ApiBase::PARAM_SUBMODULE_MAP => [ [ 'string' ] ],
		ApiBase::PARAM_SUBMODULE_PARAM_PREFIX => [ 'string' ],
		ApiBase::PARAM_ALL => [ 'boolean', 'string' ],
		ApiBase::PARAM_EXTRA_NAMESPACES => [ [ 'integer' ] ],
		ApiBase::PARAM_SENSITIVE => [ 'boolean' ],
		ApiBase::PARAM_DEPRECATED_VALUES => [ 'array' ],
		ApiBase::PARAM_ISMULTI_LIMIT1 => [ 'integer' ],
		ApiBase::PARAM_ISMULTI_LIMIT2 => [ 'integer' ],
		ApiBase::PARAM_MAX_BYTES => [ 'integer' ],
		ApiBase::PARAM_MAX_CHARS => [ 'integer' ],
		ApiBase::PARAM_TEMPLATE_VARS => [ 'array' ],
	];

	// param => [ other param that must be present => required value or null ]
	private static $paramRequirements = [
		ApiBase::PARAM_ALLOW_DUPLICATES => [ ApiBase::PARAM_ISMULTI => true ],
		ApiBase::PARAM_ALL => [ ApiBase::PARAM_ISMULTI => true ],
		ApiBase::PARAM_ISMULTI_LIMIT1 => [
			ApiBase::PARAM_ISMULTI => true,
			ApiBase::PARAM_ISMULTI_LIMIT2 => null,
		],
		ApiBase::PARAM_ISMULTI_LIMIT2 => [
			ApiBase::PARAM_ISMULTI => true,
			ApiBase::PARAM_ISMULTI_LIMIT1 => null,
		],
	];

	// param => type(s) allowed for this param ('array' is any array)
	private static $paramAllowedTypes = [
		ApiBase::PARAM_MAX => [ 'integer', 'limit' ],
		ApiBase::PARAM_MAX2 => 'limit',
		ApiBase::PARAM_MIN => [ 'integer', 'limit' ],
		ApiBase::PARAM_RANGE_ENFORCE => 'integer',
		ApiBase::PARAM_VALUE_LINKS => 'array',
		ApiBase::PARAM_HELP_MSG_PER_VALUE => 'array',
		ApiBase::PARAM_SUBMODULE_MAP => 'submodule',
		ApiBase::PARAM_SUBMODULE_PARAM_PREFIX => 'submodule',
		ApiBase::PARAM_ALL => 'array',
		ApiBase::PARAM_EXTRA_NAMESPACES => 'namespace',
		ApiBase::PARAM_DEPRECATED_VALUES => 'array',
		ApiBase::PARAM_MAX_BYTES => [ 'NULL', 'string', 'text', 'password' ],
		ApiBase::PARAM_MAX_CHARS => [ 'NULL', 'string', 'text', 'password' ],
	];

	private static $paramProhibitedTypes = [
		ApiBase::PARAM_ISMULTI => [ 'boolean', 'limit', 'upload' ],
		ApiBase::PARAM_ALL => 'namespace',
		ApiBase::PARAM_SENSITIVE => 'password',
	];

	private static $constantNames = null;

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
	 * @dataProvider provideParameterConsistency
	 * @param string $path
	 */
	public function testParameterConsistency( $path ) {
		$main = self::getMain();
		$module = TestingAccessWrapper::newFromObject( $main->getModuleFromPath( $path ) );

		$paramsPlain = $module->getFinalParams();
		$paramsForHelp = $module->getFinalParams( ApiBase::GET_VALUES_FOR_HELP );

		// avoid warnings about empty tests when no parameter needs to be checked
		$this->assertTrue( true );

		if ( self::$constantNames === null ) {
			self::$constantNames = [];

			foreach ( ( new ReflectionClass( 'ApiBase' ) )->getConstants() as $key => $val ) {
				if ( substr( $key, 0, 6 ) === 'PARAM_' ) {
					self::$constantNames[$val] = $key;
				}
			}
		}

		foreach ( [ $paramsPlain, $paramsForHelp ] as $params ) {
			foreach ( $params as $param => $config ) {
				if ( !is_array( $config ) ) {
					$config = [ ApiBase::PARAM_DFLT => $config ];
				}
				if ( !isset( $config[ApiBase::PARAM_TYPE] ) ) {
					$config[ApiBase::PARAM_TYPE] = isset( $config[ApiBase::PARAM_DFLT] )
						? gettype( $config[ApiBase::PARAM_DFLT] )
						: 'NULL';
				}

				foreach ( self::$paramTypes as $key => $types ) {
					if ( !isset( $config[$key] ) ) {
						continue;
					}
					$keyName = self::$constantNames[$key];
					$this->validateType( $types, $config[$key], $param, $keyName );
				}

				foreach ( self::$paramRequirements as $key => $required ) {
					if ( !isset( $config[$key] ) ) {
						continue;
					}
					foreach ( $required as $requireKey => $requireVal ) {
						$this->assertArrayHasKey( $requireKey, $config,
							"$param: When " . self::$constantNames[$key] . " is set, " .
							self::$constantNames[$requireKey] . " must also be set" );
						if ( $requireVal !== null ) {
							$this->assertSame( $requireVal, $config[$requireKey],
								"$param: When " . self::$constantNames[$key] . " is set, " .
								self::$constantNames[$requireKey] . " must equal " .
								var_export( $requireVal, true ) );
						}
					}
				}

				foreach ( self::$paramAllowedTypes as $key => $allowedTypes ) {
					if ( !isset( $config[$key] ) ) {
						continue;
					}

					$actualType = is_array( $config[ApiBase::PARAM_TYPE] )
						? 'array' : $config[ApiBase::PARAM_TYPE];

					$this->assertContains(
						$actualType,
						(array)$allowedTypes,
						"$param: " . self::$constantNames[$key] .
							" can only be used with PARAM_TYPE " .
							implode( ', ', (array)$allowedTypes )
					);
				}

				foreach ( self::$paramProhibitedTypes as $key => $prohibitedTypes ) {
					if ( !isset( $config[$key] ) ) {
						continue;
					}

					$actualType = is_array( $config[ApiBase::PARAM_TYPE] )
						? 'array' : $config[ApiBase::PARAM_TYPE];

					$this->assertNotContains(
						$actualType,
						(array)$prohibitedTypes,
						"$param: " . self::$constantNames[$key] .
							" cannot be used with PARAM_TYPE " .
							implode( ', ', (array)$prohibitedTypes )
					);
				}

				if ( isset( $config[ApiBase::PARAM_DFLT] ) ) {
					$this->assertFalse(
						isset( $config[ApiBase::PARAM_REQUIRED] ) &&
							$config[ApiBase::PARAM_REQUIRED],
						"$param: A required parameter cannot have a default" );

					$this->validateDefault( $param, $config );
				}

				if ( $config[ApiBase::PARAM_TYPE] === 'limit' ) {
					$this->assertTrue(
						isset( $config[ApiBase::PARAM_MAX] ) &&
							isset( $config[ApiBase::PARAM_MAX2] ),
						"$param: PARAM_MAX and PARAM_MAX2 are required for limits"
					);
					$this->assertGreaterThanOrEqual(
						$config[ApiBase::PARAM_MAX],
						$config[ApiBase::PARAM_MAX2],
						"$param: PARAM_MAX cannot be greater than PARAM_MAX2"
					);
				}

				if (
					isset( $config[ApiBase::PARAM_MIN] ) &&
					isset( $config[ApiBase::PARAM_MAX] )
				) {
					$this->assertGreaterThanOrEqual(
						$config[ApiBase::PARAM_MIN],
						$config[ApiBase::PARAM_MAX],
						"$param: PARAM_MIN cannot be greater than PARAM_MAX"
					);
				}

				if ( isset( $config[ApiBase::PARAM_RANGE_ENFORCE] ) ) {
					$this->assertTrue(
						isset( $config[ApiBase::PARAM_MIN] ) ||
							isset( $config[ApiBase::PARAM_MAX] ),
						"$param: PARAM_RANGE_ENFORCE can only be set together with " .
							"PARAM_MIN or PARAM_MAX"
					);
				}

				if ( isset( $config[ApiBase::PARAM_DEPRECATED_VALUES] ) ) {
					foreach ( $config[ApiBase::PARAM_DEPRECATED_VALUES] as $key => $unused ) {
						$this->assertContains( $key, $config[ApiBase::PARAM_TYPE],
							"$param: Deprecated value \"$key\" is not allowed, " .
							"how can it be deprecated?" );
					}
				}

				if (
					isset( $config[ApiBase::PARAM_ISMULTI_LIMIT1] ) ||
					isset( $config[ApiBase::PARAM_ISMULTI_LIMIT2] )
				) {
					$this->assertGreaterThanOrEqual( 0, $config[ApiBase::PARAM_ISMULTI_LIMIT1],
						"$param: PARAM_ISMULTI_LIMIT1 cannot be negative" );
					// Zero for both doesn't make sense, but you could have
					// zero for non-bots
					$this->assertGreaterThanOrEqual( 1, $config[ApiBase::PARAM_ISMULTI_LIMIT2],
						"$param: PARAM_ISMULTI_LIMIT2 cannot be negative or zero" );
					$this->assertGreaterThanOrEqual(
						$config[ApiBase::PARAM_ISMULTI_LIMIT1],
						$config[ApiBase::PARAM_ISMULTI_LIMIT2],
						"$param: PARAM_ISMULTI limit cannot be smaller for users with " .
							"apihighlimits rights" );
				}

				if ( isset( $config[ApiBase::PARAM_MAX_BYTES] ) ) {
					$this->assertGreaterThanOrEqual( 1, $config[ApiBase::PARAM_MAX_BYTES],
						"$param: PARAM_MAX_BYTES cannot be negative or zero" );
				}

				if ( isset( $config[ApiBase::PARAM_MAX_CHARS] ) ) {
					$this->assertGreaterThanOrEqual( 1, $config[ApiBase::PARAM_MAX_CHARS],
						"$param: PARAM_MAX_CHARS cannot be negative or zero" );
				}

				if (
					isset( $config[ApiBase::PARAM_MAX_BYTES] ) &&
					isset( $config[ApiBase::PARAM_MAX_CHARS] )
				) {
					// Length of a string in chars is always <= length in bytes,
					// so PARAM_MAX_CHARS is pointless if > PARAM_MAX_BYTES
					$this->assertGreaterThanOrEqual(
						$config[ApiBase::PARAM_MAX_CHARS],
						$config[ApiBase::PARAM_MAX_BYTES],
						"$param: PARAM_MAX_BYTES cannot be less than PARAM_MAX_CHARS"
					);
				}

				if ( isset( $config[ApiBase::PARAM_TEMPLATE_VARS] ) ) {
					$this->assertNotSame( [], $config[ApiBase::PARAM_TEMPLATE_VARS],
						"$param: PARAM_TEMPLATE_VARS cannot be empty" );
					foreach ( $config[ApiBase::PARAM_TEMPLATE_VARS] as $key => $target ) {
						$this->assertRegExp( '/^[^{}]+$/', $key,
							"$param: PARAM_TEMPLATE_VARS key may not contain '{' or '}'" );

						$this->assertContains( '{' . $key . '}', $param,
							"$param: Name must contain PARAM_TEMPLATE_VARS key {" . $key . "}" );
						$this->assertArrayHasKey( $target, $params,
							"$param: PARAM_TEMPLATE_VARS target parameter '$target' does not exist" );
						$config2 = $params[$target];
						$this->assertTrue( !empty( $config2[ApiBase::PARAM_ISMULTI] ),
							"$param: PARAM_TEMPLATE_VARS target parameter '$target' must have PARAM_ISMULTI = true" );

						if ( isset( $config2[ApiBase::PARAM_TEMPLATE_VARS] ) ) {
							$this->assertNotSame( $param, $target,
								"$param: PARAM_TEMPLATE_VARS cannot target itself" );

							$this->assertArraySubset(
								$config2[ApiBase::PARAM_TEMPLATE_VARS],
								$config[ApiBase::PARAM_TEMPLATE_VARS],
								true,
								"$param: PARAM_TEMPLATE_VARS target parameter '$target': "
								. "the target's PARAM_TEMPLATE_VARS must be a subset of the original."
							);
						}
					}

					$keys = implode( '|',
						array_map(
							function ( $key ) {
								return preg_quote( $key, '/' );
							},
							array_keys( $config[ApiBase::PARAM_TEMPLATE_VARS] )
						)
					);
					$this->assertRegExp( '/^(?>[^{}]+|\{(?:' . $keys . ')\})+$/', $param,
						"$param: Name may not contain '{' or '}' other than as defined by PARAM_TEMPLATE_VARS" );
				} else {
					$this->assertRegExp( '/^[^{}]+$/', $param,
						"$param: Name may not contain '{' or '}' without PARAM_TEMPLATE_VARS" );
				}
			}
		}
	}

	/**
	 * Throws if $value does not match one of the types specified in $types.
	 *
	 * @param array $types From self::$paramTypes array
	 * @param mixed $value Value to check
	 * @param string $param Name of param we're checking, for error messages
	 * @param string $desc Description for error messages
	 */
	private function validateType( $types, $value, $param, $desc ) {
		if ( count( $types ) === 1 ) {
			// Only one type allowed
			if ( is_string( $types[0] ) ) {
				$this->assertType( $types[0], $value, "$param: $desc type" );
			} else {
				// Array whose values have specified types, recurse
				$this->assertInternalType( 'array', $value, "$param: $desc type" );
				foreach ( $value as $subvalue ) {
					$this->validateType( $types[0], $subvalue, $param, "$desc value" );
				}
			}
		} else {
			// Multiple options
			foreach ( $types as $type ) {
				if ( is_string( $type ) ) {
					if ( class_exists( $type ) || interface_exists( $type ) ) {
						if ( $value instanceof $type ) {
							return;
						}
					} else {
						if ( gettype( $value ) === $type ) {
							return;
						}
					}
				} else {
					// Array whose values have specified types, recurse
					try {
						$this->validateType( [ $type ], $value, $param, "$desc type" );
						// Didn't throw, so we're good
						return;
					} catch ( Exception $unused ) {
					}
				}
			}
			// Doesn't match any of them
			$this->fail( "$param: $desc has incorrect type" );
		}
	}

	/**
	 * Asserts that $default is a valid default for $type.
	 *
	 * @param string $param Name of param, for error messages
	 * @param array $config Array of configuration options for this parameter
	 */
	private function validateDefault( $param, $config ) {
		$type = $config[ApiBase::PARAM_TYPE];
		$default = $config[ApiBase::PARAM_DFLT];

		if ( !empty( $config[ApiBase::PARAM_ISMULTI] ) ) {
			if ( $default === '' ) {
				// The empty array is fine
				return;
			}
			$defaults = explode( '|', $default );
			$config[ApiBase::PARAM_ISMULTI] = false;
			foreach ( $defaults as $defaultValue ) {
				// Only allow integers in their simplest form with no leading
				// or trailing characters etc.
				if ( $type === 'integer' && $defaultValue === (string)(int)$defaultValue ) {
					$defaultValue = (int)$defaultValue;
				}
				$config[ApiBase::PARAM_DFLT] = $defaultValue;
				$this->validateDefault( $param, $config );
			}
			return;
		}
		switch ( $type ) {
			case 'boolean':
				$this->assertFalse( $default,
					"$param: Boolean params may only default to false" );
				break;

			case 'integer':
				$this->assertInternalType( 'integer', $default,
					"$param: Default $default is not an integer" );
				break;

			case 'limit':
				if ( $default === 'max' ) {
					break;
				}
				$this->assertInternalType( 'integer', $default,
					"$param: Default $default is neither an integer nor \"max\"" );
				break;

			case 'namespace':
				$validValues = MWNamespace::getValidNamespaces();
				if (
					isset( $config[ApiBase::PARAM_EXTRA_NAMESPACES] ) &&
					is_array( $config[ApiBase::PARAM_EXTRA_NAMESPACES] )
				) {
					$validValues = array_merge(
						$validValues,
						$config[ApiBase::PARAM_EXTRA_NAMESPACES]
					);
				}
				$this->assertContains( $default, $validValues,
					"$param: Default $default is not a valid namespace" );
				break;

			case 'NULL':
			case 'password':
			case 'string':
			case 'submodule':
			case 'tags':
			case 'text':
				$this->assertInternalType( 'string', $default,
					"$param: Default $default is not a string" );
				break;

			case 'timestamp':
				if ( $default === 'now' ) {
					return;
				}
				$this->assertNotFalse( wfTimestamp( TS_MW, $default ),
					"$param: Default $default is not a valid timestamp" );
				break;

			case 'user':
				// @todo Should we make user validation a public static method
				// in ApiBase() or something so we don't have to resort to
				// this?  Or in User for that matter.
				$wrapper = TestingAccessWrapper::newFromObject( new ApiMain() );
				try {
					$wrapper->validateUser( $default, '' );
				} catch ( ApiUsageException $e ) {
					$this->fail( "$param: Default $default is not a valid username/IP address" );
				}
				break;

			default:
				if ( is_array( $type ) ) {
					$this->assertContains( $default, $type,
						"$param: Default $default is not any of " .
						implode( ', ', $type ) );
				} else {
					$this->fail( "Unrecognized type $type" );
				}
		}
	}

	/**
	 * @return array List of API module paths to test
	 */
	public static function provideParameterConsistency() {
		$main = self::getMain();
		$paths = self::getSubModulePaths( $main->getModuleManager() );
		array_unshift( $paths, $main->getModulePath() );

		$ret = [];
		foreach ( $paths as $path ) {
			$ret[] = [ $path ];
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
