'use strict';

const { action, utils, assert, wiki } = require( 'api-testing' );

describe( 'The tag action module', function () {

	const tagName = 'foo-bar';
	const tag2Name = 'foo-bar-baz';
	const reason = 'For testing tags';
	let bob;
	let revisionId, recentChangeId;
	let page, pageId;

	before( async () => {
		bob = await action.bob();
		page = utils.title( 'Tag_' );
		await action.makeTag( tagName );
		await action.makeTag( tag2Name );
		await bob.edit( page, { text: 'Revision to tag' } );
		await wiki.runAllJobs();
		const recentChange = await bob.getChangeEntry(
			{ rctitle: page }
		);
		pageId = recentChange.pageid;
		revisionId = recentChange.revid;
		recentChangeId = recentChange.rcid;
	} );

	it( 'Adds and removes tags from revision', async () => {
		// Add a tag to revision
		await bob.action( 'tag', {
			revid: revisionId,
			add: tagName,
			token: bob.tokens.csrftoken,
			reason
		}, 'POST' );

		const queryParams = {
			prop: 'revisions',
			revids: revisionId,
			rvprop: 'tags'
		};

		// Query to make sure tag was saved
		const { query: queryWithTags } = await bob.action( 'query', queryParams );

		const { revisions: revsWithTags } = queryWithTags.pages[ pageId ];
		assert.deepEqual( revsWithTags[ 0 ].tags, [ tagName ] );

		// Remove tag
		await bob.action( 'tag', {
			revid: revisionId,
			remove: tagName,
			token: bob.tokens.csrftoken
		}, 'POST' );
		const { query: queryWithoutTags } = await bob.action( 'query', queryParams );
		const { revisions: revsWithoutTags } = queryWithoutTags.pages[ pageId ];

		// Query to make sure no tag remains
		assert.deepEqual( revsWithoutTags[ 0 ].tags, [] );
	} );

	it( 'Adds and removes tags from recent change', async () => {
		// Add 2 tags to recent change
		await bob.action( 'tag', {
			rcid: recentChangeId,
			add: `${tagName}|${tag2Name}`,
			token: bob.tokens.csrftoken,
			reason
		}, 'POST' );

		const queryParams = {
			rctitle: page,
			rcprop: 'tags'
		};

		// Query to make sure tag was saved
		const recentChangeWithTag = await bob.list( 'recentchanges', queryParams );
		assert.deepEqual( recentChangeWithTag[ 0 ].tags, [ tagName, tag2Name ] );

		// Remove 1 of the tags
		await bob.action( 'tag', {
			rcid: recentChangeId,
			remove: tagName,
			token: bob.tokens.csrftoken
		}, 'POST' );

		// Query to make sure only 1 tag remains
		const recentChangeWithoutTag = await bob.list( 'recentchanges', queryParams );
		assert.deepEqual( recentChangeWithoutTag[ 0 ].tags, [ tag2Name ] );
	} );

} );
