<?php
class SimpleSeleniumConfig {
	
	public static function getSettings(&$includeFiles, &$globalConfigs) {
		$includes = array(
			//files that needed to be included would go here
		);
		$configs = array(
			'wgDefaultSkin' => 'chick'
		);
		$includeFiles = array_merge( $includeFiles, $includes );
		$globalConfigs = array_merge( $globalConfigs, $configs);
		return true; 
	}
}