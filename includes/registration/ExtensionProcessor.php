<?php

class ExtensionProcessor implements Processor {

	/**
	 * Keys that should be set to $GLOBALS
	 *
	 * @var array
	 */
	protected static $globalSettings = array(
		'ResourceLoaderModules',
		'ResourceLoaderSources',
		'ResourceLoaderLESSVars',
		'ResourceLoaderLESSImportPaths',
		'TrackingCategories',
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
		'RateLimits',
		'ParserTestFiles',
		'RecentChangesFlags',
		'ExtensionFunctions',
		'ExtensionEntryPointListFiles',
		'SpecialPages',
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
	 * @param string $path
	 * @param array $info
	 * @return array
	 */
	public function extractInfo( $path, array $info ) {
		$this->extractConfig( $info );
		$this->extractHooks( $info );
		$dir = dirname( $path );
		$this->extractMessageSettings( $dir, $info );
		$this->extractNamespaces( $info );
		if ( isset( $info['callback'] ) ) {
			$this->callbacks[] = $info['callback'];
		}
		foreach ( $info as $key => $val ) {
			if ( in_array( $key, self::$globalSettings ) ) {
				$this->storeGlobal( $key, $val );
			}
		}

		$this->extractCredits( $path, $info );
	}

	public function getExtractedInfo() {
		return array(
			'globals' => $this->globals,
			'defines' => $this->defines,
			'callbacks' => $this->callbacks,
			'credits' => $this->credits,
		);
	}

	protected function extractHooks( array $info ) {
		if ( isset( $info['Hooks'] ) ) {
			foreach ( $info['Hooks'] as $name => $callable ) {
				$this->globals['wgHooks'][$name][] = $callable;
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
		}
	}

	/**
	 * Set message-related settings, which need to be expanded to use
	 * absolute paths
	 *
	 * @param string $dir
	 * @param array $info
	 */
	protected function extractMessageSettings( $dir, array $info ) {
		foreach ( array( 'ExtensionMessagesFiles', 'MessagesDirs' ) as $key ) {
			if ( isset( $info[$key] ) ) {
				$this->globals["wg$key"] += array_map( function( $file ) use ( $dir ) {
					return "$dir/$file";
				}, $info[$key] );
			}
		}
	}

	protected function extractCredits( $path, array $info ) {
		$credits = array(
			'path' => $path,
			'type' => isset( $info['type'] ) ? $info['type'] : 'other',
		);
		foreach ( self::$creditsAttributes as $attr ) {
			if ( isset( $info[$attr] ) ) {
				$credits[$attr] = $info[$attr];
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
				$this->globals["wg$key"] = $val;
			}
		}
	}

	/**
	 * @param string $global will be prefixed with "wg"
	 * @param mixed $value
	 */
	protected function storeGlobal( $global, $value ) {
		$name = "wg$global";
		if ( isset( $this->globals[$name] ) ) {
			$this->globals[$name] = array_merge_recursive( $this->globals[$name], $value );
		} else {
			$this->globals[$name] = $value;
		}
	}
}
