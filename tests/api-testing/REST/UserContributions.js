'use strict';

const { REST, assert } = require( 'api-testing' );

describe( 'GET /me/contributions', () => {

	const basePath = 'rest.php/coredev/v0';
	const anon = new REST( basePath );

	it( 'Returns status 401 for anon', async () => {
		const { status, body } = await anon.get( '/me/contributions' );
		assert.equal( status, 401 );
		assert.nestedProperty( body, 'messageTranslations' );
	} );

} );
