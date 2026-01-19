import fs from 'node:fs';

const dockerExtraArgs = fs.existsSync( '/.dockerenv' ) ?
	[ '--no-sandbox', '--disable-gpu', '--disable-dev-shm-usage' ] :
	[];

const baseArgs = [
	// Disable as much as possible to make Chrome clean
	// https://github.com/GoogleChrome/chrome-launcher/blob/main/docs/chrome-flags-for-tools.md
	// https://peter.sh/experiments/chromium-command-line-switches/
	'--ash-no-nudges',
	'--disable-background-networking',
	'--disable-background-timer-throttling',
	'--disable-backgrounding-occluded-windows',
	'--disable-breakpad',
	'--disable-client-side-phishing-detection',
	'--disable-component-extensions-with-background-page',
	'--disable-component-update',
	'--disable-default-apps',
	'--disable-domain-reliability',
	'--disable-features=InterestFeedContentSuggestions',
	'--disable-features=Translate',
	'--disable-fetching-hints-at-navigation-start',
	'--disable-hang-monitor',
	'--disable-infobars',
	'--disable-ipc-flooding-protection',
	'--disable-prompt-on-repost',
	'--disable-renderer-backgrounding',
	'--disable-sync',
	'--disable-search-engine-choice-screen',
	'--disable-site-isolation-trials',
	'--mute-audio',
	'--no-default-browser-check',
	'--no-first-run',
	'--propagate-iph-for-testing',
	// Workaround inputs not working consistently post-navigation on Chrome 90
	// https://issuetracker.google.com/issues/42322798
	'--allow-pre-commit-input',
	// To disable save password popup together with prefs
	'--password-store=basic'
];

const prefs = {
	// These setting disable the password save popup together
	// with --password-store=basic.
	// eslint-disable-next-line camelcase
	credentials_enable_service: false,
	'profile.password_manager_enabled': false
};

const excludeSwitches = [ 'enable-automation' ];

export const getChromeOptions = ( isCi ) => ( {
	...( isCi && { binary: '/usr/bin/chromium' } ),
	args: [
		...dockerExtraArgs,
		...baseArgs
	],
	prefs,
	excludeSwitches
} );
