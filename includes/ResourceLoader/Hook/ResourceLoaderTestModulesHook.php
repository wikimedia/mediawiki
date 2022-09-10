<?php

namespace MediaWiki\ResourceLoader\Hook;

use MediaWiki\ResourceLoader\ResourceLoader;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "ResourceLoaderTestModules" to register handlers implementing this interface.
 *
 * @deprecated since 1.33; use the QUnitTestModule static extension registration attribute instead.
 * @ingroup ResourceLoaderHooks
 */
interface ResourceLoaderTestModulesHook {
	/**
	 * Register QUnit tests to load on [[Special:JavaScriptTest]].
	 *
	 * The tests files take the form of a ResourceLoader module that will only be registered
	 * when $wgEnableJavaScriptTest is true, and automatically discovered and loaded when
	 * visiting [[Special:JavaScriptTest]].
	 *
	 * The `$testModules` array follows the same format as $wgResourceModules, and is additionally
	 * keyed by test framework.
	 *
	 * For example:
	 *
	 *   	$testModules['qunit']['test.Example'] = [
	 *   		'localBasePath' => __DIR__ . '/tests/qunit',
	 *   		'remoteExtPath' => 'Example/tests/qunit',
	 *   		'script' => [ 'tests/qunit/foo.test.js' ],
	 *   		'dependencies' => [ 'ext.Example.foo' ]
	 *   	 ];
	 *
	 * @since 1.35
	 * @param array &$testModules
	 * @param ResourceLoader $rl
	 * @return void This hook must not abort, it must return no value
	 */
	public function onResourceLoaderTestModules( array &$testModules, ResourceLoader $rl ): void;
}
