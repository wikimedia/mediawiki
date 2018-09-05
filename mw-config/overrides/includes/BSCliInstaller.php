<?php
class BSCliInstaller extends CliInstaller {
	protected function includeExtensions(){
		global $IP;
		$exts = $this->getVar( '_Extensions' );
		$IP = $this->getVar( 'IP' );
		/**
		 * We need to include DefaultSettings before including extensions to avoid
		 * warnings about unset variables. However, the only thing we really
		 * want here is $wgHooks['LoadExtensionSchemaUpdates']. This won't work
		 * if the extension has hidden hook registration in $wgExtensionFunctions,
		 * but we're not opening that can of worms
		 * @see https://phabricator.wikimedia.org/T28857
		 */
		global $wgAutoloadClasses;
		$wgAutoloadClasses = [];
		$queue = [];
		require_once "$IP/includes/DefaultSettings.php";
		require_once __DIR__ . '/../../../extensions/BlueSpiceFoundation/includes/Defines.php';
		require_once __DIR__ . '/../../../LocalSettings.BlueSpice.php';
		foreach ( $exts as $e ) {
			if ( file_exists( "$IP/extensions/$e/extension.json" ) ) {
				$queue["$IP/extensions/$e/extension.json"] = 1;
			} else {
				require_once "$IP/extensions/$e/$e.php";
			}
		}
		$registry = new ExtensionRegistry();
		$data = $registry->readFromQueue( $queue );
		$wgAutoloadClasses += $data['autoload'];
		if ( isset( $data['autoloaderNS'] ) ) {
			AutoLoader::$psr4Namespaces += $data['autoloaderNS'];
		}
		$hooksWeWant = isset( $wgHooks['LoadExtensionSchemaUpdates'] ) ?
			/** @suppress PhanUndeclaredVariable $wgHooks is set by DefaultSettings */
			$wgHooks['LoadExtensionSchemaUpdates'] : [];
		if ( isset( $data['globals']['wgHooks']['LoadExtensionSchemaUpdates'] ) ) {
			$hooksWeWant = array_merge_recursive(
				$hooksWeWant,
				$data['globals']['wgHooks']['LoadExtensionSchemaUpdates']
			);
		}
		// Unset everyone else's hooks. Lord knows what someone might be doing
		// in ParserFirstCallInit (see T29171)
		$GLOBALS['wgHooks'] = [ 'LoadExtensionSchemaUpdates' => $hooksWeWant ];
		return Status::newGood();
	}
}
