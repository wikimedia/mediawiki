<?php
/**
 * This is a class used to hold configuration settings, particularly for multi-wiki sites.
 *
 * @package MediaWiki
 */

/**
 * The include paths change after this file is included from commandLine.inc,
 * meaning that require_once() fails to detect that it is including the same
 * file again. We use DIY C-style protection as a workaround.
 */
if (!defined('SITE_CONFIGURATION')) {
define('SITE_CONFIGURATION', 1);

/** @package MediaWiki */
class SiteConfiguration {
	var $suffixes = array();
	var $wikis = array();
	var $settings = array();
	var $localVHosts = array();

	/**
	 * Get a setting 
	 */
	function get( $setting, $wiki, $suffix, $params = array() ) {
		# Keys in order of increasing specificity
		$keys = array( 'default', $suffix, $wiki );

		# Process ordinary settings
		if ( isset( $this->settings[$setting] ) ) {
			$retval = NULL;
			foreach ( $keys as $key ) {
				if ( isset( $this->settings[$setting][$key] ) ) {
					$retval = $this->settings[$setting][$key];
				}
			}
		} else {
			$retval = NULL;
		}

		if ( is_array( $retval ) ) {
			# Array overrides
			$skey = "+$setting";
			if ( isset( $this->settings[$skey] ) ) {
				foreach ( $keys as $key ) {
					if ( isset( $this->settings[$skey][$key] ) ) {
						$retval = $this->settings[$skey][$key] + $retval;
					}
				}
			}

			# Array appends
			$skey = ".$setting";
			if ( isset( $this->settings[$skey] ) ) {
				foreach ( $keys as $key ) {
					if ( isset( $this->settings[$skey][$key] ) ) {
						$retval = array_merge( $retval, $this->settings[$skey][$key] );
					}
				}
			}				
		}

		# Replace parameters
		if ( !is_null( $retval ) && count( $params ) ) {
			foreach ( $params as $key => $value ) {
				$retval = str_replace( '$' . $key, $value, $retval );
			}
		}
		return $retval;
	}

	/** */
	function getAll( $wiki, $suffix, $params ) {
		$localSettings = array();
		foreach ( $this->settings as $varname => $stuff ) {
			$value = $this->get( $varname, $wiki, $suffix, $params );
			if ( !is_null( $value ) ) {
				$localSettings[$varname] = $value;
			}
		}
		return $localSettings;
	}

	/** */
	function getBool( $setting, $wiki, $suffix ) {
		return (bool)($this->get( $setting, $wiki, $suffix ));
	}

	/** */
	function &getLocalDatabases() {
		return $this->wikis;
	}

	/** */
	function initialise() {
	}

	/** */
	function extractVar( $setting, $wiki, $suffix, &$var, $params ) {
		$value = $this->get( $setting, $wiki, $suffix, $params );
		if ( !is_null( $value ) ) {
			$var = $value;
		}
	}

	/** */
	function extractGlobal( $setting, $wiki, $suffix, $params ) {
		$value = $this->get( $setting, $wiki, $suffix, $params );
		if ( !is_null( $value ) ) {
			$GLOBALS[$setting] = $value;
		}
	}

	/** */
	function extractAllGlobals( $wiki, $suffix, $params ) {
		foreach ( $this->settings as $varName => $setting ) {
			$this->extractGlobal( $varName, $wiki, $suffix, $params );
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
			if ( substr( $db, -strlen( $suffix ) ) == $suffix ) {
				$site = $suffix == 'wiki' ? 'wikipedia' : $suffix;
				$lang = substr( $db, 0, strlen( $db ) - strlen( $suffix ) );
				break;
			}
		}
		$lang = str_replace( '_', '-', $lang );
		return array( $site, $lang );
	}

	/** */
	function isLocalVHost( $vhost ) {
		return in_array( $vhost, $this->localVHosts );
	}
}
}

?>
