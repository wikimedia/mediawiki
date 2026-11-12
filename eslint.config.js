'use strict';

const { defineConfig, globalIgnores } = require( 'eslint/config' );
const globals = require( 'globals' );
const wikimedia = require( 'eslint-config-wikimedia/flat' );

// Code which runs in the browser, as part of MediaWiki
const resourcesConfig = {
	extends: [ wikimedia( 'client', 'jquery', 'mediawiki' ) ],
	languageOptions: {
		globals: {
			require: 'readonly',
			module: 'readonly'
		}
	},
	rules: {
		'max-len': 'off',
		'es-x/no-promise-prototype-finally': 'off',
		'no-jquery/no-done-fail': 'off',
		'no-jquery/no-global-selector': 'off',
		'jsdoc/no-undefined-types': [ 'warn', {
			definedTypes: [
				'Hooks',
				'JQuery',
				'MwApi'
			]
		} ]
	}
};

// Directories with their own config, which other configs don't apply to.
// These were directories with `"root": true` in .eslintrc.json.
const rootDirs = {
	resourcesSrc: [ 'resources/src/**', 'mw-config/**', 'tests/phpunit/data/**' ],
	startup: [ 'resources/src/startup/**' ],
	vue: [ 'resources/src/vue/**' ],
	mediawikiTitle: [ 'maintenance/mediawiki.Title/**' ],
	apiTesting: [ 'tests/api-testing/**' ],
	jest: [ 'tests/jest/**' ],
	qunit: [ 'tests/qunit/**' ],
	selenium: [ 'tests/selenium/**' ]
};
const allRootDirs = Object.values( rootDirs ).flat();

module.exports = defineConfig( [
	globalIgnores( [
		// Upstream code
		'resources/lib/',
		'maintenance/external-schemas/',
		'resources/src/mediawiki.libs.jpegmeta/',

		// Skip functions
		'resources/src/skip-*',

		// Build tooling
		'docs/coverage/',
		'docs/html/',
		'docs/js/',
		'docs/latex/',
		'vendor/',
		'tests/coverage/',

		// Test data
		'tests/phpunit/data/registration/duplicate_keys.json',
		'tests/phpunit/data/resourceloader/codex/',
		'tests/phpunit/data/resourceloader/codex-devmode/',
		'tests/phpunit/data/SelserContext/*.json',
		'tests/phpunit/data/PageEditStashContents/*.json',
		'tests/phpunit/unit/includes/Settings/Source/fixtures/bad.json',
		'tests/phpunit/**/*malformed*.json',
		'maintenance/benchmarks/data/',

		// Nested projects
		'extensions/',
		'skins/',

		// Language files written automatically by TranslateWiki
		'**/i18n/**/*.json',
		'!**/i18n/**/en.json',
		'!**/i18n/**/qqq.json',

		// Ignore directories where MW may write internal files (T291674)
		// Ref $wgCacheDirectory (installer)
		// Ref $wgUploadDirectory (run-time default)
		'cache/',
		'images/',

		// TODO: This file should be linted
		'docker-compose.yml',

		// Automatically generated file
		'docs/config-schema.yaml'
	] ),

	// Build tools, maintenance scripts, etc.
	{
		ignores: allRootDirs,
		extends: [ wikimedia( 'server' ) ],
		rules: {
			'max-len': 'warn'
		}
	},
	{
		files: [ 'docs/extension.schema.v2.json' ],
		rules: {
			'no-dupe-keys': 'off'
		}
	},
	{
		files: [ 'tests/phpunit/unit/includes/Tidy/**' ],
		rules: {
			indent: 'off',
			'eol-last': 'off'
		}
	},
	{
		files: rootDirs.mediawikiTitle,
		extends: [ wikimedia( 'server' ) ]
	},

	// Browser code
	{
		files: rootDirs.resourcesSrc,
		ignores: [ ...rootDirs.startup, ...rootDirs.vue ],
		...resourcesConfig
	},
	{
		files: [ 'tests/phpunit/data/**' ],
		rules: {
			'array-bracket-spacing': 'off',
			'comma-spacing': 'off',
			'eol-last': 'off',
			indent: 'off',
			'key-spacing': 'off',
			'no-multi-spaces': 'off',
			'no-multiple-empty-lines': 'off',
			'object-curly-spacing': 'off',
			strict: 'off'
		}
	},
	{
		files: [ 'tests/phpunit/data/languageConverter/**' ],
		rules: {
			indent: 'off',
			'eol-last': 'off'
		}
	},
	{
		files: rootDirs.startup,
		extends: [ wikimedia( 'client' ) ],
		rules: {
			'max-len': 'off',
			'no-var': 'off',
			'prefer-arrow-callback': 'off'
		}
	},
	{
		// Only for .js files, so that JSON files are still parsed as JSON
		files: [ 'resources/src/startup/**/*.js' ],
		languageOptions: {
			ecmaVersion: 3,
			globals: {
				// eslintrc defined ES5 globals for all ECMAScript versions
				JSON: 'readonly'
			}
		},
		rules: {
			// ES3 doesn't have directives, so since ESLint 9 "use strict" is
			// reported, unless ignored
			'no-unused-expressions': [ 'error', { ignoreDirectives: true } ]
		}
	},
	{
		files: rootDirs.vue,
		extends: [ wikimedia( 'client', 'mediawiki' ) ],
		rules: {
			'compat/compat': [
				'error',
				'last 2 chrome versions, last 2 firefox versions, last 2 opera versions, last 2 edge versions, safari >= 14, iOS >= 14'
			],
			'max-len': 'off'
		}
	},

	// Tests
	{
		files: rootDirs.apiTesting,
		extends: [ wikimedia( 'server', 'mocha' ) ],
		rules: {
			camelcase: 'off',
			'mocha/no-setup-in-describe': 'off',
			'security/detect-non-literal-regexp': 'off'
		}
	},
	{
		files: rootDirs.jest,
		extends: [ wikimedia( 'client/common', 'language/es2020', 'jquery', 'mediawiki' ) ],
		languageOptions: {
			sourceType: 'module',
			globals: {
				...globals.browser,
				...globals.jest,
				global: 'writable'
			}
		},
		rules: {
			'compat/compat': [ 'error', 'last 2 chrome versions, last 2 firefox versions' ],
			'max-len': 'off'
		}
	},
	{
		files: rootDirs.qunit,
		extends: [ wikimedia( 'client/common', 'language/es2021', 'jquery', 'mediawiki', 'qunit' ) ],
		languageOptions: {
			globals: {
				sinon: 'readonly'
			}
		},
		rules: {
			'compat/compat': [ 'error', 'last 2 chrome versions, last 2 firefox versions' ],
			'max-len': 'off',
			'mediawiki/no-storage': 'off',
			'no-shadow': [ 'error', { allow: [ 'hooks' ] } ],
			'no-jquery/no-done-fail': 'off',
			'no-jquery/no-global-selector': 'off',
			'no-jquery/no-parse-html-literal': 'off',
			'qunit/resolve-async': 'off'
		}
	},
	{
		files: [ 'tests/qunit/data/**' ],
		rules: {
			indent: 'off'
		}
	},
	{
		// Applied on top of the tests/qunit config
		files: [ 'tests/qunit/**/coverage-runner.js' ],
		extends: [ wikimedia( 'server' ) ]
	},
	{
		files: rootDirs.selenium,
		extends: [ wikimedia( 'selenium' ) ],
		languageOptions: {
			ecmaVersion: 2022,
			sourceType: 'module',
			globals: {
				mw: 'readonly',
				document: 'readonly'
			}
		}
	}
] );
