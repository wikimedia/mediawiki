'use strict';

const { action, assert, REST, utils } = require( 'api-testing' );

let pathPrefix = 'rest.php/content/v1';

describe( 'PUT /page/{title}', () => {
	let client, mindy, mindyToken;

	before( async () => {
		mindy = await action.mindy();
		client = new REST( pathPrefix, mindy );
		mindyToken = await mindy.token();
	} );

	const checkEditResponse = function ( title, reqBody, body ) {
		assert.containsAllKeys( body, [ 'title', 'key', 'source', 'latest', 'id', 'license', 'content_model' ] );
		assert.containsAllKeys( body.latest, [ 'id', 'timestamp' ] );
		assert.nestedPropertyVal( body, 'source', reqBody.source );
		assert.nestedPropertyVal( body, 'title', title );
		assert.nestedPropertyVal( body, 'key', utils.dbkey( title ) );
		assert.isAbove( body.latest.id, 0 );

		if ( reqBody.content_model ) {
			assert.nestedPropertyVal( body, 'content_model', reqBody.content_model );
		}
	};

	const checkSourceResponse = function ( title, reqBody, body ) {
		if ( reqBody.content_model ) {
			assert.nestedPropertyVal( body, 'content_model', reqBody.content_model );
		}

		assert.nestedPropertyVal( body, 'title', title );
		assert.nestedPropertyVal( body, 'key', utils.dbkey( title ) );
		assert.nestedPropertyVal( body, 'source', reqBody.source );
	};

	describe( 'successful operation', () => {
		it( 'should create a page if it does not exist', async () => {
			const title = utils.title( 'Edit Test ' );
			const normalizedTitle = utils.dbkey( title );

			const reqBody = {
				token: mindyToken,
				source: 'Lörem Ipsüm',
				comment: 'tästing'
			};

			const { status: editStatus, body: editBody, header: editHeader } =
				await client.put( `/page/${ title }`, reqBody );
			assert.equal( editStatus, 201 );
			assert.match( editHeader[ 'content-type' ], /^application\/json/ );
			checkEditResponse( title, reqBody, editBody );

			const { status: sourceStatus, body: sourceBody, header: sourceHeader } =
				await client.get( `/page/${ normalizedTitle }` );
			assert.equal( sourceStatus, 200 );
			assert.match( sourceHeader[ 'content-type' ], /^application\/json/ );
			checkSourceResponse( title, reqBody, sourceBody );
		} );

		it( 'should create a page with specific content model', async () => {
			const title = utils.title( 'Edit Test ' );
			const normalizedTitle = utils.dbkey( title );

			// TODO: test a content model different from the default.
			const reqBody = {
				token: mindyToken,
				source: 'Lörem Ipsüm',
				comment: 'tästing',
				content_model: 'wikitext'
			};

			const { status: editStatus, body: editBody, header: editHeader } =
				await client.put( `/page/${ title }`, reqBody );
			assert.equal( editStatus, 201 );
			assert.match( editHeader[ 'content-type' ], /^application\/json/ );
			checkEditResponse( title, reqBody, editBody );

			const { status: sourceStatus, body: sourceBody, header: sourceHeader } =
				await client.get( `/page/${ normalizedTitle }` );
			assert.equal( sourceStatus, 200 );
			assert.match( sourceHeader[ 'content-type' ], /^application\/json/ );
			checkSourceResponse( title, reqBody, sourceBody );
		} );

		it( 'should update an existing page', async () => {
			const title = utils.title( 'Edit Test ' );
			const normalizedTitle = utils.dbkey( title );

			// create
			await mindy.edit( normalizedTitle, {} );
			const { body: newPage } = await client.get( `/page/${ normalizedTitle }/bare` );

			const firstRev = newPage.latest;

			const reqBody = {
				token: mindyToken,
				source: 'Lörem Ipsüm',
				comment: 'tästing',
				latest: firstRev // provide the base revision
			};
			const { status: editStatus, body: editBody, header: editHeader } =
				await client.put( `/page/${ title }`, reqBody );

			assert.equal( editStatus, 200 );
			assert.match( editHeader[ 'content-type' ], /^application\/json/ );
			checkEditResponse( title, reqBody, editBody );
			assert.isAbove( editBody.latest.id, firstRev.id );

			const { status: sourceStatus, body: sourceBody, header: sourceHeader } =
				await client.get( `/page/${ normalizedTitle }` );
			assert.equal( sourceStatus, 200 );
			assert.match( sourceHeader[ 'content-type' ], /^application\/json/ );
			checkSourceResponse( title, reqBody, sourceBody );
		} );

		it( 'should handle null-edits (unchanged content) gracefully', async () => {
			const title = utils.title( 'Edit Test ' );

			// create
			const firstRev = await mindy.edit( title, {} );

			const reqBody = {
				token: mindyToken,
				source: firstRev.param_text,
				comment: 'nothing at all changed',
				latest: { id: firstRev.newrevid }
			};
			const resp = await client.put( `/page/${ title }`, reqBody );
			const { status: editStatus, body: editBody, header: editHeader } = resp;

			assert.equal( editStatus, 200 );
			assert.match( editHeader[ 'content-type' ], /^application\/json/ );

			checkEditResponse( title, reqBody, editBody );

			// No revision was created, new ID is the same as the old ID
			assert.equal( editBody.latest.id, firstRev.newrevid );
		} );

		it( 'should automatically solve merge conflicts', async () => {
			// XXX: this test may fail if the diff3 utility is not found on the web host

			const title = utils.title( 'Edit Test ' );

			// create
			const firstRev = await mindy.edit( title, { text: 'first line\nlorem ipsum\nsecond line' } );
			await mindy.edit( title, { text: 'FIRST LINE\nlorem ipsum\nsecond line' } );

			const reqBody = {
				token: mindyToken,
				source: 'first line\nlorem ipsum\nSECOND LINE',
				comment: 'tästing',
				content_model: 'wikitext',
				latest: { id: firstRev.newrevid }
			};
			const { status: editStatus, body: editBody, header: editHeader } =
				await client.put( `/page/${ title }`, reqBody );

			assert.equal( editStatus, 200 );
			assert.match( editHeader[ 'content-type' ], /^application\/json/ );
			const expectedText = 'FIRST LINE\nlorem ipsum\nSECOND LINE';

			assert.nestedPropertyVal( editBody, 'source', expectedText );
		} );
	} );

	describe( 'request validation', () => {
		const requiredProps = [ 'source', 'comment' ];

		requiredProps.forEach( ( missingPropName ) => {
			const title = utils.title( 'Edit Test ' );
			const reqBody = {
				token: mindyToken,
				source: 'Lörem Ipsüm',
				comment: 'tästing',
				content_model: 'wikitext'
			};

			it( `should fail when ${ missingPropName } is missing from the request body`, async () => {
				const incompleteBody = { ...reqBody };
				delete incompleteBody[ missingPropName ];

				const { status: editStatus, body: editBody, header: editHeader } =
					await client.put( `/page/${ title }`, incompleteBody );

				assert.equal( editStatus, 400 );
				assert.match( editHeader[ 'content-type' ], /^application\/json/ );
				assert.nestedProperty( editBody, 'messageTranslations' );
			} );
		} );

		it( 'should fail if no token is given', async () => {
			const title = utils.title( 'Edit Test ' );
			const reqBody = {
				// no token
				source: 'Lörem Ipsüm',
				comment: 'tästing'
			};

			const { status: editStatus, body: editBody, header: editHeader } =
				await client.put( `/page/${ title }`, reqBody );

			assert.equal( editStatus, 403 );
			assert.match( editHeader[ 'content-type' ], /^application\/json/ );
			assert.nestedProperty( editBody, 'messageTranslations' );
		} );

		it( 'should fail if a bad token is given', async () => {
			const title = utils.title( 'Edit Test ' );
			const reqBody = {
				token: 'BAD',
				source: 'Lörem Ipsüm',
				comment: 'tästing'
			};

			const { status: editStatus, body: editBody, header: editHeader } =
				await client.put( `/page/${ title }`, reqBody );

			assert.equal( editStatus, 403 );
			assert.match( editHeader[ 'content-type' ], /^application\/json/ );
			assert.nestedProperty( editBody, 'messageTranslations' );
		} );

		it( 'should fail if a bad content model is given', async () => {
			const title = utils.title( 'Edit Test ' );

			const reqBody = {
				token: mindyToken,
				source: 'Lörem Ipsüm',
				comment: 'tästing',
				content_model: 'THIS DOES NOT EXIST!'
			};
			const { status: editStatus, body: editBody, header: editHeader } =
				await client.put( `/page/${ title }`, reqBody );

			assert.equal( editStatus, 400 );
			assert.match( editHeader[ 'content-type' ], /^application\/json/ );
			assert.nestedProperty( editBody, 'messageTranslations' );
		} );

		it( 'should fail if a bad title is given', async () => {
			const title = '_|_'; // not a valid page title

			const reqBody = {
				source: 'Lörem Ipsüm',
				comment: 'tästing',
				content_model: 'wikitext',
				token: mindyToken
			};
			const { status: editStatus, body: editBody, header: editHeader } =
				await client.put( `/page/${ title }`, reqBody );

			assert.equal( editStatus, 400 );
			assert.match( editHeader[ 'content-type' ], /^application\/json/ );
			assert.nestedProperty( editBody, 'messageTranslations' );
		} );

		it( 'should fail if no title is given', async () => {
			const reqBody = {
				source: 'Lörem Ipsüm',
				comment: 'tästing',
				content_model: 'wikitext',
				token: mindyToken
			};
			const { status: editStatus, body: editBody, header: editHeader } =
				await client.put( '/page/', reqBody );

			assert.equal( editStatus, 400 );
			assert.match( editHeader[ 'content-type' ], /^application\/json/ );
			assert.nestedProperty( editBody, 'messageTranslations' );
		} );
	} );

	describe( 'failures due to system state', () => {
		it( 'should detect when the target page does not exist but revision ID was given', async () => {
			const title = utils.title( 'Edit Test ' );

			const reqBody = {
				token: mindyToken,
				source: 'Lörem Ipsüm',
				comment: 'tästing',
				latest: { id: 1234 }
			};
			const { status: editStatus, body: editBody, header: editHeader } =
				await client.put( `/page/${ title }`, reqBody );

			assert.equal( editStatus, 404 );
			assert.match( editHeader[ 'content-type' ], /^application\/json/ );
			assert.nestedProperty( editBody, 'messageTranslations' );
		} );

		it( 'should detect a conflict if page exist but no revision ID was given', async () => {
			const title = utils.title( 'Edit Test ' );

			// create
			await mindy.edit( title, {} );

			const reqBody = {
				token: mindyToken,
				source: 'Lörem Ipsüm',
				comment: 'tästing'
				// not 'latest' key, so page should be created
			};
			const { status: editStatus, body: editBody, header: editHeader } =
				await client.put( `/page/${ title }`, reqBody );

			assert.equal( editStatus, 409 );
			assert.match( editHeader[ 'content-type' ], /^application\/json/ );
			assert.nestedProperty( editBody, 'messageTranslations' );
		} );

		it( 'should detect a conflict when an old base revision ID is given and conflict resolution fails', async () => {
			const title = utils.title( 'Edit Test ' );

			// create
			const firstRev = await mindy.edit( title, { text: 'initial text' } );
			await mindy.edit( title, { text: 'updated text' } );

			const reqBody = {
				token: mindyToken,
				source: 'Lörem Ipsüm',
				comment: 'tästing',
				latest: { id: firstRev.newrevid }
			};
			const { status: editStatus, body: editBody, header: editHeader } =
				await client.put( `/page/${ title }`, reqBody );

			assert.equal( editStatus, 409 );
			assert.match( editHeader[ 'content-type' ], /^application\/json/ );
			assert.nestedProperty( editBody, 'messageTranslations' );
		} );
	} );

	describe( 'permission checks', () => {
		it( 'should fail when trying to edit a protected page without appropriate permissions', async () => {
			const title = utils.title( 'Edit Test ' );

			// create a protected page
			const firstRev = await mindy.edit( title, {} );
			await mindy.action( 'protect', {
				title,
				token: await mindy.token(),
				protections: 'edit=sysop'
			}, 'POST' );

			const anon = action.getAnon();
			const reqBody = {
				token: await anon.token(),
				source: 'Lörem Ipsüm',
				comment: 'tästing',
				latest: { id: firstRev.newrevid }
			};
			const { status: editStatus, body: editBody, header: editHeader } =
				await client.put( `/page/${ title }`, reqBody );

			assert.equal( editStatus, 403 );
			assert.match( editHeader[ 'content-type' ], /^application\/json/ );
			assert.nestedProperty( editBody, 'messageTranslations' );
		} );
	} );
} );

// eslint-disable-next-line mocha/no-exports
exports.init = function ( pp ) {
	// Allow testing both legacy and module paths using the same tests
	pathPrefix = pp;
};
