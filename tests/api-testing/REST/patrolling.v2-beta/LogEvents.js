'use strict';

const { action, assert, REST, utils } = require( 'api-testing' );

const chai = require( 'chai' );
const expect = chai.expect;

const chaiResponseValidator = require( 'chai-openapi-response-validator' ).default;

const pathPrefix = '/patrolling/v2-beta';
const specModule = '/patrolling/v2-beta';

// NOTE: The /logevents endpoints correspond to list=logevents in the action API.
describe( 'patrolling.v2-beta logevents', () => {
	const firstTitle = utils.title( 'RestLogEvents_' );
	const secondTitle = utils.title( 'RestLogEvents_' );
	let client, mindy, firstEntryId, secondEntryId;

	const entryUrl = ( id ) => `${ pathPrefix }/logevents/${ encodeURIComponent( id ) }`;

	/**
	 * Deletes a page, which makes a log entry, and returns the ID of the entry.
	 */
	const makeLogEntry = async ( pageTitle ) => {
		await mindy.edit( pageTitle, { text: 'to be deleted' } );
		await mindy.action( 'delete', {
			title: pageTitle,
			reason: `deleting ${ pageTitle }`,
			token: await mindy.token()
		}, 'POST' );

		const res = await client.get( `${ pathPrefix }/logevents`, { type: 'delete', title: pageTitle } );
		assert.equal( res.status, 200, res.text );
		assert.lengthOf( res.body.items, 1 );
		return res.body.items[ 0 ].logid;
	};

	before( async () => {
		mindy = await action.mindy();
		client = new REST( 'rest.php', mindy );

		const specPath = '/specs/v0/module' + specModule;
		const { status, text } = await client.get( specPath );
		assert.deepEqual( status, 200 );

		chai.use( chaiResponseValidator( JSON.parse( text ) ) );

		firstEntryId = await makeLogEntry( firstTitle );
		secondEntryId = await makeLogEntry( secondTitle );
	} );

	describe( 'GET /logevents', () => {
		describe( 'successful operation', () => {
			it( 'should list log entries, most recent first', async () => {
				const res = await client.get( `${ pathPrefix }/logevents`, { type: 'delete' } );
				const { status, body, header } = res;

				assert.equal( status, 200, res.text );
				assert.match( header[ 'content-type' ], /^application\/json/ );

				const ids = body.items.map( ( item ) => item.logid );
				assert.isBelow( ids.indexOf( secondEntryId ), ids.indexOf( firstEntryId ) );
			} );

			it( 'should return a response that satisfies the OpenAPI spec', async () => {
				const res = await client.get( `${ pathPrefix }/logevents`, { type: 'delete', title: firstTitle } );

				// eslint-disable-next-line no-unused-expressions
				expect( res ).to.satisfyApiSpec;
			} );

			it( 'should return the requested props', async () => {
				const res = await client.get( `${ pathPrefix }/logevents`, {
					type: 'delete', title: firstTitle, prop: 'ids|type|user|comment'
				} );
				const { status, body } = res;

				assert.equal( status, 200, res.text );
				assert.equal( body.items[ 0 ].logid, firstEntryId );
				assert.equal( body.items[ 0 ].type, 'delete' );
				assert.equal( body.items[ 0 ].action, 'delete' );
				assert.equal( body.items[ 0 ].user, mindy.username );
				assert.equal( body.items[ 0 ].comment, `deleting ${ firstTitle }` );
				assert.notProperty( body.items[ 0 ], 'timestamp' );

				// eslint-disable-next-line no-unused-expressions
				expect( res ).to.satisfyApiSpec;
			} );

			it( 'should filter by the log action, which the list module calls "action"', async () => {
				const deleteResponse = await client.get( `${ pathPrefix }/logevents`, {
					action: 'delete/delete', title: firstTitle
				} );
				assert.equal( deleteResponse.status, 200, deleteResponse.text );
				assert.lengthOf( deleteResponse.body.items, 1 );

				const restoreResponse = await client.get( `${ pathPrefix }/logevents`, {
					action: 'delete/restore', title: firstTitle
				} );
				assert.equal( restoreResponse.status, 200, restoreResponse.text );
				assert.deepEqual( restoreResponse.body.items, [] );
			} );

			it( 'should return the parsed comment if requested', async () => {
				const res = await client.get( `${ pathPrefix }/logevents`, {
					type: 'delete', title: firstTitle, prop: 'comment|parsedcomment'
				} );

				assert.equal( res.status, 200, res.text );
				assert.isNotEmpty( res.body.items );
				res.body.items.forEach( ( item ) => assert.isString( item.parsedcomment ) );
			} );
		} );

		describe( 'pagination', () => {
			it( 'should return all entries across pages, without repeats', async () => {
				const seenIds = [];
				let query = { type: 'delete', limit: 1 };
				let pages = 0;

				// The bound guards against a continuation that never ends.
				while ( query && pages < 5 ) {
					const res = await client.get( `${ pathPrefix }/logevents`, query );
					assert.equal( res.status, 200, res.text );
					assert.isAtMost( res.body.items.length, 1 );

					seenIds.push( ...res.body.items.map( ( item ) => item.logid ) );
					pages++;

					const nextLink = res.body.pagination.next;
					query = nextLink ? Object.assign( { type: 'delete', limit: 1 }, nextLink.params ) : null;
				}

				assert.isAtLeast( pages, 2, 'there is more than one entry' );
				assert.equal( new Set( seenIds ).size, seenIds.length, 'no item is repeated' );
			} );
		} );

		describe( 'failure', () => {
			it( 'should reject a limit that is not a number', async () => {
				const res = await client.get( `${ pathPrefix }/logevents`, { limit: 'many' } );

				assert.equal( res.status, 400, res.text );
			} );
		} );
	} );

	describe( 'GET /logevents/{id}', () => {
		describe( 'successful operation', () => {
			it( 'should return the entry', async () => {
				const res = await client.get( entryUrl( firstEntryId ) );
				const { status, body, header } = res;

				assert.equal( status, 200, res.text );
				assert.match( header[ 'content-type' ], /^application\/json/ );

				// The item itself, not a list of one.
				assert.notProperty( body, 'items' );
				assert.equal( body.logid, firstEntryId );
				assert.equal( body.type, 'delete' );
				assert.sameTitle( body.title, firstTitle );
				assert.equal( body.user, mindy.username );

				// eslint-disable-next-line no-unused-expressions
				expect( res ).to.satisfyApiSpec;
			} );

			it( 'should return the requested props only', async () => {
				const res = await client.get( entryUrl( secondEntryId ), { prop: 'ids|timestamp' } );

				assert.equal( res.status, 200, res.text );
				assert.containsAllKeys( res.body, [ 'logid', 'timestamp' ] );
				assert.notProperty( res.body, 'comment' );
			} );
		} );

		describe( 'failure', () => {
			it( 'should return 404 for an entry that does not exist', async () => {
				const res = await client.get( entryUrl( 2147483000 ) );

				assert.equal( res.status, 404, res.text );
				assert.match( res.header[ 'content-type' ], /^application\/json/ );
			} );

			it( 'should reject an id that is not a positive number', async () => {
				for ( const id of [ 'abc', '0', '1|2' ] ) {
					const res = await client.get( entryUrl( id ) );

					assert.equal( res.status, 400, `id ${ id }: ${ res.text }` );
				}
			} );
		} );
	} );
} );
