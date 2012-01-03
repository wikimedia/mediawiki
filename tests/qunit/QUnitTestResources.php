<?php

return array(

	/* Test suites for MediaWiki core modules */

	'mediawiki.tests.qunit.suites' => array(
		'scripts' => array(
			'tests/qunit/suites/resources/jquery/jquery.autoEllipsis.test.js',
			'tests/qunit/suites/resources/jquery/jquery.byteLength.test.js',
			'tests/qunit/suites/resources/jquery/jquery.byteLimit.test.js',
			'tests/qunit/suites/resources/jquery/jquery.client.test.js',
			'tests/qunit/suites/resources/jquery/jquery.colorUtil.test.js',
			'tests/qunit/suites/resources/jquery/jquery.getAttrs.test.js',
			'tests/qunit/suites/resources/jquery/jquery.highlightText.test.js',
			'tests/qunit/suites/resources/jquery/jquery.localize.test.js',
			'tests/qunit/suites/resources/jquery/jquery.mwExtension.test.js',
			'tests/qunit/suites/resources/jquery/jquery.tabIndex.test.js',
			'tests/qunit/suites/resources/jquery/jquery.tablesorter.test.js',
			'tests/qunit/suites/resources/jquery/jquery.textSelection.test.js',
			'tests/qunit/suites/resources/mediawiki/mediawiki.jscompat.test.js',
			'tests/qunit/suites/resources/mediawiki/mediawiki.test.js',
			'tests/qunit/suites/resources/mediawiki/mediawiki.title.test.js',
			'tests/qunit/suites/resources/mediawiki/mediawiki.user.test.js',
			'tests/qunit/suites/resources/mediawiki/mediawiki.util.test.js',
			'tests/qunit/suites/resources/mediawiki.special/mediawiki.special.recentchanges.test.js',

			// *has mw-config def:
			// This means the module overwrites/sets mw.config variables, reason being that
			// the static /qunit/index.html has an empty mw.config since it's static.
			// Until /qunit/index.html is fully replaceable and WMF's TestSwarm is up and running
			// with Special:JavaScriptTest - untill then, it is important that tests do not depend
			// on anything being in mw.config (not even wgServer).
		),
		'dependencies' => array(
			'jquery.autoEllipsis',
			'jquery.byteLength',
			'jquery.byteLimit',
			'jquery.client',
			'jquery.colorUtil',
			'jquery.getAttrs',
			'jquery.highlightText',
			'jquery.localize',
			'jquery.mwExtension',
			'jquery.tabIndex',
			'jquery.tablesorter',
			'jquery.textSelection',
			'mediawiki',
			'mediawiki.Title',
			'mediawiki.user',
			'mediawiki.util',
			'mediawiki.special.recentchanges',
		),
	)
);
