/* eslint-env node */
module.exports = function ( grunt ) {
	var wgServer = process.env.MW_SERVER,
		wgScriptPath = process.env.MW_SCRIPT_PATH,
		karmaProxy = {};

	grunt.loadNpmTasks( 'grunt-banana-checker' );
	grunt.loadNpmTasks( 'grunt-contrib-copy' );
	grunt.loadNpmTasks( 'grunt-eslint' );
	grunt.loadNpmTasks( 'grunt-karma' );
	grunt.loadNpmTasks( 'grunt-stylelint' );

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
			src: '{resources/src,mw-config}/**/*.{css,less,vue}'
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
						// WMF CI images expose CHROMIUM_FLAGS which sets that.
						flags: ( process.env.CHROMIUM_FLAGS || '' ).split( ' ' )
					}
				},
				proxies: karmaProxy,
				files: [ {
					pattern: wgServer + wgScriptPath + '/index.php?title=Special:JavaScriptTest/qunit/export',
					watched: false,
					included: true,
					served: false
				} ],
				logLevel: ( process.env.ZUUL_PROJECT ? 'DEBUG' : 'INFO' ),
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
		var ok = true;
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
