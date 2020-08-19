'use strict';
const { REST, assert, action, utils, clientFactory } = require( 'api-testing' );

describe( 'GET contributions', () => {
	const basePath = 'rest.php/coredev/v0';
	const anon = new REST( basePath );
	const limit = 2;
	const arnoldsRevisions = [];
	const arnoldsEdits = [];
	const arnoldsTags = [];
	let arnold, beth, mindy;
	let arnoldAction, samAction, mindyAction;
	const revisionText = { 0: '12345678', 1: 'A', 2: 'ABCD', 3: 'AB', 4: 'ABCDEFGH', 5: 'A' };
	const expectedRevisionDeltas = { 1: 1, 2: -4, 3: 1, 4: 4, 5: -1 };
	let editToDelete;

	before( async () => {
		// Sam will be the same Sam for all tests, even in other files
		samAction = await action.user( 'Sam', [ 'suppress' ] );

		// Arnold will be a different Arnold every time
		arnoldAction = await action.getAnon();
		await arnoldAction.account( 'Arnold_' );

		// Beth will be a different Beth every time
		const bethAction = await action.getAnon();
		await bethAction.account( 'Beth_' );

		arnold = clientFactory.getRESTClient( basePath, arnoldAction );
		mindy = clientFactory.getRESTClient( basePath, mindyAction );
		beth = clientFactory.getRESTClient( basePath, bethAction );

		const oddEditsPage = utils.title( 'UserContribution_' );
		const evenEditsPage = utils.title( 'UserContribution_' );

		// Create a tag.
		const tag = 'user-contribs-api-test';
		const tagDisplay = 'Api Test Display Text';
		await action.makeTag( tag, `''${tagDisplay}''` );

		// Beth makes 2 edits, the first one is later suppressed
		const pageToDelete = utils.title( 'UserContribution_' );
		editToDelete = await bethAction.edit( pageToDelete, [ { text: 'Beth edit 1' } ] );
		await bethAction.edit( pageToDelete, [ { text: 'Beth edit 2' } ] );

		// Arnold makes 5 edits
		let page;
		for ( let i = 1; i <= 5; i++ ) {
			const oddEdit = i % 2;
			const tags = oddEdit ? tag : null;
			page = oddEdit ? oddEditsPage : evenEditsPage;
			arnoldsTags[ i ] = oddEdit ? [ { name: tag, description: `<i>${tagDisplay}</i>` } ] : [];
			const revData = await arnoldAction.edit( page, { text: revisionText[ i ], tags } );
			await utils.sleep();
			arnoldsRevisions[ revData.newrevid ] = revData;
			arnoldsEdits[ i ] = revData;
		}
	} );

	const testGetEdits = async ( client, endpoint ) => {
		const { status, body } = await client.get( endpoint, { limit } );
		assert.equal( status, 200 );

		// assert body has property contributions
		assert.property( body, 'contributions' );
		const { contributions } = body;

		// assert body.contributions is array
		assert.isArray( contributions );

		// assert body.contributions length is limit
		assert.lengthOf( contributions, limit );

		const lastRevision = arnoldsRevisions[ arnoldsRevisions.length - 1 ];

		// assert body.contributions object schema is correct
		assert.hasAllDeepKeys( contributions[ 0 ], [
			'id', 'comment', 'timestamp', 'delta', 'size', 'page', 'tags', 'type'
		] );

		assert.equal( contributions[ 0 ].page.key, utils.dbkey( lastRevision.title ) );
		assert.equal( contributions[ 0 ].page.title, lastRevision.title );
		assert.equal( contributions[ 0 ].comment, lastRevision.param_summary );
		assert.equal( contributions[ 0 ].timestamp, lastRevision.newtimestamp );
		assert.equal( contributions[ 0 ].size, revisionText[ 5 ].length );
		assert.equal( contributions[ 0 ].type, 'revision' );
		assert.equal( contributions[ 0 ].delta, expectedRevisionDeltas[ 5 ] );
		assert.isOk( Date.parse( contributions[ 0 ].timestamp ) );
		assert.isNotOk( Date.parse( 'xyz' ) );
		assert.isArray( contributions[ 0 ].tags );

		assert.isAbove( Date.parse( contributions[ 0 ].timestamp ),
			Date.parse( contributions[ 1 ].timestamp ) );

		assert.equal( contributions[ 1 ].size, revisionText[ 4 ].length );
		assert.equal( contributions[ 1 ].delta, expectedRevisionDeltas[ 4 ] );

		// assert body.contributions contains edits only by one user
		contributions.forEach( ( rev ) => {
			assert.property( arnoldsRevisions, rev.id );
		} );
	};

	const testGetEditsByTag = async ( client, endpoint ) => {
		const taggedRevisions = [ arnoldsEdits[ 1 ], arnoldsEdits[ 3 ], arnoldsEdits[ 5 ] ];

		const { status, body } = await client.get( endpoint, { tag: 'user-contribs-api-test' } );
		assert.equal( status, 200 );

		// assert body has property contributions
		assert.property( body, 'contributions' );
		const { contributions } = body;

		// assert body.contributions length
		assert.lengthOf( contributions, taggedRevisions.length );

		// assert that there are no more contributions found
		assert.propertyVal( body, 'older', null );

		// assert body.contributions has the correct content
		assert.equal( contributions[ 0 ].id, arnoldsEdits[ 5 ].newrevid );
		assert.equal( contributions[ 1 ].id, arnoldsEdits[ 3 ].newrevid );
		assert.equal( contributions[ 2 ].id, arnoldsEdits[ 1 ].newrevid );
	};

	const testPagingForward = async ( client, endpoint ) => {
		// get latest segment
		const { body: latestSegment } = await client.get( endpoint, { limit } );
		assert.property( latestSegment, 'older' );
		assert.property( latestSegment, 'contributions' );
		assert.isArray( latestSegment.contributions );
		assert.lengthOf( latestSegment.contributions, 2 );

		// assert body.contributions has the correct content
		assert.equal( latestSegment.contributions[ 0 ].id, arnoldsEdits[ 5 ].newrevid );
		assert.equal( latestSegment.contributions[ 1 ].id, arnoldsEdits[ 4 ].newrevid );

		// Check whether the tags we applied manually are present.
		// MediaWiki can add additional software tags (such as mw-manual-revert),
		// hence the inclusion check and not equality check.
		const latestSegmentTag = latestSegment.contributions[ 0 ].tags.find( ( tag ) => tag.name === 'user-contribs-api-test' );
		assert.propertyVal( latestSegmentTag, 'description', arnoldsTags[ 5 ][ 0 ].description );

		const latestSegmentTag2 = latestSegment.contributions[ 1 ].tags.find( ( tag ) => tag.name === 'user-contribs-api-test' );
		assert.isUndefined( latestSegmentTag2 );

		// get older segment, using full url
		const req = clientFactory.getHttpClient( client );

		const { body: olderSegment } = await req.get( latestSegment.older );
		assert.property( olderSegment, 'older' );
		assert.property( olderSegment, 'contributions' );
		assert.isArray( olderSegment.contributions );
		assert.lengthOf( olderSegment.contributions, 2 );

		// assert body.contributions has the correct content
		assert.equal( olderSegment.contributions[ 0 ].id, arnoldsEdits[ 3 ].newrevid );
		assert.equal( olderSegment.contributions[ 1 ].id, arnoldsEdits[ 2 ].newrevid );

		// ensure first edit has tags and correct property values
		const olderSegmentTag = olderSegment.contributions[ 0 ].tags.find( ( tag ) => tag.name === 'user-contribs-api-test' );
		assert.propertyVal( olderSegmentTag, 'description', arnoldsTags[ 3 ][ 0 ].description );

		// ensure second edit does not have tags
		const olderSegmentTag2 = latestSegment.contributions[ 1 ].tags.find( ( tag ) => tag.name === 'user-contribs-api-test' );
		assert.isUndefined( olderSegmentTag2 );

		// get the next older segment
		const { body: finalSegment } = await req.get( olderSegment.older );
		assert.propertyVal( finalSegment, 'older', null );
		assert.property( finalSegment, 'contributions' );
		assert.isArray( finalSegment.contributions );
		assert.lengthOf( finalSegment.contributions, 1 );

		// assert body.contributions has the correct content
		assert.equal( finalSegment.contributions[ 0 ].id, arnoldsEdits[ 1 ].newrevid );

		const finalSegmentTags = olderSegment.contributions[ 0 ].tags.find( ( tag ) => tag.name === 'user-contribs-api-test' );
		assert.propertyVal( finalSegmentTags, 'description', arnoldsTags[ 1 ][ 0 ].description );
	};

	const testPagingBackwards = async ( client, endpoint ) => {
		const req = clientFactory.getHttpClient( client );

		// get latest segment
		const { body: latestSegment } = await client.get( endpoint, { limit } );
		assert.property( latestSegment, 'newer' );

		// get next older segment
		const { body: olderSegment } = await req.get( latestSegment.older );
		assert.property( olderSegment, 'newer' );

		// get the final segment
		const { body: finalSegment } = await req.get( olderSegment.older );
		assert.property( finalSegment, 'newer' );

		// Follow the chain of "newer" links back to the latest segment
		const { body: olderSegment2 } = await req.get( finalSegment.newer );
		assert.deepEqual( olderSegment, olderSegment2 );

		const { body: latestSegment2 } = await req.get( olderSegment.newer );
		assert.deepEqual( latestSegment, latestSegment2 );
	};

	const testHasLatest = async ( client, endpoint ) => {
		const req = clientFactory.getHttpClient( client );

		// get latest segment
		const { body: latestSegment } = await client.get( endpoint, { limit } );
		assert.property( latestSegment, 'latest' );

		// get next older segment
		const { body: olderSegment } = await req.get( latestSegment.older );
		assert.property( olderSegment, 'latest' );

		// get the final segment
		const { body: finalSegment } = await req.get( olderSegment.older );
		assert.property( finalSegment, 'latest' );

		// Follow all the "newer" links
		const { body: latestSegment2 } = await req.get( latestSegment.latest );
		assert.deepEqual( latestSegment, latestSegment2 );

		assert.deepEqual( latestSegment.latest, finalSegment.latest );
		assert.deepEqual( latestSegment.latest, olderSegment.latest );

	};

	const testPreserveTagFilter = async ( client, endpoint ) => {
		const req = clientFactory.getHttpClient( client );

		// get latest segment
		const { body: latestSegment } = await client.get( endpoint, { limit: 2, tag: 'user-contribs-api-test' } );

		// assert body.contributions has latest contributions with
		// "user-contribs-api-test" tag (odd edits)
		assert.equal( latestSegment.contributions[ 0 ].id, arnoldsEdits[ 5 ].newrevid );
		assert.equal( latestSegment.contributions[ 1 ].id, arnoldsEdits[ 3 ].newrevid );

		// get the final segment
		const { body: finalSegment } = await req.get( latestSegment.older );

		// assert body.contributions has oldest contributions with
		// "user-contribs-api-test" tag (odd edits)
		assert.equal( finalSegment.contributions[ 0 ].id, arnoldsEdits[ 1 ].newrevid );

		const { body: latestSegment2 } = await req.get( finalSegment.newer );
		assert.deepEqual( latestSegment, latestSegment2 );

		const { body: latestSegment3 } = await req.get( latestSegment.latest );
		assert.deepEqual( latestSegment, latestSegment3 );

		// assert that the "latest" links also preserve the "tag" parameter
		assert.deepEqual( finalSegment.latest, latestSegment.latest );
	};

	const testSuppressedRevisions = async ( client, endpoint ) => {
		await samAction.action( 'revisiondelete',
			{
				type: 'revision',
				token: await samAction.token(),
				target: editToDelete.title,
				hide: 'content|comment|user',
				ids: editToDelete.newrevid
			},
			'POST'
		);

		// Users w/o appropriate permissions can't see suppressed contributions (even their own)
		const { body: clientGetBody } = await client.get( endpoint );
		assert.lengthOf( clientGetBody.contributions, 1 );

		await samAction.action( 'revisiondelete',
			{
				type: 'revision',
				token: await samAction.token(),
				target: editToDelete.title,
				show: 'content|comment|user',
				ids: editToDelete.newrevid
			},
			'POST'
		);

		const { body: clientGetBody2 } = await client.get( endpoint );
		assert.lengthOf( clientGetBody2.contributions, 2 );
	};

	describe( 'GET /me/contributions', () => {
		const endpoint = '/me/contributions';

		it( 'Returns status 401 for anon', async () => {
			const response = await anon.get( endpoint );
			assert.equal( response.status, 401 );
			assert.nestedProperty( response.body, 'messageTranslations' );
		} );

		it( 'Returns status OK', async () => {
			const response = await arnold.get( endpoint );
			assert.equal( response.status, 200 );
		} );

		it( 'Returns 400 if segment size is out of bounds', async () => {
			const { status: minLimitStatus } = await arnold.get( endpoint, { limit: 0 } );
			assert.equal( minLimitStatus, 400 );

			const { status: maxLimitStatus } = await arnold.get( endpoint, { limit: 30 } );
			assert.equal( maxLimitStatus, 400 );
		} );

		it( 'Returns a list of the user\'s own edits', async () => {
			await testGetEdits( arnold, endpoint );
		} );

		it( 'Returns edits filtered by tag', async () => {
			await testGetEditsByTag( arnold, endpoint );
		} );

		it( 'Can fetch a chain of segments following the "older" field in the response', async () => {
			await testPagingForward( arnold, endpoint );
		} );

		it( 'Can fetch a chain of segments following the "newer" field in the response', async () => {
			await testPagingBackwards( arnold, endpoint );
		} );

		it( 'Returns a valid link to the latest segment', async () => {
			await testHasLatest( arnold, endpoint );
		} );

		it( 'Does not return suppressed contributions when requesting user does not have appropriate permissions', async () => {
			// Note that the suppressed contributions are Beth's contributions.
			await testSuppressedRevisions( beth, endpoint );
		} );

		it( 'Segment link preserves tag filtering', async () => {
			await testPreserveTagFilter( arnold, endpoint );
		} );
	} );

	describe( 'GET /user/{name}/contributions', () => {
		let endpoint;

		before( () => {
			endpoint = `/user/${arnold.username}/contributions`;
		} );

		it( 'Returns 400 if segment size is out of bounds', async () => {
			const { status: minLimitStatus } = await arnold.get( endpoint, { limit: 0 } );
			assert.equal( minLimitStatus, 400 );

			const { status: maxLimitStatus } = await arnold.get( endpoint, { limit: 30 } );
			assert.equal( maxLimitStatus, 400 );
		} );

		it( 'Returns 400 if user name is invalid', async () => {
			const xyzzy = '|||'; // an invalid user name
			const xendpoint = `/user/${xyzzy}/contributions`;
			const response = await anon.get( xendpoint );
			assert.equal( response.status, 400 );
		} );

		it( 'Returns 400 if user name is empty', async () => {
			const xendpoint = '/user//contributions';
			const response = await anon.get( xendpoint );
			assert.equal( response.status, 400 );
		} );

		it( 'Returns 404 if user is unknown', async () => {
			const xyzzy = utils.uniq(); // a non-existing user name
			const xendpoint = `/user/${xyzzy}/contributions`;
			const response = await anon.get( xendpoint );
			assert.equal( response.status, 404 );
		} );

		it( 'Returns 200 if user is an IP address', async () => {
			const xyzzy = '127.111.222.111';
			const xendpoint = `/user/${xyzzy}/contributions`;
			const response = await anon.get( xendpoint );
			assert.equal( response.status, 200 );

			assert.property( response.body, 'contributions' );
			assert.deepEqual( response.body.contributions, [] );
		} );

		it( 'Anon gets a list of arnold\'s edits', async () => {
			await testGetEdits( anon, endpoint );
		} );

		it( 'Returns Arnold\'s edits filtered by tag', async () => {
			await testGetEditsByTag( anon, endpoint );
		} );

		it( 'Arnold gets a list of arnold\'s edits', async () => {
			await testGetEdits( arnold, endpoint );
		} );

		it( 'Mindy gets a list of arnold\'s edits', async () => {
			await testGetEdits( mindy, endpoint );
		} );

		it( 'Can fetch a chain of segments following the "older" field in the response', async () => {
			await testPagingForward( anon, endpoint );
		} );

		it( 'Can fetch a chain of segments following the "newer" field in the response', async () => {
			await testPagingBackwards( anon, endpoint );
		} );

		it( 'Returns a valid link to the latest segment', async () => {
			await testHasLatest( anon, endpoint );
		} );

		it( 'Does not return suppressed contributions when requesting user does not have appropriate permissions', async () => {
			// Note that the suppressed contributions are Beth's contributions.
			const bethsEndpoint = `/user/${beth.username}/contributions`;
			await testSuppressedRevisions( anon, bethsEndpoint );
		} );

		it( 'Segment link preserves tag filtering', async () => {
			await testPreserveTagFilter( anon, endpoint );
		} );
	} );

} );
