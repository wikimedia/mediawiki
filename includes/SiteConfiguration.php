<?php

# This file is used to configure the live Wikimedia wikis. The file that includes
# it contains passwords and other sensitive data, and there's currently no public
# equivalent. 

class SiteConfiguration {
	var $suffixes, $wikis, $settings;
	var $localDatabases;
	
	function get( $setting, $wiki, $suffix, $params = array() ) {
		if ( array_key_exists( $this->settings[$setting], $wiki ) ) {
			$retval = $this->settings[$setting][$wiki];
		} elseif ( array_key_exists( $this->settings[$setting], $suffix ) ) {
			$retval = $this->settings[$setting][$suffix];
		} elseif ( array_key_exists( $this->settings[$setting], "default" ) ) {
			$retval = $this->settings[$setting]['default'];
		} else {
			$retval = NULL;
		}
		if ( !is_null( $retval ) && count( $params ) ) {
			foreach ( $params as $key => $value ) {
				str_replace( "\${$key}", $value, $retval );
			}
		}
	}

	function getBool( $setting, $wiki, $suffix ) {
		return (bool)($this->get( $setting, $wiki, $suffix ));
	}

	function &getLocalDatabases() {
		return $this->localDatabases();
	}
	
	function initialise() {
		foreach ( $this->wikis as $db ) {
			$this->localDatabases[$db] = $db;
		}
	}

	function extractVar( $setting, $wiki, $suffix, &$var, &$params ) {
		$value = $this->get( $settings, $wiki, $suffix, $params );
		if ( !is_null( $value ) ) {
			$var = $value;
		}
	}
	
	function extractGlobal( $setting, $wiki, $suffix, &$params ) {
		$value = $this->get( $settings, $wiki, $suffix, $params );
		if ( !is_null( $value ) ) {
			$GLOBALS[$setting] = $value;
		}
	}

	function extractAllGlobals( $wiki, $suffix, &$params ) {
		foreach ( $settings as $varName => $setting ) {
			$this->extractGlobal( $varName, $wiki, $suffix, $params );
		}
	}
}

	
?>
