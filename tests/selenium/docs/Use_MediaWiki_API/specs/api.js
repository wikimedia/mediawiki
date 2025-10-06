// Example code for Selenium/How-to/Use MediaWiki API
// https://www.mediawiki.org/wiki/Selenium/How-to/Use_MediaWiki_API

import assert from 'assert';
import { createApiClient } from 'wdio-mediawiki/Api.js';

// baseUrl is required for our continuous integration.
// If you don't have MW_SERVER and MW_SCRIPT_PATH environment variables set
// you can probably hardcode it to something like this:
// const baseUrl = 'http://localhost:8080/';
const baseUrl = `${ process.env.MW_SERVER }${ process.env.MW_SCRIPT_PATH }`;

const apiClient = await createApiClient( {
	baseUrl
} );

describe( 'API', () => {

	it( 'Main Page should exist', async () => {
		const response = await apiClient.read( 'Main Page' );

		// console.log( response );
		// { batchcomplete: '' (...) query: { pages: { '3': [Object] } } }

		// console.log( response.query );
		// { pages: { '3': { pageid: 3, ns: 0, title: 'Main Page', revisions: [Array] } } }

		assert.strictEqual( Object.values( response.query.pages )[ 0 ].pageid > 0, true );

	} );

	it( 'Missing Page should not exist', async () => {
		const response = await apiClient.read( 'Missing Page' );

		// console.log( response );
		// { batchcomplete: '', query: { pages: { '-1': [Object] } } }

		// console.log( response.query );
		// { pages: { '-1': { ns: 0, title: 'Missing Page', missing: '' } } }

		assert.strictEqual( response.query.pages[ '-1' ].missing, '' );

	} );

} );
