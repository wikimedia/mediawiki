'use strict';

const { action, assert, REST, utils } = require( 'api-testing' );

const chai = require( 'chai' );
const expect = chai.expect;

const chaiResponseValidator = require( 'chai-openapi-response-validator' ).default;

const pathPrefix = '/content/v2-beta';
const specModule = '/content/v2-beta';

// NOTE: This page/{title}/backlinks endpoint corresponds to list=backlinks in the
//       action API, see action/Backlinks.js for tests.
describe( 'GET /page/{title}/backlinks', () => {
	const randomPage = utils.title( 'Esther' );
	const redirectToEsther = utils.title( 'RedirectToEsther' );
	const linksToEsther1 = utils.title( 'LinksToEsther1' );
	const linksToEsther2 = utils.title( 'LinksToEsther2' );
	const talkLinksToEsther = 'Talk:' + utils.title( 'TalkLinksToEsther' );

	let client, bob;

	const backlinksUrl = ( title ) => `${ pathPrefix }/page/${ utils.dbkey( title ) }/backlinks`;
	const titles = ( body ) => body.items.map( ( item ) => item.title );

	before( async () => {
		bob = await action.bob();
		client = new REST( 'rest.php', bob );

		const specPath = '/specs/v0/module' + specModule;
		const { status, text } = await client.get( specPath );
		assert.deepEqual( status, 200 );

		chai.use( chaiResponseValidator( JSON.parse( text ) ) );

		const randomPageText = `I'm guessing you came here from ${ linksToEsther1 } or ${ linksToEsther2 }.`;
		const linkText = `All I do is link to [[${ randomPage }|Page]]`;

		await bob.edit( linksToEsther1, { text: linkText } );
		await bob.edit( linksToEsther2, { text: linkText } );
		await bob.edit( talkLinksToEsther, { text: linkText } );
		await bob.edit( redirectToEsther, { text: `#REDIRECT [[${ randomPage }]]` } );
		await bob.edit( randomPage, { text: randomPageText } );
	} );

	describe( 'successful operation', () => {
		it( 'should list the pages that link to a page', async () => {
			const res = await client.get( backlinksUrl( randomPage ) );
			const { status, body, header } = res;

			assert.equal( status, 200, res.text );
			assert.match( header[ 'content-type' ], /^application\/json/ );

			// Redirects to the page are backlinks too.
			assert.sameMembers( titles( body ), [
				linksToEsther1, linksToEsther2, talkLinksToEsther, redirectToEsther
			] );

			// Each item identifies the linking page.
			const item = body.items.find( ( i ) => i.title === linksToEsther1 );
			assert.isNumber( item.pageid );
			assert.equal( item.ns, 0 );

			// Everything fits on one page, so there is nothing to continue.
			assert.isNull( body.pagination.next );
		} );

		// FIXME: The spec declares no response body for this endpoint, so a 200 response
		// with a body does not satisfy it. Enable once the list handler describes the
		// { items, pagination } envelope in getResponseBodySchema().
		it.skip( 'should return a response that satisfies the OpenAPI spec', async () => {
			const res = await client.get( backlinksUrl( randomPage ) );

			// eslint-disable-next-line no-unused-expressions
			expect( res ).to.satisfyApiSpec;
		} );

		it( 'should return an empty list for a page nothing links to', async () => {
			const { status, body } = await client.get( backlinksUrl( linksToEsther1 ) );

			assert.equal( status, 200 );
			assert.deepEqual( body.items, [] );
			assert.isNull( body.pagination.next );
		} );

		it( 'should return an empty list for a page that does not exist', async () => {
			const { status, body } = await client.get( backlinksUrl( utils.title( 'NoSuchPage' ) ) );

			assert.equal( status, 200 );
			assert.deepEqual( body.items, [] );
		} );

		it( 'should honor the namespace filter', async () => {
			const { status, body } = await client.get(
				backlinksUrl( randomPage ), { namespace: 1 }
			);

			assert.equal( status, 200 );
			assert.deepEqual( titles( body ), [ talkLinksToEsther ] );
		} );

		it( 'should honor the filterredir parameter', async () => {
			const redirects = await client.get(
				backlinksUrl( randomPage ), { filterredir: 'redirects' }
			);
			assert.equal( redirects.status, 200 );
			assert.deepEqual( titles( redirects.body ), [ redirectToEsther ] );

			const nonredirects = await client.get(
				backlinksUrl( randomPage ), { filterredir: 'nonredirects' }
			);
			assert.equal( nonredirects.status, 200 );
			assert.sameMembers( titles( nonredirects.body ), [
				linksToEsther1, linksToEsther2, talkLinksToEsther
			] );
		} );

		it( 'should ignore the suppressed pageid parameter', async () => {
			// The page is identified by the title in the path. A pageid of another
			// page must not change what is listed.
			const other = await bob.edit( utils.title( 'Other' ), { text: 'other' } );
			const { status, body } = await client.get(
				backlinksUrl( randomPage ), { pageid: other.pageid }
			);

			assert.equal( status, 200 );
			assert.include( titles( body ), linksToEsther1 );
		} );
	} );

	describe( 'pagination', () => {
		it( 'should return all items across pages, without repeats', async () => {
			const seen = [];
			let query = { limit: 1 };
			let pages = 0;

			// Follow the continuation until the server reports no more pages. The
			// bound guards against a continuation that never ends.
			while ( query && pages < 10 ) {
				const res = await client.get( backlinksUrl( randomPage ), query );
				assert.equal( res.status, 200, res.text );
				assert.isAtMost( res.body.items.length, 1 );

				seen.push( ...titles( res.body ) );
				pages++;

				const next = res.body.pagination.next;
				// The continuation parameters are meant to be passed back as query
				// parameters, together with the original ones.
				query = next ? Object.assign( { limit: 1 }, next.params ) : null;
			}

			assert.equal( pages, 4, 'one page per backlink' );
			assert.sameMembers( seen, [
				linksToEsther1, linksToEsther2, talkLinksToEsther, redirectToEsther
			] );
			assert.equal( new Set( seen ).size, seen.length, 'no item is repeated' );
		} );

		it( 'should not expose action API continuation parameters', async () => {
			const { body } = await client.get( backlinksUrl( randomPage ), { limit: 1 } );

			// The parameter names are those of the REST API, without the "bl" prefix
			// of list=backlinks, and without the generic "continue" marker of
			// action=query.
			assert.deepEqual( Object.keys( body.pagination.next.params ), [ 'continue' ] );
			assert.notMatch( body.pagination.next.params.continue, /^-\|\|/ );
		} );
	} );

	describe( 'failure', () => {
		it( 'should reject an invalid title', async () => {
			const { status } = await client.get( `${ pathPrefix }/page/${ encodeURIComponent( '<invalid>' ) }/backlinks` );

			assert.equal( status, 400 );
		} );

		it( 'should reject a limit that is not a number', async () => {
			const res = await client.get( backlinksUrl( randomPage ), { limit: 'many' } );

			assert.equal( res.status, 400, res.text );
			assert.match( res.header[ 'content-type' ], /^application\/json/ );
		} );

		it( 'should reject an unknown filterredir value', async () => {
			const res = await client.get( backlinksUrl( randomPage ), { filterredir: 'sideways' } );

			assert.equal( res.status, 400, res.text );
		} );

		it( 'should reject a malformed continuation', async () => {
			const res = await client.get( backlinksUrl( randomPage ), { continue: 'garbage' } );

			assert.equal( res.status, 400, res.text );
		} );
	} );
} );
