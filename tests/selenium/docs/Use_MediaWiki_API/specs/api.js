// Example code for Selenium/How-to/Use MediaWiki API
// https://www.mediawiki.org/wiki/Selenium/How-to/Use_MediaWiki_API

import assert from 'assert';
import MWBot from 'mwbot';

// apiUrl is required for our continuous integration.
// If you don't have MW_SERVER and MW_SCRIPT_PATH environment variables set
// you can probably hardcode it to something like this:
// const apiUrl = 'http://localhost:8080/w/api.php';
const apiUrl = `${ process.env.MW_SERVER }${ process.env.MW_SCRIPT_PATH }/api.php`;

const bot = new MWBot( {
	apiUrl: apiUrl
} );

// Since mwbot v2 the script either needs to log in immediately or hardcode MediaWiki version.
// Without the line below, this error message is displayed:
// Invalid version. Must be a string. Got type "object".
bot.mwversion = '0.0.0';

describe( 'API', () => {

	it( 'Main Page should exist', async () => {
		const response = await bot.read( 'Main Page' );

		// console.log( response );
		// { batchcomplete: '' (...) query: { pages: { '3': [Object] } } }

		// console.log( response.query );
		// { pages: { '3': { pageid: 3, ns: 0, title: 'Main Page', revisions: [Array] } } }

		assert.strictEqual( Object.values( response.query.pages )[ 0 ].pageid > 0, true );

	} );

	it( 'Missing Page should not exist', async () => {
		const response = await bot.read( 'Missing Page' );

		// console.log( response );
		// { batchcomplete: '', query: { pages: { '-1': [Object] } } }

		// console.log( response.query );
		// { pages: { '-1': { ns: 0, title: 'Missing Page', missing: '' } } }

		assert.strictEqual( response.query.pages[ '-1' ].missing, '' );

	} );

} );
