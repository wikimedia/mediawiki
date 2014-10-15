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

	public function processInfo( $path, array $info ) {
		$this->setConfig( $info );
		$this->registerHooks( $info );
		$dir = dirname( $path );
		$this->setMessageSettings( $dir, $info );
		foreach ( $info as $key => $val ) {
			if ( in_array( $key, self::$globalSettings ) ) {
				$this->setToGlobal( $key, $val );
			}
		}

		return $this->getCredits( $path, $info );
	}

	public function callback( array $info ) {
		if ( isset( $info['callback'] ) ) {
			call_user_func( $info['callback'] );
		}
	}

	protected function registerHooks( array $info ) {
		if ( isset( $info['Hooks'] ) ) {
			foreach ( $info['Hooks'] as $name => $callable ) {
				$GLOBALS['wgHooks'][$name][] = $callable;
			}
		}
	}

	/**
	 * Register namespaces with the appropriate global settings
	 *
	 * @param array $info
	 */
	protected function registerNamespaces( array $info ) {
		if ( isset( $info['namespaces'] ) ) {
			foreach ( $info['namespaces'] as $ns ) {
				$id = $ns['id'];
				define( $ns['constant'], $id );
				$GLOBALS['wgExtraNamespaces'][$id] = $ns['name'];
				if ( isset( $ns['gender'] ) ) {
					$GLOBALS['wgExtraGenderNamespaces'][$id] = $ns['gender'];
				}
				if ( isset( $ns['subpages'] ) && $ns['subpages'] ) {
					$GLOBALS['wgNamespacesWithSubpages'][$id] = true;
				}
				if ( isset( $ns['content'] ) && $ns['content'] ) {
					$GLOBALS['wgContentNamespaces'][] = $id;
				}
				if ( isset( $ns['defaultcontentmodel'] ) ) {
					$GLOBALS['wgNamespaceContentModels'][$id] = $ns['defaultcontentmodel'];
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
	protected function setMessageSettings( $dir, array $info ) {
		foreach ( array( 'ExtensionMessagesFiles', 'MessagesDirs' ) as $key ) {
			if ( isset( $info[$key] ) ) {
				$GLOBALS["wg$key"] += array_map( function( $file ) use ( $dir ) {
					return "$dir/$file";
				}, $info[$key] );
			}
		}
	}

	protected function getCredits( $path, array $info ) {
		$credits = array(
			'path' => $path,
			'type' => isset( $info['type'] ) ? $info['type'] : 'other',
		);
		foreach ( self::$creditsAttributes as $attr ) {
			if ( isset( $info[$attr] ) ) {
				$credits[$attr] = $info[$attr];
			}
		}

		return $credits;
	}

	/**
	 * Set credits to $wgExtensionCredits
	 * @todo in the future Special:Version should just read from
	 * the registry directly
	 *
	 * @param array $credits
	 */
	protected function setCredits( array $credits ) {
		$GLOBALS['wgExtensionCredits'][$credits['type']][] = $credits;
	}

	/**
	 * Set configuration settings
	 * @todo In the future, this should be done via Config interfaces
	 *
	 * @param array $info
	 */
	protected function setConfig( array $info ) {
		if ( isset( $info['config'] ) ) {
			foreach ( $info['config'] as $key => $val ) {
				$name = "wg$key";
				if ( !isset( $GLOBALS[$name] ) ) {
					$GLOBALS[$name] = $val;
				}
			}
		}
	}

	/**
	 * @param string $global will be prefixed with "wg"
	 * @param mixed $value
	 */
	protected function setToGlobal( $global, $value ) {
		$name = "wg$global";
		$GLOBALS[$name] = array_merge_recursive( $GLOBALS[$name], $value );
	}
}