'use strict';

const { action, assert, REST, utils } = require( 'api-testing' );
const supertest = require( 'supertest' );

describe( 'POST /page', () => {
	// NOTE: the /page/{title} endpoint (PageSourceHandler) has to go to v1 first!
	const client = new REST();
	let mindy, anon, anonToken;

	before( async () => {
		mindy = await action.mindy();

		// NOTE: this only works because the same token is shared by all anons.
		// TODO: add support for login and tokens to RESTClient.
		anon = action.getAnon();
		anonToken = await anon.token();
	} );

	const checkEditResponse = function ( title, reqBody, body ) {
		assert.containsAllKeys( body, [ 'title', 'key', 'source', 'latest', 'id',
			'license', 'content_model' ] );
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
			const titleSuffix = utils.title();
			const title = 'A B+C:D@E-' + titleSuffix;

			// In "title style" encoding, spaces turn to underscores,
			// colons are preserved, and slashes and pluses get encoded.
			// FIXME: correct handling of encoded slashes depends on
			//        the server setup and can't be tested reliably.
			const encodedTitle = 'A_B%2BC:D@E-' + titleSuffix;

			const reqBody = {
				token: anonToken,
				source: 'Lörem Ipsüm',
				comment: 'tästing',
				title
			};

			const { status: editStatus, body: editBody, header } =
				await client.post( '/page', reqBody );
			assert.equal( editStatus, 201 );

			assert.nestedProperty( header, 'location' );
			const location = header.location;
			assert.match( location, new RegExp( `^https?://.*/v1/page/${encodedTitle}$` ) );
			checkEditResponse( title, reqBody, editBody );

			// follow redirect
			const { status: redirStatus, body: redirBody } = await supertest.agent( location ).get( '' );
			assert.equal( redirStatus, 200 );
			checkSourceResponse( title, reqBody, redirBody );

			// construct request to fetch content
			const { status: sourceStatus, body: sourceBody } = await client.get( `/page/${title}` );
			assert.equal( sourceStatus, 200 );
			checkSourceResponse( title, reqBody, sourceBody );
		} );

		it( 'should create a page with specified model', async () => {
			const title = utils.title( 'Edit Test ' );

			// TODO: Test with a model different from the default. This however requires
			//       the changecontentmodel permission, which anons don't have.
			const reqBody = {
				token: anonToken,
				source: 'Lörem Ipsüm',
				comment: 'tästing',
				content_model: 'wikitext',
				title
			};

			const { status: editStatus, body: editBody } = await client.post( '/page', reqBody );
			assert.equal( editStatus, 201 );
			checkEditResponse( title, reqBody, editBody );

			const { status: sourceStatus, body: sourceBody } = await client.get( `/page/${title}` );
			assert.equal( sourceStatus, 200 );
			checkSourceResponse( title, reqBody, sourceBody );
		} );
	} );

	describe( 'request validation', () => {
		const requiredProps = [ 'source', 'comment', 'title' ];

		requiredProps.forEach( ( missingPropName ) => {
			const title = utils.title( 'Edit Test ' );
			const reqBody = {
				token: anonToken,
				source: 'Lörem Ipsüm',
				comment: 'tästing',
				title
			};

			it( `should fail when ${missingPropName} is missing from the request body`, async () => {
				const incompleteBody = { ...reqBody };
				delete incompleteBody[ missingPropName ];

				const { status: editStatus, body: editBody } =
					await client.post( '/page', incompleteBody );

				assert.equal( editStatus, 400 );
				assert.nestedProperty( editBody, 'messageTranslations' );
			} );
		} );

		it( 'should fail if no token is given', async () => {
			const title = utils.title( 'Edit Test ' );
			const reqBody = {
				// no token
				source: 'Lörem Ipsüm',
				comment: 'tästing',
				title
			};

			const { status: editStatus, body: editBody } = await client.post( '/page', reqBody );

			assert.equal( editStatus, 400 );
			assert.nestedProperty( editBody, 'messageTranslations' );
		} );

		it( 'should fail if a bad token is given', async () => {
			const title = utils.title( 'Edit Test ' );
			const reqBody = {
				token: 'BAD',
				source: 'Lörem Ipsüm',
				comment: 'tästing',
				title
			};

			const { status: editStatus, body: editBody } = await client.post( '/page', reqBody );

			assert.equal( editStatus, 403 );
			assert.nestedProperty( editBody, 'messageTranslations' );
		} );

		it( 'should fail if a bad content model is given', async () => {
			const title = utils.title( 'Edit Test ' );

			const reqBody = {
				token: anonToken,
				source: 'Lörem Ipsüm',
				comment: 'tästing',
				content_model: 'THIS DOES NOT EXIST!',
				title
			};
			const { status: editStatus, body: editBody } = await client.post( '/page', reqBody );

			assert.equal( editStatus, 400 );
			assert.nestedProperty( editBody, 'messageTranslations' );
		} );

		it( 'should fail if a bad title is given', async () => {
			const title = '_|_'; // not a valid page title

			const reqBody = {
				token: anonToken,
				source: 'Lörem Ipsüm',
				comment: 'tästing',
				title
			};
			const { status: editStatus, body: editBody } = await client.post( '/page', reqBody );

			assert.equal( editStatus, 400 );
			assert.nestedProperty( editBody, 'messageTranslations' );
		} );
	} );

	describe( 'failures due to system state', () => {
		it( 'should detect a conflict if page exist', async () => {
			const title = utils.title( 'Edit Test ' );

			// create
			await mindy.edit( title, {} );

			const reqBody = {
				token: anonToken,
				source: 'Lörem Ipsüm',
				comment: 'tästing',
				title
			};
			const { status: editStatus, body: editBody } = await client.post( '/page', reqBody );

			assert.equal( editStatus, 409 );
			assert.nestedProperty( editBody, 'messageTranslations' );
		} );
	} );

	describe( 'permission checks', () => {
		it( 'should fail when trying to create a protected title without appropriate permissions', async () => {
			const title = utils.title( 'Edit Test ' );

			// protected a non-existing title against creation
			await mindy.action( 'protect', {
				title,
				token: await mindy.token(),
				protections: 'create=sysop'
			}, 'POST' );

			const reqBody = {
				token: anonToken,
				source: 'Lörem Ipsüm',
				comment: 'tästing',
				title
			};
			const { status: editStatus, body: editBody } = await client.post( '/page', reqBody );

			assert.equal( editStatus, 403 );
			assert.nestedProperty( editBody, 'messageTranslations' );
		} );

	} );
} );
