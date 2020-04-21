<?php

namespace MediaWiki\Hook;

use ResourceLoader;

/**
 * @deprecated since 1.33
 * @ingroup Hooks
 */
interface ResourceLoaderTestModulesHook {
	/**
	 * Use this hook to register ResourceLoader modules that are only available
	 * when $wgEnableJavaScriptTest is true. Use this for test suites and
	 * other test-only resources.
	 *
	 * @since 1.35
	 *
	 * @param array &$testModules One array of modules per test framework.
	 *   The modules array follows the same format as `$wgResourceModules`.
	 *   For example:
	 *   	$testModules['qunit']['ext.Example.test'] = [
	 *   		'localBasePath' => __DIR__ . '/tests/qunit',
	 *   		'remoteExtPath' => 'Example/tests/qunit',
	 *   		'script' => [ 'tests/qunit/foo.js' ],
	 *   		'dependencies' => [ 'ext.Example.foo' ]
	 *   	 ];
	 * @param ResourceLoader $ResourceLoader
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onResourceLoaderTestModules( &$testModules, $ResourceLoader );
}
