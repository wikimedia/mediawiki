<?php

class ExtensionProcessor implements Processor {

	/**
	 * Keys that should be set to $GLOBALS
	 *
	 * @var array
	 */
	protected static $globalSettings = array(
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
		'RateLimits',
		'ParserTestFiles',
		'RecentChangesFlags',
		'ExtensionFunctions',
		'ExtensionEntryPointListFiles',
		'SpecialPages',
		'SpecialPageGroups',
		'JobClasses',
		'LogTypes',
		'LogRestrictions',
		'FilterLogTypes',
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
	);

	/**
	 * Keys that are part of the extension credits
	 *
	 * @var array
	 */
	protected static $creditsAttributes = array(
		'name',
		'author',
		'version',
		'url',
		'description',
		'descriptionmsg',
		'license-name',
	);

	/**
	 * Stuff that is going to be set to $GLOBALS
	 *
	 * Some keys are pre-set to arrays so we can += to them
	 *
	 * @var array
	 */
	protected $globals = array(
		'wgExtensionMessagesFiles' => array(),
		'wgMessagesDirs' => array(),
	);

	/**
	 * Things that should be define()'d
	 *
	 * @var array
	 */
	protected $defines = array();

	/**
	 * Things to be called once registration of these extensions are done
	 *
	 * @var callable[]
	 */
	protected $callbacks = array();

	/**
	 * @var array
	 */
	protected $credits = array();

	/**
	 * Any thing else in the $info that hasn't
	 * already been processed
	 *
	 * @var array
	 */
	protected $attributes = array();

	/**
	 * List of keys that have already been processed
	 *
	 * @var array
	 */
	protected $processed = array();

	/**
	 * @param string $path
	 * @param array $info
	 * @return array
	 */
	public function extractInfo( $path, array $info ) {
		$this->extractConfig( $info );
		$this->extractHooks( $info );
		$dir = dirname( $path );
		$this->extractExtensionMessagesFiles( $dir, $info );
		$this->extractMessagesDirs( $dir, $info );
		$this->extractNamespaces( $info );
		$this->extractResourceLoaderModules( $dir, $info );
		if ( isset( $info['callback'] ) ) {
			$this->callbacks[] = $info['callback'];
			$this->processed[] = 'callback';
		}

		$this->extractCredits( $path, $info );
		foreach ( $info as $key => $val ) {
			if ( in_array( $key, self::$globalSettings ) ) {
				$this->storeToArray( "wg$key", $val, $this->globals );
			// Ignore anything that starts with a @
			} elseif ( $key[0] !== '@' && !in_array( $key, $this->processed ) ) {
				$this->storeToArray( $key, $val, $this->attributes );
			}
		}

	}

	public function getExtractedInfo() {
		return array(
			'globals' => $this->globals,
			'defines' => $this->defines,
			'callbacks' => $this->callbacks,
			'credits' => $this->credits,
			'attributes' => $this->attributes,
		);
	}

	protected function extractHooks( array $info ) {
		if ( isset( $info['Hooks'] ) ) {
			foreach ( $info['Hooks'] as $name => $callable ) {
				$this->globals['wgHooks'][$name][] = $callable;
			}
			$this->processed[] = 'Hooks';
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
				$this->globals['wgExtraNamespaces'][$id] = $ns['name'];
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
			}
			$this->processed[] = 'namespaces';
		}
	}

	protected function extractResourceLoaderModules( $dir, array $info ) {
		$defaultPaths = isset( $info['ResourceFileModulePaths'] )
			? $info['ResourceFileModulePaths']
			: false;
		if ( isset( $defaultPaths['localBasePath'] ) ) {
			$defaultPaths['localBasePath'] = "$dir/{$defaultPaths['localBasePath']}";
		}

		if ( isset( $info['ResourceModules'] ) ) {
			foreach ( $info['ResourceModules'] as $name => $data ) {
				if ( isset( $data['localBasePath'] ) ) {
					$data['localBasePath'] = "$dir/{$data['localBasePath']}";
				}
				if ( $defaultPaths ) {
					$data += $defaultPaths;
				}
				$this->globals['wgResourceModules'][$name] = $data;
			}
		}
	}

	protected function extractExtensionMessagesFiles( $dir, array $info ) {
		if ( isset( $info['ExtensionMessagesFiles'] ) ) {
			$this->globals["wgExtensionMessagesFiles"] += array_map( function( $file ) use ( $dir ) {
				return "$dir/$file";
			}, $info['ExtensionMessagesFiles'] );
			$this->processed[] = 'ExtensionMessagesFiles';
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
			$this->processed[] = 'MessagesDirs';
		}
	}

	protected function extractCredits( $path, array $info ) {
		$credits = array(
			'path' => $path,
			'type' => isset( $info['type'] ) ? $info['type'] : 'other',
		);
		$this->processed[] = 'type';
		foreach ( self::$creditsAttributes as $attr ) {
			if ( isset( $info[$attr] ) ) {
				$credits[$attr] = $info[$attr];
				$this->processed[] = $attr;
			}
		}

		$this->credits[$credits['name']] = $credits;
	}

	/**
	 * Set configuration settings
	 * @todo In the future, this should be done via Config interfaces
	 *
	 * @param array $info
	 */
	protected function extractConfig( array $info ) {
		if ( isset( $info['config'] ) ) {
			foreach ( $info['config'] as $key => $val ) {
				if ( $key[0] !== '@' ) {
					$this->globals["wg$key"] = $val;
				}
			}
			$this->processed[] = 'config';
		}
	}

	/**
	 * @param string $name
	 * @param mixed $value
	 * @param array &$array
	 */
	protected function storeToArray( $name, $value, &$array ) {
		if ( isset( $array[$name] ) ) {
			$array[$name] = array_merge_recursive( $array[$name], $value );
		} else {
			$array[$name] = $value;
		}
	}
}
