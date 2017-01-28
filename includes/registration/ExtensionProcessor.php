<?php

class ExtensionProcessor implements Processor {

	/**
	 * Keys that should be set to $GLOBALS
	 * Mapped to true purely for performance
	 * (isset faster than in_array)
	 *
	 * @var array
	 */
	protected static $globalSettings = [
		'ActionFilteredLogs' => true,
		'Actions' => true,
		'AddGroups' => true,
		'APIFormatModules' => true,
		'APIListModules' => true,
		'APIMetaModules' => true,
		'APIModules' => true,
		'APIPropModules' => true,
		'AuthManagerAutoConfig' => true,
		'AvailableRights' => true,
		'CentralIdLookupProviders' => true,
		'ChangeCredentialsBlacklist' => true,
		'ConfigRegistry' => true,
		'ContentHandlers' => true,
		'DefaultUserOptions' => true,
		'ExtensionEntryPointListFiles' => true,
		'ExtensionFunctions' => true,
		'FeedClasses' => true,
		'FileExtensions' => true,
		'FilterLogTypes' => true,
		'GrantPermissionGroups' => true,
		'GrantPermissions' => true,
		'GroupPermissions' => true,
		'GroupsAddToSelf' => true,
		'GroupsRemoveFromSelf' => true,
		'HiddenPrefs' => true,
		'ImplicitGroups' => true,
		'JobClasses' => true,
		'LogActions' => true,
		'LogActionsHandlers' => true,
		'LogHeaders' => true,
		'LogNames' => true,
		'LogRestrictions' => true,
		'LogTypes' => true,
		'MediaHandlers' => true,
		'PasswordPolicy' => true,
		'RateLimits' => true,
		'RecentChangesFlags' => true,
		'RemoveCredentialsBlacklist' => true,
		'RemoveGroups' => true,
		'ResourceLoaderLESSVars' => true,
		'ResourceLoaderSources' => true,
		'RevokePermissions' => true,
		'SessionProviders' => true,
		'SpecialPages' => true,
		'ValidSkinNames' => true,
	];

	/**
	 * Keys that are part of the extension credits
	 *
	 * @var array
	 */
	protected static $creditsAttributes = [
		'name' => true,
		'namemsg' => true,
		'author' => true,
		'version' => true,
		'url' => true,
		'description' => true,
		'descriptionmsg' => true,
		'license-name' => true,
	];

	/**
	 * Things that are not 'attributes', but are not in
	 * $globalSettings or $creditsAttributes.
	 *
	 * @var array
	 */
	protected static $notAttributes = [
		'callback' => true,
		'Hooks' => true,
		'namespaces' => true,
		'ResourceFileModulePaths' => true,
		'ResourceModules' => true,
		'ResourceModuleSkinStyles' => true,
		'ExtensionMessagesFiles' => true,
		'MessagesDirs' => true,
		'type' => true,
		'config' => true,
		'config_prefix' => true,
		'config_remove_globals' => true,
		'ServiceWiringFiles' => true,
		'ParserTestFiles' => true,
		'AutoloadClasses' => true,
		'manifest_version' => true,
		'load_composer_autoloader' => true,
	];

	/**
	 * Stuff that is going to be set to $GLOBALS
	 *
	 * Some keys are pre-set to arrays so we can += to them
	 *
	 * @var array
	 */
	protected $globals = [
		'wgExtensionMessagesFiles' => [],
		'wgMessagesDirs' => [],
	];

	/**
	 * @var array
	 */
	protected $globalMergeStrategies = [];

	/**
	 * Things that should be define()'d
	 *
	 * @var array
	 */
	protected $defines = [];

	/**
	 * Things to be called once registration of these extensions are done
	 * keyed by the name of the extension that it belongs to
	 *
	 * @var callable[]
	 */
	protected $callbacks = [];

	/**
	 * @var array
	 */
	protected $credits = [];

	/**
	 * Any thing else in the $info that hasn't
	 * already been processed
	 *
	 * @var array
	 */
	protected $attributes = [];

	/**
	 * @var Config[]
	 */
	protected $configs = [];

	/**
	 * @var array
	 */
	protected $configMergeStrategies = [];

	/**
	 * @param string $path
	 * @param array $info
	 * @param int $version manifest_version for info
	 * @return array
	 */
	public function extractInfo( $path, array $info, $version ) {
		$dir = dirname( $path );
		$name = $this->extractCredits( $path, $info );
		if ( $version === 2 ) {
			$this->extractConfig2( $info, $dir, $name );
		} else {
			// $version === 1
			$this->extractConfig1( $info, $name );
		}
		$this->extractHooks( $info );
		$this->extractExtensionMessagesFiles( $dir, $info );
		$this->extractMessagesDirs( $dir, $info );
		$this->extractNamespaces( $info );
		$this->extractResourceLoaderModules( $dir, $info );
		$this->extractServiceWiringFiles( $dir, $info );
		$this->extractParserTestFiles( $dir, $info );
		if ( isset( $info['callback'] ) ) {
			$this->callbacks[$name] = $info['callback'];
		}

		foreach ( $info as $key => $val ) {
			if ( isset( self::$globalSettings[$key] ) ) {
				$this->storeToArray( $path, "wg$key", $val, $this->globals );
			// Ignore anything that starts with a @
			} elseif ( $key[0] !== '@' && !isset( self::$notAttributes[$key] )
				&& !isset( self::$creditsAttributes[$key] )
			) {
				$this->storeToArray( $path, $key, $val, $this->attributes );
			}
		}
	}

