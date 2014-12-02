<?php

/* Modules registered when $wgEnableJavaScriptTest is true */

return array(

	/* Utilities */

	'test.sinonjs' => array(
		'scripts' => array(
			'resources/lib/sinonjs/sinon-1.9.0.js',
			// We want tests to work in IE, but can't include this as it
			// will break the placeholders in Sinon because the hack it uses
			// to hijack IE globals relies on running in the global scope
			// and in ResourceLoader this won't be running in the global scope.
			// Including it results (among other things) in sandboxed timers
			// being broken due to Date inheritance being undefined.
			// 'resources/lib/sinonjs/sinon-ie-1.9.0.js',
		),
		'targets' => array( 'desktop', 'mobile' ),
	),

	'test.mediawiki.qunit.testrunner' => array(
		'scripts' => array(
			'tests/qunit/data/testrunner.js',
		),
		'dependencies' => array(
			// Test runner configures QUnit but can't have it as dependency,
			// see SpecialJavaScriptTest::viewQUnit.
			'jquery.getAttrs',
			'mediawiki.page.ready',
			'mediawiki.page.startup',
			'test.sinonjs',
		),
		'position' => 'top',
		'targets' => array( 'desktop', 'mobile' ),
	),

	/*
		Test suites for MediaWiki core modules
		These must have a dependency on test.mediawiki.qunit.testrunner!
	*/

	'test.mediawiki.qunit.suites' => array(
		'scripts' => array(
			'tests/qunit/suites/resources/startup.test.js',
			'tests/qunit/suites/resources/jquery/jquery.autoEllipsis.test.js',
			'tests/qunit/suites/resources/jquery/jquery.byteLength.test.js',
			'tests/qunit/suites/resources/jquery/jquery.byteLimit.test.js',
			'tests/qunit/suites/resources/jquery/jquery.client.test.js',
			'tests/qunit/suites/resources/jquery/jquery.color.test.js',
			'tests/qunit/suites/resources/jquery/jquery.colorUtil.test.js',
			'tests/qunit/suites/resources/jquery/jquery.getAttrs.test.js',
			'tests/qunit/suites/resources/jquery/jquery.hidpi.test.js',
			'tests/qunit/suites/resources/jquery/jquery.highlightText.test.js',
			'tests/qunit/suites/resources/jquery/jquery.localize.test.js',
			'tests/qunit/suites/resources/jquery/jquery.makeCollapsible.test.js',
			'tests/qunit/suites/resources/jquery/jquery.mwExtension.test.js',
			'tests/qunit/suites/resources/jquery/jquery.placeholder.test.js',
			'tests/qunit/suites/resources/jquery/jquery.tabIndex.test.js',
			'tests/qunit/suites/resources/jquery/jquery.tablesorter.test.js',
			'tests/qunit/suites/resources/jquery/jquery.textSelection.test.js',
			'tests/qunit/data/mediawiki.jqueryMsg.data.js',
			'tests/qunit/suites/resources/mediawiki/mediawiki.jqueryMsg.test.js',
			'tests/qunit/suites/resources/mediawiki/mediawiki.jscompat.test.js',
			'tests/qunit/suites/resources/mediawiki/mediawiki.test.js',
			'tests/qunit/suites/resources/mediawiki/mediawiki.Title.test.js',
			'tests/qunit/suites/resources/mediawiki/mediawiki.toc.test.js',
			'tests/qunit/suites/resources/mediawiki/mediawiki.Uri.test.js',
			'tests/qunit/suites/resources/mediawiki/mediawiki.user.test.js',
			'tests/qunit/suites/resources/mediawiki/mediawiki.util.test.js',
			'tests/qunit/suites/resources/mediawiki.api/mediawiki.api.test.js',
			'tests/qunit/suites/resources/mediawiki.api/mediawiki.api.parse.test.js',
			'tests/qunit/suites/resources/mediawiki.api/mediawiki.api.watch.test.js',
			'tests/qunit/suites/resources/mediawiki.special/mediawiki.special.recentchanges.test.js',
			'tests/qunit/suites/resources/mediawiki/mediawiki.language.test.js',
			'tests/qunit/suites/resources/mediawiki/mediawiki.cldr.test.js',
		),
		'dependencies' => array(
			'jquery.autoEllipsis',
			'jquery.byteLength',
			'jquery.byteLimit',
			'jquery.client',
			'jquery.color',
			'jquery.colorUtil',
			'jquery.delayedBind',
			'jquery.getAttrs',
			'jquery.hidpi',
			'jquery.highlightText',
			'jquery.localize',
			'jquery.makeCollapsible',
			'jquery.mwExtension',
			'jquery.placeholder',
			'jquery.tabIndex',
			'jquery.tablesorter',
			'jquery.textSelection',
			'mediawiki.api',
			'mediawiki.api.parse',
			'mediawiki.api.watch',
			'mediawiki.jqueryMsg',
			'mediawiki.Title',
			'mediawiki.Uri',
			'mediawiki.user',
			'mediawiki.util',
			'mediawiki.special.recentchanges',
			'mediawiki.language',
			'mediawiki.cldr',
			'test.mediawiki.qunit.testrunner',
		),
	)
);
