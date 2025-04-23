<?php
// These modules are only registered when $wgEnableJavaScriptTest is true
use MediaWiki\Html\Html;
use MediaWiki\MediaWikiServices;
use MediaWiki\ResourceLoader\FilePath;

return [

	'sinonjs' => [
		'scripts' => [
			'resources/lib/sinonjs/sinon.js',
		],
	],

	'mediawiki.qunit-testrunner' => [
		'scripts' => [
			'tests/qunit/data/testrunner.js',
		],
		'dependencies' => [
			'mediawiki.page.ready',
			'sinonjs',
		],
	],

	// Test module exposing language-specific rules for mediawiki.language.test.js.
	'mediawiki.language.testdata' => [
		'packageFiles' => [
			[
				'name' => 'mediawiki.language.testdata.js',
				'callback' => static function () {
					// Optimization: Only compute and load data for languages that we have tests for.
					/** @phpcs-require-sorted-array */
					$langCodes = [
						'bs',
						'dsb',
						'fi',
						'ga',
						'he',
						'hsb',
						'hu',
						'hy',
						'ka',
						'la',
						'mn',
						'os',
						'ru',
						'sl',
						'uk',
					];

					$languageFactory = MediaWikiServices::getInstance()->getLanguageFactory();

					$data = [];

					foreach ( $langCodes as $langCode ) {
						$data[$langCode] = $languageFactory->getLanguage( $langCode )->getJsData();
					}

					return 'module.exports = ' . Html::encodeJsVar( $data ) . ';';
				},
			]
		],
	],

	'mediawiki.language.jqueryMsg.testdata' => [
		'localBasePath' => __DIR__ . '/data',
		'packageFiles' => [
			new FilePath( 'mediawiki.jqueryMsg.testdata.js', __DIR__ . '/data' ),
			new FilePath( 'mediawiki.jqueryMsg.data.json', __DIR__ . '/data' ),
		],
	],

	// Test module loading all language-specific convertGrammar() implementations.
	'mediawiki.language.grammar.testdata' => [
		'localBasePath' => "{$GLOBALS['IP']}/resources/src/mediawiki.language/languages",
		// Automatically discover and load every language-specific convertGrammar() implementation.
		'scripts' => ( static function () {
			$basePath = "{$GLOBALS['IP']}/resources/src/mediawiki.language/languages";
			$scripts = [];

			foreach ( new DirectoryIterator( $basePath ) as $file ) {
				/** @var DirectoryIterator $file */
				if ( $file->isFile() && $file->getExtension() === 'js' ) {
					$scripts[] = $file->getBasename();
				}
			}

			return $scripts;
		} )(),
		'dependencies' => [ 'mediawiki.language' ],
	],

	'test.MediaWiki' => [
		'scripts' => [
			'tests/qunit/resources/jquery.highlightText.test.js',
			'tests/qunit/resources/jquery.lengthLimit.test.js',
			'tests/qunit/resources/jquery.makeCollapsible.test.js',
			'tests/qunit/resources/jquery.tablesorter.test.js',
			'tests/qunit/resources/jquery.textSelection.test.js',
			'tests/qunit/resources/mediawiki.api/mediawiki.api.category.test.js',
			'tests/qunit/resources/mediawiki.api/mediawiki.api.edit.test.js',
			'tests/qunit/resources/mediawiki.api/mediawiki.api.messages.test.js',
			'tests/qunit/resources/mediawiki.api/mediawiki.api.options.test.js',
			'tests/qunit/resources/mediawiki.api/mediawiki.api.parse.test.js',
			'tests/qunit/resources/mediawiki.api/mediawiki.api.test.js',
			'tests/qunit/resources/mediawiki.api/mediawiki.api.upload.test.js',
			'tests/qunit/resources/mediawiki.api/mediawiki.api.watch.test.js',
			'tests/qunit/resources/mediawiki.api/mediawiki.rest.test.js',
			'tests/qunit/resources/mediawiki.base/errorLogger.test.js',
			'tests/qunit/resources/mediawiki.base/html.test.js',
			'tests/qunit/resources/mediawiki.base/mediawiki.base.test.js',
			'tests/qunit/resources/mediawiki.base/track.test.js',
			'tests/qunit/resources/mediawiki.cldr.test.js',
			'tests/qunit/resources/mediawiki.cookie.test.js',
			'tests/qunit/resources/mediawiki.DateFormatter/DateFormatter.test.js',
			'tests/qunit/resources/mediawiki.deflate.test.js',
			'tests/qunit/resources/mediawiki.experiments.test.js',
			'tests/qunit/resources/mediawiki.ForeignApi/mediawiki.ForeignApi.test.js',
			'tests/qunit/resources/mediawiki.ForeignApi/mediawiki.ForeignRest.test.js',
			'tests/qunit/resources/mediawiki.inspect.test.js',
			'tests/qunit/resources/mediawiki.interface.helpers/linker.test.js',
			'tests/qunit/resources/mediawiki.jqueryMsg.test.js',
			'tests/qunit/resources/mediawiki.language.test.js',
			'tests/qunit/resources/mediawiki.messagePoster/factory.test.js',
			'tests/qunit/resources/mediawiki.rcfilters/dm.FilterItem.test.js',
			'tests/qunit/resources/mediawiki.rcfilters/dm.FiltersViewModel.test.js',
			'tests/qunit/resources/mediawiki.rcfilters/dm.SavedQueriesModel.test.js',
			'tests/qunit/resources/mediawiki.rcfilters/dm.SavedQueryItemModel.test.js',
			'tests/qunit/resources/mediawiki.rcfilters/UriProcessor.test.js',
			'tests/qunit/resources/mediawiki.router.test.js',
			'tests/qunit/resources/mediawiki.storage.test.js',
			'tests/qunit/resources/mediawiki.String.test.js',
			'tests/qunit/resources/mediawiki.template.mustache.test.js',
			'tests/qunit/resources/mediawiki.template.test.js',
			'tests/qunit/resources/mediawiki.Title.test.js',
			'tests/qunit/resources/mediawiki.toc.test.js',
			'tests/qunit/resources/mediawiki.Uri.test.js',
			'tests/qunit/resources/mediawiki.user.test.js',
			'tests/qunit/resources/mediawiki.util/accessKeyLabel.test.js',
			'tests/qunit/resources/mediawiki.util/util.test.js',
			'tests/qunit/resources/mediawiki.visibleTimeout.test.js',
			'tests/qunit/resources/mediawiki.widgets/MediaSearch/mediawiki.widgets.APIResultsQueue.test.js',
			'tests/qunit/resources/mediawiki.widgets/NamespaceInput/mediawiki.widgets.NamespaceInputWidget.test.js',
			'tests/qunit/resources/mediawiki.widgets/Table/mediawiki.widgets.TableWidget.test.js',
			'tests/qunit/resources/mediawiki.widgets/UserInputWidget/mediawiki.widgets.UserInputWidget.test.js',
			[
				'name' => 'tests/qunit/resources/startup/clientprefs.js',
				'callback' => static function () {
					return 'mw.clientprefs = function ( document, $VARS ) { '
						. strtr(
							file_get_contents( MW_INSTALL_PATH . '/resources/src/startup/clientprefs.js' ),
							[ '__COOKIE_PREFIX__' => '' ]
						)
						. '};';
				}
			],
			'tests/qunit/resources/startup/clientprefs.test.js',
			'tests/qunit/resources/startup/jscompat.test.js',
			'tests/qunit/resources/startup/mediawiki.test.js',
			'tests/qunit/resources/startup/mw.loader.test.js',
			'tests/qunit/resources/startup/mw.Map.test.js',
			'tests/qunit/resources/startup/mw.requestIdleCallback.test.js',
			'tests/qunit/resources/testrunner.test.js',
		],
		/** @phpcs-require-sorted-array */
		'dependencies' => [
			'jquery.highlightText',
			'jquery.lengthLimit',
			'jquery.makeCollapsible',
			'jquery.tablesorter',
			'jquery.textSelection',
			'mediawiki.api',
			'mediawiki.cldr',
			'mediawiki.cookie',
			'mediawiki.DateFormatter',
			'mediawiki.deflate',
			'mediawiki.experiments',
			'mediawiki.ForeignApi.core',
			'mediawiki.inspect',
			'mediawiki.interface.helpers',
			'mediawiki.jqueryMsg',
			'mediawiki.language',
			'mediawiki.language.grammar.testdata',
			'mediawiki.language.jqueryMsg.testdata',
			'mediawiki.language.testdata',
			'mediawiki.messagePoster',
			'mediawiki.qunit-testrunner',
			'mediawiki.rcfilters.filters.ui',
			'mediawiki.router',
			'mediawiki.storage',
			'mediawiki.String',
			'mediawiki.template',
			'mediawiki.template.mustache',
			'mediawiki.Title',
			'mediawiki.toc',
			'mediawiki.Uri',
			'mediawiki.user',
			'mediawiki.util',
			'mediawiki.visibleTimeout',
			'mediawiki.widgets.MediaSearch',
			'mediawiki.widgets.Table',
			'mediawiki.widgets.UserInputWidget',
		],
	]
];
