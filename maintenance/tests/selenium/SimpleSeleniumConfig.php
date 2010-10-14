<?php
class SimpleSeleniumConfig {
	
	public static function getSettings(&$includeFiles, &$globalConfigs) {
		$includes = array(
			'skins/Chick.php'
		);
		$configs = array(
			'wgDefaultSkin' => 'chick'
		);
		$includeFiles = array_merge( $includeFiles, $includes );
		$globalConfigs = array_merge( $globalConfigs, $configs);
		return true; 
	}
}