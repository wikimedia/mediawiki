<?php
/**
 *This file is used to configure the live Wikimedia wikis. The file that
 * includes it contains passwords and other sensitive data, and there's
 * currently no public equivalent.
 */

/**
 *
 */
class SiteConfiguration {
	var $suffixes, $wikis, $settings;
	var $localDatabases;
	
	function get( $setting, $wiki, $suffix, $params = array() ) {
		if ( array_key_exists( $wiki, $this->settings[$setting] ) ) {
			$retval = $this->settings[$setting][$wiki];
		} elseif ( array_key_exists( $suffix, $this->settings[$setting] ) ) {
			$retval = $this->settings[$setting][$suffix];
		} elseif ( array_key_exists( 'default', $this->settings[$setting] ) ) {
			$retval = $this->settings[$setting]['default'];
		} else {
			$retval = NULL;
		}
		if ( !is_null( $retval ) && count( $params ) ) {
			foreach ( $params as $key => $value ) {
				$retval = str_replace( '$' . $key, $value, $retval );
			}
		}
		return $retval;
	}

	function getBool( $setting, $wiki, $suffix ) {
		return (bool)($this->get( $setting, $wiki, $suffix ));
	}

	function &getLocalDatabases() {
		return $this->localDatabases;
	}
	
	function initialise() {
		foreach ( $this->wikis as $db ) {
			$this->localDatabases[$db] = $db;
		}
	}

	function extractVar( $setting, $wiki, $suffix, &$var, $params ) {
		$value = $this->get( $setting, $wiki, $suffix, $params );
		if ( !is_null( $value ) ) {
			$var = $value;
		}
	}
	
	function extractGlobal( $setting, $wiki, $suffix, $params ) {
		$value = $this->get( $setting, $wiki, $suffix, $params );
		if ( !is_null( $value ) ) {
			$GLOBALS[$setting] = $value;
		}
	}

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
		return array( $site, $lang );
	}
}

	
?>