	public function getExtractedInfo() {
		return [
			'globals' => $this->globals,
			'globalMergeStrategies' => $this->globalMergeStrategies,
			'configs' => $this->configs,
			'configMergeStrategies' => $this->configMergeStrategies,
			'defines' => $this->defines,
			'callbacks' => $this->callbacks,
			'credits' => $this->credits,
			'attributes' => $this->attributes,
		];
	}

	public function getRequirements( array $info ) {
		return isset( $info['requires'] ) ? $info['requires'] : [];
	}

	protected function extractHooks( array $info ) {
		if ( isset( $info['Hooks'] ) ) {
			foreach ( $info['Hooks'] as $name => $value ) {
				if ( is_array( $value ) ) {
					foreach ( $value as $callback ) {
						$this->globals['wgHooks'][$name][] = $callback;
					}
				} else {
					$this->globals['wgHooks'][$name][] = $value;
				}
			}
		}
	}

	/**
	 * Register namespaces with the appropriate global settings
	 *
	 * @param array $info
	 */
	protected function extractNamespaces( array $info ) {
		if ( isset( $info['namespaces'] ) ) {
			foreach ( $info['namespaces'] as $ns ) {
				$id = $ns['id'];
				$this->defines[$ns['constant']] = $id;
				if ( !( isset( $ns['conditional'] ) && $ns['conditional'] ) ) {
					// If it is not conditional, register it
					$this->attributes['ExtensionNamespaces'][$id] = $ns['name'];
				}
				if ( isset( $ns['gender'] ) ) {
					$this->globals['wgExtraGenderNamespaces'][$id] = $ns['gender'];
				}
				if ( isset( $ns['subpages'] ) && $ns['subpages'] ) {
					$this->globals['wgNamespacesWithSubpages'][$id] = true;
				}
				if ( isset( $ns['content'] ) && $ns['content'] ) {
					$this->globals['wgContentNamespaces'][] = $id;
				}
				if ( isset( $ns['defaultcontentmodel'] ) ) {
					$this->globals['wgNamespaceContentModels'][$id] = $ns['defaultcontentmodel'];
				}
				if ( isset( $ns['protection'] ) ) {
					$this->globals['wgNamespaceProtection'][$id] = $ns['protection'];
				}
				if ( isset( $ns['capitallinkoverride'] ) ) {
					$this->globals['wgCapitalLinkOverrides'][$id] = $ns['capitallinkoverride'];
				}
			}
		}
	}

	protected function extractResourceLoaderModules( $dir, array $info ) {
		$defaultPaths = isset( $info['ResourceFileModulePaths'] )
			? $info['ResourceFileModulePaths']
			: false;
		if ( isset( $defaultPaths['localBasePath'] ) ) {
			if ( $defaultPaths['localBasePath'] === '' ) {
				// Avoid double slashes (e.g. /extensions/Example//path)
				$defaultPaths['localBasePath'] = $dir;
			} else {
				$defaultPaths['localBasePath'] = "$dir/{$defaultPaths['localBasePath']}";
			}
		}

		foreach ( [ 'ResourceModules', 'ResourceModuleSkinStyles' ] as $setting ) {
			if ( isset( $info[$setting] ) ) {
				foreach ( $info[$setting] as $name => $data ) {
					if ( isset( $data['localBasePath'] ) ) {
						if ( $data['localBasePath'] === '' ) {
							// Avoid double slashes (e.g. /extensions/Example//path)
							$data['localBasePath'] = $dir;
						} else {
							$data['localBasePath'] = "$dir/{$data['localBasePath']}";
						}
					}
					if ( $defaultPaths ) {
						$data += $defaultPaths;
					}
					$this->globals["wg$setting"][$name] = $data;
				}
			}
		}
	}

	protected function extractExtensionMessagesFiles( $dir, array $info ) {
		if ( isset( $info['ExtensionMessagesFiles'] ) ) {
			$this->globals["wgExtensionMessagesFiles"] += array_map( function( $file ) use ( $dir ) {
				return "$dir/$file";
			}, $info['ExtensionMessagesFiles'] );
		}
	}

