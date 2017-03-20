/* eslint-env node */

module.exports = function ( grunt ) {

	var wgServer = process.env.MW_SERVER,
		wgScriptPath = process.env.MW_SCRIPT_PATH,
		WebdriverIOconfigFile,
		karmaProxy = {};

	grunt.loadNpmTasks( 'grunt-banana-checker' );
	grunt.loadNpmTasks( 'grunt-contrib-copy' );
	grunt.loadNpmTasks( 'grunt-contrib-watch' );
	grunt.loadNpmTasks( 'grunt-eslint' );
	grunt.loadNpmTasks( 'grunt-jsonlint' );
	grunt.loadNpmTasks( 'grunt-karma' );
	grunt.loadNpmTasks( 'grunt-stylelint' );
	grunt.loadNpmTasks( 'grunt-webdriver' );

	karmaProxy[ wgScriptPath ] = {
		target: wgServer + wgScriptPath,
		changeOrigin: true
	};

	if ( process.env.JENKINS_HOME ) {
		WebdriverIOconfigFile = './tests/selenium/wdio.conf.jenkins.js';
	} else {
		WebdriverIOconfigFile = './tests/selenium/wdio.conf.js';
	}

	grunt.initConfig( {
		eslint: {
			all: [
				'**/*.js',
				'!docs/**',
				'!node_modules/**',
				'!resources/lib/**',
				'!resources/src/jquery.tipsy/**',
				'!resources/src/jquery/jquery.farbtastic.js',
				'!resources/src/mediawiki.libs/**',
				// Third-party code of PHPUnit coverage report
				'!tests/coverage/**',
				'!vendor/**',
				// Explicitly say "**/*.js" here in case of symlinks
				'!extensions/**/*.js',
				'!skins/**/*.js',
				// Skip functions aren't even parseable
				'!resources/src/dom-level2-skip.js',
				'!resources/src/es5-skip.js',
				'!resources/src/mediawiki.hidpi-skip.js'
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
			api: 'includes/api/i18n/',
			installer: 'includes/installer/i18n/'
		},
		stylelint: {
			options: {
				syntax: 'less'
			},
			src: '{resources/src,mw-config}/**/*.{css,less}'
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
				browsers: [ 'Chrome' ]
			},
			chromium: {
				browsers: [ 'Chromium' ]
			},
			more: {
				browsers: [ 'Chrome', 'Firefox' ]
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
		},

		// Configure WebdriverIO task
		webdriver: {
			test: {
				configFile: WebdriverIOconfigFile
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

	grunt.registerTask( 'lint', [ 'eslint', 'banana', 'stylelint' ] );
	grunt.registerTask( 'qunit', [ 'assert-mw-env', 'karma:main' ] );

	grunt.registerTask( 'test', [ 'lint' ] );
	grunt.registerTask( 'default', 'test' );
};
