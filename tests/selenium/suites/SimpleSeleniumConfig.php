<?php
class SimpleSeleniumConfig {
	
	public static function getSettings(&$includeFiles, &$globalConfigs, &$resourceFiles) {
		global $IP;
		$includes = array(
			//files that needed to be included would go here
		);
		$configs = array(
			'wgDefaultSkin' => 'chick'
		);
		$resources = array(
			'db' => "$IP/tests/selenium/data/SimpleSeleniumTestDB.sql",
			'images' => "$IP/tests/selenium/data/SimpleSeleniumTestImages.zip"
		);

		$includeFiles = array_merge( $includeFiles, $includes );
		$globalConfigs = array_merge( $globalConfigs, $configs);
		$resourceFiles = array_merge( $resourceFiles, $resources );
		return true; 
	}
}