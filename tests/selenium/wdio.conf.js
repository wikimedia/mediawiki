import { config as wdioDefaults } from 'wdio-mediawiki/wdio-defaults.conf.js';

export const config = { ...wdioDefaults,
	// Override, or add to, the setting from wdio-mediawiki.
	// Learn more at https://webdriver.io/docs/configurationfile
	//
	// Example:
	// logLevel: 'info',
	specs: [
		'docs/**/specs/*.js',
		'specs/**/*.js',
		'wdio-mediawiki/specs/*.js'
	],
	suites: {
		daily: [
			'specs/page.js',
			'wdio-mediawiki/specs/BlankPage.js'
		]
	},
	mochaOpts: {
		...wdioDefaults.mochaOpts,
		retries: 1
	}
};
