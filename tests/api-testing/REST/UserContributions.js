'use strict';
const { REST, assert, action, utils, clientFactory } = require( 'api-testing' );

describe( 'GET /me/contributions', () => {
	const basePath = 'rest.php/coredev/v0';
	const anon = new REST( basePath );
	const limit = 2;
	const arnoldsRevisions = [];
	const arnoldsEdits = [];
	const arnoldsTags = [];
	let arnold;
	let arnoldAction;
	let samAction;

	before( async () => {
		const bobAction = await action.bob();
		samAction = await action.user( 'sam', [ 'suppress' ] );
		arnoldAction = await action.user( 'arnold' );
		arnold = clientFactory.getRESTClient( basePath, arnoldAction );

		let page = utils.title( 'UserContribution_' );

		// create a tag
		await action.makeTag( 'api-test' );

		// bob makes 1 edit
		await bobAction.edit( page, [ { text: 'Bob revision 1', summary: 'Bob made revision 1' } ] );

		for ( let i = 1; i <= 5; i++ ) {
			// tag odd edits
			const tags = i % 2 ? 'api-test' : null;
			arnoldsTags[ i ] = tags ? tags.split( '|' ) : [];

			const revData = await arnoldAction.edit( page, { text: `arnold revision ${i}`, tags } );
			await utils.sleep();
			arnoldsRevisions[ revData.newrevid ] = revData;
			arnoldsEdits[ i ] = revData;
			page = utils.title( 'UserContribution_' );
		}
	} );

	it( 'Returns status 401 for anon', async () => {
		const { status, body } = await anon.get( '/me/contributions' );
		assert.equal( status, 401 );
		assert.nestedProperty( body, 'messageTranslations' );
	} );

	it( 'Returns status OK', async () => {
		const response = await arnold.get( '/me/contributions' );
		assert.equal( response.status, 200 );
	} );

	it( 'Returns a list of arnold\'s edits', async () => {
		const { status, body } = await arnold.get( `/me/contributions?limit=${limit}` );
		assert.equal( status, 200 );

		// assert body has property revisions
		assert.property( body, 'revisions' );
		const { revisions } = body;

		// assert body.revisions is array
		assert.isArray( revisions );

		// assert body.revisions length is limit
		assert.lengthOf( revisions, limit );

		const lastRevision = arnoldsRevisions[ arnoldsRevisions.length - 1 ];

		// assert body.revisions object schema is correct
		assert.hasAllDeepKeys( revisions[ 0 ], [
			'id', 'comment', 'timestamp', 'size', 'page', 'tags'
		] );

		assert.equal( revisions[ 0 ].page.key, utils.dbkey( lastRevision.title ) );
		assert.equal( revisions[ 0 ].page.title, lastRevision.title );
		assert.equal( revisions[ 0 ].comment, lastRevision.param_summary );
		assert.equal( revisions[ 0 ].timestamp, lastRevision.newtimestamp );
		assert.isOk( Date.parse( revisions[ 0 ].timestamp ) );
		assert.isNotOk( Date.parse( 'xyz' ) );

		assert.isArray( revisions[ 0 ].tags );

		assert.isAbove( Date.parse( revisions[ 0 ].timestamp ),
			Date.parse( revisions[ 1 ].timestamp ) );

		// assert body.revisions contains edits only by one user
		revisions.forEach( ( rev ) => {
			assert.property( arnoldsRevisions, rev.id );
		} );
	} );

	it( 'Can fetch a chain of segments following the "older" field in the response', async () => {
		// get latest segment
		const { body: latestSegment } = await arnold.get( `/me/contributions?limit=${limit}` );
		assert.property( latestSegment, 'older' );
		assert.property( latestSegment, 'revisions' );
		assert.isArray( latestSegment.revisions );
		assert.lengthOf( latestSegment.revisions, 2 );

		// assert body.revisions has the correct content
		assert.equal( latestSegment.revisions[ 0 ].id, arnoldsEdits[ 5 ].newrevid );
		assert.equal( latestSegment.revisions[ 1 ].id, arnoldsEdits[ 4 ].newrevid );

		assert.deepEqual( latestSegment.revisions[ 0 ].tags, arnoldsTags[ 5 ] );
		assert.deepEqual( latestSegment.revisions[ 1 ].tags, arnoldsTags[ 4 ] );

		// get older segment, using full url
		const req = clientFactory.getHttpClient( arnold );

		const { body: olderSegment } = await req.get( latestSegment.older );
		assert.property( olderSegment, 'older' );
		assert.property( olderSegment, 'revisions' );
		assert.isArray( olderSegment.revisions );
		assert.lengthOf( olderSegment.revisions, 2 );

		// assert body.revisions has the correct content
		assert.equal( olderSegment.revisions[ 0 ].id, arnoldsEdits[ 3 ].newrevid );
		assert.equal( olderSegment.revisions[ 1 ].id, arnoldsEdits[ 2 ].newrevid );

		assert.deepEqual( olderSegment.revisions[ 0 ].tags, arnoldsTags[ 3 ] );
		assert.deepEqual( olderSegment.revisions[ 1 ].tags, arnoldsTags[ 2 ] );

		// get the next older segment
		const { body: finalSegment } = await req.get( olderSegment.older );
		assert.propertyVal( finalSegment, 'older', null );
		assert.property( finalSegment, 'revisions' );
		assert.isArray( finalSegment.revisions );
		assert.lengthOf( finalSegment.revisions, 1 );

		// assert body.revisions has the correct content
		assert.equal( finalSegment.revisions[ 0 ].id, arnoldsEdits[ 1 ].newrevid );

		assert.deepEqual( finalSegment.revisions[ 0 ].tags, arnoldsTags[ 1 ] );
	} );

	it( 'Returns 400 if segment size is out of bounds', async () => {
		const { status: minLimitStatus } = await arnold.get( '/me/contributions?limit=0' );
		assert.equal( minLimitStatus, 400 );

		const { status: maxLimitStatus } = await arnold.get( '/me/contributions?limit=30' );
		assert.equal( maxLimitStatus, 400 );
	} );

	it( 'Can fetch a chain of segments following the "newer" field in the response', async () => {
		const req = clientFactory.getHttpClient( arnold );

		// get latest segment
		const { body: latestSegment } = await arnold.get( `/me/contributions?limit=${limit}` );
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
	} );

	it( 'Returns a valid link to the latest segment', async () => {
		const req = clientFactory.getHttpClient( arnold );

		// get latest segment
		const { body: latestSegment } = await arnold.get( `/me/contributions?limit=${limit}` );
		assert.property( latestSegment, 'latest' );

		// get next older segment
		const { body: olderSegment } = await req.get( latestSegment.older );
		assert.property( olderSegment, 'latest' );

		// get the final segment
		const { body: finalSegment } = await req.get( olderSegment.older );
		assert.property( finalSegment, 'latest' );

		// Follow all the "newer" links
		const { body: latestSegment2 } = await req.get( finalSegment.latest );
		assert.deepEqual( latestSegment, latestSegment2 );

		const { body: latestSegment3 } = await req.get( olderSegment.latest );
		assert.deepEqual( latestSegment, latestSegment3 );

		const { body: latestSegment4 } = await req.get( finalSegment.latest );
		assert.deepEqual( latestSegment, latestSegment4 );
	} );

	it( 'Does not return suppressed revisions when requesting user does not have appropriate permissions', async () => {
		const { body: preDeleteBody } = await arnold.get( '/me/contributions?limit=10' );
		assert.lengthOf( preDeleteBody.revisions, 5 );

		const pageToDelete = utils.title( 'UserContribution_' );
		const editToDelete = await arnoldAction.edit( pageToDelete, [ { text: 'Delete me 1' } ] );
		await arnoldAction.edit( pageToDelete, [ { text: 'Delete me 2' } ] );

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

		// Users without appropriate permissions cannot see suppressed revisions (even their own)
		const { body: arnoldGetBody } = await arnold.get( '/me/contributions?limit=10' );
		assert.lengthOf( arnoldGetBody.revisions, 6 );

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

		// Users with appropriate permissions can see suppressed revisions
		const { body: arnoldGetBody2 } = await arnold.get( '/me/contributions?limit=10' );
		assert.lengthOf( arnoldGetBody2.revisions, 7 );
	} );

} );
