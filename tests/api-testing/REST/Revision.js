'use strict';

const { action, assert, REST, utils } = require( 'api-testing' );

describe( 'Revision', () => {
	const client = new REST();
	let mindy;

	before( async () => {
		mindy = await action.mindy();
	} );

	describe( 'GET /revision/{from}/compare/{to}', () => {
		const page = utils.title( 'RevisionCompare_' );
		const pageTwo = utils.title( 'RevisionCompare_2' );
		const nonExistentRevId = 999;
		const validRevId = 1;
		const invalidRevId = '#';

		it( 'should return 400 if revision id is not an integer', async () => {
			const { status } = await client.get( `/revision/${validRevId}/compare/${invalidRevId}` );
			assert.equal( status, 400 );
		} );

		it( 'should successfully get diff between 2 valid revisions', async () => {
			// XXX: this test requires php-wikidiff2 1.10 or later to be installed
			const { newrevid: revId1 } = await mindy.edit( page, { text: 'Mindy Edit 1' } );
			const { newrevid: revId2 } = await mindy.edit( page, { text: 'Mindy Edit 2' } );
			const { status, body } = await client.get( `/revision/${revId1}/compare/${revId2}` );
			assert.strictEqual( status, 200 );
			assert.nestedPropertyVal( body, 'from.id', revId1 );
			assert.nestedPropertyVal( body, 'to.id', revId2 );
			assert.nestedProperty( body, 'diff' );
			assert.isArray( body.diff );
		} );

		it( 'should return 404 for revision that does not exist', async () => {
			const { status } = await client.get( `/revision/${validRevId}/compare/${nonExistentRevId}` );
			assert.strictEqual( status, 404 );
		} );

		it( 'should return 400 if revision ids belong to different pages', async () => {
			const { newrevid: pageOneRev } = await mindy.edit( page, { text: 'Page 1 edit' } );
			const { newrevid: pageTwoRev } = await mindy.edit( pageTwo, { text: 'Page 2 edit' } );
			const { status } = await client.get( `/revision/${pageOneRev}/compare/${pageTwoRev}` );
			assert.strictEqual( status, 400 );
		} );
	} );

	describe( 'GET /revision/{id}/bare', () => {
		it( 'should successfully get information about revision', async () => {
			const page = utils.title( 'Revision' );
			const { newrevid, pageid, param_summary } = await mindy.edit( page, {
				text: 'Hello World',
				summary: 'creating page'
			} );
			const { status, body, headers } = await client.get( `/revision/${newrevid}/bare` );

			assert.strictEqual( status, 200 );
			assert.strictEqual( body.id, newrevid );
			assert.strictEqual( body.minor, false );
			assert.deepEqual( body.page, { id: pageid, title: page } );
			assert.nestedProperty( body, 'timestamp' );
			assert.nestedPropertyVal( body, 'user.name', mindy.username );
			assert.strictEqual( body.comment, param_summary );
			assert.isOk( headers.etag, 'etag' );
			assert.equal( Date.parse( body.timestamp ), Date.parse( headers[ 'last-modified' ] ) );
		} );

		it( 'should return 404 for revision that does not exist', async () => {
			const { status } = await client.get( '/revision/99999999/bare' );

			assert.strictEqual( status, 404 );
		} );
	} );
} );
