'use strict';
module.exports = function ( grunt ) {
	grunt.loadNpmTasks( 'grunt-banana-checker' );
	grunt.loadNpmTasks( 'grunt-contrib-copy' );
	grunt.loadNpmTasks( 'grunt-eslint' );
	grunt.loadNpmTasks( 'grunt-karma' );
	grunt.loadNpmTasks( 'grunt-stylelint' );

	const fs = require( 'fs' );
	const path = require( 'path' );
	const wgServer = process.env.MW_SERVER;
	const wgScriptPath = process.env.MW_SCRIPT_PATH;
	const karmaProxy = {};

	let qunitPattern = wgServer + wgScriptPath + '/index.php?title=Special:JavaScriptTest/qunit/export';

	// "MediaWiki" for core, or extension/skin name (e.g. "GrowthExperiments")
	const qunitComponent = grunt.option( 'qunit-component' );
	const qunitWatch = grunt.option( 'qunit-watch' ) || false;
	const qunitWatchFiles = [];
	if ( qunitComponent ) {
		let qunitWatchSourcePattern;
		let qunitWatchTestPattern;
		qunitPattern = qunitPattern + '&component=' + qunitComponent;
		if ( qunitWatch ) {
			// Special-case MediaWiki core.
			if ( qunitComponent === 'MediaWiki' ) {
				qunitWatchTestPattern = 'tests/qunit/**/*.js';
				qunitWatchSourcePattern = 'resources/**/*.js';
			} else {
				let settingsJson,
					basePath;
				try {
					basePath = 'extensions';
					// eslint-disable-next-line security/detect-non-literal-fs-filename
					settingsJson = fs.readFileSync(
						path.resolve( __dirname + '/' + basePath + '/' + qunitComponent + '/extension.json' )
					);
				} catch ( e ) {
					basePath = 'skins';
					// eslint-disable-next-line security/detect-non-literal-fs-filename
					settingsJson = fs.readFileSync(
						path.resolve( __dirname + '/' + basePath + '/' + qunitComponent + '/skin.json' )
					);
				}
				settingsJson = JSON.parse( settingsJson );
				qunitWatchSourcePattern =
					path.resolve( __dirname + '/' + basePath + '/' + qunitComponent + '/' + settingsJson.ResourceFileModulePaths.localBasePath + '/**/*.js' );
				qunitWatchTestPattern = path.resolve( __dirname + '/' + basePath + '/' + qunitComponent + '/tests/qunit/**/*.js' );
			}
			qunitWatchFiles.push( {
				pattern: qunitWatchSourcePattern,
				type: 'js',
				watched: true,
				included: false,
				served: false
			} );
			qunitWatchFiles.push( {
				pattern: qunitWatchTestPattern,
				type: 'js',
				watched: true,
				included: false,
				served: false
			} );
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
			exif: 'languages/i18n/exif/',
			api: 'includes/api/i18n/',
			rest: 'includes/Rest/i18n/',
			installer: 'includes/installer/i18n/',
			paramvalidator: 'includes/libs/ParamValidator/i18n/'
		},
		stylelint: {
			options: {
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
					pattern: qunitPattern,
					type: 'js',
					watched: false,
					included: true,
					served: false
				}, ...qunitWatchFiles ],
				logLevel: ( process.env.ZUUL_PROJECT ? 'DEBUG' : 'INFO' ),
				frameworks: [ 'qunit' ],
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
				browsers: [ 'ChromeCustom' ]
			},
			firefox: {
				browsers: [ 'FirefoxHeadless' ]
			}
		},
		copy: {
			jsdoc: {
				src: 'resources/**/*',
				dest: 'docs/js/modules',
				expand: true,
				rename: function ( dest, src ) {
					return require( 'path' ).join( dest, src.replace( 'resources/', '' ) );
				}
			}
		}
	} );

	grunt.registerTask( 'assert-mw-env', function () {
		let ok = true;
		if ( !process.env.MW_SERVER ) {
			grunt.log.error( 'Environment variable MW_SERVER must be set.\n' +
				'Set this like $wgServer, e.g. "http://localhost"'
			);
			ok = false;
		}
		if ( !process.env.MW_SCRIPT_PATH ) {
			grunt.log.error( 'Environment variable MW_SCRIPT_PATH must be set.\n' +
				'Set this like $wgScriptPath, e.g. "/w"' );
			ok = false;
		}
		return ok;
	} );

	grunt.registerTask( 'lint', [ 'eslint', 'banana', 'stylelint' ] );
	grunt.registerTask( 'qunit', [ 'assert-mw-env', 'karma:main' ] );
};
