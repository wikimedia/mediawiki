'use strict';

const { action, assert, REST, utils, wiki } = require( 'api-testing' );

const chai = require( 'chai' );
const expect = chai.expect;

const chaiResponseValidator = require( 'chai-openapi-response-validator' ).default;

const pathPrefix = '/patrolling/v2-beta';
const specModule = '/patrolling/v2-beta';

// NOTE: The /recentchanges endpoints correspond to list=recentchanges in the
//       action API, see action/RecentChanges.js for tests.
describe( 'patrolling.v2-beta recentchanges', () => {
	const title = utils.title( 'RestRecentChanges_' );
	let client, alice, firstEdit, secondEdit;

	const changeIds = ( body ) => body.items.map( ( item ) => item.rcid );

	before( async () => {
		alice = await action.alice();
		client = new REST( 'rest.php', alice );

		const specPath = '/specs/v0/module' + specModule;
		const { status, text } = await client.get( specPath );
		assert.deepEqual( status, 200 );

		chai.use( chaiResponseValidator( JSON.parse( text ) ) );

		firstEdit = await alice.edit( title, { text: 'Recent changes, first', summary: 'first edit' } );
		secondEdit = await alice.edit( title, { text: 'Recent changes, second', summary: 'second edit' } );
		await wiki.runAllJobs();
	} );

	describe( 'GET /recentchanges', () => {
		describe( 'successful operation', () => {
			it( 'should list recent changes, most recent first', async () => {
				const res = await client.get( `${ pathPrefix }/recentchanges`, { title, prop: 'ids' } );
				const { status, body, header } = res;

				assert.equal( status, 200, res.text );
				assert.match( header[ 'content-type' ], /^application\/json/ );
				assert.deepEqual(
					body.items.map( ( item ) => item.revid ).slice( 0, 2 ),
					[ secondEdit.newrevid, firstEdit.newrevid ]
				);
			} );

			it( 'should return a response that satisfies the OpenAPI spec', async () => {
				const res = await client.get( `${ pathPrefix }/recentchanges`, { title } );

				// eslint-disable-next-line no-unused-expressions
				expect( res ).to.satisfyApiSpec;
			} );

			it( 'should return the requested props', async () => {
				const res = await client.get(
					`${ pathPrefix }/recentchanges`, { title, prop: 'user|comment|flags|timestamp' }
				);
				const { status, body } = res;

				assert.equal( status, 200, res.text );
				assert.equal( body.items[ 0 ].user, alice.username );
				assert.equal( body.items[ 0 ].comment, 'second edit' );
				assert.isBoolean( body.items[ 0 ].minor );
				assert.property( body.items[ 0 ], 'timestamp' );
				assert.notProperty( body.items[ 0 ], 'revid' );

				// eslint-disable-next-line no-unused-expressions
				expect( res ).to.satisfyApiSpec;
			} );

			it( 'should only list changes of the requested type', async () => {
				const res = await client.get( `${ pathPrefix }/recentchanges`, { title, type: 'new' } );

				assert.equal( res.status, 200, res.text );
				assert.lengthOf( res.body.items, 1 );
			} );

			it( 'should return the parsed comment if requested', async () => {
				const res = await client.get(
					`${ pathPrefix }/recentchanges`, { title, prop: 'comment|parsedcomment' }
				);

				assert.equal( res.status, 200, res.text );
				assert.isNotEmpty( res.body.items );
				res.body.items.forEach( ( item ) => assert.isString( item.parsedcomment ) );
			} );
		} );

		describe( 'pagination', () => {
			it( 'should return all changes across pages, without repeats', async () => {
				const seenIds = [];
				let query = { title, prop: 'ids', limit: 1 };
				let pages = 0;

				// The bound guards against a continuation that never ends.
				while ( query && pages < 5 ) {
					const res = await client.get( `${ pathPrefix }/recentchanges`, query );
					assert.equal( res.status, 200, res.text );
					assert.isAtMost( res.body.items.length, 1 );

					seenIds.push( ...changeIds( res.body ) );
					pages++;

					const nextLink = res.body.pagination.next;
					query = nextLink ? Object.assign( { title, prop: 'ids', limit: 1 }, nextLink.params ) : null;
				}

				assert.isAtLeast( pages, 2, 'there is more than one change' );
				assert.equal( new Set( seenIds ).size, seenIds.length, 'no item is repeated' );
			} );
		} );

		describe( 'failure', () => {
			it( 'should reject a limit that is not a number', async () => {
				const res = await client.get( `${ pathPrefix }/recentchanges`, { limit: 'many' } );

				assert.equal( res.status, 400, res.text );
				assert.match( res.header[ 'content-type' ], /^application\/json/ );
			} );
		} );
	} );

} );
