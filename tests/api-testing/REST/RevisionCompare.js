'use strict';

const { action, assert, REST, utils } = require( 'api-testing' );

const chai = require( 'chai' );
const expect = chai.expect;

const chaiResponseValidator = require( 'chai-openapi-response-validator' ).default;

describe( 'Revision Compare', () => {
	const client = new REST( 'rest.php' );
	let mindy, openApiSpec;

	before( async () => {
		mindy = await action.mindy();

		const { status, text } = await client.get( '/specs/v0/module/-' );
		assert.deepEqual( status, 200 );

		openApiSpec = JSON.parse( text );
		chai.use( chaiResponseValidator( openApiSpec ) );
	} );

	describe( 'GET /revision/{from}/compare/{to}', () => {
		const pageOne = utils.title( 'RevisionCompare_1' );
		const pageTwo = utils.title( 'RevisionCompare_2' );
		const nonExistentRevId = 99999;
		const validRevId = 1;
		const invalidRevId = 'invalidString';

		it( 'should return 400 if revision id is not an integer', async () => {
			const { status, text } = await client.get( `/v1/revision/${ validRevId }/compare/${ invalidRevId }` );
			assert.equal( status, 400 );
			// eslint-disable-next-line no-unused-expressions
			expect( text ).to.satisfySchemaInApiSpec( 'GenericErrorResponseModel' );
		} );

		it( 'should successfully get diff between 2 valid revisions', async () => {
			// XXX: this test requires php-wikidiff2 1.10 or later to be installed
			const { newrevid: revId1 } = await mindy.edit( pageOne, { text: 'Mindy Edit 1' } );
			const { newrevid: revId2 } = await mindy.edit( pageOne, { text: 'Mindy Edit 2' } );
			const response = await client.get( `/v1/revision/${ revId1 }/compare/${ revId2 }` );
			const { status, body, text, headers } = response;
			assert.strictEqual( status, 200, text );
			assert.match( headers[ 'content-type' ], /^application\/json/ );
			assert.nestedPropertyVal( body, 'from.id', revId1 );
			assert.nestedPropertyVal( body, 'to.id', revId2 );
			assert.nestedProperty( body, 'diff' );
			assert.isArray( body.diff );
			// eslint-disable-next-line no-unused-expressions
			expect( response ).to.satisfyApiSpec;
		} );

		it( 'should return 404 for revision that does not exist', async () => {
			const { status, text } = await client.get( `/v1/revision/${ validRevId }/compare/${ nonExistentRevId }` );
			assert.strictEqual( status, 404 );
			// eslint-disable-next-line no-unused-expressions
			expect( text ).to.satisfySchemaInApiSpec( 'GenericErrorResponseModel' );
		} );

		it( 'should return 400 if revision ids belong to different pages', async () => {
			const { newrevid: pageOneRev } = await mindy.edit( pageOne, { text: 'Page 1 edit' } );
			const { newrevid: pageTwoRev } = await mindy.edit( pageTwo, { text: 'Page 2 edit' } );
			const { status, text } = await client.get( `/v1/revision/${ pageOneRev }/compare/${ pageTwoRev }` );
			assert.strictEqual( status, 400 );
			// eslint-disable-next-line no-unused-expressions
			expect( text ).to.satisfySchemaInApiSpec( 'GenericErrorResponseModel' );
		} );
	} );

} );
