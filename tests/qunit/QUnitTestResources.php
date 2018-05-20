<?php

/* Modules registered when $wgEnableJavaScriptTest is true */

return [

	/* Utilities */

	'test.sinonjs' => [
		'scripts' => [
			'tests/qunit/suites/resources/test.sinonjs/index.js',
			'resources/lib/sinonjs/sinon-1.17.3.js',
			// We want tests to work in IE, but can't include this as it
			// will break the placeholders in Sinon because the hack it uses
			// to hijack IE globals relies on running in the global scope
			// and in ResourceLoader this won't be running in the global scope.
			// Including it results (among other things) in sandboxed timers
			// being broken due to Date inheritance being undefined.
			// 'resources/lib/sinonjs/sinon-ie-1.15.4.js',
		],
		'targets' => [ 'desktop', 'mobile' ],
	],

	'test.mediawiki.qunit.testrunner' => [
		'scripts' => [
			'tests/qunit/data/testrunner.js',
		],
		'dependencies' => [
			// Test runner configures QUnit but can't have it as dependency,
			// see SpecialJavaScriptTest::viewQUnit.
			'jquery.getAttrs',
			'mediawiki.page.ready',
			'mediawiki.page.startup',
			'test.sinonjs',
		],
		'targets' => [ 'desktop', 'mobile' ],
	],

	/*
		Test suites for MediaWiki core modules
		These must have a dependency on test.mediawiki.qunit.testrunner!
	*/

	'test.mediawiki.qunit.suites' => [
		'scripts' => [
			'tests/qunit/suites/resources/startup.test.js',
			'tests/qunit/suites/resources/jquery/jquery.accessKeyLabel.test.js',
			'tests/qunit/suites/resources/jquery/jquery.color.test.js',
			'tests/qunit/suites/resources/jquery/jquery.colorUtil.test.js',
			'tests/qunit/suites/resources/jquery/jquery.getAttrs.test.js',
			'tests/qunit/suites/resources/jquery/jquery.hidpi.test.js',
			'tests/qunit/suites/resources/jquery/jquery.highlightText.test.js',
			'tests/qunit/suites/resources/jquery/jquery.lengthLimit.test.js',
			'tests/qunit/suites/resources/jquery/jquery.localize.test.js',
			'tests/qunit/suites/resources/jquery/jquery.makeCollapsible.test.js',
			'tests/qunit/suites/resources/jquery/jquery.tabIndex.test.js',
			'tests/qunit/suites/resources/jquery/jquery.tablesorter.test.js',
			'tests/qunit/suites/resources/jquery/jquery.tablesorter.parsers.test.js',
			'tests/qunit/suites/resources/jquery/jquery.textSelection.test.js',
			'tests/qunit/data/mediawiki.jqueryMsg.data.js',
			'tests/qunit/suites/resources/mediawiki/mediawiki.requestIdleCallback.test.js',
			'tests/qunit/suites/resources/mediawiki/mediawiki.errorLogger.test.js',
			'tests/qunit/suites/resources/mediawiki/mediawiki.jqueryMsg.test.js',
			'tests/qunit/suites/resources/mediawiki/mediawiki.jscompat.test.js',
			'tests/qunit/suites/resources/mediawiki/mediawiki.messagePoster.factory.test.js',
			'tests/qunit/suites/resources/mediawiki/mediawiki.RegExp.test.js',
			'tests/qunit/suites/resources/mediawiki/mediawiki.String.byteLength.test.js',
			'tests/qunit/suites/resources/mediawiki/mediawiki.String.trimByteLength.test.js',
			'tests/qunit/suites/resources/mediawiki/mediawiki.storage.test.js',
			'tests/qunit/suites/resources/mediawiki/mediawiki.template.test.js',
			'tests/qunit/suites/resources/mediawiki/mediawiki.template.mustache.test.js',
			'tests/qunit/suites/resources/mediawiki/mediawiki.test.js',
			'tests/qunit/suites/resources/mediawiki/mediawiki.loader.test.js',
			'tests/qunit/suites/resources/mediawiki/mediawiki.html.test.js',
			'tests/qunit/suites/resources/mediawiki/mediawiki.inspect.test.js',
			'tests/qunit/suites/resources/mediawiki/mediawiki.Title.test.js',
			'tests/qunit/suites/resources/mediawiki/mediawiki.toc.test.js',
			'tests/qunit/suites/resources/mediawiki/mediawiki.track.test.js',
			'tests/qunit/suites/resources/mediawiki/mediawiki.Uri.test.js',
			'tests/qunit/suites/resources/mediawiki/mediawiki.user.test.js',
			'tests/qunit/suites/resources/mediawiki/mediawiki.util.test.js',
			'tests/qunit/suites/resources/mediawiki/mediawiki.viewport.test.js',
			'tests/qunit/suites/resources/mediawiki.api/mediawiki.api.test.js',
			'tests/qunit/suites/resources/mediawiki.api/mediawiki.api.category.test.js',
			'tests/qunit/suites/resources/mediawiki.api/mediawiki.api.edit.test.js',
			'tests/qunit/suites/resources/mediawiki.api/mediawiki.api.messages.test.js',
			'tests/qunit/suites/resources/mediawiki.api/mediawiki.api.options.test.js',
			'tests/qunit/suites/resources/mediawiki.api/mediawiki.api.parse.test.js',
			'tests/qunit/suites/resources/mediawiki.api/mediawiki.api.upload.test.js',
			'tests/qunit/suites/resources/mediawiki.api/mediawiki.api.watch.test.js',
			'tests/qunit/suites/resources/mediawiki.api/mediawiki.ForeignApi.test.js',
			'tests/qunit/suites/resources/mediawiki.special/mediawiki.special.recentchanges.test.js',
			'tests/qunit/suites/resources/mediawiki.rcfilters/dm.FiltersViewModel.test.js',
			'tests/qunit/suites/resources/mediawiki.rcfilters/dm.FilterItem.test.js',
			'tests/qunit/suites/resources/mediawiki.rcfilters/dm.SavedQueryItemModel.test.js',
			'tests/qunit/suites/resources/mediawiki.rcfilters/dm.SavedQueriesModel.test.js',
			'tests/qunit/suites/resources/mediawiki.rcfilters/UriProcessor.test.js',
			'tests/qunit/suites/resources/mediawiki/mediawiki.language.test.js',
			'tests/qunit/suites/resources/mediawiki/mediawiki.cldr.test.js',
			'tests/qunit/suites/resources/mediawiki/mediawiki.cookie.test.js',
			'tests/qunit/suites/resources/mediawiki/mediawiki.experiments.test.js',
			'tests/qunit/suites/resources/mediawiki/mediawiki.visibleTimeout.test.js',
		],
		'dependencies' => [
			'jquery.accessKeyLabel',
			'jquery.color',
			'jquery.colorUtil',
			'jquery.getAttrs',
			'jquery.hidpi',
			'jquery.highlightText',
			'jquery.lengthLimit',
			'jquery.localize',
			'jquery.makeCollapsible',
			'jquery.tabIndex',
			'jquery.tablesorter',
			'jquery.textSelection',
			'mediawiki.api',
			'mediawiki.ForeignApi.core',
			'mediawiki.jqueryMsg',
			'mediawiki.messagePoster',
			'mediawiki.RegExp',
			'mediawiki.String',
			'mediawiki.storage',
			'mediawiki.Title',
			'mediawiki.toc',
			'mediawiki.Uri',
			'mediawiki.user',
			'mediawiki.template.mustache',
			'mediawiki.template',
			'mediawiki.util',
			'mediawiki.viewport',
			'mediawiki.special.recentchanges',
			'mediawiki.rcfilters.filters.dm',
			'mediawiki.language',
			'mediawiki.cldr',
			'mediawiki.cookie',
			'mediawiki.experiments',
			'mediawiki.inspect',
			'mediawiki.visibleTimeout',
			'test.mediawiki.qunit.testrunner',
		],
	]
];
