<?php

/**
 * The include paths change after this file is included from commandLine.inc,
 * meaning that require_once() fails to detect that it is including the same
 * file again. We use DIY C-style protection as a workaround.
 */

// Hide this pattern from Doxygen, which spazzes out at it
/// @cond
if( !defined( 'SITE_CONFIGURATION' ) ){
define( 'SITE_CONFIGURATION', 1 );
/// @endcond

/**
 * This is a class used to hold configuration settings, particularly for multi-wiki sites.
 */
class SiteConfiguration {

	/**
	 * Array of suffixes, for self::siteFromDB()
	 */
	public $suffixes = array();

	/**
	 * Array of wikis, should be the same as $wgLocalDatabases
	 */
	public $wikis = array();

	/**
	 * The whole array of settings
	 */
	public $settings = array();

	/**
	 * Array of domains that are local and can be handled by the same server
	 */
	public $localVHosts = array();
	
	/**
	 * Optional callback to load full configuration data.
	 */
	public $fullLoadCallback = null;
	
	/** Whether or not all data has been loaded */
	public $fullLoadDone = false;

	/**
	 * A callback function that returns an array with the following keys (all
	 * optional):
	 * - suffix: site's suffix
	 * - lang: site's lang
	 * - tags: array of wiki tags
	 * - params: array of parameters to be replaced
	 * The function will receive the SiteConfiguration instance in the first
	 * argument and the wiki in the second one.
	 * if suffix and lang are passed they will be used for the return value of
	 * self::siteFromDB() and self::$suffixes will be ignored
	 */
	public $siteParamsCallback = null;

	/**
	 * Retrieves a configuration setting for a given wiki.
	 * @param $settingName String ID of the setting name to retrieve
	 * @param $wiki String Wiki ID of the wiki in question.
	 * @param $suffix String The suffix of the wiki in question.
	 * @param $params Array List of parameters. $.'key' is replaced by $value in all returned data.
	 * @param $wikiTags Array The tags assigned to the wiki.
	 * @return Mixed the value of the setting requested.
	 */
	public function get( $settingName, $wiki, $suffix = null, $params = array(), $wikiTags = array() ) {
		$params = $this->mergeParams( $wiki, $suffix, $params, $wikiTags );
		return $this->getSetting( $settingName, $wiki, $params );
	}

	/**
	 * Really retrieves a configuration setting for a given wiki.
	 *
	 * @param $settingName String ID of the setting name to retrieve.
	 * @param $wiki String Wiki ID of the wiki in question.
	 * @param $params Array: array of parameters.
	 * @return Mixed the value of the setting requested.
	 */
	protected function getSetting( $settingName, $wiki, /*array*/ $params ){
		$retval = null;
		if( array_key_exists( $settingName, $this->settings ) ) {
			$thisSetting =& $this->settings[$settingName];
			do {
				// Do individual wiki settings
				if( array_key_exists( $wiki, $thisSetting ) ) {
					$retval = $thisSetting[$wiki];
					break;
				} elseif( array_key_exists( "+$wiki", $thisSetting ) && is_array( $thisSetting["+$wiki"] ) ) {
					$retval = $thisSetting["+$wiki"];
				}

				// Do tag settings
				foreach( $params['tags'] as $tag ) {
					if( array_key_exists( $tag, $thisSetting ) ) {
						if ( isset( $retval ) && is_array( $retval ) && is_array( $thisSetting[$tag] ) ) {
							$retval = self::arrayMerge( $retval, $thisSetting[$tag] );
						} else {
							$retval = $thisSetting[$tag];
						}
						break 2;
					} elseif( array_key_exists( "+$tag", $thisSetting ) && is_array($thisSetting["+$tag"]) ) {
						if( !isset( $retval ) )
							$retval = array();
						$retval = self::arrayMerge( $retval, $thisSetting["+$tag"] );
					}
				}
				// Do suffix settings
				$suffix = $params['suffix'];
				if( !is_null( $suffix ) ) {
					if( array_key_exists( $suffix, $thisSetting ) ) {
						if ( isset($retval) && is_array($retval) && is_array($thisSetting[$suffix]) ) {
							$retval = self::arrayMerge( $retval, $thisSetting[$suffix] );
						} else {
							$retval = $thisSetting[$suffix];
						}
						break;
					} elseif( array_key_exists( "+$suffix", $thisSetting ) && is_array($thisSetting["+$suffix"]) ) {
						if (!isset($retval))
							$retval = array();
						$retval = self::arrayMerge( $retval, $thisSetting["+$suffix"] );
					}
				}

				// Fall back to default.
				if( array_key_exists( 'default', $thisSetting ) ) {
					if( is_array( $retval ) && is_array( $thisSetting['default'] ) ) {
						$retval = self::arrayMerge( $retval, $thisSetting['default'] );
					} else {
						$retval = $thisSetting['default'];
					}
					break;
				}
			} while ( false );
		}

		if( !is_null( $retval ) && count( $params['params'] ) ) {
			foreach ( $params['params'] as $key => $value ) {
				$retval = $this->doReplace( '$' . $key, $value, $retval );
			}
		}
		return $retval;
	}

	/**
	 * Type-safe string replace; won't do replacements on non-strings
	 * private?
	 */
	function doReplace( $from, $to, $in ) {
		if( is_string( $in ) ) {
			return str_replace( $from, $to, $in );
		} elseif( is_array( $in ) ) {
			foreach( $in as $key => $val ) {
				$in[$key] = $this->doReplace( $from, $to, $val );
			}
			return $in;
		} else {
			return $in;
		}
	}

	/**
	 * Gets all settings for a wiki
	 * @param $wiki String Wiki ID of the wiki in question.
	 * @param $suffix String The suffix of the wiki in question.
	 * @param $params Array List of parameters. $.'key' is replaced by $value in all returned data.
	 * @param $wikiTags Array The tags assigned to the wiki.
	 * @return Array Array of settings requested.
	 */
	public function getAll( $wiki, $suffix = null, $params = array(), $wikiTags = array() ) {
		$params = $this->mergeParams( $wiki, $suffix, $params, $wikiTags );
		$localSettings = array();
		foreach( $this->settings as $varname => $stuff ) {
			$append = false;
			$var = $varname;
			if ( substr( $varname, 0, 1 ) == '+' ) {
				$append = true;
				$var = substr( $varname, 1 );
			}

			$value = $this->getSetting( $varname, $wiki, $params );
			if ( $append && is_array( $value ) && is_array( $GLOBALS[$var] ) )
				$value = self::arrayMerge( $value, $GLOBALS[$var] );
			if ( !is_null( $value ) ) {
				$localSettings[$var] = $value;
			}
		}
		return $localSettings;
	}

	/**
	 * Retrieves a configuration setting for a given wiki, forced to a boolean.
	 * @param $settingName String ID of the setting name to retrieve
	 * @param $wiki String Wiki ID of the wiki in question.
	 * @param $suffix String The suffix of the wiki in question.
	 * @param $params Array List of parameters. $.'key' is replaced by $value in all returned data.
	 * @param $wikiTags Array The tags assigned to the wiki.
	 * @return bool The value of the setting requested.
	 */
	public function getBool( $setting, $wiki, $suffix = null, $wikiTags = array() ) {
		return (bool)($this->get( $setting, $wiki, $suffix, array(), $wikiTags ) );
	}

	/** Retrieves an array of local databases */
	function &getLocalDatabases() {
		return $this->wikis;
	}

	/** A no-op */
	function initialise() {
	}

	/**
	 * Retrieves the value of a given setting, and places it in a variable passed by reference.
	 * @param $settingName String ID of the setting name to retrieve
	 * @param $wiki String Wiki ID of the wiki in question.
	 * @param $suffix String The suffix of the wiki in question.
	 * @param $var Reference The variable to insert the value into.
	 * @param $params Array List of parameters. $.'key' is replaced by $value in all returned data.
	 * @param $wikiTags Array The tags assigned to the wiki.
	 */
	public function extractVar( $setting, $wiki, $suffix, &$var, $params = array(), $wikiTags = array() ) {
		$value = $this->get( $setting, $wiki, $suffix, $params, $wikiTags );
		if ( !is_null( $value ) ) {
			$var = $value;
		}
	}

	/**
	 * Retrieves the value of a given setting, and places it in its corresponding global variable.
	 * @param $settingName String ID of the setting name to retrieve
	 * @param $wiki String Wiki ID of the wiki in question.
	 * @param $suffix String The suffix of the wiki in question.
	 * @param $params Array List of parameters. $.'key' is replaced by $value in all returned data.
	 * @param $wikiTags Array The tags assigned to the wiki.
	 */
	public function extractGlobal( $setting, $wiki, $suffix = null, $params = array(), $wikiTags = array() ) {
		$params = $this->mergeParams( $wiki, $suffix, $params, $wikiTags );
		$this->extractGlobalSetting( $setting, $wiki, $params );
	}

	public function extractGlobalSetting( $setting, $wiki, $params ) {
		$value = $this->getSetting( $setting, $wiki, $params );
		if ( !is_null( $value ) ) {
			if (substr($setting,0,1) == '+' && is_array($value)) {
				$setting = substr($setting,1);
				if ( is_array($GLOBALS[$setting]) ) {
					$GLOBALS[$setting] = self::arrayMerge( $GLOBALS[$setting], $value );
				} else {
					$GLOBALS[$setting] = $value;
				}
			} else {
				$GLOBALS[$setting] = $value;
			}
		}
	}

	/**
	 * Retrieves the values of all settings, and places them in their corresponding global variables.
	 * @param $wiki String Wiki ID of the wiki in question.
	 * @param $suffix String The suffix of the wiki in question.
	 * @param $params Array List of parameters. $.'key' is replaced by $value in all returned data.
	 * @param $wikiTags Array The tags assigned to the wiki.
	 */
	public function extractAllGlobals( $wiki, $suffix = null, $params = array(), $wikiTags = array() ) {
		$params = $this->mergeParams( $wiki, $suffix, $params, $wikiTags );
		foreach ( $this->settings as $varName => $setting ) {
			$this->extractGlobalSetting( $varName, $wiki, $params );
		}
	}

	/**
	 * Return specific settings for $wiki
	 * See the documentation of self::$siteParamsCallback for more in-depth
	 * documentation about this function
	 *
	 * @param $wiki String
	 * @return array
	 */
	protected function getWikiParams( $wiki ){
		static $default = array(
			'suffix' => null,
			'lang' => null,
			'tags' => array(),
			'params' => array(),
		);

		if( !is_callable( $this->siteParamsCallback ) )
			return $default;

		$ret = call_user_func_array( $this->siteParamsCallback, array( $this, $wiki ) );
		# Validate the returned value
		if( !is_array( $ret ) )
			return $default;

		foreach( $default as $name => $def ){
			if( !isset( $ret[$name] ) || ( is_array( $default[$name] ) && !is_array( $ret[$name] ) ) )
				$ret[$name] = $default[$name];
		}

		return $ret;
	}

	/**
	 * Merge params beetween the ones passed to the function and the ones given
	 * by self::$siteParamsCallback for backward compatibility
	 * Values returned by self::getWikiParams() have the priority.
	 *
	 * @param $wiki String Wiki ID of the wiki in question.
	 * @param $suffix String The suffix of the wiki in question.
	 * @param $params Array List of parameters. $.'key' is replaced by $value in
	 *                all returned data.
	 * @param $wikiTags Array The tags assigned to the wiki.
	 * @return array
	 */
	protected function mergeParams( $wiki, $suffix, /*array*/ $params, /*array*/ $wikiTags ){
		$ret = $this->getWikiParams( $wiki );

		if( is_null( $ret['suffix'] ) )
			$ret['suffix'] = $suffix;

		$ret['tags'] = array_unique( array_merge( $ret['tags'], $wikiTags ) );

		$ret['params'] += $params;

		// Automatically fill that ones if needed
		if( !isset( $ret['params']['lang'] ) && !is_null( $ret['lang'] ) )
			$ret['params']['lang'] = $ret['lang'];
		if( !isset( $ret['params']['site'] ) && !is_null( $ret['suffix'] ) )
			$ret['params']['site'] = $ret['suffix'];

		return $ret;
	}

	/**
	 * Work out the site and language name from a database name
	 * @param $db
	 */
	public function siteFromDB( $db ) {
		// Allow override
		$def = $this->getWikiParams( $db );
		if( !is_null( $def['suffix'] ) && !is_null( $def['lang'] ) )
			return array( $def['suffix'], $def['lang'] );

		$site = null;
		$lang = null;
		foreach ( $this->suffixes as $suffix ) {
			if ( $suffix === '' ) {
				$site = '';
				$lang = $db;
				break;
			} elseif ( substr( $db, -strlen( $suffix ) ) == $suffix ) {
				$site = $suffix == 'wiki' ? 'wikipedia' : $suffix;
				$lang = substr( $db, 0, strlen( $db ) - strlen( $suffix ) );
				break;
			}
		}
		$lang = str_replace( '_', '-', $lang );
		return array( $site, $lang );
	}

	/**
	 * Returns true if the given vhost is handled locally.
	 * @param $vhost String
	 * @return bool
	 */
	public function isLocalVHost( $vhost ) {
		return in_array( $vhost, $this->localVHosts );
	}

	/**
	 * Merge multiple arrays together.
	 * On encountering duplicate keys, merge the two, but ONLY if they're arrays.
	 * PHP's array_merge_recursive() merges ANY duplicate values into arrays,
	 * which is not fun
	 */
	static function arrayMerge( $array1/* ... */ ) {
		$out = $array1;
		for( $i=1; $i < func_num_args(); $i++ ) {
			foreach( func_get_arg( $i ) as $key => $value ) {
				if ( isset($out[$key]) && is_array($out[$key]) && is_array($value) ) {
					$out[$key] = self::arrayMerge( $out[$key], $value );
				} elseif ( !isset($out[$key]) || !$out[$key] && !is_numeric($key) ) {
					// Values that evaluate to true given precedence, for the primary purpose of merging permissions arrays.
					$out[$key] = $value;
				} elseif ( is_numeric( $key ) ) {
					$out[] = $value;
				}
			}
		}

		return $out;
	}
	
	public function loadFullData() {
		if ($this->fullLoadCallback && !$this->fullLoadDone) {
			call_user_func( $this->fullLoadCallback, $this );
			$this->fullLoadDone = true;
		}
	}
}
} // End of multiple inclusion guard
