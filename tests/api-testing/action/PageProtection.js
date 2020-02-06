const { action, assert, utils } = require( 'api-testing' );

describe( 'Test page protection levels and effectiveness', function () {
	// users
	let admin, wikiUser;
	const anonymousUser = action.getAnon();

	const protectedPage = utils.title( 'Protected_' );
	const semiProtectedPage = utils.title( 'SemiProtected_' );

	before( async () => {
		admin = await action.mindy();
		wikiUser = await action.alice();

		// Get edit token for admin
		const adminEditToken = await admin.token();

		// Create Protected page
		await admin.edit( protectedPage, { text: 'Protected Page' } );

		// Create SemiProtected page
		await admin.edit( semiProtectedPage, { text: 'Semi Protected Page' } );

		// Add edit protections to only allow members of sysop group to edit Protected page
		const addSysopProtection = await admin.action( 'protect', { title: protectedPage, token: adminEditToken, protections: 'edit=sysop' }, 'POST' );
		assert.equal( addSysopProtection.protect.protections[ 0 ].edit, 'sysop' );

		// Add edit protections to only allow auto confirmed users to edit Semi Protected page
		const addAutoConfirmedProtection = await admin.action( 'protect', { title: semiProtectedPage, token: adminEditToken, protections: 'edit=autoconfirmed' }, 'POST' );
		assert.equal( addAutoConfirmedProtection.protect.protections[ 0 ].edit, 'autoconfirmed' );
	} );

	it( 'should allow admin to edit Protected page', async () => {
		await admin.edit( protectedPage, { text: 'Admin editing protected page' } );
	} );

	it( 'should allow admin to edit Semi Protected page', async () => {
		await admin.edit( semiProtectedPage, { text: 'Admin editing semi protected page' } );
	} );

	it( 'should NOT allow autoconfirmed user to edit Protected page', async () => {
		const token = await wikiUser.token();
		const editPage = await wikiUser.actionError( 'edit', {
			title: protectedPage,
			token,
			text: 'wikiUser editing protected page'
		}, 'POST' );

		assert.equal( editPage.code, 'protectedpage' );
	} );

	it( 'should allow autoconfirmed user to edit Semi Protected page', async () => {
		await wikiUser.edit( semiProtectedPage, { text: 'wikiUser editing semi protected page' } );
	} );

	it( 'should NOT allow anonymous user to edit Protected page', async () => {
		const token = await anonymousUser.token();
		const editPage = await anonymousUser.actionError( 'edit', {
			title: protectedPage,
			token,
			text: 'anonymous user editing protected page'
		}, 'POST' );

		assert.equal( editPage.code, 'protectedpage' );
	} );

	it( 'should NOT allow anonymous user to edit Semi Protected page', async () => {
		const token = await anonymousUser.token();
		const editPage = await anonymousUser.actionError( 'edit', {
			title: semiProtectedPage,
			token,
			text: 'anonymous user editing semi protected page'
		}, 'POST' );

		assert.equal( editPage.code, 'protectedpage' );
	} );
} );