	/**
	 * Set message-related settings, which need to be expanded to use
	 * absolute paths
	 *
	 * @param string $dir
	 * @param array $info
	 */
	protected function extractMessagesDirs( $dir, array $info ) {
		if ( isset( $info['MessagesDirs'] ) ) {
			foreach ( $info['MessagesDirs'] as $name => $files ) {
				foreach ( (array)$files as $file ) {
					$this->globals["wgMessagesDirs"][$name][] = "$dir/$file";
				}
			}
		}
	}

	/**
	 * @param string $path
	 * @param array $info
	 * @return string Name of thing
	 * @throws Exception
	 */
	protected function extractCredits( $path, array $info ) {
		$credits = [
			'path' => $path,
			'type' => isset( $info['type'] ) ? $info['type'] : 'other',
		];
		foreach ( self::$creditsAttributes as $attr => &$t ) {
			if ( isset( $info[$attr] ) ) {
				$credits[$attr] = $info[$attr];
			}
		}

		$name = $credits['name'];

		// If someone is loading the same thing twice, throw
		// a nice error (T121493)
		if ( isset( $this->credits[$name] ) ) {
			$firstPath = $this->credits[$name]['path'];
			$secondPath = $credits['path'];
			throw new Exception( "It was attempted to load $name twice, from $firstPath and $secondPath." );
		}

		$this->credits[$name] = $credits;
		$this->globals['wgExtensionCredits'][$credits['type']][] = $credits;

		return $name;
	}

	/**
	 * Set configuration settings for manifest_version == 1
	 * @todo In the future, this should be done via Config interfaces
	 *
	 * @param array $info
	 * @param string $name
	 */
	protected function extractConfig1( array $info, $name ) {
		if ( isset( $info['config'] ) ) {
			if ( isset( $info['config']['_prefix'] ) ) {
				$prefix = $info['config']['_prefix'];
				unset( $info['config']['_prefix'] );
			} else {
				$prefix = 'wg';
			}
			$this->configs[$name] = [];
			$populateGlobals = !isset( $data['config_remove_globals'] );
			foreach ( $info['config'] as $key => $value ) {
				if ( $key[0] !== '@' ) {
					if ( $populateGlobals ) {
						$this->globals["$prefix$key"] = $value;
					}
					$this->configs[$name][$key] = $value;
				}
			}
		}
	}

	/**
	 * Set configuration settings for manifest_version == 2
	 * @todo In the future, this should be done via Config interfaces
	 *
	 * @param array $info
	 * @param string $dir
	 * @param string $name
	 */
	protected function extractConfig2( array $info, $dir, $name ) {
		if ( isset( $info['config_prefix'] ) ) {
			$prefix = $info['config_prefix'];
		} else {
			$prefix = 'wg';
		}
		if ( isset( $info['config'] ) ) {
			$this->configs[$name] = [];
			$this->configMergeStrategies[$name] = [];
			$populateGlobals = !isset( $data['config_remove_globals'] );
			foreach ( $info['config'] as $key => $data ) {
				$value = $data['value'];
				if ( isset( $data['merge_strategy'] ) ) {
					$this->configMergeStrategies[$name][$key] = $data['merge_strategy'];
				}
				if ( isset( $data['path'] ) && $data['path'] ) {
					$value = "$dir/$value";
				}
				if ( $populateGlobals ) {
					$this->globals["$prefix$key"] = $value;
					if ( isset( $data['merge_strategy'] ) ) {
						$this->globalMergeStrategies["$prefix$key"] = $data['merge_strategy'];
					}
				}
				$this->configs[$name][$key] = $value;
			}
		}
	}

	protected function extractServiceWiringFiles( $dir, array $info ) {
		if ( isset( $info['ServiceWiringFiles'] ) ) {
			foreach ( $info['ServiceWiringFiles'] as $path ) {
				$this->globals['wgServiceWiringFiles'][] = "$dir/$path";
			}
		}
	}

	protected function extractParserTestFiles( $dir, array $info ) {
		if ( isset( $info['ParserTestFiles'] ) ) {
			foreach ( $info['ParserTestFiles'] as $path ) {
				$this->globals['wgParserTestFiles'][] = "$dir/$path";
			}
		}
	}

	/**
	 * @param string $path
	 * @param string $name
	 * @param array $value
	 * @param array &$array
	 * @throws InvalidArgumentException
	 */
	protected function storeToArray( $path, $name, $value, &$array ) {
		if ( !is_array( $value ) ) {
			throw new InvalidArgumentException( "The value for '$name' should be an array (from $path)" );
		}
		if ( isset( $array[$name] ) ) {
			$array[$name] = array_merge_recursive( $array[$name], $value );
		} else {
			$array[$name] = $value;
		}
	}

	public function getExtraAutoloaderPaths( $dir, array $info ) {
		$paths = [];
		if ( isset( $info['load_composer_autoloader'] ) && $info['load_composer_autoloader'] === true ) {
			$path = "$dir/vendor/autoload.php";
			if ( file_exists( $path ) ) {
				$paths[] = $path;
			}
		}
		return $paths;
	}
}
