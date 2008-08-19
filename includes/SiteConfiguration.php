<?php

/**
 * The include paths change after this file is included from commandLine.inc,
 * meaning that require_once() fails to detect that it is including the same
 * file again. We use DIY C-style protection as a workaround.
 */

// Hide this pattern from Doxygen, which spazzes out at it
/// @cond
if (!defined('SITE_CONFIGURATION')) {
define('SITE_CONFIGURATION', 1);
/// @endcond

/**
 * This is a class used to hold configuration settings, particularly for multi-wiki sites.
 *
 */
class SiteConfiguration {
	var $suffixes = array();
	var $wikis = array();
	var $settings = array();
	var $localVHosts = array();

	/**
	 * Retrieves a configuration setting for a given wiki.
	 * @param $settingName String ID of the setting name to retrieve
	 * @param $wiki String Wiki ID of the wiki in question.
	 * @param $suffix String The suffix of the wiki in question.
	 * @param $params Array List of parameters. $.'key' is replaced by $value in all returned data.
	 * @param $wikiTags Array The tags assigned to the wiki.
	 * @return Mixed the value of the setting requested.
	 */
	function get( $settingName, $wiki, $suffix, $params = array(), $wikiTags = array() ) {
		if ( array_key_exists( $settingName, $this->settings ) ) {
			$thisSetting =& $this->settings[$settingName];
			do {
				// Do individual wiki settings
				if ( array_key_exists( $wiki, $thisSetting ) ) {
					$retval = $thisSetting[$wiki];
					break;
				} elseif ( array_key_exists( "+$wiki", $thisSetting ) && is_array($thisSetting["+$wiki"]) ) {
					$retval = $thisSetting["+$wiki"];
				}
				
				// Do tag settings
				foreach ( $wikiTags as $tag ) {
					if ( array_key_exists( $tag, $thisSetting ) ) {
						if ( isset($retval) && is_array($retval) && is_array($thisSetting[$tag]) ) {
							$retval = array_merge( $retval, $thisSetting[$tag] );
						} else {
							$retval = $thisSetting[$tag];
						}
						break 2;
					} elseif ( array_key_exists( "+$tag", $thisSetting ) && is_array($thisSetting["+$tag"]) ) {
						if (!isset($retval))
							$retval = array();
						$retval = array_merge( $retval, $thisSetting["+$tag"] );
					}
				}
				
				// Do suffix settings
				if ( array_key_exists( $suffix, $thisSetting ) ) {
					if ( isset($retval) && is_array($retval) && is_array($thisSetting[$suffix]) ) {
						$retval = array_merge( $retval, $thisSetting[$suffix] );
					} else {
						$retval = $thisSetting[$suffix];
					}
					break;
				} elseif ( array_key_exists( "+$suffix", $thisSetting ) && is_array($thisSetting["+$suffix"]) ) {
					if (!isset($retval))
						$retval = array();
					$retval = array_merge( $retval, $thisSetting["+$suffix"] );
				}
				
				// Fall back to default.
				if ( array_key_exists( 'default', $thisSetting ) ) {
					if ( isset($retval) && is_array($retval) && is_array($thisSetting['default']) ) {
						$retval = array_merge( $retval, $thisSetting['default'] );
					} else {
						$retval = $thisSetting['default'];
					}
					break;
				}
				$retval = null;
			} while ( false );
		} else {
			$retval = NULL;
		}

		if ( !is_null( $retval ) && count( $params ) ) {
			foreach ( $params as $key => $value ) {
				$retval = $this->doReplace( '$' . $key, $value, $retval );
			}
		}
		return $retval;
	}

	/** Type-safe string replace; won't do replacements on non-strings */
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
	function getAll( $wiki, $suffix, $params, $wikiTags = array() ) {
		$localSettings = array();
		foreach ( $this->settings as $varname => $stuff ) {
			$value = $this->get( $varname, $wiki, $suffix, $params, $wikiTags );
			if ( !is_null( $value ) ) {
				$localSettings[$varname] = $value;
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
	function getBool( $setting, $wiki, $suffix, $wikiTags = array() ) {
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
	function extractVar( $setting, $wiki, $suffix, &$var, $params, $wikiTags = array() ) {
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
	function extractGlobal( $setting, $wiki, $suffix, $params, $wikiTags = array() ) {
		$value = $this->get( $setting, $wiki, $suffix, $params, $wikiTags );
		if ( !is_null( $value ) ) {
			if (substr($setting,0,1) == '+' && is_array($value)) {
				$setting = substr($setting,1);
				if ( is_array($GLOBALS[$setting]) ) {
					$GLOBALS[$setting] = array_merge( $GLOBALS[$setting], $value );
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
	function extractAllGlobals( $wiki, $suffix, $params, $wikiTags = array() ) {
		foreach ( $this->settings as $varName => $setting ) {
			$this->extractGlobal( $varName, $wiki, $suffix, $params, $wikiTags );
		}
	}

	/**
	 * Work out the site and language name from a database name
	 * @param $db
	 */
	function siteFromDB( $db ) {
		$site = NULL;
		$lang = NULL;
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

	/** Returns true if the given vhost is handled locally. */
	function isLocalVHost( $vhost ) {
		return in_array( $vhost, $this->localVHosts );
	}
}
}
