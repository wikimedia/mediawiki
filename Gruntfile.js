'use strict';
module.exports = function ( grunt ) {
	grunt.loadNpmTasks( 'grunt-banana-checker' );
	grunt.loadNpmTasks( 'grunt-eslint' );
	grunt.loadNpmTasks( 'grunt-karma' );
	grunt.loadNpmTasks( 'grunt-stylelint' );

	const fs = require( 'fs' );
	const wgServer = process.env.MW_SERVER;
	const wgScriptPath = process.env.MW_SCRIPT_PATH;
	const karmaProxy = {};

	let qunitURL = wgServer + wgScriptPath + '/index.php?title=Special:JavaScriptTest/qunit/export';

	// "MediaWiki" for core, or extension/skin name (e.g. "GrowthExperiments")
	const qunitComponent = grunt.option( 'qunit-component' );
	const qunitWatch = grunt.option( 'qunit-watch' ) || false;
	const qunitWatchFiles = [];
	if ( qunitComponent ) {
		qunitURL = qunitURL + '&component=' + qunitComponent;
	}
	if ( qunitWatch ) {
		if ( !qunitComponent || qunitComponent === 'MediaWiki' ) {
			// MediaWiki core
			qunitWatchFiles.push( 'tests/qunit/**' );
			qunitWatchFiles.push( 'resources/**' );
		} else {
			// one extension or skin
			const extPath = __dirname + '/extensions/' + qunitComponent + '/extension.json';
			const skinPath = __dirname + '/skins/' + qunitComponent + '/skin.json';
			// eslint-disable-next-line security/detect-non-literal-fs-filename
			if ( fs.existsSync( extPath ) ) {
				qunitWatchFiles.push( 'extensions/' + qunitComponent + '/extension.json' );
				qunitWatchFiles.push( 'extensions/' + qunitComponent + '/{modules,resources,tests}/**' );
			}
			// eslint-disable-next-line security/detect-non-literal-fs-filename
			if ( fs.existsSync( skinPath ) ) {
				qunitWatchFiles.push( 'skins/' + qunitComponent + '/skin.json' );
				qunitWatchFiles.push( 'skins/' + qunitComponent + '/{modules,resources,tests}/**' );
			}
		}
	}

	karmaProxy[ wgScriptPath ] = {
		target: wgServer + wgScriptPath,
		changeOrigin: true
	};

	grunt.initConfig( {
		eslint: {
			options: {
				extensions: [ '.js', '.json', '.vue' ],
				cache: true,
				fix: grunt.option( 'fix' )
			},
			all: '.'
		},
		banana: {
			options: {
				requireLowerCase: false,
				disallowBlankTranslations: false
			},
			core: 'languages/i18n/',
			botpasswords: 'languages/i18n/botpasswords/',
			codex: 'languages/i18n/codex/',
			datetime: 'languages/i18n/datetime/',
			exif: 'languages/i18n/exif/',
			preferences: 'languages/i18n/preferences/',
			api: 'includes/api/i18n/',
			rest: 'includes/Rest/i18n/',
			installer: 'includes/installer/i18n/',
			paramvalidator: 'includes/libs/ParamValidator/i18n/'
		},
		stylelint: {
			options: {
				cache: true,
				reportNeedlessDisables: true
			},
			resources: 'resources/src/**/*.{css,less,vue}',
			config: 'mw-config/**/*.css'
		},
		watch: {
			files: [
				'.{stylelintrc,eslintrc}.json',
				'**/*',
				'!{extensions,node_modules,skins,vendor}/**'
			],
			tasks: 'test'
		},
		karma: {
			options: {
				plugins: [
					'@wikimedia/karma-firefox-launcher',
					'karma-*'
				],
				customLaunchers: {
					ChromeCustom: {
						base: 'ChromeHeadless',
						// Chrome requires --no-sandbox in Docker/CI.
						// WMF CI images expose CHROMIUM_FLAGS which sets that.
						flags: process.env.CHROMIUM_FLAGS ? ( process.env.CHROMIUM_FLAGS || '' ).split( ' ' ) : []
					}
				},
				proxies: karmaProxy,
				files: [ {
					pattern: qunitURL,
					type: 'js',
					watched: false,
					included: true,
					served: false
				}, ...qunitWatchFiles.map( ( file ) => ( {
					pattern: file,
					type: 'js',
					watched: true,
					included: false,
					served: false
				} ) ) ],
				logLevel: ( process.env.ZUUL_PROJECT ? 'DEBUG' : 'INFO' ),
				frameworks: [ 'qunit' ],
				// Disable autostart because we load modules asynchronously.
				client: {
					qunit: {
						autostart: false
					}
				},
				reporters: [ 'mocha' ],
				singleRun: !qunitWatch,
				autoWatch: qunitWatch,
				// Some tests in extensions don't yield for more than the default 10s (T89075)
				browserNoActivityTimeout: 60 * 1000,
				// Karma requires Same-Origin (or CORS) by default since v1.1.1
				// for better stacktraces. But we load the first request from wgServer
				crossOriginAttribute: false
			},
			main: {
				browsers: [ 'FirefoxHeadless' ]
			},
			firefox: {
				browsers: [ 'FirefoxHeadless' ]
			},
			chrome: {
				browsers: [ 'ChromeCustom' ]
			}
		}
	} );

	grunt.registerTask( 'assert-mw-env', () => {
		let ok = true;
		if ( !process.env.MW_SERVER ) {
			grunt.log.error( 'Environment variable MW_SERVER must be set.\n' +
				'Set this like $wgServer, e.g. "http://localhost"'
			);
			ok = false;
		}
		// MW_SCRIPT_PATH= empty string is valid, e.g. for docroot installs
		// This includes "composer serve" (Quickstart)
		if ( process.env.MW_SCRIPT_PATH === undefined ) {
			grunt.log.error( 'Environment variable MW_SCRIPT_PATH must be set.\n' +
				'Set this like $wgScriptPath, e.g. "/w"' );
			ok = false;
		}
		return ok;
	} );

	grunt.registerTask( 'lint', [ 'eslint', 'banana', 'stylelint' ] );
	grunt.registerTask( 'qunit', [ 'assert-mw-env', 'karma:firefox' ] );
};
