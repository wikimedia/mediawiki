'use strict';

const { action, assert, REST, utils } = require( 'api-testing' );

const chai = require( 'chai' );
const expect = chai.expect;

const chaiResponseValidator = require( 'chai-openapi-response-validator' ).default;

let pathPrefix = '/content/v2-beta';
let specModule = '/content/v2-beta';

// NOTE: This page:move endpoint corresponds to the action=move in the action API,
//       see action/Move.js for tests.
describe( 'POST /page:move', () => {
	let client, mindy, mindyToken, openApiSpec;

	before( async () => {
		mindy = await action.mindy();
		client = new REST( 'rest.php', mindy );
		mindyToken = await mindy.token();

		const specPath = '/specs/v0/module' + specModule;
		const { status, text } = await client.get( specPath );
		assert.deepEqual( status, 200 );

		openApiSpec = JSON.parse( text );
		chai.use( chaiResponseValidator( openApiSpec ) );
	} );

	describe( 'successful operation', () => {
		it( 'should move an existing page to a new title', async () => {
			const from = utils.title( 'Move Test Source ' );
			const to = utils.title( 'Move Test Target ' );

			// create source page
			await mindy.edit( from, { text: 'content to move' } );

			const reqBody = {
				token: mindyToken,
				from,
				to,
				reason: 'tästing the move endpoint'
			};

			const res = await client.post( `${ pathPrefix }/page:move`, reqBody );
			const { status: moveStatus, body: moveBody, header: moveHeader } = res;

			assert.equal( moveStatus, 200 );
			assert.match( moveHeader[ 'content-type' ], /^application\/json/ );

			assert.nestedPropertyVal( moveBody, 'from', from );
			assert.nestedPropertyVal( moveBody, 'to', to );
			assert.nestedPropertyVal( moveBody, 'reason', 'tästing the move endpoint' );
			// A redirect is left behind by default.
			assert.equal( moveBody.redirectcreated, true );

			// New title should contain the original content.
			const targetUrl = `/v1/page/${ utils.dbkey( to ) }`;
			const targetGet = await client.get( targetUrl );
			assert.equal( targetGet.status, 200, targetGet.text );
			assert.nestedPropertyVal( targetGet.body, 'source', 'content to move' );

			// Source title should now redirect to the new title.
			const sourceGet = await client.get(
				`/v1/page/${ utils.dbkey( from ) }/html`
			);
			assert.equal( sourceGet.status, 307 );
			assert.match( sourceGet.header.location, new RegExp( utils.dbkey( to ) ) );

			// eslint-disable-next-line no-unused-expressions
			expect( res ).to.satisfyApiSpec;
		} );

		it( 'should move by page id (fromid)', async () => {
			const from = utils.title( 'Move ById Source ' );
			const to = utils.title( 'Move ById Target ' );

			const firstRev = await mindy.edit( from, { text: 'content to move' } );

			const reqBody = {
				token: mindyToken,
				fromid: firstRev.pageid,
				to,
				reason: 'move by id'
			};

			const res = await client.post( `${ pathPrefix }/page:move`, reqBody );
			assert.equal( res.status, 200 );
			assert.nestedPropertyVal( res.body, 'to', to );

			// eslint-disable-next-line no-unused-expressions
			expect( res ).to.satisfyApiSpec;
		} );

		it( 'should also move the talk page and subpages when requested', async () => {
			const baseName = utils.title( 'MoveTalkSub_' );
			const from = `User:${ baseName }`;
			const to = `User:${ utils.title( 'MoveTalkSubTarget_' ) }`;
			const fromTalk = `User_talk:${ baseName }`;
			const subName = utils.title( 'sub_' );
			const fromSubpage = `${ from }/${ subName }`;

			// Set up source page with a talk page and a subpage.
			await mindy.edit( from, { text: 'main' } );
			await mindy.edit( fromSubpage, { text: 'sub' } );
			await mindy.edit( fromTalk, { text: 'talk' } );

			const reqBody = {
				token: mindyToken,
				from,
				to,
				reason: 'with talk and subpages',
				movetalk: true,
				movesubpages: true
			};

			const res = await client.post( `${ pathPrefix }/page:move`, reqBody );
			const { status, body } = res;

			assert.equal( status, 200 );

			// Top-level fields.
			assert.sameTitle( body.from, from );
			assert.sameTitle( body.to, to );
			assert.equal( body.redirectcreated, true );

			// Nested response fields must flow through the REST wrapper.
			assert.sameTitle( body.talkfrom, fromTalk );
			assert.nestedProperty( body, 'talkto' );
			assert.isArray( body.subpages );
			assert.sameTitle( body.subpages[ 0 ].from, fromSubpage );
			assert.nestedProperty( body.subpages[ 0 ], 'to' );

			// eslint-disable-next-line no-unused-expressions
			expect( res ).to.satisfyApiSpec;
		} );

		it( 'should leave no redirect when noredirect is set', async () => {
			const from = utils.title( 'Move NoRedirect Source ' );
			const to = utils.title( 'Move NoRedirect Target ' );

			await mindy.edit( from, { text: 'content' } );

			const reqBody = {
				token: mindyToken,
				from,
				to,
				reason: 'no redirect',
				noredirect: true
			};

			const res = await client.post( `${ pathPrefix }/page:move`, reqBody );
			assert.equal( res.status, 200 );
			// The action API preserves the field as `false` (rather than
			// omitting it) when no redirect was created.
			assert.equal( res.body.redirectcreated, false );

			// Source title should now 404 — no redirect was left behind.
			const sourceGet = await client.get( `/v1/page/${ utils.dbkey( from ) }/bare` );
			assert.equal( sourceGet.status, 404 );

			// eslint-disable-next-line no-unused-expressions
			expect( res ).to.satisfyApiSpec;
		} );
	} );

	describe( 'request validation', () => {
		it( 'should fail when both from and fromid are missing', async () => {
			const to = utils.title( 'Move Missing Source ' );

			const reqBody = {
				token: mindyToken,
				to,
				reason: 'missing source'
			};

			const res = await client.post( `${ pathPrefix }/page:move`, reqBody );

			// requireOnlyOneParameter throws 'missingparam' → unmapped → 400
			assert.equal( res.status, 400 );
			assert.match( res.header[ 'content-type' ], /^application\/json/ );
			expect( res.text ).to.satisfySchemaInApiSpec( 'GenericErrorResponseModel' );
		} );

		it( 'should fail when to is missing', async () => {
			const from = utils.title( 'Move Missing Target ' );
			await mindy.edit( from, {} );

			const reqBody = {
				token: mindyToken,
				from,
				reason: 'missing target'
			};

			const res = await client.post( `${ pathPrefix }/page:move`, reqBody );
			assert.equal( res.status, 400 );
			expect( res.text ).to.satisfySchemaInApiSpec( 'GenericErrorResponseModel' );
		} );
	} );

	describe( 'authentication', () => {
		it( 'should return 400 when no token is given', async () => {
			const from = utils.title( 'Move No Token Source ' );
			const to = utils.title( 'Move No Token Target ' );
			await mindy.edit( from, {} );

			const reqBody = {
				// no token
				from,
				to,
				reason: 'no token'
			};

			const res = await client.post( `${ pathPrefix }/page:move`, reqBody );

			// A missing token surfaces as the 'missingparam' Action API error,
			// which is not in the hardcoded REST status map, so it falls
			// through to 400. (An *invalid* token surfaces as 'badtoken',
			// which is mapped to 401 — see the next test.)
			assert.equal( res.status, 400 );
			assert.match( res.header[ 'content-type' ], /^application\/json/ );
			expect( res.text ).to.satisfySchemaInApiSpec( 'GenericErrorResponseModel' );
		} );

		it( 'should return 401 when a bad token is given', async () => {
			const from = utils.title( 'Move Bad Token Source ' );
			const to = utils.title( 'Move Bad Token Target ' );
			await mindy.edit( from, {} );

			const reqBody = {
				token: 'BAD',
				from,
				to,
				reason: 'bad token'
			};

			const res = await client.post( `${ pathPrefix }/page:move`, reqBody );
			assert.equal( res.status, 401 );
			expect( res.text ).to.satisfySchemaInApiSpec( 'GenericErrorResponseModel' );
		} );
	} );

	describe( 'failures due to system state', () => {
		it( 'should return 404 when the source page does not exist', async () => {
			const from = utils.title( 'Nonexistent Source ' );
			const to = utils.title( 'Move Target ' );

			const reqBody = {
				token: mindyToken,
				from,
				to,
				reason: 'source missing'
			};

			const res = await client.post( `${ pathPrefix }/page:move`, reqBody );

			// missingtitle → 404 per the hardcoded mapping
			assert.equal( res.status, 404 );
			expect( res.text ).to.satisfySchemaInApiSpec( 'GenericErrorResponseModel' );
		} );

		it( 'should return 404 when fromid refers to a nonexistent page', async () => {
			const to = utils.title( 'Move Target ' );

			const reqBody = {
				token: mindyToken,
				fromid: 999999999,
				to,
				reason: 'fromid missing'
			};

			const res = await client.post( `${ pathPrefix }/page:move`, reqBody );

			// nosuchpageid → 404 per the hardcoded mapping
			assert.equal( res.status, 404 );
			expect( res.text ).to.satisfySchemaInApiSpec( 'GenericErrorResponseModel' );
		} );
	} );

	// The /page:move/{from} route backs the same action=move module as /page:move,
	// but takes the source title from the path instead of the request body.
	describe( 'using the {from} path parameter', () => {
		it( 'should move an existing page identified by the path segment', async () => {
			const from = utils.title( 'Move PathParam Source ' );
			const to = utils.title( 'Move PathParam Target ' );

			await mindy.edit( from, { text: 'content to move' } );

			const reqBody = {
				token: mindyToken,
				to,
				reason: 'tästing the path-based move endpoint'
			};

			const res = await client.post( `${ pathPrefix }/page:move/${ from }`, reqBody );
			const { status: moveStatus, body: moveBody, header: moveHeader } = res;

			assert.equal( moveStatus, 200 );
			assert.match( moveHeader[ 'content-type' ], /^application\/json/ );

			assert.nestedPropertyVal( moveBody, 'from', from );
			assert.nestedPropertyVal( moveBody, 'to', to );
			assert.nestedPropertyVal( moveBody, 'reason', 'tästing the path-based move endpoint' );
			assert.equal( moveBody.redirectcreated, true );

			// New title should contain the original content.
			const targetUrl = `/v1/page/${ utils.dbkey( to ) }`;
			const targetGet = await client.get( targetUrl );
			assert.equal( targetGet.status, 200, targetGet.text );
			assert.nestedPropertyVal( targetGet.body, 'source', 'content to move' );

			// Source title should now redirect to the new title.
			const sourceGet = await client.get(
				`/v1/page/${ utils.dbkey( from ) }/html`
			);
			assert.equal( sourceGet.status, 307 );
			assert.match( sourceGet.header.location, new RegExp( utils.dbkey( to ) ) );

			// eslint-disable-next-line no-unused-expressions
			expect( res ).to.satisfyApiSpec;
		} );

		it( 'should fail when to is missing', async () => {
			const from = utils.title( 'Move PathParam Missing Target ' );
			await mindy.edit( from, {} );

			const reqBody = {
				token: mindyToken,
				reason: 'missing target'
			};

			const res = await client.post( `${ pathPrefix }/page:move/${ from }`, reqBody );
			assert.equal( res.status, 400 );
			expect( res.text ).to.satisfySchemaInApiSpec( 'GenericErrorResponseModel' );
		} );

		it( 'should return 404 when the page named in the path does not exist', async () => {
			const from = utils.title( 'Nonexistent PathParam Source ' );
			const to = utils.title( 'Move PathParam Target ' );

			const reqBody = {
				token: mindyToken,
				to,
				reason: 'source missing'
			};

			const res = await client.post( `${ pathPrefix }/page:move/${ from }`, reqBody );

			// missingtitle → 404 per the hardcoded mapping
			assert.equal( res.status, 404 );
			expect( res.text ).to.satisfySchemaInApiSpec( 'GenericErrorResponseModel' );
		} );
	} );
} );

// eslint-disable-next-line mocha/no-exports
exports.init = function ( pp, sm ) {
	// Allow testing both legacy and module paths using the same tests
	pathPrefix = pp;
	specModule = sm;
};
