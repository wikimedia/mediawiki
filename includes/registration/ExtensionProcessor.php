<?php

class ExtensionProcessor implements Processor {

	/**
	 * Keys that should be set to $GLOBALS
	 *
	 * @var array
	 */
	protected static $globalSettings = [
		'ActionFilteredLogs',
		'Actions',
		'AddGroups',
		'APIFormatModules',
		'APIListModules',
		'APIMetaModules',
		'APIModules',
		'APIPropModules',
		'AuthManagerAutoConfig',
		'AvailableRights',
		'CentralIdLookupProviders',
		'ChangeCredentialsBlacklist',
		'ConfigRegistry',
		'ContentHandlers',
		'DefaultUserOptions',
		'ExtensionEntryPointListFiles',
		'ExtensionFunctions',
		'FeedClasses',
		'FileExtensions',
		'FilterLogTypes',
		'GrantPermissionGroups',
		'GrantPermissions',
		'GroupPermissions',
		'GroupsAddToSelf',
		'GroupsRemoveFromSelf',
		'HiddenPrefs',
		'ImplicitGroups',
		'JobClasses',
		'LogActions',
		'LogActionsHandlers',
		'LogHeaders',
		'LogNames',
		'LogRestrictions',
		'LogTypes',
		'MediaHandlers',
		'PasswordPolicy',
		'RateLimits',
		'RecentChangesFlags',
		'RemoveCredentialsBlacklist',
		'RemoveGroups',
		'ResourceLoaderLESSVars',
		'ResourceLoaderSources',
		'RevokePermissions',
		'SessionProviders',
		'SpecialPages',
		'ValidSkinNames',
	];

	/**
	 * Top-level attributes that come from MW core
	 *
	 * @var string[]
	 */
	protected static $coreAttributes = [
		'SkinOOUIThemes',
		'TrackingCategories',
	];

	/**
	 * Mapping of global settings to their specific merge strategies.
	 *
	 * @see ExtensionRegistry::exportExtractedData
	 * @see getExtractedInfo
	 * @var array
	 */
	protected static $mergeStrategies = [
		'wgAuthManagerAutoConfig' => 'array_plus_2d',
		'wgCapitalLinkOverrides' => 'array_plus',
		'wgExtensionCredits' => 'array_merge_recursive',
		'wgExtraGenderNamespaces' => 'array_plus',
		'wgGrantPermissions' => 'array_plus_2d',
		'wgGroupPermissions' => 'array_plus_2d',
		'wgHooks' => 'array_merge_recursive',
		'wgNamespaceContentModels' => 'array_plus',
		'wgNamespaceProtection' => 'array_plus',
		'wgNamespacesWithSubpages' => 'array_plus',
		'wgPasswordPolicy' => 'array_merge_recursive',
		'wgRateLimits' => 'array_plus_2d',
		'wgRevokePermissions' => 'array_plus_2d',
	];

	/**
	 * Keys that are part of the extension credits
	 *
	 * @var array
	 */
	protected static $creditsAttributes = [
		'name',
		'namemsg',
		'author',
		'version',
		'url',
		'description',
		'descriptionmsg',
		'license-name',
	];

