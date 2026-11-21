'use strict';

const { action, assert, REST, utils, wiki } = require( 'api-testing' );

const chai = require( 'chai' );
const expect = chai.expect;

const chaiResponseValidator = require( 'chai-openapi-response-validator' ).default;

const pathPrefix = '/navigation/v2-beta';
const specModule = '/navigation/v2-beta';

// NOTE: /categories and /categories/{name} correspond to list=allcategories in the
//       action API, and /categories/{name}/members to list=categorymembers.
describe( 'navigation.v2-beta categories', () => {
	// Category names, without the namespace prefix.
	const categoryName = utils.title( 'RestCategory_' );
	const subcategoryName = utils.title( 'RestSubcategory_' );
	const firstMember = utils.title( 'RestCategoryMemberA_' );
	const secondMember = utils.title( 'RestCategoryMemberB_' );
	let client, mindy;

	const categoryUrl = ( name ) => `${ pathPrefix }/categories/${ encodeURIComponent( name ) }`;
	const membersUrl = ( name ) => `${ categoryUrl( name ) }/members`;

	before( async () => {
		mindy = await action.mindy();
		client = new REST( 'rest.php', mindy );

		const specPath = '/specs/v0/module' + specModule;
		const { status, text } = await client.get( specPath );
		assert.deepEqual( status, 200 );

		chai.use( chaiResponseValidator( JSON.parse( text ) ) );

		await mindy.edit( firstMember, { text: `[[Category:${ categoryName }]]` } );
		await mindy.edit( secondMember, { text: `[[Category:${ categoryName }]]` } );
		await mindy.edit( `Category:${ subcategoryName }`, { text: `[[Category:${ categoryName }]]` } );
		// The member counts of a category are updated by a job.
		await wiki.runAllJobs();
	} );

	describe( 'GET /categories', () => {
		describe( 'successful operation', () => {
			it( 'should list the categories that match the filter, with their size by default', async () => {
				const res = await client.get( `${ pathPrefix }/categories`, { prefix: categoryName } );
				const { status, body, header } = res;

				assert.equal( status, 200, res.text );
				assert.match( header[ 'content-type' ], /^application\/json/ );

				assert.lengthOf( body.items, 1 );
				assert.sameTitle( body.items[ 0 ].category, categoryName );
				assert.equal( body.items[ 0 ].size, 3 );
				assert.equal( body.items[ 0 ].hidden, false );

				// eslint-disable-next-line no-unused-expressions
				expect( res ).to.satisfyApiSpec;
			} );

			it( 'should not offer the name parameter of the item route', async () => {
				const res = await client.get( `${ pathPrefix }/categories`, {
					prefix: categoryName, name: subcategoryName
				} );

				// The name is ignored, so the prefix filter alone applies.
				assert.equal( res.status, 200, res.text );
				assert.lengthOf( res.body.items, 1 );
				assert.sameTitle( res.body.items[ 0 ].category, categoryName );
			} );
		} );

		describe( 'pagination', () => {
			it( 'should return all categories across pages, by name, without repeats', async () => {
				const seenNames = [];
				let query = { limit: 1, prop: '' };
				let pages = 0;

				// The bound guards against a continuation that never ends.
				while ( query && pages < 5 ) {
					const res = await client.get( `${ pathPrefix }/categories`, query );
					assert.equal( res.status, 200, res.text );
					assert.isAtMost( res.body.items.length, 1 );

					seenNames.push( ...res.body.items.map( ( item ) => item.category ) );
					pages++;

					const nextLink = res.body.pagination.next;
					query = nextLink ? Object.assign( { limit: 1, prop: '' }, nextLink.params ) : null;
				}

				assert.isAtLeast( pages, 2, 'there is more than one category' );
				assert.equal( new Set( seenNames ).size, seenNames.length, 'no item is repeated' );
				assert.deepEqual( seenNames, [ ...seenNames ].sort(), 'items are ordered by name' );
			} );
		} );

		describe( 'failure', () => {
			it( 'should reject a limit that is not a number', async () => {
				const res = await client.get( `${ pathPrefix }/categories`, { limit: 'many' } );

				assert.equal( res.status, 400, res.text );
			} );
		} );
	} );

	describe( 'GET /categories/{name}', () => {
		describe( 'successful operation', () => {
			it( 'should return the category, with its size by default', async () => {
				const res = await client.get( categoryUrl( categoryName ) );
				const { status, body, header } = res;

				assert.equal( status, 200, res.text );
				assert.match( header[ 'content-type' ], /^application\/json/ );

				// The item itself, not a list of one.
				assert.notProperty( body, 'items' );
				assert.sameTitle( body.category, categoryName );
				assert.equal( body.size, 3 );
				assert.equal( body.pages, 2 );
				assert.equal( body.subcats, 1 );
				assert.equal( body.files, 0 );
				assert.equal( body.hidden, false );

				// eslint-disable-next-line no-unused-expressions
				expect( res ).to.satisfyApiSpec;
			} );

			it( 'should return the requested props only', async () => {
				const res = await client.get( categoryUrl( categoryName ), { prop: 'hidden' } );

				assert.equal( res.status, 200, res.text );
				assert.containsAllKeys( res.body, [ 'category', 'hidden' ] );
				assert.notProperty( res.body, 'size' );
			} );
		} );

		describe( 'failure', () => {
			it( 'should return 404 for a category that does not exist', async () => {
				const res = await client.get( categoryUrl( utils.title( 'RestNoSuchCategory_' ) ) );

				assert.equal( res.status, 404, res.text );
				assert.match( res.header[ 'content-type' ], /^application\/json/ );
			} );

			it( 'should reject a name that names several categories', async () => {
				const res = await client.get( categoryUrl( `${ categoryName }|${ subcategoryName }` ) );

				assert.equal( res.status, 400, res.text );
			} );
		} );
	} );

	describe( 'GET /categories/{name}/members', () => {
		describe( 'successful operation', () => {
			it( 'should list the members of the category', async () => {
				const res = await client.get( membersUrl( categoryName ) );
				const { status, body, header } = res;

				assert.equal( status, 200, res.text );
				assert.match( header[ 'content-type' ], /^application\/json/ );

				assert.sameMembers(
					body.items.map( ( item ) => utils.dbkey( item.title ) ),
					[ firstMember, secondMember, `Category:${ subcategoryName }` ].map( utils.dbkey )
				);

				// eslint-disable-next-line no-unused-expressions
				expect( res ).to.satisfyApiSpec;
			} );

			it( 'should only list members of the requested type', async () => {
				const res = await client.get( membersUrl( categoryName ), { type: 'subcat', prop: 'title|type' } );

				assert.equal( res.status, 200, res.text );
				assert.lengthOf( res.body.items, 1 );
				assert.equal( res.body.items[ 0 ].type, 'subcat' );
				assert.sameTitle( res.body.items[ 0 ].title, `Category:${ subcategoryName }` );
			} );

			it( 'should return no members for a category that does not exist', async () => {
				const res = await client.get( membersUrl( utils.title( 'RestNoSuchCategory_' ) ) );

				assert.equal( res.status, 200, res.text );
				assert.deepEqual( res.body.items, [] );
			} );
		} );

		describe( 'pagination', () => {
			it( 'should return all members across pages, without repeats', async () => {
				const seenIds = [];
				let query = { limit: 1 };
				let pages = 0;

				// The bound guards against a continuation that never ends.
				while ( query && pages < 5 ) {
					const res = await client.get( membersUrl( categoryName ), query );
					assert.equal( res.status, 200, res.text );
					assert.isAtMost( res.body.items.length, 1 );

					seenIds.push( ...res.body.items.map( ( item ) => item.pageid ) );
					pages++;

					const nextLink = res.body.pagination.next;
					query = nextLink ? Object.assign( { limit: 1 }, nextLink.params ) : null;
				}

				assert.equal( pages, 3 );
				assert.equal( new Set( seenIds ).size, 3, 'no member is repeated' );
			} );
		} );

		describe( 'failure', () => {
			it( 'should reject a name that is not a valid title', async () => {
				const res = await client.get( membersUrl( 'Bad<name>' ) );

				assert.equal( res.status, 400, res.text );
			} );

			it( 'should not offer the title parameter that the action API takes', async () => {
				const res = await client.get( membersUrl( categoryName ), { title: 'Category:Other' } );

				// The path decides the category, the parameter is ignored.
				assert.equal( res.status, 200, res.text );
				assert.lengthOf( res.body.items, 3 );
			} );
		} );
	} );
} );
