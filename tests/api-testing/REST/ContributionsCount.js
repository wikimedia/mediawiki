'use strict';
const { REST, assert, action, utils, clientFactory } = require( 'api-testing' );

describe( 'GET /contributions/count', () => {
	const basePath = 'rest.php/coredev/v0';
	let arnold, beth;
	let arnoldAction, bethAction, samAction;
	let editToDelete;

	before( async () => {
		// Sam will be the same Sam for all tests, even in other files
		samAction = await action.user( 'Sam', [ 'suppress' ] );

		// Arnold will be a different Arnold every time
		arnoldAction = await action.getAnon();
		await arnoldAction.account( 'Arnold_' );

		// Beth will be a different Beth every time
		bethAction = await action.getAnon();
		await bethAction.account( 'Beth_' );

		arnold = clientFactory.getRESTClient( basePath, arnoldAction );
		beth = clientFactory.getRESTClient( basePath, bethAction );

		const oddEditsPage = utils.title( 'UserContribution_' );
		const evenEditsPage = utils.title( 'UserContribution_' );

		// Create a tag.
		await action.makeTag( 'api-test' );

		// bob makes 1 edit
		const bobAction = await action.bob();
		await bobAction.edit( evenEditsPage, [ { summary: 'Bob made revision 1' } ] );

		// arnold makes 2 edits. 1 with a tag, 1 without.
		const tags = 'api-test';
		await arnoldAction.edit( oddEditsPage, { tags } );
		await utils.sleep();
		await arnoldAction.edit( evenEditsPage, {} );

		const pageDeleteRevs = utils.title( 'UserContribution_' );
		editToDelete = await bethAction.edit( pageDeleteRevs, [ { text: 'Beth edit 1' } ] );
		await bethAction.edit( pageDeleteRevs, [ { text: 'Beth edit 2' } ] );
	} );

	const testGetContributionsCount = async ( client, endpoint ) => {
		const { status, body } = await client.get( endpoint );
		assert.equal( status, 200 );
		assert.property( body, 'count' );

		const { count } = body;
		assert.deepEqual( count, 2 );
	};

	const testGetContributionsCountByTag = async ( client, endpoint ) => {
		const { status, body } = await client.get( endpoint, { tag: 'api-test' } );
		assert.equal( status, 200 );

		const { count } = body;
		assert.deepEqual( count, 1 );
	};

	const testSuppressedContributions = async ( client, endpoint ) => {
		const { body: body } = await client.get( endpoint );
		assert.deepEqual( body.count, 2 );

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

		// Users w/o appropriate permissions should not have suppressed revisions included in count
		const { body: suppressedRevBody } = await client.get( endpoint );
		assert.deepEqual( suppressedRevBody.count, 1 );

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

		const { body: unsuppressedRevBody } = await client.get( endpoint );
		assert.deepEqual( unsuppressedRevBody.count, 2 );
	};

	describe( 'GET /me/contributions/count', () => {
		const endpoint = '/me/contributions/count';

		it( 'Returns status 404 for anon', async () => {
			const anon = new REST( basePath );
			const response = await anon.get( endpoint );
			assert.equal( response.status, 401 );
			assert.nestedProperty( response.body, 'messageTranslations' );
		} );

		it( 'Returns status OK', async () => {
			const response = await arnold.get( endpoint );
			assert.equal( response.status, 200 );
		} );

		it( 'Returns a list of another user\'s edits', async () => {
			await testGetContributionsCount( arnold, endpoint );
		} );

		it( 'Returns edits filtered by tag', async () => {
			await testGetContributionsCountByTag( arnold, endpoint );
		} );

		it( 'Does not return suppressed contributions when requesting user does not have appropriate permissions', async () => {
			// Note that the suppressed contributions are Beth's contributions.
			await testSuppressedContributions( beth, endpoint );
		} );

	} );

	describe( 'GET /user/{user}/contributions/count', () => {
		let endpoint;
		before( () => {
			endpoint = `/user/${arnold.username}/contributions/count`;
		} );

		it( 'Returns status 404 for unknown user', async () => {
			const anon = new REST( basePath );
			const unknownUser = `Unknown ${utils.uniq()}`;
			const response = await anon.get( `/user/${unknownUser}/contributions/count` );
			assert.equal( response.status, 404 );
			assert.nestedProperty( response.body, 'messageTranslations' );
		} );

		it( 'Returns status OK', async () => {
			const response = await arnold.get( endpoint );
			assert.equal( response.status, 200 );
		} );

		it( 'Returns a list of another user\'s edits', async () => {
			await testGetContributionsCount( beth, endpoint );
		} );

		it( 'Returns edits filtered by tag', async () => {
			await testGetContributionsCountByTag( beth, endpoint );
		} );

		it( 'Does not return suppressed contributions when requesting user does not have appropriate permissions', async () => {
			// Note that the suppressed contributions are Beth's contributions.
			const bethsEndpoint = `/user/${beth.username}/contributions/count`;
			await testSuppressedContributions( arnold, bethsEndpoint );
		} );

	} );

} );