	/**
	 * Things that are not 'attributes', but are not in
	 * $globalSettings or $creditsAttributes.
	 *
	 * @var array
	 */
	protected static $notAttributes = [
		'callback',
		'Hooks',
		'namespaces',
		'ResourceFileModulePaths',
		'ResourceModules',
		'ResourceModuleSkinStyles',
		'ExtensionMessagesFiles',
		'MessagesDirs',
		'type',
		'config',
		'config_prefix',
		'ServiceWiringFiles',
		'ParserTestFiles',
		'AutoloadClasses',
		'manifest_version',
		'load_composer_autoloader',
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
	 * Extension attributes, keyed by name =>
	 *  settings.
	 *
	 * @var array
	 */
	protected $extAttributes = [];

	/**
	 * @param string $path
	 * @param array $info
	 * @param int $version manifest_version for info
	 * @return array
	 */
	public function extractInfo( $path, array $info, $version ) {
		$dir = dirname( $path );
		$this->extractHooks( $info );
		$this->extractExtensionMessagesFiles( $dir, $info );
		$this->extractMessagesDirs( $dir, $info );
		$this->extractNamespaces( $info );
		$this->extractResourceLoaderModules( $dir, $info );
		if ( isset( $info['ServiceWiringFiles'] ) ) {
			$this->extractPathBasedGlobal(
				'wgServiceWiringFiles',
				$dir,
				$info['ServiceWiringFiles']
			);
		}
		if ( isset( $info['ParserTestFiles'] ) ) {
			$this->extractPathBasedGlobal(
				'wgParserTestFiles',
				$dir,
				$info['ParserTestFiles']
			);
		}
		$name = $this->extractCredits( $path, $info );
		if ( isset( $info['callback'] ) ) {
			$this->callbacks[$name] = $info['callback'];
		}

		// config should be after all core globals are extracted,
		// so duplicate setting detection will work fully
		if ( $version === 2 ) {
			$this->extractConfig2( $info, $dir );
		} else {
			// $version === 1
			$this->extractConfig1( $info );
		}

		if ( $version === 2 ) {
			$this->extractAttributes( $path, $info );
		}

		foreach ( $info as $key => $val ) {
			// If it's a global setting,
			if ( in_array( $key, self::$globalSettings ) ) {
				$this->storeToArray( $path, "wg$key", $val, $this->globals );
				continue;
			}
			// Ignore anything that starts with a @
			if ( $key[0] === '@' ) {
				continue;
			}

			if ( $version === 2 ) {
				// Only whitelisted attributes are set
				if ( in_array( $key, self::$coreAttributes ) ) {
					$this->storeToArray( $path, $key, $val, $this->attributes );
				}
			} else {
				// version === 1
				if ( !in_array( $key, self::$notAttributes )
					&& !in_array( $key, self::$creditsAttributes )
				) {
					// If it's not blacklisted, it's an attribute
					$this->storeToArray( $path, $key, $val, $this->attributes );
				}
			}

		}
	}

	/**
	 * @param string $path
	 * @param array $info
	 */
	protected function extractAttributes( $path, array $info ) {
		if ( isset( $info['attributes'] ) ) {
			foreach ( $info['attributes'] as $extName => $value ) {
				$this->storeToArray( $path, $extName, $value, $this->extAttributes );
			}
		}
	}

	public function getExtractedInfo() {
		// Make sure the merge strategies are set
		foreach ( $this->globals as $key => $val ) {
			if ( isset( self::$mergeStrategies[$key] ) ) {
				$this->globals[$key][ExtensionRegistry::MERGE_STRATEGY] = self::$mergeStrategies[$key];
			}
		}

		// Merge $this->extAttributes into $this->attributes depending on what is loaded
		foreach ( $this->extAttributes as $extName => $value ) {
			// Only set the attribute if $extName is loaded (and hence present in credits)
			if ( isset( $this->credits[$extName] ) ) {
				foreach ( $value as $attrName => $attrValue ) {
					$this->storeToArray(
						'', // Don't provide a path since it's impossible to generate an error here
						$extName . $attrName,
						$attrValue,
						$this->attributes
					);
				}
				unset( $this->extAttributes[$extName] );
			}
		}

		return [
			'globals' => $this->globals,
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
				if ( defined( $ns['constant'] ) ) {
					// If the namespace constant is already defined, use it.
					// This allows namespace IDs to be overwritten locally.
					$id = constant( $ns['constant'] );
				} else {
					$id = $ns['id'];
					$this->defines[ $ns['constant'] ] = $id;
				}

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
			foreach ( $info['ExtensionMessagesFiles'] as &$file ) {
				$file = "$dir/$file";
			}
			$this->globals["wgExtensionMessagesFiles"] += $info['ExtensionMessagesFiles'];
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
		foreach ( self::$creditsAttributes as $attr ) {
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
	 */
	protected function extractConfig1( array $info ) {
		if ( isset( $info['config'] ) ) {
			if ( isset( $info['config']['_prefix'] ) ) {
				$prefix = $info['config']['_prefix'];
				unset( $info['config']['_prefix'] );
			} else {
				$prefix = 'wg';
			}
			foreach ( $info['config'] as $key => $val ) {
				if ( $key[0] !== '@' ) {
					$this->addConfigGlobal( "$prefix$key", $val, $info['name'] );
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
	 */
	protected function extractConfig2( array $info, $dir ) {
		if ( isset( $info['config_prefix'] ) ) {
			$prefix = $info['config_prefix'];
		} else {
			$prefix = 'wg';
		}
		if ( isset( $info['config'] ) ) {
			foreach ( $info['config'] as $key => $data ) {
				$value = $data['value'];
				if ( isset( $data['merge_strategy'] ) ) {
					$value[ExtensionRegistry::MERGE_STRATEGY] = $data['merge_strategy'];
				}
				if ( isset( $data['path'] ) && $data['path'] ) {
					$value = "$dir/$value";
				}
				$this->addConfigGlobal( "$prefix$key", $value, $info['name'] );
			}
		}
	}

	/**
	 * Helper function to set a value to a specific global, if it isn't set already.
	 *
	 * @param string $key The config key with the prefix and anything
	 * @param mixed $value The value of the config
	 * @param string $extName Name of the extension
	 */
	private function addConfigGlobal( $key, $value, $extName ) {
		if ( array_key_exists( $key, $this->globals ) ) {
			throw new RuntimeException(
				"The configuration setting '$key' was already set by MediaWiki core or"
				. " another extension, and cannot be set again by $extName." );
		}
		$this->globals[$key] = $value;
	}

	protected function extractPathBasedGlobal( $global, $dir, $paths ) {
		foreach ( $paths as $path ) {
			$this->globals[$global][] = "$dir/$path";
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
			$paths[] = "$dir/vendor/autoload.php";
		}
		return $paths;
	}
}
