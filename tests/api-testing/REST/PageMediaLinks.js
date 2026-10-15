'use strict';

const { action, assert, REST, utils } = require( 'api-testing' );
const useModuleSpec = require( '../useModuleSpec.js' );
const chai = require( 'chai' );
const expect = chai.expect;

describe( 'Page media links', () => {
	let client, mindy;
	const baseEditText = "''Edit 1'' and '''Edit 2'''";
	const page = utils.title( 'PageMediaLinks_' );

	beforeEach( async () => {
		mindy = await action.mindy();
		await mindy.edit( page, { text: baseEditText } );
		client = new REST( 'rest.php' );

		await useModuleSpec( '-' );

	} );

	describe( 'GET /page/{title}/links/media', () => {
		it( 'Should successfully return media links for the page', async () => {
			const res = await client.get( `/v1/page/${ page }/links/media`, null, {
				'accept-language': 'en-x-piglatin'
			} );
			const { status } = res;
			assert.deepEqual( status, 200 );
			// eslint-disable-next-line no-unused-expressions
			expect( res ).to.satisfyApiSpec;

		} );
		it( 'Should return 404 for non existing page', async () => {
			const dummyPageTitle = utils.title( 'DummyPage_' );
			const { status, text } = await client.get( `/v1/page/${ dummyPageTitle }/links/media` );
			assert.deepEqual( status, 404 );

			expect( JSON.parse( text ) ).to.satisfySchemaInApiSpec( 'GenericErrorResponseModel' );
		} );
	} );
} );
