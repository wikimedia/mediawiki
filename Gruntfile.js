/* eslint-env node */

module.exports = function ( grunt ) {

	var wgServer = process.env.MW_SERVER,
		wgScriptPath = process.env.MW_SCRIPT_PATH,
		karmaProxy = {};

	grunt.loadNpmTasks( 'grunt-banana-checker' );
	grunt.loadNpmTasks( 'grunt-contrib-copy' );
	grunt.loadNpmTasks( 'grunt-eslint' );
	grunt.loadNpmTasks( 'grunt-jsonlint' );
	grunt.loadNpmTasks( 'grunt-karma' );
	grunt.loadNpmTasks( 'grunt-stylelint' );
	grunt.loadNpmTasks( 'grunt-svgmin' );

	karmaProxy[ wgScriptPath ] = {
		target: wgServer + wgScriptPath,
		changeOrigin: true
	};

	grunt.initConfig( {
		eslint: {
			options: {
				reportUnusedDisableDirectives: true,
				cache: true
			},
			all: [
				'**/*.js',
				'!docs/**',
				'!node_modules/**',
				'!resources/lib/**',
				'!resources/src/jquery.tipsy/**',
				'!resources/src/mediawiki.libs.jpegmeta/**',
				// Third-party code of PHPUnit coverage report
				'!tests/coverage/**',
				'!vendor/**',
				// Explicitly say "**/*.js" here in case of symlinks
				'!extensions/**/*.js',
				'!skins/**/*.js'
			]
		},
		jsonlint: {
			all: [
				'**/*.json',
				'!{docs/js,extensions,node_modules,skins,vendor}/**'
			]
		},
		banana: {
			options: {
				disallowBlankTranslations: false
			},
			core: 'languages/i18n/',
			exif: 'languages/i18n/exif/',
			api: 'includes/api/i18n/',
			installer: 'includes/installer/i18n/'
		},
		stylelint: {
			src: '{resources/src,mw-config}/**/*.{css,less}'
		},
		svgmin: {
			options: {
				js2svg: {
					indent: '\t',
					pretty: true
				},
				multipass: true,
				plugins: [ {
					cleanupIDs: false
				}, {
					removeDesc: false
				}, {
					removeRasterImages: true
				}, {
					removeTitle: false
				}, {
					removeViewBox: false
				}, {
					removeXMLProcInst: false
				}, {
					sortAttrs: true
				} ]
			},
			all: {
				files: [ {
					expand: true,
					cwd: 'resources/src',
					src: [
						'**/*.svg'
					],
					dest: 'resources/src/',
					ext: '.svg'
				} ]
			}
		},
		watch: {
			files: [
				'.{stylelintrc,eslintrc.json}',
				'**/*',
				'!{docs,extensions,node_modules,skins,vendor}/**'
			],
			tasks: 'test'
		},
		karma: {
			options: {
				customLaunchers: {
					ChromeCustom: {
						base: 'ChromeHeadless',
						// Chrome requires --no-sandbox in Docker/CI.
						// Newer CI images expose CHROMIUM_FLAGS which sets this (and
						// anything else it might need) automatically. Older CI images,
						// (including Quibble for MW) don't set it yet.
						flags: ( process.env.CHROMIUM_FLAGS ||
							( process.env.ZUUL_PROJECT ? '--no-sandbox' : '' )
						).split( ' ' )
					}
				},
				proxies: karmaProxy,
				files: [ {
					pattern: wgServer + wgScriptPath + '/index.php?title=Special:JavaScriptTest/qunit/export',
					watched: false,
					included: true,
					served: false
				} ],
				logLevel: 'DEBUG',
				frameworks: [ 'qunit' ],
				reporters: [ 'mocha' ],
				singleRun: true,
				autoWatch: false,
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
			jsduck: {
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
		if ( !process.env.MW_SERVER ) {
			grunt.log.error( 'Environment variable MW_SERVER must be set.\n' +
				'Set this like $wgServer, e.g. "http://localhost"'
			);
		}
		if ( !process.env.MW_SCRIPT_PATH ) {
			grunt.log.error( 'Environment variable MW_SCRIPT_PATH must be set.\n' +
				'Set this like $wgScriptPath, e.g. "/w"' );
		}
		return !!( process.env.MW_SERVER && process.env.MW_SCRIPT_PATH );
	} );

	grunt.registerTask( 'minify', 'svgmin' );
	grunt.registerTask( 'lint', [ 'eslint', 'banana', 'stylelint' ] );
	grunt.registerTask( 'qunit', [ 'assert-mw-env', 'karma:main' ] );
};
