<?php

class ExtensionProcessor extends Processor {

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

	protected static $dirSettings = array(
		'MessageDirs',
	);

	/**
	 * Keys that are part of the extension credits
	 *
	 * @var array
	 */
	protected static $creditsAttributes = array(
		'name',
		'authors',
		'version',
		'url',
		'description',
		'descriptionmsg',
		'license-name',
	);

	public function processInfo( $path, $info ) {
		$this->setCredits( $path, $info );
		$this->setConfig( $info );
		$this->registerHooks( $info );
		$dir = dirname( $path );
		$this->setMessageSettings( $dir, $info );
		foreach ( $info as $key => $val ) {
			if ( in_array( $key, self::$globalSettings ) ) {
				$this->setToGlobal( $key, $val );
			}
		}
	}

	protected function registerHooks( $info ) {
		if ( isset( $info['Hooks'] ) ) {
			foreach ( $info['Hooks'] as $name => $callable ) {
				$GLOBALS['wgHooks'][$name][] = $callable;
			}
		}
	}

	protected function setMessageSettings( $dir, $info ) {
		foreach ( array( 'ExtensionMessagesFiles', 'MessagesDirs' ) as $key ) {
			if ( isset( $info[$key] ) ) {
				$GLOBALS["wg$key"] += array_map( function( $file ) use ( $dir ) {
					return "$dir/$file";
				}, $info[$key] );
			}
		}
	}

	protected function setCredits( $path, $info ) {
		$credits = array(
			'path' => $path,
		);
		foreach ( self::$creditsAttributes as $attr ) {
			if ( isset( $info[$attr] ) ) {
				$credits[$attr] = $info[$attr];
			}
		}
		$type = isset( $info['type'] ) ? $info['type'] : 'other';

		$GLOBALS['wgExtensionCredits'][$type][] = $credits;
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