<?php

class ExtensionProcessor implements Processor {

	/**
	 * Keys that should be set to $GLOBALS
	 *
	 * @var array
	 */
	protected static $globalSettings = [
		'ResourceLoaderSources',
		'ResourceLoaderLESSVars',
		'ResourceLoaderLESSImportPaths',
		'DefaultUserOptions',
		'HiddenPrefs',
		'GroupPermissions',
		'RevokePermissions',
		'ImplicitGroups',
		'GroupsAddToSelf',
		'GroupsRemoveFromSelf',
		'AddGroups',
		'RemoveGroups',
		'AvailableRights',
		'ContentHandlers',
		'ConfigRegistry',
		'SessionProviders',
		'AuthManagerAutoConfig',
		'CentralIdLookupProviders',
		'RateLimits',
		'RecentChangesFlags',
		'MediaHandlers',
		'ExtensionFunctions',
		'ExtensionEntryPointListFiles',
		'SpecialPages',
		'JobClasses',
		'LogTypes',
		'LogRestrictions',
		'FilterLogTypes',
		'ActionFilteredLogs',
		'LogNames',
		'LogHeaders',
		'LogActions',
		'LogActionsHandlers',
		'Actions',
		'APIModules',
		'APIFormatModules',
		'APIMetaModules',
		'APIPropModules',
		'APIListModules',
		'ValidSkinNames',
		'FeedClasses',
	];

	/**
	 * Mapping of global settings to their specific merge strategies.
	 *
	 * @see ExtensionRegistry::exportExtractedData
	 * @see getExtractedInfo
	 * @var array
	 */
	protected static $mergeStrategies = [
		'wgGroupPermissions' => 'array_plus_2d',
		'wgRevokePermissions' => 'array_plus_2d',
		'wgHooks' => 'array_merge_recursive',
		'wgExtensionCredits' => 'array_merge_recursive',
		'wgExtraGenderNamespaces' => 'array_plus',
		'wgNamespacesWithSubpages' => 'array_plus',
		'wgNamespaceContentModels' => 'array_plus',
		'wgNamespaceProtection' => 'array_plus',
		'wgCapitalLinkOverrides' => 'array_plus',
		'wgRateLimits' => 'array_plus_2d',
		'wgAuthManagerAutoConfig' => 'array_plus_2d',
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
	 * @param string $path
	 * @param array $info
	 * @param int $version manifest_version for info
	 * @return array
	 */
	public function extractInfo( $path, array $info, $version ) {
		$this->extractConfig( $info );
		$this->extractHooks( $info );
		$dir = dirname( $path );
		$this->extractExtensionMessagesFiles( $dir, $info );
		$this->extractMessagesDirs( $dir, $info );
		$this->extractNamespaces( $info );
		$this->extractResourceLoaderModules( $dir, $info );
		$this->extractParserTestFiles( $dir, $info );
		if ( isset( $info['callback'] ) ) {
			$this->callbacks[] = $info['callback'];
		}

		$this->extractCredits( $path, $info );
		foreach ( $info as $key => $val ) {
			if ( in_array( $key, self::$globalSettings ) ) {
				$this->storeToArray( $path, "wg$key", $val, $this->globals );
			// Ignore anything that starts with a @
			} elseif ( $key[0] !== '@' && !in_array( $key, self::$notAttributes )
				&& !in_array( $key, self::$creditsAttributes )
			) {
				$this->storeToArray( $path, $key, $val, $this->attributes );
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

		return [
			'globals' => $this->globals,
			'defines' => $this->defines,
			'callbacks' => $this->callbacks,
			'credits' => $this->credits,
			'attributes' => $this->attributes,
		];
	}

	public function getRequirements( array $info ) {
		$requirements = [];
		$key = ExtensionRegistry::MEDIAWIKI_CORE;
		if ( isset( $info['requires'][$key] ) ) {
			$requirements[$key] = $info['requires'][$key];
		}

		return $requirements;
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
				$this->attributes['ExtensionNamespaces'][$id] = $ns['name'];
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
	}

	/**
	 * Set configuration settings
	 * @todo In the future, this should be done via Config interfaces
	 *
	 * @param array $info
	 */
	protected function extractConfig( array $info ) {
		if ( isset( $info['config'] ) ) {
			if ( isset( $info['config']['_prefix'] ) ) {
				$prefix = $info['config']['_prefix'];
				unset( $info['config']['_prefix'] );
			} else {
				$prefix = 'wg';
			}
			foreach ( $info['config'] as $key => $val ) {
				if ( $key[0] !== '@' ) {
					$this->globals["$prefix$key"] = $val;
				}
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
