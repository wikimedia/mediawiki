'use strict';

const { assert, action, utils } = require( 'api-testing' );

describe( 'Testing default autopatrolling rights', () => {
	const anonymousUser = action.getAnon();
	let title, mindy;

	before( async () => {
		title = await utils.title( 'Autopatrol_' );
		mindy = await action.mindy();
	} );

	it( 'should edit page as a user in autopatrol group', async () => {
		await mindy.edit( title, { text: 'Is this page autopatrolled?' } );
	} );

	it( 'should edit page as an anonymous user', async () => {
		await anonymousUser.edit( title, { text: 'Anonymous: Is this page autopatrolled?' } );
	} );

	it( 'should confirm autopatrolling rights', async () => {
		const anonUserInfo = await anonymousUser.meta( 'userinfo', {} );
		const result = await mindy.list( 'recentchanges', { rctitle: title, rcprop: 'patrolled|user' } );

		assert.lengthOf( result, 2 );

		// Test different properties if Temporary Accounts is enabled or not
		const siteInfoQuery = await anonymousUser.action(
			'query',
			// fetch flag $wgAutoCreateTempUser['enabled'], and format to
			// true/false for convenience
			{ meta: 'siteinfo', siprop: 'autocreatetempuser', formatversion: 2 }
		);
		if ( siteInfoQuery.query.autocreatetempuser.enabled ) {
			assert.sameDeepMembers( result, [
				{ temp: '', type: 'edit', unpatrolled: '', user: anonUserInfo.name },
				{ type: 'new', patrolled: '', autopatrolled: '', user: mindy.username }
			] );
		} else {
			assert.sameDeepMembers( result, [
				{ anon: '', type: 'edit', unpatrolled: '', user: anonUserInfo.name },
				{ type: 'new', patrolled: '', autopatrolled: '', user: mindy.username }
			] );
		}
	} );
} );
